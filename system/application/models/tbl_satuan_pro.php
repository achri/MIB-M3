<?php
class Tbl_satuan_pro extends Model {
	
	function Tbl_satuan_pro(){
	// call the Model constructor
		parent::Model();
	// load database class and connect to MySQL
		$this->load->database();
	}
	
	
	function cek_satuan($proid, $sat){
		$data = array('pro_id' => $proid,
					  'satuan_unit_id' => $sat	
		);
		$this->db->select('value');
		$this->db->where($data);
		return $query = $this->db->get('prc_satuan_produk');
	}
	
	function get_satuan($proid){
		return $this->db->query("SELECT s.satuan_unit_id, st.satuan_name
			FROM `prc_satuan_produk` AS s
			INNER JOIN prc_master_satuan AS st ON s.satuan_unit_id = st.satuan_id
			WHERE pro_id ='$proid'");
	}
}
	
?>