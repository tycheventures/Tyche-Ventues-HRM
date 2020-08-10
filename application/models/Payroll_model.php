<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class payroll_model extends CI_Model
	{
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
 
	// get payroll templates
	public function get_templates() {
	  return $this->db->get("xin_salary_templates");
	}
	
	// get payroll templates
	public function get_employee_template($id) {
		return $query = $this->db->query("SELECT * from xin_employees where user_id='".$id."'");
	}
	
	// get total hours work > hourly template > payroll generate
	public function total_hours_worked($id,$attendance_date) {
		return $query = $this->db->query("SELECT * from xin_attendance_time where employee_id='".$id."' and attendance_date='".$attendance_date."'");
	}
	
	// get payment history > all payslips
	public function all_payment_history() {
	  return $this->db->get("xin_make_payment");
	}
	
	// get payslips of single employee
	public function get_payroll_slip($id) {
		return $query = $this->db->query("SELECT * from xin_make_payment where employee_id='".$id."'");
	}
	
	// get hourly wages
	public function get_hourly_wages()
	{
	  return $this->db->get("xin_hourly_templates");
	}
	 
	 public function read_template_information($id) {
	
		$condition = "salary_template_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_salary_templates');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		
		return $query->result();
	}
	
	public function read_hourly_wage_information($id) {
	
		$condition = "hourly_rate_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_hourly_templates');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		
		return $query->result();
	}
	
	public function read_make_payment_information($id) {
	
		$condition = "make_payment_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_make_payment');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		
		return $query->result();
	}
	
	
	// Function to add record in table
	public function add_template($data){
		$this->db->insert('xin_salary_templates', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	// Function to add record in table
	public function add_hourly_wages($data){
		$this->db->insert('xin_hourly_templates', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	// Function to add record in table
	public function add_monthly_payment_payslip($data){
		$this->db->insert('xin_make_payment', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	// Function to add record in table
	public function add_hourly_payment_payslip($data){
		$this->db->insert('xin_make_payment', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	// Function to Delete selected record from table
	public function delete_template_record($id){
		$this->db->where('salary_template_id', $id);
		$this->db->delete('xin_salary_templates');
		
	}
	
	// Function to Delete selected record from table
	public function delete_hourly_wage_record($id){
		$this->db->where('hourly_rate_id', $id);
		$this->db->delete('xin_hourly_templates');
		
	}
	
	// Function to update record in table
	public function update_template_record($data, $id){
		$this->db->where('salary_template_id', $id);
		if( $this->db->update('xin_salary_templates',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	// get all hourly templates
	public function all_hourly_templates()
	{
	  $query = $this->db->query("SELECT * from xin_hourly_templates");
  	  return $query->result();
	}
	
	// get all salary tempaltes > payroll templates
	public function all_salary_templates()
	{
	  $query = $this->db->query("SELECT * from xin_salary_templates");
  	  return $query->result();
	}
	
	// Function to update record in table
	public function update_hourly_wages_record($data, $id){
		$this->db->where('hourly_rate_id', $id);
		if( $this->db->update('xin_hourly_templates',$data)) {
			return true;
		} else {
			return false;
		}		
	}	
	
	// Function to update record in table > manage salary
	public function update_salary_template($data, $id){
		$this->db->where('user_id', $id);
		if( $this->db->update('xin_employees',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	// Function to update record in table > empty grade status
	public function update_empty_salary_template($data, $id){
		$this->db->where('user_id', $id);
		if( $this->db->update('xin_employees',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	// Function to update record in table > set hourly grade
	public function update_hourlygrade_salary_template($data, $id){
		$this->db->where('user_id', $id);
		if( $this->db->update('xin_employees',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	// Function to update record in table > set monthly grade
	public function update_monthlygrade_salary_template($data, $id){
		$this->db->where('user_id', $id);
		if( $this->db->update('xin_employees',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	// Function to update record in table > zero hourly grade
	public function update_hourlygrade_zero($data, $id){
		$this->db->where('user_id', $id);
		if( $this->db->update('xin_employees',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	// Function to update record in table > zero monthly grade
	public function update_monthlygrade_zero($data, $id){
		$this->db->where('user_id', $id);
		if( $this->db->update('xin_employees',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	public function read_make_payment_payslip_check($employee_id,$p_date) {
	
		$condition = "employee_id =" . "'" . $employee_id . "' and payment_date =" . "'" . $p_date . "'";
		$this->db->select('*');
		$this->db->from('xin_make_payment');
		$this->db->where($condition);
		$this->db->limit(10000);
		return $query = $this->db->get();
		
		//return $query->result();
	}
	
	public function read_make_payment_payslip($employee_id,$p_date) {
	
		$condition = "employee_id =" . "'" . $employee_id . "' and payment_date =" . "'" . $p_date . "'";
		$this->db->select('*');
		$this->db->from('xin_make_payment');
		$this->db->where($condition);
		$this->db->limit(10000);
		$query = $this->db->get();
		
		return $query->result();
	}
}
?>