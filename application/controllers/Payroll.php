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
 * @package  Workable Zone - Payroll
 * @author-email  workablezone@gmail.com
 * @copyright  Copyright 2017 © workablezone.com. All Rights Reserved
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Payroll extends MY_Controller {
	
	 public function __construct() {
        Parent::__construct();
		$this->load->library('session');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->database();
		$this->load->library('form_validation');
		//load the model
		$this->load->model("Payroll_model");
		$this->load->model("Xin_model");
		$this->load->model("Employees_model");
		$this->load->model("Designation_model");
		$this->load->model("Department_model");
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
	
	 // payroll templates
	 public function templates()
     {
		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		$data['breadcrumbs'] = 'Payroll Templates';
		$data['path_url'] = 'payroll_templates';
		$role_resources_ids = $this->Xin_model->user_role_resource();
		if(in_array('38',$role_resources_ids)) {
			if(!empty($session)){
			$data['subview'] = $this->load->view("payroll/templates", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}		  
     }
	 
	 // create pdf - payroll
	 public function pdf_create() {
		 
		$this->load->library('Pdf');
		 // create new PDF document
   		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$id = $this->uri->segment(4);
		$payment = $this->Payroll_model->read_make_payment_information($id);
		$user = $this->Xin_model->read_user_info($payment[0]->employee_id);
		$_des_name = $this->Designation_model->read_designation_information($user[0]->designation_id);
		$department = $this->Department_model->read_department_information($user[0]->department_id);
		$location = $this->Xin_model->read_location_info($department[0]->location_id);
		// company info
		$company = $this->Xin_model->read_company_info($location[0]->company_id);
		$system = $this->Xin_model->read_setting_info(1);
		
		$p_method = '';
		if($payment[0]->payment_method==1){
		  $p_method = 'Online';
		} else if($payment[0]->payment_method==2){
		  $p_method = 'PayPal';
		} else if($payment[0]->payment_method==3) {
		  $p_method = 'Payoneer';
		} else if($payment[0]->payment_method==4){
		  $p_method = 'Bank Transfer';
		} else if($payment[0]->payment_method==5) {
		  $p_method = 'Cheque';
		} else {
		  $p_method = 'Cash';
		}

		//$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
		$company_name = $company[0]->name;
		// set default header data
		$c_info_email = $company[0]->email;
		$c_info_phone = $company[0]->contact_number;
		$country = $this->Xin_model->read_country_info($company[0]->country);
		$c_info_address = $company[0]->address_1.' '.$company[0]->address_2.', '.$company[0]->city.' - '.$company[0]->zipcode.', '.$country[0]->country_name;
		$header_string ="Email : $c_info_email | Phone : $c_info_phone \nAddress: $c_info_address";
		
		// set document information
		$pdf->SetCreator('Workable-Zone');
		$pdf->SetHeaderData('../../../uploads/logo/payroll/'.$system[0]->payroll_logo, 40, $company_name, $header_string);
		
		// set header and footer fonts
		$pdf->setHeaderFont(Array('helvetica', '', 11.5));
		$pdf->setFooterFont(Array('helvetica', '', 8));
		
		// set default monospaced font
		$pdf->SetDefaultMonospacedFont('courier');
		
		// set margins
		$pdf->SetMargins(15, 27, 15);
		$pdf->SetHeaderMargin(5);
		$pdf->SetFooterMargin(10);
		
		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, 25);
		
		// set image scale factor
		$pdf->setImageScale(1.25);
		$pdf->SetAuthor('Mian Abdullah Jan');
		$pdf->SetTitle($company[0]->name.' - Print Payslip');
		$pdf->SetSubject('Payslip');
		$pdf->SetKeywords('Payslip');
		// set font
		$pdf->SetFont('helvetica', 'B', 11);
		
		// add a page
		$pdf->AddPage();
		
		$pdf->SetFont('helvetica', '', 10);
		
		// -----------------------------------------------------------------------------
		
		$tbl = '
		<table cellpadding="1" cellspacing="1" border="0">
			<tr>
				<td align="center"><h1>Payslip</h1></td>
			</tr>
			<tr>
				<td align="center"><strong>Payslip NO:</strong> #'.$payment[0]->make_payment_id.'</td>
			</tr>
			<tr>
				<td align="center"><strong>Date:</strong> '.date("d F, Y").'</td>
			</tr>
		</table>
		';
		$pdf->writeHTML($tbl, true, false, false, false, '');
		
		// -----------------------------------------------------------------------------
		
		$fname = $user[0]->first_name.' '.$user[0]->last_name;
		$tbl = '
		<table cellpadding="5" cellspacing="0" border="1">
			<tr>
				<td>Name</td>
				<td>'.$fname.'</td>
				<td>Employee ID</td>
				<td>'.$user[0]->employee_id.'</td>
			</tr>
			<tr>
				<td>Department</td>
				<td>'.$department[0]->department_name.'</td>
				<td>Designation</td>
				<td>'.$_des_name[0]->designation_name.'</td>
			</tr>
			<tr>
				<td>Salary Month</td>
				<td>'.date("F Y", strtotime($payment[0]->payment_date)).'</td>
				<td>Payslip No</td>
				<td>'.$payment[0]->make_payment_id.'</td>
			</tr>
		
		</table>
		';
	
		$pdf->writeHTML($tbl, true, false, true, false, '');
		
		if(null!=$this->uri->segment(3) && $this->uri->segment(3)=='sl') {
		// -----------------------------------------------------------------------------
		
		// Allowances
		if($payment[0]->house_rent_allowance!='' || $payment[0]->house_rent_allowance!=0){
			$hra = $this->Xin_model->currency_sign($payment[0]->house_rent_allowance);
		} else { $hra = '0';}
		if($payment[0]->medical_allowance!='' || $payment[0]->medical_allowance!=0){
			$ma = $this->Xin_model->currency_sign($payment[0]->medical_allowance);
		} else { $ma = '0';}
		if($payment[0]->travelling_allowance!='' || $payment[0]->travelling_allowance!=0){
			$ta = $this->Xin_model->currency_sign($payment[0]->travelling_allowance);
		} else { $ta = '0';}
		if($payment[0]->dearness_allowance!='' || $payment[0]->dearness_allowance!=0){
			$da = $this->Xin_model->currency_sign($payment[0]->dearness_allowance);
		} else { $da = '0';}
		
		// Deductions
		if($payment[0]->provident_fund!='' || $payment[0]->provident_fund!=0){
			$pf = $this->Xin_model->currency_sign($payment[0]->provident_fund);
		} else { $pf = '0';}
		if($payment[0]->tax_deduction!='' || $payment[0]->tax_deduction!=0){
			$td = $this->Xin_model->currency_sign($payment[0]->tax_deduction);
		} else { $td = '0';}
		if($payment[0]->security_deposit!='' || $payment[0]->security_deposit!=0){
			$sd = $this->Xin_model->currency_sign($payment[0]->security_deposit);
		} else { $sd = '0';}
		
		$tbl = '
		<table cellpadding="4" cellspacing="0" border="0">
			<tr>
				<td><table cellpadding="5" cellspacing="0" border="1">
			<tr style="background-color:#9F9;">
				<td><strong>Earning Salary</strong></td>
				<td align="right"><strong>Amount</strong></td>
			</tr>
			<tr>
				<td>House Rent Allowance</td>
				<td align="right">'.$hra.'</td>
			</tr>
			<tr>
				<td>Medical Allowance</td>
				<td align="right">'.$ma.'</td>
			</tr>
			<tr>
				<td>Travelling Allowance</td>
				<td align="right">'.$ta.'</td>
			</tr>
			<tr>
				<td>Dearness Allowance</td>
				<td align="right">'.$da.'</td>
			</tr>
		</table></td>
				<td><table cellpadding="5" cellspacing="0" border="1">
			<tr style="background-color:#ff7575;">
				<td><strong>Deduction Salary</strong></td>
				<td align="right"><strong>Amount</strong></td>
			</tr>
			<tr>
				<td>Provident Fund</td>
				<td align="right">'.$pf.'</td>
			</tr>
			<tr>
				<td>Tax Deduction</td>
				<td align="right">'.$td.'</td>
			</tr>
			<tr>
				<td>Security Deposit</td>
				<td align="right">'.$sd.'</td>
			</tr>
		</table></td>
			</tr>
		</table>
		';
		
		$pdf->writeHTML($tbl, true, false, false, false, '');
		
		// -----------------------------------------------------------------------------
		
		$tbl = '
		<table cellpadding="5" cellspacing="0" border="1">
			<tr style="background-color:#c4e5fd;">
			  <th colspan="4" align="center"><strong>Payment Details</strong></th>
			 </tr>
			 <tr>
				<td colspan="2">Basic Salary</td>
				<td colspan="2" align="right">'.$this->Xin_model->currency_sign($payment[0]->basic_salary).'</td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
				<td>Gross Salary</td>
				<td align="right">'.$this->Xin_model->currency_sign($payment[0]->gross_salary).'</td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
				<td>Total Allowance</td>
				<td align="right">'.$this->Xin_model->currency_sign($payment[0]->total_allowances).'</td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
				<td>Total Deduction</td>
				<td align="right">'.$this->Xin_model->currency_sign($payment[0]->total_deductions).'</td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
				<td>Net Salary</td>
				<td align="right">'.$this->Xin_model->currency_sign($payment[0]->net_salary).'</td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
				<td>Paid Amount</td>
				<td align="right">'.$this->Xin_model->currency_sign($payment[0]->net_salary).'</td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
				<td>Payment Method</td>
				<td align="right">'.$p_method.'</td>
			</tr>
		</table>
		';
		
		$pdf->writeHTML($tbl, true, false, false, false, '');
		}
		if(null!=$this->uri->segment(3) && $this->uri->segment(3)=='hr') {
		// -----------------------------------------------------------------------------
		$tbl = '
		<table cellpadding="5" cellspacing="0" border="1">
			<tr style="background-color:#c4e5fd;">
			  <th colspan="4" align="center"><strong>Payment Details</strong></th>
			 </tr>
			<tr>
				<td colspan="2">Hourly Rate</td>
				<td colspan="2" align="right">'.$this->Xin_model->currency_sign($payment[0]->hourly_rate).'</td>
			</tr>
			<tr>
				<td colspan="2">Total Hours Worked</td>
				<td colspan="2" align="right">'.$payment[0]->total_hours_work.'</td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
				<td>Gross Salary</td>
				<td align="right">'.$this->Xin_model->currency_sign($payment[0]->payment_amount).'</td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
				<td>Net Salary</td>
				<td align="right">'.$this->Xin_model->currency_sign($payment[0]->payment_amount).'</td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
				<td>Paid Amount</td>
				<td align="right">'.$this->Xin_model->currency_sign($payment[0]->payment_amount).'</td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
				<td>Payment Method</td>
				<td align="right">'.$p_method.'</td>
			</tr>
		</table>
		';
		
		$pdf->writeHTML($tbl, true, false, false, false, '');
		}
		// -----------------------------------------------------------------------------
		
		$tbl = '
		<table cellpadding="5" cellspacing="0" border="0">
			<tr>
				<td align="right" colspan="4">Authorised Signatory</td>
			</tr>
		</table>
		';
		
		$pdf->writeHTML($tbl, true, false, false, false, '');
	
		$fname = strtolower($fname);
		$pay_month = strtolower(date("F Y", strtotime($payment[0]->payment_date)));
		//Close and output PDF document
		$pdf->Output('payslip_'.$fname.'_'.$pay_month.'.pdf', 'D');		  
     }
	 
	 // hourly wage templates
	 public function hourly_wages()
     {
		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		$data['breadcrumbs'] = 'Hourly Wages';
		$data['path_url'] = 'hourly_wages';
		$role_resources_ids = $this->Xin_model->user_role_resource();
		if(in_array('39',$role_resources_ids)) {
			if(!empty($session)){ 
			$data['subview'] = $this->load->view("payroll/hourly_wages", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}		  
     }
	 
	 // manage employee salary
	 public function manage_salary()
     {
		$data['title'] = $this->Xin_model->site_title();
		$data['all_employees'] = $this->Xin_model->all_employees();
		$data['breadcrumbs'] = 'Manage Salary';
		$data['path_url'] = 'manage_salary';
		$session = $this->session->userdata('username');
		$role_resources_ids = $this->Xin_model->user_role_resource();
		if(in_array('40',$role_resources_ids)) {
			if(!empty($session)){ 
			$data['subview'] = $this->load->view("payroll/manage_salary", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}		  
     }
	 
	 // generate payslips
	 public function generate_payslip()
     {
		$data['title'] = $this->Xin_model->site_title();
		$data['all_employees'] = $this->Xin_model->all_employees();
		$session = $this->session->userdata('username');
		$data['breadcrumbs'] = 'Generate Payslip';
		$data['path_url'] = 'generate_payslip';
		$role_resources_ids = $this->Xin_model->user_role_resource();
		if(in_array('41',$role_resources_ids)) {
			if(!empty($session)){ 
			$data['subview'] = $this->load->view("payroll/generate_payslip", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}
     }
	 
	 // payment history
	 public function payslip()
     {
		$data['title'] = $this->Xin_model->site_title();
		$payment_id = $this->uri->segment(4);
		
		$result = $this->Payroll_model->read_make_payment_information($payment_id);
		$p_method = '';
		if($result[0]->payment_method==1){
		  $p_method = 'Online';
		} else if($result[0]->payment_method==2){
		  $p_method = 'PayPal';
		} else if($result[0]->payment_method==3) {
		  $p_method = 'Payoneer';
		} else if($result[0]->payment_method==4){
		  $p_method = 'Bank Transfer';
		} else if($result[0]->payment_method==5) {
		  $p_method = 'Cheque';
		} else {
		  $p_method = 'Cash';
		}
		// get addd by > template
		$user = $this->Xin_model->read_user_info($result[0]->employee_id);
		// user full name
		$full_name = $user[0]->first_name.' '.$user[0]->last_name;
		// get designation
		$designation = $this->Designation_model->read_designation_information($user[0]->designation_id);
		// department
		$department = $this->Department_model->read_department_information($user[0]->department_id);
		//$department_designation = $designation[0]->designation_name.'('.$department[0]->department_name.')';
		$data['all_employees'] = $this->Xin_model->all_employees();
		$data = array(
				'title' => $this->Xin_model->site_title(),
				'first_name' => $user[0]->first_name,
				'last_name' => $user[0]->last_name,
				'employee_id' => $user[0]->employee_id,
				'contact_no' => $user[0]->contact_no,
				'date_of_joining' => $user[0]->date_of_joining,
				'department_name' => $department[0]->department_name,
				'designation_name' => $designation[0]->designation_name,
				'date_of_joining' => $user[0]->date_of_joining,
				'profile_picture' => $user[0]->profile_picture,
				'gender' => $user[0]->gender,
				'monthly_grade_id' => $user[0]->monthly_grade_id,
				'hourly_grade_id' => $user[0]->hourly_grade_id,
				'make_payment_id' => $result[0]->make_payment_id,
				'basic_salary' => $result[0]->basic_salary,
				'payment_date' => $result[0]->payment_date,
				'payment_amount' => $result[0]->payment_amount,
				'payment_method' => $p_method,
				'overtime_rate' => $result[0]->overtime_rate,
				'hourly_rate' => $result[0]->hourly_rate,
				'total_hours_work' => $result[0]->total_hours_work,
				'is_payment' => $result[0]->is_payment,
				'house_rent_allowance' => $result[0]->house_rent_allowance,
				'medical_allowance' => $result[0]->medical_allowance,
				'travelling_allowance' => $result[0]->travelling_allowance,
				'convey_allowance' => $result[0]->convey_allowance,
				'dearness_allowance' => $result[0]->dearness_allowance,
				'provident_fund' => $result[0]->provident_fund,
				'security_deposit' => $result[0]->security_deposit,
				'tax_deduction' => $result[0]->tax_deduction,
				'gross_salary' => $result[0]->gross_salary,
				'total_allowances' => $result[0]->total_allowances,
				'total_deductions' => $result[0]->total_deductions,
				'net_salary' => $result[0]->net_salary,
				'comments' => $result[0]->comments,
				);
		$data['breadcrumbs'] = 'Employee Payslip';
		$data['path_url'] = 'payslip';		
		$session = $this->session->userdata('username');
		$role_resources_ids = $this->Xin_model->user_role_resource();
		if(!empty($session)){ 
		$data['subview'] = $this->load->view("payroll/payslip", $data, TRUE);
		$this->load->view('layout_main', $data); //page load
		} else {
			redirect('');
		}
     }
	 
	 // payment history
	 public function payment_history()
     {
		$data['title'] = $this->Xin_model->site_title();
		$data['all_employees'] = $this->Xin_model->all_employees();
		$data['breadcrumbs'] = 'Payment History';
		$data['path_url'] = 'payment_history';
		$session = $this->session->userdata('username');
		$role_resources_ids = $this->Xin_model->user_role_resource();
		if(in_array('42',$role_resources_ids)) {
			if(!empty($session)){
			$data['subview'] = $this->load->view("payroll/payment_history", $data, TRUE);
			$this->load->view('layout_main', $data); //page load
			} else {
				redirect('');
			}
		} else {
			redirect('dashboard/');
		}		  
     }
	 
 	 // payroll template list
    public function template_list()
     {

		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("payroll/templates", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		
		$template = $this->Payroll_model->get_templates();
		
		$data = array();

          foreach($template->result() as $r) {

			  // get addd by > template
			  $user = $this->Xin_model->read_user_info($r->added_by);
			  // user full name
			  $full_name = $user[0]->first_name.' '.$user[0]->last_name;
			  
			  // get basic salary
			  $sbs = $this->Xin_model->currency_sign($r->basic_salary);
			  // get net salary
			  $sns = $this->Xin_model->currency_sign($r->net_salary);
			  // get date > created at > and format
			  $cdate = $this->Xin_model->set_date_format($r->created_at);
			  // total allowance
				if($r->total_allowance == 0 || $r->total_allowance=='') {
					$allowance = '-';
				} else{
					$allowance = $this->Xin_model->currency_sign($r->total_allowance);
				}

               $data[] = array(
			   		'<span data-toggle="tooltip" data-placement="top" title="Edit"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-salary_template_id="'. $r->salary_template_id . '"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="Delete"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->salary_template_id . '"><i class="fa fa-trash-o"></i></button></span>',
                    $r->salary_grades,
                    $sbs,
                    $sns,
                    $allowance,
					$full_name,
					$cdate
               );
          }

          $output = array(
               "draw" => $draw,
                 "recordsTotal" => $template->num_rows(),
                 "recordsFiltered" => $template->num_rows(),
                 "data" => $data
            );
          echo json_encode($output);
          exit();
     }
	 
	 // hourly_list > templates
	 public function payment_history_list()
     {

		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("payroll/hourly_wages", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		
		$history = $this->Payroll_model->all_payment_history();
		
		$data = array();

          foreach($history->result() as $r) {

			  // get addd by > template
			  $user = $this->Xin_model->read_user_info($r->employee_id);
			  // user full name
			  $full_name = $user[0]->first_name.' '.$user[0]->last_name;
			  
			  $emp_link = '<a target="_blank" href="'.site_url().'employees/detail/'.$r->employee_id.'/">'.$user[0]->employee_id.'</a>';
			  		  
			  $month_payment = date("F, Y", strtotime($r->payment_date));

			  $p_amount = $this->Xin_model->currency_sign($r->payment_amount);
	
			  // get date > created at > and format
			  $created_at = $this->Xin_model->set_date_format($r->created_at);
			   // get hourly rate
			  // payslip
		 	 $payslip = '<a class="text-success" href="'.site_url().'payroll/payslip/id/'.$r->make_payment_id.'/">Generate Payslip</a>';
			 // view
			 $functions = '<span data-toggle="tooltip" data-placement="top" title="View"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".detail_modal_data" data-employee_id="'. $r->employee_id . '" data-pay_id="'. $r->make_payment_id . '"><i class="fa fa-arrow-circle-right"></i></button></span>';
			  
			  if($r->payment_method==1){
			  $p_method = 'Online';
			  } else if($r->payment_method==2){
				  $p_method = 'PayPal';
			  } else if($r->payment_method==3) {
				  $p_method = 'Payoneer';
			  } else if($r->payment_method==4){
				  $p_method = 'Bank Transfer';
			  } else if($r->payment_method==5) {
				  $p_method = 'Cheque';
			  } else {
				  $p_method = 'Cash';
			  }

               $data[] = array(
			   		$functions,
					$emp_link,
                    $full_name,
                    $p_amount,
                    $month_payment,
                    $created_at,
					$p_method,
					$payslip
               );
          }

          $output = array(
               "draw" => $draw,
                 "recordsTotal" => $history->num_rows(),
                 "recordsFiltered" => $history->num_rows(),
                 "data" => $data
            );
          echo json_encode($output);
          exit();
     }
	 
	 // hourly_list > templates
	 public function hourly_list()
     {

		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("payroll/hourly_wages", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		
		$hourly_wages = $this->Payroll_model->get_hourly_wages();
		
		$data = array();

          foreach($hourly_wages->result() as $r) {

			  // get addd by > template
			  $user = $this->Xin_model->read_user_info($r->added_by);
			  // user full name
			  $full_name = $user[0]->first_name.' '.$user[0]->last_name;
	
			  // get date > created at > and format
			  $cdate = $this->Xin_model->set_date_format($r->created_at);
			   // get hourly rate
			  $hourly_rate = $this->Xin_model->currency_sign($r->hourly_rate);

               $data[] = array(
			   		'<span data-toggle="tooltip" data-placement="top" title="Edit"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-hourly_rate_id="'. $r->hourly_rate_id . '"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="Delete"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->hourly_rate_id . '"><i class="fa fa-trash-o"></i></button></span>',
                    $r->hourly_grade,
                    $hourly_rate,
                    $full_name,
                    $cdate
               );
          }

          $output = array(
               "draw" => $draw,
                 "recordsTotal" => $hourly_wages->num_rows(),
                 "recordsFiltered" => $hourly_wages->num_rows(),
                 "data" => $data
            );
          echo json_encode($output);
          exit();
     }
	 
	 // hourly_list > templates
	 public function payslip_list()
     {

		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("payroll/generate_payslip", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		// date and employee id
		if($this->input->get("employee_id")){
			$employee_id = $this->input->get("employee_id");
			$p_date = $this->input->get("month_year");
			$payslip = $this->Payroll_model->get_employee_template($employee_id);
		} else {
			$employee_id = $this->input->get("employee_id");
			$p_date = $this->input->get("month_year");
			$payslip = $this->Employees_model->get_employees();
		}
		
		$data = array();

          foreach($payslip->result() as $r) {
			  // user full name
			$full_name = $r->first_name.' '.$r->last_name;
			
			// get total hours > worked > employee
			$result = $this->Payroll_model->total_hours_worked($r->user_id,$this->input->get('month_year'));
			/* total work clock-in > clock-out  */
			$hrs_old_int1 = '';
			$Total = '';
			$Trest = '';
			$total_time_rs = '';
			$hrs_old_int_res1 = '';
			foreach ($result->result() as $hour_work){
				// total work			
				$clock_in =  new DateTime($hour_work->clock_in);
				$clock_out =  new DateTime($hour_work->clock_out);
				$interval_late = $clock_in->diff($clock_out);
				$hours_r  = $interval_late->format('%h');
				$minutes_r = $interval_late->format('%i');			
				$total_time = $hours_r .":".$minutes_r.":".'00';
				
				$str_time = $total_time;
			
				$str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $str_time);
				
				sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);
				
				$hrs_old_seconds = $hours * 3600 + $minutes * 60 + $seconds;
				
				$hrs_old_int1 += $hrs_old_seconds;
				
				$Total = gmdate("H", $hrs_old_int1);			
			}
			
			if($r->monthly_grade_id =='' || $r->monthly_grade_id ==0) {
				$hourly_template = $this->Payroll_model->read_hourly_wage_information($r->hourly_grade_id);
				if($hourly_template[0]->hourly_grade){
					$template = $hourly_template[0]->hourly_grade.' (Hourly)';
					$basic_salary = $hourly_template[0]->hourly_rate.' (Per Hour)';
					$net_salary = $Total * $hourly_template[0]->hourly_rate;
					$create_id = $hourly_template[0]->hourly_rate_id;
					$gd = 'hr';
					$p_class = 'emo_hourly_pay';
					$unpaid_view = '<span data-toggle="tooltip" data-placement="top" title="View"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".hourlywages_template_modal" data-employee_id="'. $r->user_id . '"><i class="fa fa-arrow-circle-right"></i></button></span>';
				}
			} else if($r->monthly_grade_id !='' || $r->monthly_grade_id !=0) {
				$grade_template = $this->Payroll_model->read_template_information($r->monthly_grade_id);
				if($grade_template[0]->salary_grades){
					$template = $grade_template[0]->salary_grades.' (Monthly)';
					$basic_salary = $grade_template[0]->basic_salary;
					$net_salary = $grade_template[0]->net_salary;
					$create_id = $grade_template[0]->salary_template_id;
					$gd = 'sl';
					$p_class = 'emo_monthly_pay';
					$unpaid_view = '<span data-toggle="tooltip" data-placement="top" title="View"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".payroll_template_modal" data-employee_id="'. $r->user_id . '"><i class="fa fa-arrow-circle-right"></i></button></span>';
				}
			}
				
			// make payment
			$payment_check = $this->Payroll_model->read_make_payment_payslip_check($r->user_id,$p_date);
			if($payment_check->num_rows() > 0){
				$make_payment = $this->Payroll_model->read_make_payment_payslip($r->user_id,$p_date);
				$functions = '<a class="text-success" href="'.site_url().'payroll/payslip/id/'.$make_payment[0]->make_payment_id.'/">Generate Payslip</a>';
				$status = '<span class="tag tag-success">Paid</span>';
				$p_details = '<span data-toggle="tooltip" data-placement="top" title="View"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".detail_modal_data" data-employee_id="'. $r->user_id . '" data-pay_id="'. $make_payment[0]->make_payment_id . '"><i class="fa fa-arrow-circle-right"></i></button></span>';
				} else {
					if($net_salary > 0) {
					$functions = '<span data-toggle="tooltip" data-placement="top" title="Make Payment"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".'.$p_class.'" data-employee_id="'. $r->user_id . '" data-payment_date="'. $p_date . '"><i class="fa fa-money"></i></button>...</span>';
					} else {
					$functions = "<span class='text-danger' data-toggle='tooltip' data-placement='left' title='You cant make payment because net salary is 0'>0 Net Salary</span>";
					}
				$status = '<span class="tag tag-danger">UnPaid</span>';
				$p_details = $unpaid_view;
				//$p_details = '-';
				}
			$data[] = array(
				$r->employee_id,
				$full_name,
				$template,
				$basic_salary,
				$net_salary,
				$p_details,
				$status,
				$functions
			);
          }

          $output = array(
               "draw" => $draw,
                 "recordsTotal" => $payslip->num_rows(),
                 "recordsFiltered" => $payslip->num_rows(),
                 "data" => $data
            );
          echo json_encode($output);
          exit();
     }
	 
	 // salary list
	 public function salary_list()
     {

		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("payroll/manage_salary", $data);
		} else {
			redirect('');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		if($this->input->get("employee_id")) {
			$salary = $this->Payroll_model->get_employee_template($this->input->get("employee_id"));
		} else {
			$salary = $this->Employees_model->get_employees();
		}
		
		$data = array();

		foreach($salary->result() as $r) {
		
		// user role
		$role = $this->Xin_model->read_user_role_info($r->user_role_id);
		// get designation
		$designation = $this->Designation_model->read_designation_information($r->designation_id);
		// department
		$department = $this->Department_model->read_department_information($r->department_id);
		$department_designation = $designation[0]->designation_name.'('.$department[0]->department_name.')';		  
		  
		/* for salary template > hourly*/
		$checked = '';
		/* for salary template > monthly*/
		$m_checked = '';			
		/* for salary template > hourly*/
		$disabled = '';
		if($r->hourly_grade_id == 0 || $r->hourly_grade_id == '') {
			$disabled = 'disabled';
		} else {
			$checked = 'checked';
		}
		/* for salary template > monthly*/
		$m_disabled = '';
		if($r->monthly_grade_id == 0 || $r->monthly_grade_id == '') {
			$m_disabled = 'disabled';
		} else {
			$m_checked = 'checked';
		}
		
		/*  all hourly templates */
		$hourly_rate = '';
		$hr_radio = '
		<span data-toggle="tooltip" data-placement="top" title="Select Hourly"><label class="custom-control custom-checkbox">
			<input type="checkbox" class="custom-control-input hourly_grade hourly_'.$r->user_id.'" id="'.$r->user_id.'" name="grade_status['.$r->user_id.']" value="hourly" '.$checked.'>
			<span class="custom-control-indicator"></span>
			<span class="custom-control-description">&nbsp;</span>
		</label></span>
		<input type="hidden" name="user['.$r->user_id.']" value="'.$r->user_id.'">
		';
		$hourly_rate = $hr_radio . ' <select class="custom-select m-r-1 sm_hourly_'.$r->user_id.'" name="hourly_grade_id['.$r->user_id.']" '.$disabled.'>';
		$hourly_rate .= '<option value="0">--Select--</option>';
		$selected = '';
		foreach($this->Payroll_model->all_hourly_templates() as $hourly_template){
			if($r->hourly_grade_id == $hourly_template->hourly_rate_id) {
				$selected = 'selected';
			} else {
				$selected = '';
			}
			$hourly_rate .= '<option value="'.$hourly_template->hourly_rate_id.'" '.$selected.'>'.$hourly_template->hourly_grade.'</option>';
		}
		$hourly_rate .= '</select>';
		
		/*  all salary templates */
		$_salary_template = '';
		$salary_radio = '
		<span data-toggle="tooltip" data-placement="top" title="Select Monthly">
		<label class="custom-control custom-checkbox">
			<input type="checkbox" class="custom-control-input monthly_grade monthly_'.$r->user_id.'" id="'.$r->user_id.'" name="grade_status['.$r->user_id.']" value="monthly" '.$m_checked.'>
			<span class="custom-control-indicator"></span>
			<span class="custom-control-description">&nbsp;</span>
		</label></span>
		';
		$_salary_template = $salary_radio . ' <select class="custom-select m-r-1 sm_monthly_'.$r->user_id.'" name="monthly_grade_id['.$r->user_id.']" '.$m_disabled.'>';
		$_salary_template .= '<option value="0">--Select--</option>';
		$m_selected = '';
		foreach($this->Payroll_model->all_salary_templates() as $salary_template){
		
		if($r->monthly_grade_id == $salary_template->salary_template_id) {
			$m_selected = 'selected';
		} else {
			$m_selected = '';
		}
		$_salary_template .= '<option value="'.$salary_template->salary_template_id.'" '.$m_selected.'>'.$salary_template->salary_grades.'</option>';
		}
		$_salary_template .= '</select>';
		
		$_salary_template .= '<script type="text/javascript">
		$(document).ready(function () {
			$(".hourly_grade").click(function(e){
				var th = $(this), id = th.attr("id");
				$(".monthly_"+id).prop("checked", false);
				$(".sm_monthly_"+id).prop("disabled", true);
				$(".sm_monthly_"+id).val("0");
				if (th.is(":checked")) {
					$(".sm_hourly_"+id).prop("disabled", false);
				} else {
					$(".sm_hourly_"+id).val("0");
				}
			});
		});
		</script>';
		$_salary_template .= '<script type="text/javascript">
		$(document).ready(function () {
			$(".monthly_grade").click(function(e){
				var th = $(this), id = th.attr("id");
				$(".hourly_"+id).prop("checked", false);
				$(".sm_hourly_"+id).prop("disabled", true);
				$(".sm_hourly_"+id).val("0");
				if (th.is(":checked")) {
					$(".sm_monthly_"+id).prop("disabled", false);
				} else {
					$(".sm_monthly_"+id).val("0");
				}
			});
		});
		</script>';
		$fname = $r->first_name.' '.$r->last_name;
		
		if(($r->monthly_grade_id ==0 || $r->hourly_grade_id=='') && ($r->hourly_grade_id ==0 || $r->hourly_grade_id=='')) {
			$functions = '-';
		} else {
			if($r->monthly_grade_id!=0){
			$functions = '<span data-toggle="tooltip" data-placement="top" title="View"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".payroll_template_modal" data-employee_id="'. $r->user_id . '"><i class="fa fa-arrow-circle-right"></i></button></span>';
			} else {
				$functions = '<span data-toggle="tooltip" data-placement="top" title="View"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".hourlywages_template_modal" data-employee_id="'. $r->user_id . '"><i class="fa fa-arrow-circle-right"></i></button></span>';
			}
		}

               $data[] = array(
			   		$functions,
					$fname,
                    $r->username,
                    $department_designation,
                    $hourly_rate,
                    $_salary_template
               );
          }

          $output = array(
               "draw" => $draw,
                 "recordsTotal" => $salary->num_rows(),
                 "recordsFiltered" => $salary->num_rows(),
                 "data" => $data
            );
          echo json_encode($output);
          exit();
     }
	 
	// make payment info by id
	public function make_payment_view()
	{
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('pay_id');
       // $data['all_countries'] = $this->xin_model->get_countries();
		$result = $this->Payroll_model->read_make_payment_information($id);
		// get addd by > template
		$user = $this->Xin_model->read_user_info($result[0]->employee_id);
		// get designation
		$designation = $this->Designation_model->read_designation_information($user[0]->designation_id);
		// department
		$department = $this->Department_model->read_department_information($user[0]->department_id);
		
		$data = array(
				'first_name' => $user[0]->first_name,
				'last_name' => $user[0]->last_name,
				'employee_id' => $user[0]->employee_id,
				'department_name' => $department[0]->department_name,
				'designation_name' => $designation[0]->designation_name,
				'date_of_joining' => $user[0]->date_of_joining,
				'profile_picture' => $user[0]->profile_picture,
				'gender' => $user[0]->gender,
				'monthly_grade_id' => $user[0]->monthly_grade_id,
				'hourly_grade_id' => $user[0]->hourly_grade_id,
				'basic_salary' => $result[0]->basic_salary,
				'payment_date' => $result[0]->payment_date,
				'payment_method' => $result[0]->payment_method,
				'overtime_rate' => $result[0]->overtime_rate,
				'hourly_rate' => $result[0]->hourly_rate,
				'total_hours_work' => $result[0]->total_hours_work,
				'is_payment' => $result[0]->is_payment,
				'house_rent_allowance' => $result[0]->house_rent_allowance,
				'medical_allowance' => $result[0]->medical_allowance,
				'travelling_allowance' => $result[0]->travelling_allowance,
				'convey_allowance' => $result[0]->convey_allowance,
				'dearness_allowance' => $result[0]->dearness_allowance,
				'provident_fund' => $result[0]->provident_fund,
				'security_deposit' => $result[0]->security_deposit,
				'tax_deduction' => $result[0]->tax_deduction,
				'gross_salary' => $result[0]->gross_salary,
				'total_allowances' => $result[0]->total_allowances,
				'total_deductions' => $result[0]->total_deductions,
				'net_salary' => $result[0]->net_salary,
				'comments' => $result[0]->comments,
				);
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view('payroll/dialog_payslip', $data);
		} else {
			redirect('');
		}
	}
	
	// pay monthly > create payslip
	public function pay_monthly()
	{
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('employee_id');
        // get addd by > template
		$user = $this->Xin_model->read_user_info($id);
		$result = $this->Payroll_model->read_template_information($user[0]->monthly_grade_id);
		$department = $this->Department_model->read_department_information($user[0]->department_id);
		$location = $this->Location_model->read_location_information($department[0]->location_id);
		$data = array(
				'department_id' => $user[0]->department_id,
				'designation_id' => $user[0]->designation_id,
				'location_id' => $location[0]->location_id,
				'company_id' => $location[0]->company_id,
				'salary_template_id' => $result[0]->salary_template_id,
				'user_id' => $user[0]->user_id,
				'salary_grades' => $result[0]->salary_grades,
				'basic_salary' => $result[0]->basic_salary,
				'overtime_rate' => $result[0]->overtime_rate,
				'house_rent_allowance' => $result[0]->house_rent_allowance,
				'medical_allowance' => $result[0]->medical_allowance,
				'travelling_allowance' => $result[0]->travelling_allowance,
				'convey_allowance' => $result[0]->convey_allowance,
				'dearness_allowance' => $result[0]->dearness_allowance,
				'security_deposit' => $result[0]->security_deposit,
				'provident_fund' => $result[0]->provident_fund,
				'tax_deduction' => $result[0]->tax_deduction,
				'gross_salary' => $result[0]->gross_salary,
				'total_allowance' => $result[0]->total_allowance,
				'total_deduction' => $result[0]->total_deduction,
				'net_salary' => $result[0]->net_salary,
				'added_by' => $result[0]->added_by,
				);
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view('payroll/dialog_make_payment', $data);
		} else {
			redirect('');
		}
	}
	
	// pay hourly > create payslip
	public function pay_hourly()
	{
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('employee_id');
        // get addd by > template
		$user = $this->Xin_model->read_user_info($id);
		$result = $this->Payroll_model->read_hourly_wage_information($user[0]->hourly_grade_id);
		$department = $this->Department_model->read_department_information($user[0]->department_id);
		$location = $this->Location_model->read_location_information($department[0]->location_id);
		$data = array(
				'department_id' => $user[0]->department_id,
				'designation_id' => $user[0]->designation_id,
				'location_id' => $location[0]->location_id,
				'company_id' => $location[0]->company_id,
				'hourly_rate_id' => $result[0]->hourly_rate_id,
				'user_id' => $user[0]->user_id,
				'hourly_rate' => $result[0]->hourly_rate,
				);
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view('payroll/dialog_make_payment', $data);
		} else {
			redirect('');
		}
	}
	 
	// get payroll template info by id
	public function template_read()
	{
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('salary_template_id');
       // $data['all_countries'] = $this->xin_model->get_countries();
		$result = $this->Payroll_model->read_template_information($id);
		$data = array(
				'salary_template_id' => $result[0]->salary_template_id,
				'salary_grades' => $result[0]->salary_grades,
				'basic_salary' => $result[0]->basic_salary,
				'overtime_rate' => $result[0]->overtime_rate,
				'house_rent_allowance' => $result[0]->house_rent_allowance,
				'medical_allowance' => $result[0]->medical_allowance,
				'travelling_allowance' => $result[0]->travelling_allowance,
				'convey_allowance' => $result[0]->convey_allowance,
				'dearness_allowance' => $result[0]->dearness_allowance,
				'security_deposit' => $result[0]->security_deposit,
				'provident_fund' => $result[0]->provident_fund,
				'tax_deduction' => $result[0]->tax_deduction,
				'gross_salary' => $result[0]->gross_salary,
				'total_allowance' => $result[0]->total_allowance,
				'total_deduction' => $result[0]->total_deduction,
				'net_salary' => $result[0]->net_salary,
				'added_by' => $result[0]->added_by,
				);
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view('payroll/dialog_templates', $data);
		} else {
			redirect('');
		}
	}
	
	// get payroll template info by id
	public function payroll_template_read()
	{
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('employee_id');
		// get addd by > template
		$user = $this->Xin_model->read_user_info($id);
		// user full name
		$full_name = $user[0]->first_name.' '.$user[0]->last_name;
		// get designation
		$designation = $this->Designation_model->read_designation_information($user[0]->designation_id);
		// department
		$department = $this->Department_model->read_department_information($user[0]->department_id);
		$data = array(
				'first_name' => $user[0]->first_name,
				'last_name' => $user[0]->last_name,
				'employee_id' => $user[0]->employee_id,
				'department_name' => $department[0]->department_name,
				'designation_name' => $designation[0]->designation_name,
				'date_of_joining' => $user[0]->date_of_joining,
				'profile_picture' => $user[0]->profile_picture,
				'gender' => $user[0]->gender,
				'monthly_grade_id' => $user[0]->monthly_grade_id,
				'hourly_grade_id' => $user[0]->hourly_grade_id
				);
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view('payroll/dialog_templates', $data);
		} else {
			redirect('');
		}
	}
	
	// get hourly wage template info by id
	public function hourlywage_template_read()
	{
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('employee_id');
		// get addd by > template
		$user = $this->Xin_model->read_user_info($id);
		// user full name
		$full_name = $user[0]->first_name.' '.$user[0]->last_name;
		// get designation
		$designation = $this->Designation_model->read_designation_information($user[0]->designation_id);
		// department
		$department = $this->Department_model->read_department_information($user[0]->department_id);
		$data = array(
				'first_name' => $user[0]->first_name,
				'last_name' => $user[0]->last_name,
				'employee_id' => $user[0]->employee_id,
				'department_name' => $department[0]->department_name,
				'designation_name' => $designation[0]->designation_name,
				'date_of_joining' => $user[0]->date_of_joining,
				'profile_picture' => $user[0]->profile_picture,
				'gender' => $user[0]->gender,
				'monthly_grade_id' => $user[0]->monthly_grade_id,
				'hourly_grade_id' => $user[0]->hourly_grade_id
				);
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view('payroll/dialog_templates', $data);
		} else {
			redirect('');
		}
	}
	
	// get hourly wage info by id
	public function hourly_wage_read()
	{
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('hourly_rate_id');
       // $data['all_countries'] = $this->xin_model->get_countries();
		$result = $this->Payroll_model->read_hourly_wage_information($id);
		$data = array(
				'hourly_rate_id' => $result[0]->hourly_rate_id,
				'hourly_grade' => $result[0]->hourly_grade,
				'hourly_rate' => $result[0]->hourly_rate,
				'added_by' => $result[0]->added_by
				);
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view('payroll/dialog_hourly_wages', $data);
		} else {
			redirect('');
		}
	}
	
	// Validate and add info in database
	public function add_template() {
	
		if($this->input->post('add_type')=='payroll') {		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
			
		/* Server side PHP input validation */
		if($this->input->post('salary_grades')==='') {
        	$Return['error'] = "The name of template field is required.";
		} else if($this->input->post('basic_salary')==='') {
			$Return['error'] = "The basic salary field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'salary_grades' => $this->input->post('salary_grades'),
		'basic_salary' => $this->input->post('basic_salary'),
		'overtime_rate' => $this->input->post('overtime_rate'),
		'house_rent_allowance' => $this->input->post('house_rent_allowance'),
		'medical_allowance' => $this->input->post('medical_allowance'),
		'travelling_allowance' => $this->input->post('travelling_allowance'),
		'convey_allowance' => $this->input->post('convey_allowance'),
		'dearness_allowance' => $this->input->post('dearness_allowance'),
		'provident_fund' => $this->input->post('provident_fund'),
		'tax_deduction' => $this->input->post('tax_deduction'),
		'security_deposit' => $this->input->post('security_deposit'),
		'gross_salary' => $this->input->post('gross_salary'),
		'total_allowance' => $this->input->post('total_allowance'),
		'total_deduction' => $this->input->post('total_deduction'),
		'net_salary' => $this->input->post('net_salary'),
		'added_by' => $this->input->post('user_id'),
		'created_at' => date('d-m-Y h:i:s'),
		
		);
		$result = $this->Payroll_model->add_template($data);
		if ($result == TRUE) {
			$Return['result'] = 'Payroll Tempalte added.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}
	
	// Validate and add info in database
	public function add_hourly_rate() {
	
		if($this->input->post('add_type')=='payroll') {		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
			
		/* Server side PHP input validation */
		if($this->input->post('hourly_grade')==='') {
        	$Return['error'] = "The title field is required.";
		} else if($this->input->post('hourly_rate')==='') {
			$Return['error'] = "The hourly rate field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'hourly_grade' => $this->input->post('hourly_grade'),
		'hourly_rate' => $this->input->post('hourly_rate'),
		'added_by' => $this->input->post('user_id'),
		'created_at' => date('d-m-Y h:i:s')
		);
		$result = $this->Payroll_model->add_hourly_wages($data);
		if ($result == TRUE) {
			$Return['result'] = 'Hourly Wage Template added.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}
	
	// Validate and update info in database
	public function update_template() {
	
		if($this->input->post('edit_type')=='payroll') {
			
		$id = $this->uri->segment(3);
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
			
		/* Server side PHP input validation */
		if($this->input->post('salary_grades')==='') {
        	$Return['error'] = "The name of template field is required.";
		} else if($this->input->post('basic_salary')==='') {
			$Return['error'] = "The basic salary field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'salary_grades' => $this->input->post('salary_grades'),
		'basic_salary' => $this->input->post('basic_salary'),
		'overtime_rate' => $this->input->post('overtime_rate'),
		'house_rent_allowance' => $this->input->post('house_rent_allowance'),
		'medical_allowance' => $this->input->post('medical_allowance'),
		'travelling_allowance' => $this->input->post('travelling_allowance'),
		'convey_allowance' => $this->input->post('convey_allowance'),
		'dearness_allowance' => $this->input->post('dearness_allowance'),
		'provident_fund' => $this->input->post('provident_fund'),
		'tax_deduction' => $this->input->post('tax_deduction'),
		'security_deposit' => $this->input->post('security_deposit'),
		'gross_salary' => $this->input->post('gross_salary'),
		'total_allowance' => $this->input->post('total_allowance'),
		'total_deduction' => $this->input->post('total_deduction'),
		'net_salary' => $this->input->post('net_salary')
		);	
		
		$result = $this->Payroll_model->update_template_record($data,$id);		
		
		if ($result == TRUE) {
			$Return['result'] = 'Payroll Template updated.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}
	
	// Validate and update info in database
	public function update_hourly_wages() {
	
		if($this->input->post('edit_type')=='payroll') {
			
		$id = $this->uri->segment(3);
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
			
		/* Server side PHP input validation */
		if($this->input->post('hourly_grade')==='') {
        	$Return['error'] = "The title field is required.";
		} else if($this->input->post('hourly_rate')==='') {
			$Return['error'] = "The hourly rate field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'hourly_grade' => $this->input->post('hourly_grade'),
		'hourly_rate' => $this->input->post('hourly_rate')
		);
		
		$result = $this->Payroll_model->update_hourly_wages_record($data,$id);		
		
		if ($result == TRUE) {
			$Return['result'] = 'Hourly Wage Template updated.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}
	
	// Validate and update info in database > update salary template
	public function user_salary_template() {
	
		if($this->input->post('edit_type')=='payroll') {
					
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
		$count = count($this->input->post('grade_status'));
			
		/* Set Salary Template for User*/
	   if($count > 0) {
		    $grade_status = $this->input->post("grade_status");
		   foreach($grade_status as $key=>$val) {
				//update salary template info in DB
				$data = array(
				'salary_template' => $val
				);
				$this->Payroll_model->update_salary_template($data, $key);
		   }
	   }  else {
			foreach($this->input->post('user') as $key=>$val) {
				//update salary template info in DB
				if(null==$this->input->post('grade_monthly')) {
					//update salary template info in DB
					$data = array(
					'salary_template' => ''
					);
					$this->Payroll_model->update_empty_salary_template($data, $key);
				}
		   }
	   }
	   
	   /* Set Hourly Grade/ for User */
	   if(null!=$this->input->post('hourly_grade_id')) {
		foreach($this->input->post('hourly_grade_id') as $key=>$val) {
			//update Hourly Grade info in DB
			$data = array(
			'hourly_grade_id' => $val,
			'monthly_grade_id' => '0'
			);
			$this->Payroll_model->update_hourlygrade_salary_template($data, $key);
		}
	   } else {
			foreach($this->input->post('user') as $key=>$val) {
				//update salary template info in DB
				if(null==$this->input->post('hourly_grade_id')) {
					//update Hourly Grade info in DB
					$data = array(
					'hourly_grade_id' => '0',
					);
					$this->Payroll_model->update_hourlygrade_zero($data, $key);
				}
		   }
	   }
	   
	   /* Set Monthly Grade/ for User */
	   if(null!=$this->input->post('monthly_grade_id')) {
		   foreach($this->input->post('monthly_grade_id') as $key=>$val) {
				//update Hourly Grade info in DB
				$data = array(
				'hourly_grade_id' => '0',
				'monthly_grade_id' => $val
				);
				$this->Payroll_model->update_monthlygrade_salary_template($data, $key);
			
		   }
	   } else {
			foreach($this->input->post('user') as $key=>$val) {
				if(null==$this->input->post('monthly_grade_id')) {
					//update Hourly Grade info in DB
					$data = array(
					'monthly_grade_id' => '0'
					);
					$this->Payroll_model->update_monthlygrade_zero($data, $key);
				}
		   }
	   }
	   
		$Return['result'] = 'Salary Information updated.';
		$this->output($Return);
		exit;
		}
	}
	
	// Validate and add info in database > add monthly payment
	public function add_pay_monthly() {
	if($this->input->post('add_type')=='add_monthly_payment') {		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
		/* Server side PHP input validation */
		if($this->input->post('payment_method')==='') {
        	$Return['error'] = "The payment method field is required.";
		} else if($this->input->post('comments')==='') {
			$Return['error'] = "The comments field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'employee_id' => $this->input->post('emp_id'),
		'department_id' => $this->input->post('department_id'),
		'company_id' => $this->input->post('company_id'),
		'location_id' => $this->input->post('location_id'),
		'designation_id' => $this->input->post('designation_id'),
		'payment_date' => $this->input->post('pay_date'),
		'basic_salary' => $this->input->post('basic_salary'),
		'payment_amount' => $this->input->post('payment_amount'),
		'gross_salary' => $this->input->post('gross_salary'),
		'total_allowances' => $this->input->post('total_allowances'),
		'total_deductions' => $this->input->post('total_deductions'),
		'net_salary' => $this->input->post('net_salary'),
		'house_rent_allowance' => $this->input->post('house_rent_allowance'),
		'medical_allowance' => $this->input->post('medical_allowance'),
		'travelling_allowance' => $this->input->post('travelling_allowance'),
		'convey_allowance' => $this->input->post('convey_allowance'),
		'dearness_allowance' => $this->input->post('dearness_allowance'),
		'provident_fund' => $this->input->post('provident_fund'),
		'tax_deduction' => $this->input->post('tax_deduction'),
		'security_deposit' => $this->input->post('security_deposit'),
		'overtime_rate' => $this->input->post('overtime_rate'),
		'is_payment' => '1',
		'payment_method' => $this->input->post('payment_method'),
		'comments' => $this->input->post('comments'),
		'status' => '1',
		'created_at' => date('d-m-Y h:i:s')
		);
		$result = $this->Payroll_model->add_monthly_payment_payslip($data);
		if ($result == TRUE) {
			
			$Return['result'] = 'Payment paid.';
			
			//get setting info 
			$setting = $this->Xin_model->read_setting_info(1);
			if($setting[0]->enable_email_notification == 'yes') {
				
				// load email library
				$this->load->library('email');
				$this->email->set_mailtype("html");
				//get company info
				$cinfo = $this->Xin_model->read_company_setting_info(1);
				//get email template
				$template = $this->Xin_model->read_email_template(1);
				//get employee info
				$user_info = $this->Xin_model->read_user_info($this->input->post('emp_id'));
				$full_name = $user_info[0]->first_name.' '.$user_info[0]->last_name;
				// get date
				$d = explode('-',$this->input->post('pay_date'));
				$get_month = date('F', mktime(0, 0, 0, $d[1], 10));
				$pdate = $get_month.', '.$d[0];
				
				$subject = $template[0]->subject.' - '.$cinfo[0]->company_name;
				$logo = base_url().'uploads/logo/'.$cinfo[0]->logo;
				$cid = $this->email->attachment_cid($logo);
				
				$message = '
			<div style="background:#f6f6f6;font-family:Verdana,Arial,Helvetica,sans-serif;font-size:12px;margin:0;padding:0;padding: 20px;">
			<img src="'.$logo.'" title="'.$cinfo[0]->company_name.'"><br>'.str_replace(array("{var site_name}","{var site_url}","{var employee_name}","{var payslip_date}"),array($cinfo[0]->company_name,site_url(),$full_name,$pdate),html_entity_decode(stripslashes($template[0]->message))).'</div>';
				
				$this->email->from($cinfo[0]->email, $cinfo[0]->company_name);
				$this->email->to($user_info[0]->email);
				
				$this->email->subject($subject);
				$this->email->message($message);
				
				$this->email->send();
			}
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}
	
	// Validate and add info in database > add hourly payment
	public function add_pay_hourly() {
	
		if($this->input->post('add_type')=='pay_hourly') {		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
			
		/* Server side PHP input validation */
		if($this->input->post('payment_method')==='') {
        	$Return['error'] = "The payment method field is required.";
		} else if($this->input->post('comments')==='') {
			$Return['error'] = "The comments field is required.";
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'employee_id' => $this->input->post('emp_id'),
		'department_id' => $this->input->post('department_id'),
		'company_id' => $this->input->post('company_id'),
		'location_id' => $this->input->post('location_id'),
		'designation_id' => $this->input->post('designation_id'),
		'payment_date' => $this->input->post('pay_date'),
		'payment_amount' => $this->input->post('payment_amount'),
		'total_hours_work' => $this->input->post('total_hours_work'),
		'hourly_rate' => $this->input->post('hourly_rate'),
		'is_payment' => '1',
		'payment_method' => $this->input->post('payment_method'),
		'comments' => $this->input->post('comments'),
		'status' => '1',
		'created_at' => date('d-m-Y h:i:s')
		);
		$result = $this->Payroll_model->add_hourly_payment_payslip($data);
		if ($result == TRUE) {
			$Return['result'] = 'Payment paid.';
			
			//get setting info 
			$setting = $this->Xin_model->read_setting_info(1);
			if($setting[0]->enable_email_notification == 'yes') {
				
				// load email library
				$this->load->library('email');
				$this->email->set_mailtype("html");
				//get company info
				$cinfo = $this->Xin_model->read_company_setting_info(1);
				//get email template
				$template = $this->Xin_model->read_email_template(1);
				//get employee info
				$user_info = $this->Xin_model->read_user_info($this->input->post('emp_id'));
				$full_name = $user_info[0]->first_name.' '.$user_info[0]->last_name;
				// get date
				$d = explode('-',$this->input->post('pay_date'));
				$get_month = date('F', mktime(0, 0, 0, $d[1], 10));
				$pdate = $get_month.', '.$d[0];
				
				$subject = $template[0]->subject.' - '.$cinfo[0]->company_name;
				$logo = base_url().'uploads/logo/'.$cinfo[0]->logo;
				
				$message = '
			<div style="background:#f6f6f6;font-family:Verdana,Arial,Helvetica,sans-serif;font-size:12px;margin:0;padding:0;padding: 20px;">
			<img src="'.$logo.'" title="'.$cinfo[0]->company_name.'"><br>'.str_replace(array("{var site_name}","{var site_url}","{var employee_name}","{var payslip_date}"),array($cinfo[0]->company_name,site_url(),$full_name,$pdate),html_entity_decode(stripslashes($template[0]->message))).'</div>';
				
				$this->email->from($cinfo[0]->email, $cinfo[0]->company_name);
				$this->email->to($user_info[0]->email);
				
				$this->email->subject($subject);
				$this->email->message($message);
				
				$this->email->send();
			}
			
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
		exit;
		}
	}
	
	public function delete_template() {
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
		$id = $this->uri->segment(3);
		$result = $this->Payroll_model->delete_template_record($id);
		if(isset($id)) {
			$Return['result'] = 'Payroll Template deleted.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
	}
	
	public function delete_hourly_wage() {
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'');
		$id = $this->uri->segment(3);
		$result = $this->Payroll_model->delete_hourly_wage_record($id);
		if(isset($id)) {
			$Return['result'] = 'Hourly Wage Template deleted.';
		} else {
			$Return['error'] = 'Bug. Something went wrong, please try again.';
		}
		$this->output($Return);
	}
}
