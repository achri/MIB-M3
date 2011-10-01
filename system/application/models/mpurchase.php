<?php
class Mpurchase extends Model{

	function Mpurchase(){
	// call the Model constructor
		parent::Model();
	// load database class and connect to MySQL
		$this->load->database();
		$this->CI =& get_instance();
	}

	function list_task($catParent) {
		$this->db->where('cat_parent',$catParent);
		$this->db->order_by('cat_id','ASC');
		return $this->db->get('tbl_prc_category');
	}
	
	function list_dep_ASC() {
		$this->db->order_by('dep_name','ASC');
		return $this->db->get('tbl_prc_departement'); 
	}
	
	function list_dep_DESC() {
		$this->db->order_by('dep_name','DESC');
		return $this->db->get('tbl_prc_departement'); 
	}
//==================================================	
	function list_jabatan_ASC() {
		$this->db->order_by('jab_name','ASC');
		return $this->db->get('tbl_prc_jabatan'); 
	}
	
	function list_jabatan_DESC() {
		$this->db->order_by('jab_name','DESC');
		return $this->db->get('tbl_prc_jabatan'); 
	}

//==================================================
	function list_bank_ASC() {
		$this->db->order_by('bank_name_lengkap','ASC');
		return $this->db->get('prc_master_bank'); 
	}
	
	function list_bank_DESC() {
		$this->db->order_by('bank_name_lengkap','DESC');
		return $this->db->get('prc_master_bank'); 
	}
	
//==================================================

	function list_satuan_ASC() {
		$this->db->order_by('satuan_name','ASC');
		return $this->db->get('prc_master_satuan'); 
	}
	
	function list_satuan_DESC() {
		$this->db->order_by('satuan_name','DESC');
		return $this->db->get('prc_master_satuan'); 
	}
//==================================================

	function list_term_ASC() {
		$this->db->order_by('term_name','ASC');
		return $this->db->get('prc_master_credit_term'); 
	}
	
	function list_term_DESC() {
		$this->db->order_by('term_name','DESC');
		return $this->db->get('prc_master_credit_term'); 
	}
//==================================================

	function list_legal() {
		$this->db->order_by('legal_name','ASC');
		return $this->db->get('prc_master_legality'); 
	}	
	
	function list_negara() {
		$this->db->order_by('negara_name','ASC');
		return $this->db->get('prc_master_negara'); 
	}

	function list_provinsi($id) {
		$this->db->where('negara_id',$id);
		$this->db->order_by('provinsi_name','ASC');
		return $this->db->get('prc_master_provinsi'); 
	}	

	function list_kota() {
		$this->db->order_by('kota_name','ASC');
		return $this->db->get('prc_master_kota');
	}
	
	function list_code($id) {
		$this->db->select('code_area');
		$this->db->where('kota_id',$id);
		$query = $this->db->get('prc_master_kota');
		$code = $query->row();
		return $code->code_area;
	}
	
	function list_bank() {
		$this->db->order_by('bank_name_singkat','ASC');
		return $this->db->get('prc_master_bank'); 
	}
	
	function list_term() {
		$this->db->order_by('term_id','ASC');
		return $this->db->get('prc_master_credit_term'); 
	}
	
	function list_supp() {
		$this->db->select('sup_id, sup_name');
		$this->db->order_by('sup_name','ASC');
		return $query = $this->db->get('prc_master_supplier'); 
	}
	
