<?php
class Tbl_purchase_type extends Model {
	
	function Tbl_purchase_type(){
	// call the Model constructor
		parent::Model();
	}
	
	function list_prc_type(){
		$this->db->order_by('pty_id','ASC');
		return $this->db->get('prc_master_purchase_type');
	}
	
}
?>