<?php
class Tbl_counter extends Model {
	
	function Tbl_counter(){
		parent::Model();
	}
	
	function insert_counter_def($y, $m){
		$data = array(
			'thn' => $y,
			'bln' => $m
		);
		$this->db->insert('prc_sys_counter', $data); 
	}
	
	function insert_counter($y, $m, $no){
		$data = array(
			'thn' => $y,
			'bln' => $m,
			'grl_no' => $no
		);
		$this->db->insert('prc_sys_counter', $data); 
	}
	
	function cek_counter($y, $m){
		$data = array(
			'thn' => $y,
			'bln' => $m
		);
		$this->db->where($data);
		return $query = $this->db->get('prc_sys_counter'); 
	}
	
	function update_counter($no, $y, $m, $field){
		$datau = array(
			$field => $no
		);
		$dataw = array(
			'thn' => $y,
			'bln' => $m
		);
		$this->db->where($dataw);
		return $query = $this->db->update('prc_sys_counter',$datau); 
	}
}

?>