	function list_cat($id) {
		$this->db->select('sup_id, cat_name');
		$this->db->from('prc_master_supplier_category');
		$this->db->join('tbl_prc_category', 'tbl_prc_category.cat_id = prc_master_supplier_category.cat_id');
		$this->db->where('sup_id',$id);
		return $query = $this->db->get();
	}
//==================================================	
	function get_supp_flex(){
		$this->db->select('sup_id,sup_name,sup_npwp,sup_address,sup_phone1,sup_fax1,sup_email');
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
	
	function get_contact_flex(){
		$this->db->select('per_id, per_Fname, sup_id, per_address, per_city, per_phone, per_fax');
		$this->db->from('prc_master_contact_person');
		$this->CI->flexigrid->build_query(TRUE);
		$return['records'] = $this->db->get();
				
		//Build count query
		$this->db->select('count(per_id) as record_count');
		$this->db->from('prc_master_contact_person');
		$this->CI->flexigrid->build_query(FALSE);
		$return['record_count'] = $this->db->get()->row()->record_count;
		//$row = $record_count->row();
		
		return $return;
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
	
	function get_supplier($id) {
		$this->db->where('sup_id',$id);
		return $query = $this->db->get('prc_master_supplier');	
	}
	
	function get_contact($id) {
		$this->db->where('per_id',$id);
		return $query = $this->db->get('prc_master_contact_person');	
	}
	
	function get_departement($id) {
		$this->db->where('dep_id',$id);
		return $query = $this->db->get('tbl_prc_departement');	
	}
	
	function get_jabatan($id) {
		$this->db->where('jab_id',$id);
		return $query = $this->db->get('tbl_prc_jabatan');	
	}
	
	function get_legal($id) {
		$this->db->where('legal_id',$id);
		return $query = $this->db->get('prc_master_legality');	
	}
	
	function get_city($id) {
		$this->db->where('kota_id',$id);
		return $query = $this->db->get('prc_master_kota');	
	}
	
	function get_prov($id) {
		$this->db->where('provinsi_id',$id);
		return $query = $this->db->get('prc_master_provinsi');	
	}
	
	function get_negara($id) {
		$this->db->where('negara_id',$id);
		return $query = $this->db->get('prc_master_negara');	
	}
	
	function get_sup_cat($id) {
		$this->db->select('prc_master_supplier_category.sup_id, prc_master_supplier_category.cat_id, cat_name');
		$this->db->from('prc_master_supplier_category');
		$this->db->join('tbl_prc_category', 'tbl_prc_category.cat_id = prc_master_supplier_category.cat_id');
		$this->db->where('sup_id',$id);	
		return $query = $this->db->get();
	}
	
	function get_sup_cat_rest($id){
		$this->db->where_not_in('cat_id',$id);
		$this->db->where('cat_level',1);
		return $query = $this->db->get('tbl_prc_category');
	}
	
	function get_bank($id) {
		$this->db->select('sup_id, prc_master_bank.bank_id, acc_no, bank_name_singkat');
		$this->db->from('prc_master_supplier_bank_account');
		$this->db->join('prc_master_bank', 'prc_master_bank.bank_id = prc_master_supplier_bank_account.bank_id');
		$this->db->where('sup_id',$id);
		return $query = $this->db->get();	
	}
//==================================================

	function set_cat() {
		$query = $this->db->query('SELECT cat_id, cat_parent FROM tbl_prc_category WHERE cat_parent = 0');
		return $query->num_rows();
	}
	
	function num_cat($catParent) {
		$this->db->select_max('cat_code','numcode');
		$this->db->where('cat_parent',$catParent);
		$query = $this->db->get('tbl_prc_category');
		$query_rows = $query->row();
		return $query_rows->numcode;
	}
	
	function set_class($catParent) {
		$query = $this->db->query("SELECT cat_id, cat_parent FROM tbl_prc_category WHERE cat_parent = '$catParent'");
		return $query->num_rows();
	}

	// get data tree ============================
	function set_level1() {
		$this->db->select('cat_id, cat_code, cat_level, cat_name');
		$this->db->where('cat_level',1);
		$this->db->order_by ('cat_id', 'ASC');
		return $query = $this->db->get('tbl_prc_category');
		//return $this->db->query("SELECT cat_id, cat_code, cat_level, cat_name FROM tbl_prc_category WHERE cat_level = 1");
	}

	function set_level2($cat_id) {
		$this->db->select('cat_id, cat_code, cat_level, cat_name');
		$this->db->where('cat_parent',$cat_id);
		$this->db->order_by ('cat_id', 'ASC');
		return $query = $this->db->get('tbl_prc_category');
		//return $this->db->query("SELECT cat_id, cat_code, cat_level, cat_name FROM tbl_prc_category WHERE cat_parent = '$cat_id'");
	}

	function set_level3($cat_id) {
		$this->db->select('cat_id, cat_code, cat_level, cat_name');
		$this->db->where('cat_parent',$cat_id);
		$this->db->order_by ('cat_id', 'ASC');
		return $query = $this->db->get('tbl_prc_category');
		//return $this->db->query("SELECT cat_id, cat_code, cat_level, cat_name FROM tbl_prc_category WHERE cat_parent = '$cat_id'");
	}
	//===========================================

	function insert_cat($cat_code, $cat_parent, $cat_level, $cat_name, $detail) {
		$data = array(
               'cat_code' => $cat_code, 
			   'cat_aprent' => $cat_parent, 
			   'cat_level' => $cat_level,
			   'cat_name' => $cat_name, 
			   'need_realization' => $detail
            );
		$this->db->insert('tbl_prc_category', $data);
	}
	
	function insert_dep($dep_name) {
		$data = array(
               'dep_name' => $dep_name
            );
		$this->db->insert('tbl_prc_departemen', $data);
	}
	
	function insert_jab($jab_name) {
		$data = array(
               'jab_name' => $jab_name
            );
		$this->db->insert('tbl_prc_jabatan', $data); 
	}
	
	function insert_bank($name1, $name2) {
		$data = array(
               'bank_name_singkat' => $name1,
               'bank_name_lengkap' => $name2 
            );
		$this->db->insert('prc_master_bank', $data); 
	}
	
	function insert_satuan($name1) {
		$data = array(
               'satuan_name' => $name1
            );
		$this->db->insert('prc_master_satuan', $data); 
	}
	
	function insert_term($id, $desc, $days, $disct) {
		$data = array(
               	'term_id' => $id,
				'term_name' => $desc,
				'term_days' => $days,
				'term_discount' => $disct
            );
		$this->db->insert('prc_master_credit_term', $data); 
	}

	function insert_supp($supp, $legal,	$npwp, $alamat, $kota, 
						$phone1, $phone2, $phone3, $fax1, $fax2, $fax3, $email,
						$term, $status) {
			$data = array(
	               	'sup_name' => $supp,
					'legal_id' => $legal,
					'sup_npwp' => $npwp,
					'sup_address' => $alamat,
					'sup_city' => $kota,
					'sup_phone1' => $phone1,
					'sup_phone2' => $phone2,
					'sup_phone3' => $phone3,
					'sup_fax1' => $fax1,
					'sup_fax2' => $fax2,
					'sup_fax3' => $fax3,
					'sup_email' => $email,
					'term_id' => $term,
					'sup_status' => $status
	            );
			$this->db->insert('prc_master_supplier', $data);
			return $id = $this->db->insert_id(); 
	}
	
	function supp_cat ($id, $cat_sup){
		$arr_cat = explode(",",$cat_sup);	
		for($i=0;$i<sizeof($arr_cat);$i++) {
				$data = array(
	               	'sup_id' => $id,
					'cat_id' => $arr_cat[$i]
				);
			$this->db->insert('prc_master_supplier_category', $data);
		}
	}
	
	function supp_bank ($id, $bank, $norek){	
		for($i=0;$i<sizeof($bank);$i++) {
				$data = array(
	               	'sup_id' => $id,
					'bank_id' => $bank[$i],
					'acc_no' => $norek[$i]
				);
			$this->db->insert('prc_master_supplier_bank_account', $data);
		}
	}
	
	function insert_contact($nama_depan, $nama_belakang, $nama_panggilan,
		$perusahaan, $departemen, $jabatan, $alamat, $kota, $tlp, $handphone, $fax) {
			$data = array(
					'per_Fname' => $nama_depan,
					'per_Lname' => $nama_belakang,
					'per_Nickname' => $nama_panggilan,
					'per_address' => $alamat,
					'per_city' => $kota,
					'ttl_id' => $jabatan,
					'dep_id' => $departemen,
					'per_phone' => $tlp,
					'per_handphone' => $handphone,
					'per_fax' => $fax,
					'sup_id' => $perusahaan
	            	);
			$this->db->insert('prc_master_contact_person', $data);
	}
	
	//============================== validation =================================
	function cek_kelas($name, $level) {
		$query = $this->db->query("SELECT cat_name FROM tbl_prc_category WHERE cat_name = '$name' AND cat_level='$level'");
		return $query->num_rows();
	}

	function cek_departemen($name) {
		$query = $this->db->query("SELECT dep_name FROM tbl_prc_departement WHERE dep_name = '$name'");
		return $query->num_rows();
	}
	
	function cek_jabatan($name) {
		$query = $this->db->query("SELECT jab_name FROM tbl_prc_jabatan WHERE jab_name = '$name'");
		return $query->num_rows();
	}
	
	function cek_bank($name) {
		$this->db->select('bank_name_singkat');
		$this->db->where('bank_name_singkat',$name);
		return $query = $this->db->get('prc_master_bank')->num_rows();
	}
	
	function cek_satuan($name){
		$this->db->select('satuan_name');
		$this->db->where('satuan_name',$name);
		return $query = $this->db->get('prc_master_satuan')->num_rows();
	}
	
	function cek_term($id){
		$this->db->select('term_id');
		$this->db->where('term_id',$id);
		return $query = $this->db->get('prc_master_credit_term')->num_rows();
	}
	
	//================================= Update Data =============================
	function update_cat($cat_id,$cat_name) {
		$data = array(
			   'cat_name' => $cat_name
			);
		$this->db->where('cat_id', $cat_id);
		$this->db->update('tbl_prc_category', $data);	
	}
	
	function update_dep($dep_id,$dep_name) {
		$data = array(
			   'dep_name' => $dep_name
			); 
		$this->db->where('dep_id', $dep_id);
		$this->db->update('tbl_prc_departement', $data);	
	}
	
	function update_jab($jab_id,$jab_name) {
		$data = array(
			   'jab_name' => $jab_name
			); 
		$this->db->where('jab_id', $jab_id);
		$this->db->update('tbl_prc_jabatan', $data);	
	}
	
	function update_bank1($bank_id,$bank_name) {
		$data = array(
			   'bank_name_singkat' => $bank_name
			); 
		$this->db->where('bank_id', $bank_id);
		$this->db->update('prc_master_bank', $data);	
	}
	
	function update_bank2($bank_id,$bank_name) {
		$data = array(
			   'bank_name_lengkap' => $bank_name
			); 
		$this->db->where('bank_id', $bank_id);
		$this->db->update('prc_master_bank', $data);	
	}

	function update_satuan($id, $name) {
		$data = array(
			   'satuan_name' => $name
			); 
		$this->db->where('satuan_id', $id);
		$this->db->update('prc_master_satuan', $data);	
	}
	
	function update_term($id, $name, $upd) {
		$data = array(
			   $upd => $name
			); 
		$this->db->where('term_id', $id);
		$this->db->update('prc_master_credit_term', $data);	
	}
	
	function update_supp($id, $name, $legal, $npwp, $alamat, $kota, 
						$phone1, $phone2, $phone3, $fax1, $fax2, $fax3, $email,
						$term) {
			$data = array(
	               	'sup_name' => $name,
					'legal_id' => $legal,
					'sup_npwp' => $npwp,
					'sup_address' => $alamat,
					'sup_city' => $kota,
					'sup_phone1' => $phone1,
					'sup_phone2' => $phone2,
					'sup_phone3' => $phone3,
					'sup_fax1' => $fax1,
					'sup_fax2' => $fax2,
					'sup_fax3' => $fax3,
					'sup_email' => $email,
					'term_id' => $term);
			$this->db->where('sup_id',$id);
			$this->db->update('prc_master_supplier', $data);
	}
	
	function update_contact_person($id, $nama_depan, $nama_belakang, $nama_panggilan,
						$perusahaan, $departemen, $jabatan, $alamat, 
						$kota, $tlp, $handphone, $fax) {
			$data = array(
	               	'per_Fname' => $nama_depan,
					'per_Lname' => $nama_belakang,
					'per_Nickname' => $nama_panggilan,
					'per_address' => $alamat,
					'per_city' => $kota,
					'ttl_id' => $jabatan,
					'dep_id' => $departemen,
					'per_phone' => $tlp,
					'per_handphone' => $handphone,
					'per_fax' => $fax,
					'sup_id' => $perusahaan);
			$this->db->where('per_id',$id);
			$this->db->update('prc_master_contact_person', $data);
	}
	//================================= Delete Data Cat =============================
	function delete_cat_bank($id) {
		$this->db->where('sup_id', $id);
		$this->db->delete('prc_master_supplier_bank_account'); 
	}
	
	function delete_cat_sup($id) {
		$this->db->where('sup_id', $id);
		$this->db->delete('prc_master_supplier_category'); 
	}
	
	function delete_cat($cat_id) {
		$this->db->where('cat_id', $cat_id);
		$this->db->delete('tbl_prc_category'); 
	}
	
	function delete_dep($dep_id) {
		$this->db->where('dep_id', $dep_id);
		$this->db->delete('tbl_prc_departement'); 
	}
	
	function delete_jabatan($jab_id) {
		$this->db->where('jab_id', $jab_id);
		$this->db->delete('tbl_prc_jabatan'); 
	}

	function delete_grup($cat_id) {
		$this->db->where('cat_id', $cat_id);
		$this->db->delete('tbl_prc_category'); 
	}
	
	function delete_bank($bank_id) {
		$this->db->where('bank_id', $bank_id);
		$this->db->delete('prc_master_bank'); 
	}
	
	function delete_satuan($id) {
		$this->db->where('satuan_id', $id);
		$this->db->delete('prc_master_satuan'); 
	}
	
	function delete_term($id) {
		$this->db->where('term_id', $id);
		$this->db->delete('prc_master_credit_term'); 
	}
	
	public function delete_contact($per_id) {
		$delete_contact = $this->db->query('DELETE FROM prc_master_contact_person WHERE per_id='.$per_id);	
		return TRUE;
	}
}
?>