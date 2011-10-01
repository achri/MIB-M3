<?php
class Tbl_term extends Model {
	
	function Tbl_term(){
	// call the Model constructor
		parent::Model();
	// load database class and connect to MySQL
		$this->load->database();
		$this->CI =& get_instance();
	}
	
	function list_term() {
		$this->db->order_by('term_id_name','ASC');
		return $this->db->get('prc_master_credit_term'); 
	}
	
	function get_term($id) {
		$this->db->select('term_id, term_id_name, term_days');
		$this->db->where('term_id', $id);
		return $query = $this->db->get('prc_master_credit_term');
	}
	
	function cek_term($name){
		$this->db->select('term_id_name');
		$this->db->where('term_id_name',$name);
		return $query = $this->db->get('prc_master_credit_term')->num_rows();
	}
	
	function get_term_flex(){
		$this->db->select('term_id, term_id_name, term_name, term_days, term_discount');
		$this->db->from('prc_master_credit_term');
		$this->CI->flexigrid->build_query(TRUE);
		$return['records'] = $this->db->get();
				
		//Build count query
		$this->db->select('count(term_id) as record_count');
		$this->db->from('prc_master_credit_term');
		$this->CI->flexigrid->build_query(FALSE);
		$return['record_count'] = $this->db->get()->row()->record_count;
		//$row = $record_count->row();
		
		return $return;
	}
	
	function insert_term($name, $desc, $days, $disct,$usrid) {
		$data = array(
               	'term_id_name' => $name,
				'term_name' => $desc,
				'term_days' => $days,
				'term_discount' => $disct,
			   	'rec_creator' => $usrid,
			   	'rec_created' => date('Y-m-d')
            );
		$this->db->insert('prc_master_credit_term', $data);
		return $this->db->insert_id();
	}
	
	function delete_term($id) {
		$this->db->where('term_id', $id);
		return $this->db->delete('prc_master_credit_term'); 
	}
	
	function update_term($id, $name, $upd, $usrid) {
		$data = array(
			   $upd => $name,
			   'rec_edit' => 1,
			   'rec_editor' => $usrid,
			   'rec_edited' => date('Y-m-d')
			); 
		$this->db->where('term_id', $id);
		$this->db->update('prc_master_credit_term', $data);	
	}
	
	function get_po_term($po_id) {
		return $this->db->query("select * from prc_po inner join prc_master_credit_term as term on 
		term_id = term.term_id where po_id='".$po_id."'");
	}
}
?>