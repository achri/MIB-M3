<?php
class Tbl_departemen extends Model {
	
	function Tbl_departemen(){
	// call the Model constructor
		parent::Model();
	// load database class and connect to MySQL
		$this->load->database();
		$this->CI =& get_instance();
	}
	
	function get_dep_flex(){
		$this->db->select('dep_id, dep_name');
		$this->db->from('prc_master_departemen');
		$this->CI->flexigrid->build_query(TRUE);
		$return['records'] = $this->db->get();
				
		//Build count query
		$this->db->select('count(dep_id) as record_count');
		$this->db->from('prc_master_departemen');
		$this->CI->flexigrid->build_query(FALSE);
		$return['record_count'] = $this->db->get()->row()->record_count;
		//$row = $record_count->row();
		
		return $return;
	}
	
	function cek_dep(){
		$this->db->select('count(dep_id) as record_count');
		$this->db->from('prc_master_departemen');
		return $this->db->get()->row()->record_count;
		//$row = $record_count->row();
	}
	
	function delete_dep($dep_id) {
		$this->db->where('dep_id', $dep_id);
		$this->db->delete('prc_master_departemen'); 
	}
	
	function update_dep($dep_id,$dep_name,$usrid) {
		$data = array(
			   'dep_name' => $dep_name,
			   'rec_edit' => 1, //status 1 = record edit || 0 = record not edit
			   'rec_editor' => $usrid,
			   'rec_edited' => date('Y-m-d')
			); 
		$this->db->where('dep_id', $dep_id);
		$this->db->update('prc_master_departemen', $data);
	}

	function cek_departemen($name) {
		$query = $this->db->query("SELECT dep_name FROM prc_master_departemen WHERE dep_name = '$name'");
		return $query->num_rows();
	}
	
	function insert_dep($dep_name,$usrid) {
		$data = array(
               'dep_name' => $dep_name,
			   'rec_creator' => $usrid,
			   'rec_created' => date('Y-m-d')
            );
		$this->db->insert('prc_master_departemen', $data); 
		return $this->db->insert_id();
	}
	
	function list_dep() {
		$this->db->order_by('dep_name','ASC');
		return $this->db->get('prc_master_departemen'); 
	}
	
	function get_departemen($id) {
		$this->db->where('dep_id',$id);
		return $query = $this->db->get('prc_master_departemen');	
	}
	
	//======================cek dep for delete===================
	function cek_delete($id){
		$return['cek1'] = $this->db->query("SELECT dep_id FROM prc_sys_user WHERE dep_id = '$id'")->num_rows();
		$return['cek2'] = $this->db->query("SELECT dep_id FROM prc_master_contact_person WHERE dep_id = '$id'")->num_rows();
		return $return;
	}
}
?>