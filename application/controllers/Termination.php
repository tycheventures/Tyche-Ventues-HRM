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
 * @package  Workable Zone - Terminations
 * @author-email  workablezone@gmail.com
 * @copyright  Copyright 2017 © workablezone.com. All Rights Reserved
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Termination extends MY_Controller {
	
	 public function __construct() {
        Parent::__construct();
		$this->load->library('session');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->database();
		$this->load->library('form_validation');
		//load the model
		$this->load->model("Termination_model");
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
		$data['all_employees'] = $this->Xin_model->all_employees();
		$data['all_termination_types'] = $this->Termination_model->all_termination_types();
		$data['breadcrumbs'] = 'Terminations';
		$data['path_url'] = 'termination';
		$session = $this->session->userdata('username');
		$role_resources_ids = $this->Xin_model->user_role_resource();
		if(in_array('23',$role_resources_ids)) {
			if(!empty($session)){ 
			$data['subview'] = $this->load->view("termination/termination_list", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}
     }
 
    public function termination_list()
     {

		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("termination/termination_list", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		
		$termination = $this->Termination_model->get_terminations();
		
		$data = array();

          foreach($termination->result() as $r) {
			 			  
		// get user > warning to
		$user = $this->Xin_model->read_user_info($r->employee_id);
		// user full name
		$ful_name = $user[0]->first_name.' '.$user[0]->last_name;
		// get notice date
		$notice_date = $this->Xin_model->set_date_format($r->notice_date);
		// get termination date
		$termination_date = $this->Xin_model->set_date_format($r->termination_date);
				
		// get status
		if($r->status==0): $status = 'Pending';
		elseif($r->status==1): $status = 'Accepted'; else: $status = 'Rejected'; endif;
		// get warning type
		$termination_type = $this->Termination_model->read_termination_type_information($r->termination_type_id);
		
		$data[] = array(
			'<span data-toggle="tooltip" data-placement="top" title="Edit"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-termination_id="'. $r->termination_id . '"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="View"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".view-modal-data" data-termination_id="'. $r->termination_id . '"><i class="fa fa-eye"></i></button></span><span data-toggle="tooltip" data-placement="top" title="Delete"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->termination_id . '"><i class="fa fa-trash-o"></i></button></span>',
			$ful_name,
			$termination_type[0]->type,
			$notice_date,
			$termination_date,
			$status
		);
      }

	  $output = array(
		   "draw" => $draw,
			 "recordsTotal" => $termination->num_rows(),
			 "recordsFiltered" => $termination->num_rows(),
			 "data" => $data
		);
	  echo json_encode($output);
	  exit();
     }
	 
	 public function read()
	{
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('termination_id');
		$result = $this->Termination_model->read_termination_information($id);
		$data = array(
				'termination_id' => $result[0]->termination_id,
				'employee_id' => $result[0]->employee_id,
				'terminated_by' => $result[0]->terminated_by,
				'termination_type_id' => $result[0]->termination_type_id,
				'termination_date' => $result[0]->termination_date,
				'notice_date' => $result[0]->notice_date,
				'description' => $result[0]->description,
				'status' => $result[0]->status,
				'all_employees' => $this->Xin_model->all_employees(),
				'all_termination_types' => $this->Termination_model->all_termination_types(),
				);
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view('termination/dialog_termination', $data);
		} else {
			redirect('');
		}
	}
	
	// Validate and add info in database
	public function add_termination() {
	
		if($this->input->post('add_type')=='termination') {		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
			
		/* Server side PHP input validation */
		$notice_date = $this->input->post('notice_date');
		$termination_date = $this->input->post('termination_date');
		$nt_date = strtotime($notice_date);
    	$tt_date = strtotime($termination_date);
		$description = $this->input->post('description');
		$qt_description = htmlspecialchars(addslashes($description), ENT_QUOTES);
		
		if($this->input->post('employee_id')==='') {
       		 $Return['error'] = "The employee field is required.";
		} else if($this->input->post('notice_date')==='') {
			$Return['error'] = "The notice date field is required.";
		} else if($this->input->post('termination_date')==='') {
			 $Return['error'] = "The termination date field is required.";
		} else if($nt_date > $tt_date) {
        	$Return['error'] = "Notice Date should be less than or equal to Termination Date.";
		} else if(empty($this->input->post('type'))) {
			 $Return['error'] = "The termination type field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'employee_id' => $this->input->post('employee_id'),
		'notice_date' => $this->input->post('notice_date'),
		'description' => $qt_description,
		'termination_date' => $this->input->post('termination_date'),
		'termination_type_id' => $this->input->post('type'),
		'terminated_by' => $this->input->post('user_id'),
		'status' => '0',
		'created_at' => date('d-m-Y'),
		);
		$result = $this->Termination_model->add($data);
		if ($result == TRUE) {
			$Return['result'] = 'Termination added.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}
	
	// Validate and update info in database
	public function update() {
	
		if($this->input->post('edit_type')=='termination') {
			
		$id = $this->uri->segment(3);
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
			
		/* Server side PHP input validation */
		$notice_date = $this->input->post('notice_date');
		$termination_date = $this->input->post('termination_date');
		$nt_date = strtotime($notice_date);
    	$tt_date = strtotime($termination_date);
		$description = $this->input->post('description');
		$qt_description = htmlspecialchars(addslashes($description), ENT_QUOTES);
		
		if($this->input->post('employee_id')==='') {
       		 $Return['error'] = "The employee field is required.";
		} else if($this->input->post('notice_date')==='') {
			$Return['error'] = "The notice date field is required.";
		} else if($this->input->post('termination_date')==='') {
			 $Return['error'] = "The termination date field is required.";
		} else if($nt_date > $tt_date) {
        	$Return['error'] = "Notice Date should be less than or equal to Termination Date.";
		} else if(empty($this->input->post('type'))) {
			 $Return['error'] = "The termination type field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'employee_id' => $this->input->post('employee_id'),
		'notice_date' => $this->input->post('notice_date'),
		'description' => $qt_description,
		'termination_date' => $this->input->post('termination_date'),
		'termination_type_id' => $this->input->post('type'),
		'terminated_by' => $this->input->post('user_id'),
		'status' => $this->input->post('status'),
		);
		
		$result = $this->Termination_model->update_record($data,$id);		
		
		if ($result == TRUE) {
			$Return['result'] = 'Termination updated.';
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
		$result = $this->Termination_model->delete_record($id);
		if(isset($id)) {
			$Return['result'] = 'Termination deleted.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
	}
}
