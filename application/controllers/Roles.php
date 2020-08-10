<?php
 /**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Workable Zone License
 * that is bundled with this package in the file license.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.workablezone.com/license.txt
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to workablezone@gmail.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * versions in the future. If you wish to customize this extension for your
 * needs please contact us at workablezone@gmail.com for more information.
 *
 * @author   Mian Abdullah Jan - Workable Zone
 * @package  Workable Zone - User Roles
 * @author-email  workablezone@gmail.com
 * @copyright  Copyright 2017 © workablezone.com. All Rights Reserved
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Roles extends MY_Controller {
	
	 public function __construct() {
        Parent::__construct();
		$this->load->library('session');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->database();
		$this->load->library('form_validation');
		//load the model
		$this->load->model("Roles_model");
		$this->load->model("Xin_model");
	}
	
	/*Function to set JSON output*/
	public function output($Return=array()){
		/*Set response header*/
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		/*Final JSON response*/
		exit(json_encode($Return));
	}
	
	 public function index()
     {
		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		$data['breadcrumbs'] = 'User Roles';
		$data['path_url'] = 'roles';
		$role_resources_ids = $this->Xin_model->user_role_resource();
		if(in_array('14',$role_resources_ids)) {
			if(!empty($session)){ 
			$data['subview'] = $this->load->view("roles/role_list", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}		  
     }
 
    public function role_list()
     {

		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("roles/role_list", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		
		$role = $this->Roles_model->get_user_roles();
		
		$data = array();

          foreach($role->result() as $r) {
			  
			  /* get job status*/
			if($r->role_access==1): $r_access = 'All Menu Access'; 
			elseif($r->role_access==2): $r_access = 'Custom Menu Access'; endif;
			// 
			$created_at = $this->Xin_model->set_date_format($r->created_at);

		   $data[] = array(
				'<span data-toggle="tooltip" data-placement="top" title="Edit"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-role_id="'. $r->role_id . '"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="Delete"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->role_id . '"><i class="fa fa-trash-o"></i></button></span>',
				$r->role_id,
				$r->role_name,
				$r_access,
				$created_at
		   );
          }

          $output = array(
               "draw" => $draw,
                 "recordsTotal" => $role->num_rows(),
                 "recordsFiltered" => $role->num_rows(),
                 "data" => $data
            );
          echo json_encode($output);
          exit();
     }
	 
	 public function read()
	{
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('role_id');
		$result = $this->Roles_model->read_role_information($id);
		$data = array(
				'role_id' => $result[0]->role_id,
				'role_name' => $result[0]->role_name,
				'role_access' => $result[0]->role_access,
				'role_resources' => $result[0]->role_resources,
				);
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view('roles/dialog_role', $data);
		} else {
			redirect('');
		}
	}
	
	// Validate and add info in database
	public function add_role() {
	
		if($this->input->post('add_type')=='role') {		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
			
		/* Server side PHP input validation */
		if($this->input->post('role_name')==='') {
        	$Return['error'] = "The role name field is required.";
		} else if($this->input->post('role_access')==='') {
			$Return['error'] = "The access field is required.";
		}
		
		$role_resources = implode(',',$this->input->post('role_resources'));
						
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'role_name' => $this->input->post('role_name'),
		'role_access' => $this->input->post('role_access'),
		'role_resources' => $role_resources,
		'created_at' => date('d-m-Y'),
		);
		
		$result = $this->Roles_model->add($data);
		if ($result == TRUE) {
			$Return['result'] = 'User Role added.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}
	
	// Validate and update info in database
	public function update() {
	
		if($this->input->post('edit_type')=='role') {
			
		$id = $this->uri->segment(3);
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
			
		/* Server side PHP input validation */
		if($this->input->post('role_name')==='') {
        	$Return['error'] = "The role name field is required.";
		} else if($this->input->post('role_access')==='') {
			$Return['error'] = "The access field is required.";
		}
		
		$role_resources = implode(',',$this->input->post('role_resources'));
						
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'role_name' => $this->input->post('role_name'),
		'role_access' => $this->input->post('role_access'),
		'role_resources' => $role_resources,
		);	
		
		$result = $this->Roles_model->update_record($data,$id);		
		
		if ($result == TRUE) {
			$Return['result'] = 'User Role updated.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}
	
	public function delete() {
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
		$id = $this->uri->segment(3);
		$result = $this->Roles_model->delete_record($id);
		if(isset($id)) {
			$Return['result'] = 'User Role deleted.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
	}
}
