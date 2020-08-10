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
 * @package  Workable Zone - User > Leave
 * @author-email  workablezone@gmail.com
 * @copyright  Copyright 2017 © workablezone.com. All Rights Reserved
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Leave extends MY_Controller {
	
	 public function __construct() {
        Parent::__construct();
		$this->load->library('session');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->database();
		$this->load->library('form_validation');
		//load the model
		$this->load->model("Timesheet_model");
		$this->load->model("Employees_model");
		$this->load->model("Xin_model");
		$this->load->model("Department_model");
		$this->load->model("Designation_model");
		$this->load->model("Roles_model");
		$this->load->model("Location_model");
	}
	
	/*Function to set JSON output*/
	public function output($Return=array()){
		/*Set response header*/
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		/*Final JSON response*/
		exit(json_encode($Return));
	}
	 
	 // leave>index
	 public function index()
     {
		$data['title'] = $this->Xin_model->site_title();
		$data['all_employees'] = $this->Xin_model->all_employees();
		$data['all_leave_types'] = $this->Timesheet_model->all_leave_types();
		$session = $this->session->userdata('username');
		$data['breadcrumbs'] = 'Leave';
		$data['path_url'] = 'user/user_leave';

		if(!empty($session)){

			$c_session = $this->session->userdata('username');
			$result2 = $this->Employees_model->read_emp_leave_information($c_session['user_id']);

			//$type = $this->Timesheet_model->read_leave_type_information($result[0]->leave_type_id);

			$data['casual_leave']=$result2[0]->casual_leave;
			$data['medical_leave']=$result2[0]->medical_leave;
			

			$data['subview'] = $this->load->view("user/leave", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
		} else {
			redirect('');
		}
		  
     }
	 
	 // leave list 
	 public function leave_list() {

		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("timesheet/leave", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		
		
		$leave = $this->Timesheet_model->get_employee_leaves($session['user_id']);
		
		$data = array();

          foreach($leave->result() as $r) {
			  
			 // get start date and end date
			 $user = $this->Xin_model->read_user_info($r->employee_id);
			 $full_name = $user[0]->first_name. ' '.$user[0]->last_name;
			 
			 // get leave type
		 	 $leave_type = $this->Timesheet_model->read_leave_type_information($r->leave_type_id);
			 
			 $applied_on = $this->Xin_model->set_date_format($r->applied_on);
			 $duration = $this->Xin_model->set_date_format($r->from_date).' to '.$this->Xin_model->set_date_format($r->to_date);
			 
			 if($r->status==1): $status = 'Pending'; elseif($r->status==2): $status = 'Approved'; elseif($r->status==3): $status = 'Rejected'; endif;
			
		   $data[] = array(
				'<span data-toggle="tooltip" data-placement="top" title="View Details"><a href="'.site_url().'timesheet/leave_details/id/'.$r->leave_id.'/"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" title="View Details"><i class="fa fa-arrow-circle-right"></i></button></a></span>',
				$full_name,
				$leave_type[0]->type_name,
				$duration,
				$applied_on,
				$r->reason,
				$status
		   );
	  }

	  $output = array(
		   "draw" => $draw,
			 "recordsTotal" => $leave->num_rows(),
			 "recordsFiltered" => $leave->num_rows(),
			 "data" => $data
		);
	  echo json_encode($output);
	  exit();
     }
}
