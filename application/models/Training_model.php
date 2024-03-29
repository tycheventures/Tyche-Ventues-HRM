<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class training_model extends CI_Model {
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
 
	// get training
	public function get_training() {
	  return $this->db->get("xin_training");
	}
	
	// get training type
	public function get_training_type()
	{
	  return $this->db->get("xin_training_types");
	}
	
	// all training_types
	public function all_training_types() {
	  $query = $this->db->query("SELECT * from xin_training_types");
  	  return $query->result();
	}
	 
	 public function read_training_information($id) {
	
		$condition = "training_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_training');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		
		return $query->result();
	}
	// get training type by id
	public function read_training_type_information($id) {
	
		$condition = "training_type_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_training_types');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		
		return $query->result();
	}
	
	// Function to add record in table
	public function add($data){
		$this->db->insert('xin_training', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	// Function to add record in table
	public function add_type($data){
		$this->db->insert('xin_training_types', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	// Function to Delete selected record from table
	public function delete_record($id){
		$this->db->where('training_id', $id);
		$this->db->delete('xin_training');
		
	}
	
	// Function to Delete selected record from table
	public function delete_type_record($id){
		$this->db->where('training_type_id', $id);
		$this->db->delete('xin_training_types');
		
	}
	
	// Function to update record in table
	public function update_record($data, $id){
		$this->db->where('training_id', $id);
		if( $this->db->update('xin_training',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	// Function to update record in table
	public function update_status($data, $id){
		$this->db->where('training_id', $id);
		if( $this->db->update('xin_training',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	// Function to update record in table
	public function update_type_record($data, $id){
		$this->db->where('training_type_id', $id);
		if( $this->db->update('xin_training_types',$data)) {
			return true;
		} else {
			return false;
		}		
	}
}
?>