<?php
class Tbl_hutang extends Model {
	
	
	function insert_unit_satuan($data_sat) {
		$this->db->insert($this->config->item('tbl_unit_satuan'),$data_sat);
	}
	
	function delete_unit_satuan($where) {
		$this->db->delete($this->config->item('tbl_unit_satuan'),$where);
	}
}
?>