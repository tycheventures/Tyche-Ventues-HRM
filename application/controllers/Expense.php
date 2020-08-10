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
 * @package  Workable Zone - Expense
 * @author-email  workablezone@gmail.com
 * @copyright  Copyright 2017 © workablezone.com. All Rights Reserved
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Expense extends MY_Controller {
	
	 public function __construct() {
        Parent::__construct();
		$this->load->library('session');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->database();
		$this->load->library('form_validation');
		//load the login model
		$this->load->model("Expense_model");
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
		$data['all_expense_types'] = $this->Expense_model->all_expense_types();
		$data['all_employees'] = $this->Xin_model->all_employees();
		$data['breadcrumbs'] = $this->lang->line('xin_expenses');
		$data['path_url'] = 'expense';
		$session = $this->session->userdata('username');
		
		$role_resources_ids = $this->Xin_model->user_role_resource();
		if(in_array('10',$role_resources_ids)) {
			if(!empty($session)){ 
			$data['subview'] = $this->load->view("expense/expense_list", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}
     }
 
    public function expense_list()
     {

		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("expense/expense_list", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		
		$expense = $this->Expense_model->get_expenses();
		
		$data = array();

          foreach($expense->result() as $r) {
			  
			  // get country
			  $expense_type = $this->Expense_model->read_expense_type_information($r->expense_type_id);
			  // get user
			  $user = $this->Xin_model->read_user_info($r->employee_id);
			  // user full name
			  $full_name = $user[0]->first_name.' '.$user[0]->last_name;
			  // get date
			  $edate = $this->Xin_model->set_date_format($r->purchase_date);
			  // get currency
			  $currency = $this->Xin_model->currency_sign($r->amount);
			  // download
			  $download = '';
			  
			  if($r->status==0): $status = 'Pending'; elseif($r->status==1): $status = 'Approved'; else: $status = 'Cancel'; endif;
			  if($r->billcopy_file!='' && $r->billcopy_file!='no file') {
			 	$download = '<span data-toggle="tooltip" data-placement="top" title="Download"><a href="download?type=expense&filename='.$r->billcopy_file.'"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" title="'.$this->lang->line('xin_download').'"><i class="fa fa-download"></i></button></a></span>';
			 }

               $data[] = array(
			   		'<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-expense_id="'. $r->expense_id . '"><i class="fa fa-pencil-square-o"></i></button></span>'.$download.'<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_view').'"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".view-modal-data" data-expense_id="'. $r->expense_id . '"><i class="fa fa-eye"></i></button></span><span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->expense_id . '"><i class="fa fa-trash-o"></i></button></span>',
                    $full_name,
                    $expense_type[0]->name,
                    $currency,
                    $edate,
                    $status,
               );
          }

          $output = array(
               "draw" => $draw,
                 "recordsTotal" => $expense->num_rows(),
                 "recordsFiltered" => $expense->num_rows(),
                 "data" => $data
            );
          echo json_encode($output);
          exit();
     }
	 
	 public function read()
	{
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('expense_id');
		$result = $this->Expense_model->read_expense_information($id);
		$data = array(
				'expense_id' => $result[0]->expense_id,
				'employee_id' => $result[0]->employee_id,
				'expense_type_id' => $result[0]->expense_type_id,
				'billcopy_file' => $result[0]->billcopy_file,
				'amount' => $result[0]->amount,
				'purchase_date' => $result[0]->purchase_date,
				'remarks' => $result[0]->remarks,
				'status' => $result[0]->status,
				'all_expense_types' => $this->Expense_model->all_expense_types(),
				'all_employees' => $this->Xin_model->all_employees()
				);
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view('expense/dialog_expense', $data);
		} else {
			redirect('');
		}
	}
	
	// Validate and add info in database
	public function add_expense() {
	
		if($this->input->post('add_type')=='expense') {
		// Check validation for user input
		$file = $_FILES['bill_copy']['tmp_name'];
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
			
		$remarks = $this->input->post('description');
		$qt_remarks = htmlspecialchars(addslashes($remarks), ENT_QUOTES);	
		/* Server side PHP input validation */
		if($this->input->post('expense_type')==='') {
        	$Return['error'] = $this->lang->line('xin_error_expense_type');
		} else if($this->input->post('purchase_date')==='') {
			$Return['error'] = $this->lang->line('xin_error_purchase_date');
		} else if($this->input->post('amount')==='') {
			$Return['error'] = $this->lang->line('xin_error_expense_amount');
		} else if($this->input->post('employee_id')==='') {
			$Return['error'] = $this->lang->line('xin_error_employee_id');
		} 
		
		/* Check if file uploaded..*/
		else if($_FILES['bill_copy']['size'] == 0) {
			$fname = 'no file';
		} else {
			if(is_uploaded_file($_FILES['bill_copy']['tmp_name'])) {
				//checking image type
				$allowed =  array('png','jpg','jpeg','gif');
				$filename = $_FILES['bill_copy']['name'];
				$ext = pathinfo($filename, PATHINFO_EXTENSION);
				
				if(in_array($ext,$allowed)){
					$tmp_name = $_FILES["bill_copy"]["tmp_name"];
					$bill_copy = "uploads/expense/";
					// basename() may prevent filesystem traversal attacks;
					// further validation/sanitation of the filename may be appropriate
					$lname = basename($_FILES["bill_copy"]["name"]);
					$newfilename = 'bill_copy_'.round(microtime(true)).'.'.$ext;
					move_uploaded_file($tmp_name, $bill_copy.$newfilename);
					$fname = $newfilename;
				} else {
					$Return['error'] = $this->lang->line('xin_error_expense_file_type');
				}
			}
		}
		
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'expense_type_id' => $this->input->post('expense_type'),
		'purchase_date' => $this->input->post('purchase_date'),
		'amount' => $this->input->post('amount'),
		'employee_id' => $this->input->post('employee_id'),
		'billcopy_file' => $fname,
		'remarks' => $qt_remarks,
		'created_at' => date('d-m-Y'),
		);
		$result = $this->Expense_model->add($data);
		if ($result == TRUE) {
			$Return['result'] = $this->lang->line('xin_success_add_expense');
		} else {
			$Return['error'] = $this->lang->line('xin_error_msg');
		}
		$this->output($Return);
		exit;
		}
	}
	
	// Validate and update info in database
	public function update() {
	
		if($this->input->post('edit_type')=='expense') {
		$id = $this->uri->segment(3);
		// Check validation for user input
		$file = $_FILES['bill_copy']['tmp_name'];
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
			
		$remarks = $this->input->post('description');
		$qt_remarks = htmlspecialchars(addslashes($remarks), ENT_QUOTES);		
		
		$no_logo_data = array(
		'expense_type_id' => $this->input->post('expense_type'),
		'purchase_date' => $this->input->post('purchase_date'),
		'amount' => $this->input->post('amount'),
		'employee_id' => $this->input->post('employee_id'),
		'status' => $this->input->post('status'),
		'remarks' => $qt_remarks,
		);
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
			
		/* Server side PHP input validation */
		if($this->input->post('expense_type')==='') {
        	$Return['error'] = $this->lang->line('xin_error_expense_type');
		} else if($this->input->post('purchase_date')==='') {
			$Return['error'] = $this->lang->line('xin_error_purchase_date');
		} else if($this->input->post('amount')==='') {
			$Return['error'] = $this->lang->line('xin_error_expense_amount');
		} else if($this->input->post('employee_id')==='') {
			$Return['error'] = $this->lang->line('xin_error_employee_id');
		}  
		
		/* Check if file uploaded..*/
		else if($_FILES['bill_copy']['size'] == 0) {
			$fname = 'no file';
			 $result = $this->Expense_model->update_record_no_logo($no_logo_data,$id);
		} else {
			if(is_uploaded_file($_FILES['bill_copy']['tmp_name'])) {
				//checking image type
				$allowed =  array('png','jpg','jpeg','gif');
				$filename = $_FILES['bill_copy']['name'];
				$ext = pathinfo($filename, PATHINFO_EXTENSION);
				
				if(in_array($ext,$allowed)){
					$tmp_name = $_FILES["bill_copy"]["tmp_name"];
					$bill_copy = "uploads/expense/";
					// basename() may prevent filesystem traversal attacks;
					// further validation/sanitation of the filename may be appropriate
					$lname = basename($_FILES["bill_copy"]["name"]);
					$newfilename = 'bill_copy_'.round(microtime(true)).'.'.$ext;
					move_uploaded_file($tmp_name, $bill_copy.$newfilename);
					$fname = $newfilename;
					$data = array(
					'expense_type_id' => $this->input->post('expense_type'),
					'purchase_date' => $this->input->post('purchase_date'),
					'amount' => $this->input->post('amount'),
					'employee_id' => $this->input->post('employee_id'),
					'status' => $this->input->post('status'),
					'billcopy_file' => $fname,
					'remarks' => $qt_remarks,		
					);
					// update record > model
					$result = $this->Expense_model->update_record($data,$id);
				} else {
					$Return['error'] = $this->lang->line('xin_error_expense_file_type');
				}
			}
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
		
		
		if ($result == TRUE) {
			$Return['result'] = $this->lang->line('xin_success_update_expense');
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
		$result = $this->Expense_model->delete_record($id);
		if(isset($id)) {
			$Return['result'] = $this->lang->line('xin_success_delete_expense');
		} else {
			$Return['error'] = $this->lang->line('xin_error_msg');
		}
		$this->output($Return);
	}
}
