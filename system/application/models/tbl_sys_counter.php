<?php
class Tbl_sys_counter extends Model {
	function get_counter($where) {
		if(is_array($where)):
			foreach($where as $key=>$val):
				$this->db->where($key,$val);
			endforeach;
		endif;
		return $this->db->get($this->config->item('tbl_sys_counter'));
	}
	
	function insert_counter($data) {
		return $this->db->insert($this->config->item('tbl_sys_counter'),$data);
	}
	
	function update_counter($where,$update) {
		if(is_array($where)):
			foreach($where as $key=>$val):
				$this->db->where($key,$val);
			endforeach;
		endif;
		return $this->db->update($this->config->item('tbl_sys_counter'),$update);		
	}
}
?>