<?php
class Tbl_rptnote extends Model {
	
	function Tbl_rptnote(){
	// call the Model constructor
		parent::Model();
	// load database class and connect to MySQL
		$this->load->database();
		$this->CI =& get_instance();
	}
	
	function list_rptnote() {
		//$this->db->select('id', 'var_name');
		//return $this->db->get('prc_sys_printnote'); 
		return $this->db->query("Select id, var_name FROM prc_sys_printnote");
	}
	
	function update_note($var,$content) {
		$data['note'] = $content;
		$this->db->where('id', $var);
		$this->db->update('prc_sys_printnote', $data);	
	}
	
	function get_note($id){
		return $this->db->query("Select note FROM prc_sys_printnote where id = '$id'");
	}
}
?>