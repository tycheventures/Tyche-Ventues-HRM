<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class travel_model extends CI_Model {
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
 
	public function get_travel() {
	  return $this->db->get("xin_employee_travels");
	}
	
	public function get_employee_travel($id) {
	 	return $query = $this->db->query("SELECT * from xin_employee_travels where employee_id = '".$id."'");
	}
	 
	 public function read_travel_information($id) {
	
		$condition = "travel_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_employee_travels');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		
		if ($query->num_rows() == 1) {
			return $query->result();
		} else {
			return false;
		}
	}
	
	// get all travel arrangement types
	public function travel_arrangement_types()
	{
	  $query = $this->db->query("SELECT * from xin_travel_arrangement_type");
  	  return $query->result();
	}
	
	// Function to add record in table
	public function add($data){
		$this->db->insert('xin_employee_travels', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	// Function to Delete selected record from table
	public function delete_record($id){
		$this->db->where('travel_id', $id);
		$this->db->delete('xin_employee_travels');
		
	}
	
	// Function to update record in table
	public function update_record($data, $id){
		$this->db->where('travel_id', $id);
		if( $this->db->update('xin_employee_travels',$data)) {
			return true;
		} else {
			return false;
		}		
	}
}
?>