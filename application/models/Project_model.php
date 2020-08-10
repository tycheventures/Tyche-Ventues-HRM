<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class project_model extends CI_Model {
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
 
	public function get_projects()
	{
	  return $this->db->get("xin_projects");
	}
	 
	 public function read_project_information($id) {
	
		$condition = "project_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_projects');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		
		if ($query->num_rows() == 1) {
			return $query->result();
		} else {
			return false;
		}
	}
	
	
	// Function to add record in table
	public function add($data){
		$this->db->insert('xin_projects', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	// Function to Delete selected record from table
	public function delete_record($id){
		$this->db->where('project_id', $id);
		$this->db->delete('xin_projects');
		
	}
	
	// Function to update record in table
	public function update_record($data, $id){
		$this->db->where('project_id', $id);
		if( $this->db->update('xin_projects',$data)) {
			return true;
		} else {
			return false;
		}		
	}
}
?>