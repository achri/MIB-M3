<?php
class Tbl_bkbk extends Model {
	function insert_bkbk($data) {
		return $this->db->insert($this->config->item('tbl_bkbk'),$data);
	}
	
	function insert_bkbk_det($data) {
		return $this->db->insert($this->config->item('tbl_bkbk_det'),$data);
	}
	
	function update_bkbk($where,$data) {
		if (is_array($where)):
			foreach ($where as $key=>$val):
				$this->db->where($key,$val);
			endforeach;
		endif;
		return $this->db->update($this->config->item('tbl_bkbk'),$data);
	}
}
?>