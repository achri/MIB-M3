<?php
class Tbl_supplier extends Model {
	function Tbl_supplier(){
	// call the Model constructor
		parent::Model();
		$this->load->database();
		$this->CI =& get_instance();
	}
	
	function list_supp() {
		$this->db->select('sup_id, sup_name, legal_id, sup_address, term_id');
		$this->db->order_by('sup_name','ASC');
		return $query = $this->db->get('prc_master_supplier'); 
	}
	
	function get_supplier($id) {
		$this->db->where('sup_id',$id);
		return $query = $this->db->get('prc_master_supplier');	
	}
	
	function get_supplier_full($sup_id) {
		$sql = "select sup.*,sup.sup_name, leg.legal_name 
		from prc_master_supplier as sup 
		inner join prc_master_legality as leg on leg.legal_id = sup.legal_id 
		where sup.sup_id = $sup_id";
		$sup_name = '';
		if ($query = $this->db->query($sql)):
			$sup_name = $query->row()->legal_name.'. '.$query->row()->sup_name;
		endif;
		return $sup_name;
	}
	
	function get_supplier_deactive($sup_id) {
		$sql_supp = "select sup.*,sup.sup_name, leg.legal_name, neg.negara_name, prov.provinsi_name, kota.kota_name  
		from prc_master_supplier as sup 
		inner join prc_master_legality as leg on leg.legal_id = sup.legal_id
		left join prc_master_kota as kota on kota.kota_id = sup.sup_city
		left join prc_master_provinsi as prov on prov.provinsi_id = kota.provinsi_id
		left join prc_master_negara as neg on neg.negara_id = prov.negara_id
		where sup.sup_id = $sup_id";
		return $this->db->query($sql_supp);
	}

	function get_supp_flex(){
		$this->db->select('sup_id,sup_name,legal_id,sup_npwp,sup_address,sup_phone1,sup_fax,sup_email,sup_status');
		$this->db->from('prc_master_supplier');
		$this->CI->flexigrid->build_query(TRUE);
		$return['records'] = $this->db->get();
				
		//Build count query
		$this->db->select('count(sup_id) as record_count');
		$this->db->from('prc_master_supplier');
		$this->CI->flexigrid->build_query(FALSE);
		$return['record_count'] = $this->db->get()->row()->record_count;
		//$row = $record_count->row();
		
		return $return;
	}


	function delete_sup($sup_id) {
		$this->db->where('sup_id', $sup_id);
		$this->db->delete('prc_master_supplier'); 
	}

	
	function insert_supp($supp, $legal,	$npwp, $alamat, $kota, 
						$phone1, $phone2, $phone3, $fax, $hp, $email,
						$term, $status, $usrid) {
			$data = array(
	               	'sup_name' => $supp,
					'legal_id' => $legal,
					'sup_npwp' => $npwp,
					'sup_address' => $alamat,
					'sup_city' => $kota,
					'sup_phone1' => $phone1,
					'sup_phone2' => $phone2,
					'sup_phone3' => $phone3,
					'sup_fax' => $fax,
					'sup_handphone' => $hp,
					'sup_email' => $email,
					'term_id' => $term,
					'sup_status' => $status,
					'rec_created' => date('Y-m-d'),
			   	'rec_creator' => $usrid
	            );
			$this->db->insert('prc_master_supplier', $data);
			return $id = $this->db->insert_id(); 
	}
	
	function update_supp($id, $name, $legal, $npwp, $alamat, $kota, 
						$phone1, $phone2, $phone3, $fax, $hp, $email,
						$term, $usrid) {
			$data = array(
	               	'sup_name' => $name,
					'legal_id' => $legal,
					'sup_npwp' => $npwp,
					'sup_address' => $alamat,
					'sup_city' => $kota,
					'sup_phone1' => $phone1,
					'sup_phone2' => $phone2,
					'sup_phone3' => $phone3,
					'sup_fax' => $fax,
					'sup_handphone' => $hp,
					'sup_email' => $email,
					'term_id' => $term,
					'rec_edit' => 1,
				    'rec_editor' => $usrid,
				    'rec_edited' => date('Y-m-d')		
			);
			$this->db->where('sup_id',$id);
			$this->db->update('prc_master_supplier', $data);
	}
}
?>