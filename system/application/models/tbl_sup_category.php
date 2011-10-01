<?php
class Tbl_sup_category extends Model {
	
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
	
	function delete_cat_sup($id) {
		$this->db->where('sup_id', $id);
		$this->db->delete('prc_master_supplier_category'); 
	}
	
	function get_sup_cat($id) {
		$this->db->select('prc_master_supplier_category.sup_id, prc_master_supplier_category.cat_id, cat_name');
		$this->db->from('prc_master_supplier_category');
		$this->db->join('prc_master_category', 'prc_master_category.cat_id = prc_master_supplier_category.cat_id');
		$this->db->where('sup_id',$id);	
		return $query = $this->db->get();
	}
	
}
?>