<?php
class Tbl_currency extends Model {
	
	function Tbl_currency (){
	// call the Model constructor
		parent::Model();
	// load database class and connect to MySQL
		$this->load->database();
		$this->CI =& get_instance();
	}
	
	function list_currency() {
		$this->db->order_by('cur_id','ASC');
		return $this->db->get('prc_master_currency');
	}
	
	function get_currency ($where='') {
		if (is_array($where)):
			foreach ($where as $key=>$val):
				$this->db->where($key,$val);
			endforeach;
		endif;
		return $this->db->get($this->config->item('tbl_matauang'));
	}
}
?>