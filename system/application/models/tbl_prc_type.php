<?php
class Tbl_prc_type extends Model {
	function get_prc_type($where='') {
		if(is_array($where)):
			foreach ($where as $key=>$val):
				$this->db->where($key,$val);
			endforeach;
		endif;
		$this->db->order_by('pty_id');
		return $this->db->get($this->config->item('tbl_prc_type'));
	}
	
	function get_mr_type($where='') {
		if(is_array($where)):
			foreach ($where as $key=>$val):
				$this->db->where($key,$val);
			endforeach;
		endif;
		$this->db->order_by('mrt_id');
		return $this->db->get($this->config->item('tbl_mr_type'));
	}
}
?>