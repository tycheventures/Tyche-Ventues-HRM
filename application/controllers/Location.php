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
 * @package  Workable Zone - Locations
 * @author-email  workablezone@gmail.com
 * @copyright  Copyright 2017 © workablezone.com. All Rights Reserved
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Location extends MY_Controller {
	
	 public function __construct() {
        Parent::__construct();
		$this->load->library('session');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->database();
		$this->load->library('form_validation');
		//load the model
		$this->load->model("Location_model");
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
		$data['all_countries'] = $this->Xin_model->get_countries();
		$data['all_companies'] = $this->Xin_model->get_companies();
		$data['all_employees'] = $this->Xin_model->all_employees();
		$data['breadcrumbs'] = $this->lang->line('xin_locations');
		$data['path_url'] = 'location';
		$session = $this->session->userdata('username');
		$role_resources_ids = $this->Xin_model->user_role_resource();
		if(in_array('4',$role_resources_ids)) {
			if(!empty($session)){ 
			$data['subview'] = $this->load->view("location/location_list", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}		  
     }
 
    public function location_list()
     {

		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("location/location_list", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		
		$location = $this->Location_model->get_locations();
		
		$data = array();

          foreach($location->result() as $r) {
			  
			  // get country
			  $country = $this->Xin_model->read_country_info($r->country);
			  // get company
			  $company = $this->Xin_model->read_company_info($r->company_id);
			  // get user
			  $user = $this->Xin_model->read_user_info($r->added_by);
			  // user full name
			  $full_name = $user[0]->first_name.' '.$user[0]->last_name;
			  
			   // get location head
			  $location_head = $this->Xin_model->read_user_info($r->location_head);
			  // user full name
			  $head_name = $location_head[0]->first_name.' '.$location_head[0]->last_name;

               $data[] = array(
			   		'<span data-toggle="tooltip" data-placement="top" title="Edit"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light"  data-toggle="modal" data-target="#edit-modal-data"  data-location_id="'. $r->location_id . '"><i class="fa fa-pencil-square-o"></i></button></span></span><span data-toggle="tooltip" data-placement="top" title="View"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".view-modal-data" data-location_id="'. $r->location_id . '"><i class="fa fa-eye"></i></button></span><span data-toggle="tooltip" data-placement="top" title="Delete"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->location_id . '"><i class="fa fa-trash-o"></i></button></span>',
                    $r->location_name,
					$head_name,
                    $company[0]->name,
                    $r->city,
                    $country[0]->country_name,
					$full_name
               );
          }

          $output = array(
               "draw" => $draw,
                 "recordsTotal" => $location->num_rows(),
                 "recordsFiltered" => $location->num_rows(),
                 "data" => $data
            );
          echo json_encode($output);
          exit();
     }
	 
	 public function read()
	{
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('location_id');
       // $data['all_countries'] = $this->xin_model->get_countries();
		$result = $this->Location_model->read_location_information($id);
		$data = array(
				'location_id' => $result[0]->location_id,
				'company_id' => $result[0]->company_id,
				'location_head' => $result[0]->location_head,
				'location_manager' => $result[0]->location_manager,
				'location_name' => $result[0]->location_name,
				'email' => $result[0]->email,
				'phone' => $result[0]->phone,
				'fax' => $result[0]->fax,
				'address_1' => $result[0]->address_1,
				'address_2' => $result[0]->address_2,
				'city' => $result[0]->city,
				'state' => $result[0]->state,
				'zipcode' => $result[0]->zipcode,
				'countryid' => $result[0]->country,
				'all_countries' => $this->Xin_model->get_countries(),
				'all_companies' => $this->Xin_model->get_companies(),
				'all_employees' => $this->Xin_model->all_employees()
				);
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view('location/dialog_location', $data);
		} else {
			redirect('');
		}
	}
	
	// Validate and add info in database
	public function add_location() {
	
		if($this->input->post('add_type')=='location') {
		// Check validation for user input
		$this->form_validation->set_rules('company', 'Company', 'trim|required|xss_clean');
		$this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean');
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
			
		/* Server side PHP input validation */
		if($this->input->post('company')==='') {
        	$Return['error'] = $this->lang->line('error_company_field');
		} else if($this->input->post('name')==='') {
			$Return['error'] = $this->lang->line('xin_error_name_field');
		} else if($this->input->post('location_head')==='') {
			$Return['error'] = $this->lang->line('error_locationhead_field');
		} else if($this->input->post('city')==='') {
			$Return['error'] = $this->lang->line('xin_error_city_field');
		} else if($this->input->post('country')==='') {
			$Return['error'] = $this->lang->line('xin_error_country_field');
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'company_id' => $this->input->post('company'),
		'location_name' => $this->input->post('name'),
		'location_head' => $this->input->post('location_head'),
		'location_manager' => $this->input->post('location_manager'),
		'email' => $this->input->post('email'),
		'phone' => $this->input->post('phone'),
		'fax' => $this->input->post('fax'),
		'address_1' => $this->input->post('address_1'),
		'address_2' => $this->input->post('address_2'),
		'city' => $this->input->post('city'),
		'state' => $this->input->post('state'),
		'zipcode' => $this->input->post('zipcode'),
		'country' => $this->input->post('country'),
		'added_by' => $this->input->post('user_id'),
		'created_at' => date('d-m-Y'),
		
		);
		$result = $this->Location_model->add($data);
		if ($result == TRUE) {
			$Return['result'] = $this->lang->line('xin_success_add_location');
		} else {
			$Return['error'] = $this->lang->line('xin_error_msg');
		}
		$this->output($Return);
		exit;
		}
	}
	
	// Validate and update info in database
	public function update() {
	
		if($this->input->post('edit_type')=='location') {
			
		$id = $this->uri->segment(3);
		
		// Check validation for user input
		$this->form_validation->set_rules('company', 'Company', 'trim|required|xss_clean');
		$this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean');
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
			
		/* Server side PHP input validation */
		if($this->input->post('company')==='') {
        	$Return['error'] = $this->lang->line('error_company_field');
		} else if($this->input->post('name')==='') {
			$Return['error'] = $this->lang->line('xin_error_name_field');
		} else if($this->input->post('location_head')==='') {
			$Return['error'] = $this->lang->line('error_locationhead_field');
		} else if($this->input->post('city')==='') {
			$Return['error'] = $this->lang->line('xin_error_city_field');
		} else if($this->input->post('country')==='') {
			$Return['error'] = $this->lang->line('xin_error_country_field');
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'company_id' => $this->input->post('company'),
		'location_name' => $this->input->post('name'),
		'location_head' => $this->input->post('location_head'),
		'location_manager' => $this->input->post('location_manager'),
		'email' => $this->input->post('email'),
		'phone' => $this->input->post('phone'),
		'fax' => $this->input->post('fax'),
		'address_1' => $this->input->post('address_1'),
		'address_2' => $this->input->post('address_2'),
		'city' => $this->input->post('city'),
		'state' => $this->input->post('state'),
		'zipcode' => $this->input->post('zipcode'),
		'country' => $this->input->post('country'),		
		);	
		
		$result = $this->Location_model->update_record($data,$id);		
		
		if ($result == TRUE) {
			$Return['result'] = $this->lang->line('xin_success_update_location');
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
		$result = $this->Location_model->delete_record($id);
		if(isset($id)) {
			$Return['result'] = $this->lang->line('xin_success_delete_location');
		} else {
			$Return['error'] = $this->lang->line('xin_error_msg');
		}
		$this->output($Return);
	}
}
