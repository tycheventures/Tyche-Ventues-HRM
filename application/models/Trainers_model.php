<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class trainers_model extends CI_Model {
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
 
	public function get_trainers() {
	  return $this->db->get("xin_trainers");
	}
	
	// all trainers
	public function all_trainers() {
	  $query = $this->db->query("SELECT * from xin_trainers");
  	  return $query->result();
	}
	 
	 public function read_trainer_information($id) {
	
		$condition = "trainer_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_trainers');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		
		return $query->result();
	}
	
	
	// Function to add record in table
	public function add($data){
		$this->db->insert('xin_trainers', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	// Function to Delete selected record from table
	public function delete_record($id){
		$this->db->where('trainer_id', $id);
		$this->db->delete('xin_trainers');
		
	}
	
	// Function to update record in table
	public function update_record($data, $id){
		$this->db->where('trainer_id', $id);
		if( $this->db->update('xin_trainers',$data)) {
			return true;
		} else {
			return false;
		}		
	}
}
?>