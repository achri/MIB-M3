<?php
class Tbl_satuan extends Model {
	
	function Tbl_satuan(){
	// call the Model constructor
		parent::Model();
	// load database class and connect to MySQL
		$this->load->database();
		$this->CI =& get_instance();
	}
	
	function list_satuan() {
		$this->db->order_by('satuan_name','ASC');
		return $this->db->get('prc_master_satuan');
	}
	
	function cek_satuan($name){
		$this->db->select('satuan_name');
		$this->db->where('satuan_name',$name);
		return $query = $this->db->get('prc_master_satuan')->num_rows();
	}
	
	function get_satuan_flex(){
		$this->db->select('satuan_id, satuan_name, satuan_format');
		$this->db->from('prc_master_satuan');
		$this->CI->flexigrid->build_query(TRUE);
		$return['records'] = $this->db->get();
				
		//Build count query
		$this->db->select('count(satuan_id) as record_count');
		$this->db->from('prc_master_satuan');
		$this->CI->flexigrid->build_query(FALSE);
		$return['record_count'] = $this->db->get()->row()->record_count;
		//$row = $record_count->row();
		
		return $return;
	}
	
	function insert_satuan($satuan,$format,$usrid) {
		$data = array(
               	'satuan_name' => $satuan,
				'satuan_format' => $format,
				'rec_creator' => $usrid,
			   	'rec_created' => date('Y-m-d')
            );
		$this->db->insert('prc_master_satuan', $data); 
	}
	
	function update_satuan($id, $name, $usrid) {
		$data = array(
			   'satuan_name' => $name,
			   'rec_edit' => 1,
			   'rec_editor' => $usrid,
			   'rec_edited' => date('Y-m-d')
			); 
		$this->db->where('satuan_id', $id);
		$this->db->update('prc_master_satuan', $data);	
	}
	
	function update_satuan_digit($id, $digit, $usrid) {
		$data = array(
			   'satuan_format' => $digit,
			   'rec_edit' => 1,
			   'rec_editor' => $usrid,
			   'rec_edited' => date('Y-m-d')
			); 
		$this->db->where('satuan_id', $id);
		$this->db->update('prc_master_satuan', $data);	
	}
	
	function delete_satuan($id) {
		$this->db->where('satuan_id', $id);
		$this->db->delete('prc_master_satuan'); 
	}
}
	
?>