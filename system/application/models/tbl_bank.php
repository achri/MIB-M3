<?php
class Tbl_bank extends Model {
	
	function Tbl_bank(){
	// call the Model constructor
		parent::Model();
	// load database class and connect to MySQL
		$this->load->database();
		$this->CI =& get_instance();
	}
	
	function list_bank() {
		$this->db->order_by('bank_name_singkat','ASC');
		return $this->db->get('prc_master_bank'); 
	}
	
	function get_bank($id) {
		$this->db->select('sup_id, prc_master_bank.bank_id, acc_no, bank_name_singkat');
		$this->db->from('prc_master_supplier_bank_account');
		$this->db->join('prc_master_bank', 'prc_master_bank.bank_id = prc_master_supplier_bank_account.bank_id');
		$this->db->where('sup_id',$id);
	return $query = $this->db->get();
	}
	
	function cek_bank($name) {
		$this->db->select('bank_name_singkat');
		$this->db->where('bank_name_singkat',$name);
		return $query = $this->db->get('prc_master_bank')->num_rows();
	}
	
	function get_bank_flex(){
		$this->db->select('bank_id, bank_name_singkat, bank_name_lengkap');
		$this->db->from('prc_master_bank');
		$this->CI->flexigrid->build_query(TRUE);
		$return['records'] = $this->db->get();
				
		//Build count query
		$this->db->select('count(bank_id) as record_count');
		$this->db->from('prc_master_bank');
		$this->CI->flexigrid->build_query(FALSE);
		$return['record_count'] = $this->db->get()->row()->record_count;
		//$row = $record_count->row();
		
		return $return;
	}
	
	function insert_bank($name1, $name2, $usrid) {
		$data = array(
               'bank_name_singkat' => $name1,
               'bank_name_lengkap' => $name2,
			   'rec_creator' => $usrid,
			   'rec_created' => date('Y-m-d')
            );
		$this->db->insert('prc_master_bank', $data); 
	}
	
	function update_bank1($bank_id,$bank_name,$usrid) {
		$data = array(
			   'bank_name_singkat' => $bank_name,
			   'rec_edit' => 1,
			   'rec_editor' => $usrid,
			   'rec_edited' => date('Y-m-d')
			); 
		$this->db->where('bank_id', $bank_id);
		$this->db->update('prc_master_bank', $data);	
	}
	
	function update_bank2($bank_id,$bank_name,$usrid) {
		$data = array(
			   'bank_name_lengkap' => $bank_name,
			   'rec_edit' => 1,
			   'rec_editor' => $usrid,
			   'rec_edited' => date('Y-m-d')
			); 
		$this->db->where('bank_id', $bank_id);
		$this->db->update('prc_master_bank', $data);	
	}
	
	function delete_bank($bank_id) {
		$this->db->where('bank_id', $bank_id);
		$this->db->delete('prc_master_bank'); 
	}
}
?>