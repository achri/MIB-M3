<?php
class Tbl_sup_bank extends Model {
	function supp_bank ($id, $bank, $norek){	
		for($i=0;$i<sizeof($bank);$i++) {
			if ($bank[$i] != '' && $norek[$i] != ''){
			$data = array(
	               	'sup_id' => $id,
					'bank_id' => $bank[$i],
					'acc_no' => $norek[$i]
				);
			$this->db->insert('prc_master_supplier_bank_account', $data);
			}
		}
	}
	
	function delete_cat_bank($id) {
		$this->db->where('sup_id', $id);
		$this->db->delete('prc_master_supplier_bank_account'); 
	}
}
?>