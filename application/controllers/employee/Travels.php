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
 * @package  Workable Zone - User > Travels
 * @author-email  workablezone@gmail.com
 * @copyright  Copyright 2017 © workablezone.com. All Rights Reserved
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Travels extends MY_Controller {
	
	 public function __construct() {
        Parent::__construct();
		$this->load->library('session');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->database();
		$this->load->library('form_validation');
		//load the model
		$this->load->model("Travel_model");
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
		$data['travel_arrangement_types'] = $this->Travel_model->travel_arrangement_types();
		$session = $this->session->userdata('username');
		$data['breadcrumbs'] = 'Travels';
		$data['path_url'] = 'user/user_travels';
		if(!empty($session)){ 
			$data['subview'] = $this->load->view("user/travel_list", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
		} else {
			redirect('');
		}
		  
     }
 
    public function travel_list()
     {

		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("user/travel_list", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		
		$travel = $this->Travel_model->get_employee_travel($session['user_id']);
		
		$data = array();

          foreach($travel->result() as $r) {
			 			  
		// get user > added by
		$user = $this->Xin_model->read_user_info($r->added_by);
		// user full name
		$full_name = $user[0]->first_name.' '.$user[0]->last_name;
		
		// get user > employee_
		$employee = $this->Xin_model->read_user_info($r->employee_id);
		// employee full name
		$employee_name = $employee[0]->first_name.' '.$employee[0]->last_name;
		// get start date
		$start_date = $this->Xin_model->set_date_format($r->start_date);
		// get end date
		$end_date = $this->Xin_model->set_date_format($r->end_date);
		// status
		if($r->status==0): $status = 'Pending';
		elseif($r->status==1): $status = 'Accepted'; else: $status = 'Rejected'; endif;
		
		$data[] = array('<span data-toggle="tooltip" data-placement="top" title="View"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".view-modal-data" data-travel_id="'. $r->travel_id . '"><i class="fa fa-eye"></i></button></span>',
			$employee_name,
			$r->visit_purpose,
			$r->visit_place,
			$start_date,
			$end_date,
			$status,
			$full_name
		);
      }

	  $output = array(
		   "draw" => $draw,
			 "recordsTotal" => $travel->num_rows(),
			 "recordsFiltered" => $travel->num_rows(),
			 "data" => $data
		);
	  echo json_encode($output);
	  exit();
     }
}
