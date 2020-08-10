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
 * @package  Workable Zone - Job Interviews
 * @author-email  workablezone@gmail.com
 * @copyright  Copyright 2017 © workablezone.com. All Rights Reserved
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Job_interviews extends MY_Controller {
	
	 public function __construct() {
        Parent::__construct();
		$this->load->library('session');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->database();
		$this->load->library('form_validation');
		//load the model
		$this->load->model("Job_post_model");
		$this->load->model("Xin_model");
		$this->load->model("Designation_model");
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
		$data['all_interview_jobs'] = $this->Job_post_model->all_interview_jobs();
		$data['breadcrumbs'] = 'Job Interviews';
		$data['path_url'] = 'job_interviews';
		$session = $this->session->userdata('username');
		$role_resources_ids = $this->Xin_model->user_role_resource();
		if(in_array('47',$role_resources_ids)) {
			if(!empty($session)){ 
			$data['subview'] = $this->load->view("job_post/job_interviews", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}		  
     }
 
    public function interview_list()
     {

		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("job_post/job_interviews", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		
		$interview = $this->Job_post_model->all_interviews();
		
		$data = array();

        foreach($interview->result() as $r) {
		
		// get job title
		$job = $this->Job_post_model->read_job_information($r->job_id);
		// get date
		$interview_date = $this->Xin_model->set_date_format($r->interview_date);		
		// get interviewees
		if($r->interviewees_id == '') {
			$interviewees = '-';
		} else {
			$interviewees = '<ol class="nl">';
		foreach(explode(',',$r->interviewees_id) as $interviewees_id) {
			$user_intwee = $this->Xin_model->read_user_info($interviewees_id);
			$interviewees .= '<li>'.$user_intwee[0]->first_name. ' '.$user_intwee[0]->last_name.'</li>';
			}
			$interviewees .= '</ol>';
		}
		
		// get interviewers
		if($r->interviewers_id == '') {
			$interviewers = '-';
		} else {
			$interviewers = '<ol class="nl">';
		foreach(explode(',',$r->interviewers_id) as $interviewers_id) {
			$user_intwer = $this->Xin_model->read_user_info($interviewers_id);
			$interviewers .= '<li>'.$user_intwer[0]->first_name. ' '.$user_intwer[0]->last_name.'</li>';
			}
			$interviewers .= '</ol>';
		}
		
		// get time
		$interview_time = $r->interview_date.' '.$r->interview_time;
		$interview_ex_time =  new DateTime($interview_time);
		$int_time = $interview_ex_time->format('h:i a');
		
		// interview date and time
		$interview_d_t = $interview_date.' '.$int_time;
		// interview added by
		$u_added = $this->Xin_model->read_user_info($r->added_by);
		$int_addedby = $u_added[0]->first_name. ' '.$u_added[0]->last_name;
		
		$data[] = array(
			'<span data-toggle="tooltip" data-placement="top" title="Delete"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->job_interview_id . '"><i class="fa fa-trash-o"></i></button></span>',
			$job[0]->job_title,
			$interviewees,
			$r->interview_place,
			$interview_d_t,
			$interviewers,
			$int_addedby
		);
      }

	  $output = array(
		   "draw" => $draw,
			 "recordsTotal" => $interview->num_rows(),
			 "recordsFiltered" => $interview->num_rows(),
			 "data" => $data
		);
	  echo json_encode($output);
	  exit();
     }
	 	
	// Validate and add info in database
	public function add_interview() {
	
		if($this->input->post('add_type')=='interview') {		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
			
		/* Server side PHP input validation */
		$description = $this->input->post('description');	
		$qt_description = htmlspecialchars(addslashes($description), ENT_QUOTES);
		
		if($this->input->post('job_id')==='') {
       		$Return['error'] = "The job post field is required.";
		} else if($this->input->post('interview_date')==='') {
			$Return['error'] = "The interview date field is required.";
		} else if(empty($this->input->post('interviewees'))) {
			$Return['error'] = "The candidate field is required.";
		} else if($this->input->post('interview_place')==='') {
			$Return['error'] = "The interview place field is required.";
		} else if($this->input->post('interview_time')==='') {
       		$Return['error'] = "The interview time field is required.";
		} else if(empty($this->input->post('interviewers'))) {
       		$Return['error'] = "The interviewers field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
		
		if(!empty($this->input->post('interviewees'))) {
			$interviewees_ids = implode(',',$this->input->post('interviewees'));
		} else {
			$interviewees_ids = '';
		}
		
		if(!empty($this->input->post('interviewers'))) {
			$interviewers_ids = implode(',',$this->input->post('interviewers'));
		} else {
			$interviewers_ids = '';
		}
	
		$data = array(
		'job_id' => $this->input->post('job_id'),
		'interview_date' => $this->input->post('interview_date'),
		'interviewees_id' => $interviewees_ids,
		'description' => $qt_description,
		'interview_place' => $this->input->post('interview_place'),
		'interview_time' => $this->input->post('interview_time'),
		'interviewers_id' => $interviewers_ids,
		'added_by' => $this->input->post('user_id'),
		'created_at' => date('Y-m-d h:i:s')		
		);
		
		$result = $this->Job_post_model->add_interview($data);
		if ($result == TRUE) {
			$Return['result'] = 'Job Interview added.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}	
	
	// get job employees
	 public function get_employees() {

		$data['title'] = $this->Xin_model->site_title();
		$id = $this->uri->segment(3);
		$data = array(
			'job_id' => $id,
			'all_employees' => $this->Xin_model->all_employees(),
			);
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("job_post/get_job_employees", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
	 }
	
	public function delete() {
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
		$id = $this->uri->segment(3);
		$result = $this->Job_post_model->delete_interview_record($id);
		if(isset($id)) {
			$Return['result'] = 'Job Interview deleted.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
	}
}
