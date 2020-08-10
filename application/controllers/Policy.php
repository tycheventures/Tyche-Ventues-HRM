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
 * @package  Workable Zone - Policy
 * @author-email  workablezone@gmail.com
 * @copyright  Copyright 2017 © workablezone.com. All Rights Reserved
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Policy extends MY_Controller {
	
	 public function __construct() {
        Parent::__construct();
		$this->load->library('session');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->database();
		$this->load->library('form_validation');
		//load the model
		$this->load->model("Policy_model");
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
		$data['all_companies'] = $this->Xin_model->get_companies();
		$data['breadcrumbs'] = $this->lang->line('xin_policies');
		$data['path_url'] = 'policy';
		$session = $this->session->userdata('username');
		$role_resources_ids = $this->Xin_model->user_role_resource();
		if(in_array('9',$role_resources_ids)) {
			if(!empty($session)){ 
			$data['subview'] = $this->load->view("policy/policy_list", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}		  
     }
 
    public function policy_list()
     {

		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("policy/policy_list", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		
		$policy = $this->Policy_model->get_policies();
		
		$data = array();

          foreach($policy->result() as $r) {
			 			  
		// get user > added by
		$user = $this->Xin_model->read_user_info($r->added_by);
		// user full name
		$full_name = $user[0]->first_name.' '.$user[0]->last_name;
		// get date
		$pdate = $this->Xin_model->set_date_format($r->created_at);
		// get company
		if($r->company_id=='0'){
			$company = $this->lang->line('xin_all_companies');
		} else {
			$p_company = $this->Xin_model->read_company_info($r->company_id);
			$company = $p_company[0]->name;
		}
		
				
		$data[] = array(
			'<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-policy_id="'. $r->policy_id . '"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_view').'"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".view-modal-data" data-policy_id="'. $r->policy_id . '"><i class="fa fa-eye"></i></button></span><span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->policy_id . '"><i class="fa fa-trash-o"></i></button></span>',
			$r->title,
			$company,
			$pdate,
			$full_name
		);
      }

	  $output = array(
		   "draw" => $draw,
			 "recordsTotal" => $policy->num_rows(),
			 "recordsFiltered" => $policy->num_rows(),
			 "data" => $data
		);
	  echo json_encode($output);
	  exit();
     }

	 public function read()
	{
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('policy_id');
		$result = $this->Policy_model->read_policy_information($id);
		$data = array(
				'policy_id' => $result[0]->policy_id,
				'company_id' => $result[0]->company_id,
				'title' => $result[0]->title,
				'description' => $result[0]->description,
				'all_companies' => $this->Xin_model->get_companies()
				);
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view('policy/dialog_policy', $data);
		} else {
			redirect('');
		}
	}
	
	// Validate and add info in database
	public function add_policy() {
	
		if($this->input->post('add_type')=='policy') {		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
			
		/* Server side PHP input validation */
		$description = $this->input->post('description');
		$qt_description = htmlspecialchars(addslashes($description), ENT_QUOTES);
		
		if($this->input->post('company')==='') {
       		$Return['error'] = $this->lang->line('xin_error_company');
		} else if($this->input->post('title')==='') {
			$Return['error'] = $this->lang->line('xin_error_title');
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'company_id' => $this->input->post('company'),
		'title' => $this->input->post('title'),
		'description' => $qt_description,
		'added_by' => $this->input->post('user_id'),
		'created_at' => date('d-m-Y'),
		
		);
		$result = $this->Policy_model->add($data);
		if ($result == TRUE) {
			$Return['result'] = $this->lang->line('xin_success_add_policy');
		} else {
			$Return['error'] = $this->lang->line('xin_error_msg');
		}
		$this->output($Return);
		exit;
		}
	}
	
	// Validate and update info in database
	public function update() {
	
		if($this->input->post('edit_type')=='policy') {
			
		$id = $this->uri->segment(3);
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
			
		/* Server side PHP input validation */
		$description = $this->input->post('description');
		$qt_description = htmlspecialchars(addslashes($description), ENT_QUOTES);
		
		if($this->input->post('company')==='') {
       		$Return['error'] = $this->lang->line('xin_error_company');
		} else if($this->input->post('title')==='') {
			$Return['error'] = $this->lang->line('xin_error_title');
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'company_id' => $this->input->post('company'),
		'title' => $this->input->post('title'),
		'description' => $qt_description,		
		);
		
		$result = $this->Policy_model->update_record($data,$id);		
		
		if ($result == TRUE) {
			$Return['result'] = $this->lang->line('xin_success_update_policy');
		} else {
			$Return['error'] = $this->lang->line('xin_error_msg');
		}
		$this->output($Return);
		exit;
		}
	}
	
	public function delete() {
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
		$id = $this->uri->segment(3);
		$result = $this->Policy_model->delete_record($id);
		if(isset($id)) {
			$Return['result'] = $this->lang->line('xin_success_delete_policy');
		} else {
			$Return['error'] = $this->lang->line('xin_error_msg');
		}
		$this->output($Return);
	}
}
