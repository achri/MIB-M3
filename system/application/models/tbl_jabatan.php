<?php
class Tbl_jabatan extends Model {
	
	function Tbl_jabatan(){
	// call the Model constructor
		parent::Model();
	// load database class and connect to MySQL
		$this->load->database();
		$this->CI =& get_instance();
	}

	function get_jab_flex(){
		$this->db->select('jab_id, jab_name');
		$this->db->from('prc_master_jabatan');
		$this->CI->flexigrid->build_query(TRUE);
		$return['records'] = $this->db->get();
				
		//Build count query
		$this->db->select('count(jab_id) as record_count');
		$this->db->from('prc_master_jabatan');
		$this->CI->flexigrid->build_query(FALSE);
		$return['record_count'] = $this->db->get()->row()->record_count;
		//$row = $record_count->row();
		
		return $return;
	}

	function insert_jabatan($jab_name,$usrid) {
		$data = array(
               'jab_name' => $jab_name,
			   'rec_creator' => $usrid,
			   'rec_created' => date('Y-m-d')
            );
		$this->db->insert('prc_master_jabatan', $data);
		return $this->db->insert_id();
	}
	
	function cek_jabatan($name) {
		$query = $this->db->query("SELECT jab_name FROM prc_master_jabatan WHERE jab_name = '$name'");
		return $query->num_rows();
	}
	
	function delete_jabatan($jab_id) {
		$this->db->where('jab_id', $jab_id);
		$this->db->delete('prc_master_jabatan'); 
	}
	
	function update_jabatan($jab_id,$jab_name,$usrid) {
		$data = array(
			   	'jab_name' => $jab_name,
				'rec_edit' => 1,
				'rec_editor' => $usrid,
				'rec_edited' => date('Y-m-d')
			); 
		$this->db->where('jab_id', $jab_id);
		$this->db->update('prc_master_jabatan', $data);	
	}
	
	function list_jabatan() {
		$this->db->order_by('jab_name','ASC');
		return $this->db->get('prc_master_jabatan'); 
	}
	
	function get_jabatan($id) {
		$this->db->where('jab_id',$id);
		return $query = $this->db->get('prc_master_jabatan');	
	}
	
	//======================cek jabatan for delete===================
	function cek_delete($id){
		$return['cek1'] = $this->db->query("SELECT ttl_id FROM prc_sys_user WHERE ttl_id = '$id'")->num_rows();
		$return['cek2'] = $this->db->query("SELECT ttl_id FROM prc_master_contact_person WHERE ttl_id = '$id'")->num_rows();
		return $return;
	}
}
?>