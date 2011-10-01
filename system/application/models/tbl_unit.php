<?php
class Tbl_unit extends Model {
	function get_unit($where='') {
		if (is_array($where)):
			foreach ($where as $key=>$val):
				$this->db->where($key,$val);
			endforeach;
		//else:
			//$this->db->where('um_id',$where);
		endif;
		$this->db->order_by('satuan_name');
		return $this->db->get($this->config->item('tbl_unit'));
	}
	
	function get_unit_satuan($where,$join=false) {
		if (is_array($where)):
			foreach ($where as $key=>$val):
				$this->db->where($key,$val);
			endforeach;
		endif;
		
		if($join):
			$this->db->from($this->config->item('tbl_unit_satuan'));
			$this->db->join($this->config->item('tbl_unit'),$this->config->item('tbl_unit_satuan').'.satuan_unit_id='.$this->config->item('tbl_unit').'.satuan_id');
			return $this->db->get();
		else:
			return $this->db->get($this->config->item('tbl_unit_satuan'));
		endif;
		
	}
	
	function insert_unit_satuan($data_sat) {
		$this->db->insert($this->config->item('tbl_unit_satuan'),$data_sat);
	}
	
	function delete_unit_satuan($where) {
		$this->db->delete($this->config->item('tbl_unit_satuan'),$where);
	}
}
?>