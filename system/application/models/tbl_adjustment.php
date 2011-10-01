<?php
class tbl_adjustment extends Model {
	function get_adj($where='') {
		if(is_array($where)):
			foreach($where as $key=>$val):
				$this->db->where($key,$val);
			endforeach;
		endif;
		return $this->db->get($this->config->item('tbl_adj'));
	}
	
	function get_adj_detail($where) {
		if(is_array($where)):
			foreach($where as $key=>$val):
				$this->db->where($key,$val);
			endforeach;
		endif;
		return $this->db->get($this->config->item('tbl_adj_detail'));
	}
	
	function insert_adj($data) {
		return $this->db->insert($this->config->item('tbl_adj'),$data);
	}
	
	function insert_adj_detail($data) {
		return $this->db->insert($this->config->item('tbl_adj_detail'),$data);
	}
	
	function update_adj($where,$data) {
		if(is_array($where)):
			foreach ($where as $key=>$val):
				$this->db->where($key,$val);
			endforeach;
		endif;
		return $this->db->update($this->config->item('tbl_adj'),$data);
	}
	
	function update_adj_detail($where,$data) {
		if(is_array($where)):
			foreach ($where as $key=>$val):
				$this->db->where($key,$val);
			endforeach;
		endif;
		return $this->db->update($this->config->item('tbl_adj_detail'),$data);
	}
	
	function delete_adj($where) {
		if(is_array($where)):
			foreach ($where as $key=>$val):
				$this->db->where($key,$val);
			endforeach;
		endif;
		return $this->db->delete($this->config->item('tbl_adj'));
	}
	
	function delete_adj_detail($where) {
		if(is_array($where)):
			foreach ($where as $key=>$val):
				$this->db->where($key,$val);
			endforeach;
		endif;
		return $this->db->delete($this->config->item('tbl_adj_detail'));
	}
	
}
?>