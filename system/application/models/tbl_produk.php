<?php
class Tbl_produk extends Model {
	function Tbl_produk() {
		parent::Model();
		$this->obj =& get_instance();
	}
		
	function get_product($where=false,$like=false,$flexigrid=false,$sort="asc") {
		$this->db->select('*');
		
		//$this->db->from($from);
		
		if (is_array($where)):
			foreach ($where as $key=>$val):
				$this->db->where($key,$val);
			endforeach;
		endif;
		
		if (is_array($like)):
			foreach ($like as $key=>$val):
				$this->db->like($key,$val,'after');
			endforeach;
		endif;
		
		if ($sort!="asc"):
			$this->db->order_by('pro_code','desc');
		endif;

		if ($flexigrid):
			$this->obj->flexigrid->build_query();		
			$return['result'] = $this->db->get($this->config->item('tbl_produk'));
			//$return['result'] = $this->db->get();
			if (is_array($where)):
				foreach ($where as $key=>$val):
					$this->db->where($key,$val);
				endforeach;
			endif;
			
			if (is_array($like)):
				foreach ($like as $key=>$val):
					$this->db->like($key,$val.'%');
				endforeach;
			endif;
			
			if ($sort!="asc"):
				$this->db->order_by('pro_code','desc');
			endif;			
			$this->obj->flexigrid->build_query(FALSE);
			$return['count'] = $this->db->get($this->config->item('tbl_produk'))->num_rows();
			//$return['count'] = $this->db->get()->num_rows();
		else:
			//$this->db->order_by('pro_code');
			$return = $this->db->get($this->config->item('tbl_produk'));
		endif;
		return $return;
	}
	
	function get_product_linked($where,$like=false) {
		$this->db->select('*');
		if (is_array($where)):
			foreach ($where as $key=>$val):
				if ($like):
					$this->db->like($key,'%'.$val.'%');
				else:
					$this->db->where($key,$val);
				endif;
			endforeach;
		endif;
		
		$this->db->from('prc_master_product');
		$this->db->join('prc_master_category','prc_master_category.cat_id=prc_master_product.cat_id');
		$this->db->join('prc_master_unitmeasure','prc_master_unitmeasure.um_id=prc_master_product.um_id');
		//$this->db->join('prc_master_unit_sat','prc_master_unit_sat.pro_id=prc_master_product.pro_id');
		return $this->db->get();
	}
	
	function pro_name_change($pro_id,$pro_name) {
		$this->db->where('pro_id',$pro_id);
		return $this->db->update($this->config->item('tbl_produk'),array('pro_name'=>$pro_name));
	}
	
	function pro_delete($pro_id) {
		$this->db->where('pro_id',$pro_id);
		$this->db->where('pro_status','non active');
		return $this->db->delete($this->config->item('tbl_produk'));
	}
	
	function pro_insert($data) {
		return $this->db->insert($this->config->item('tbl_produk'),$data);
	}
	
	function get_cat_code($cat_id) {
		if ($cat_id!=''):
			$this->db->where('cat_id',$cat_id);
			$get_cat = $this->db->get($this->config->item('tbl_kategori'));
			if ($get_cat->num_rows() > 0):
				return $pro_code = $get_cat->row()->cat_code;
			endif;
		endif;
	}
	
	function pro_edit($pro_id,$data) {
		$this->db->where('pro_id',$pro_id);
		return $this->db->update($this->config->item('tbl_produk'),$data);
	}
	
	function pro_supcat_del($where) {
		if (is_array($where)):
			foreach ($where as $key=>$val):
				$this->db->where($key,$val);
			endforeach;
		endif;
		$this->db->delete('prc_master_supplier_product');
	}
	
	function pro_supcat_add($data) {
		return $this->db->insert('prc_master_supplier_product',$data);
	}
	
	function pro_supcat_edit($where,$data) {
		if (is_array($where)):
			foreach ($where as $key=>$val):
				$this->db->where($key,$val);
			endforeach;
		else:
			$this->db->where('sup_id',$where);
		endif;
		return $this->db->update('prc_master_supplier_product');
	}
	
	function get_cat_sup($where) {
		if (is_array($where)):
			foreach ($where as $key=>$val):
				$this->db->where($key,$val);
			endforeach;
		endif;
		$this->db->from('prc_master_category');
		$this->db->join('prc_master_supplier_category','prc_master_supplier_category.cat_id = prc_master_category.cat_id');
		$this->db->join('prc_master_supplier','prc_master_supplier.sup_id = prc_master_supplier_category.sup_id');
		$this->db->join('prc_master_legality','prc_master_legality.legal_id = prc_master_supplier.legal_id');
		$this->db->order_by('prc_master_supplier.sup_name');
		return $this->db->get();
	}
	
	function get_sup_pro($where) {
		if (is_array($where)):
			foreach ($where as $key=>$val):
				$this->db->where($key,$val);
			endforeach;
		endif;
		$this->db->from('prc_master_supplier_product');
		$this->db->join('prc_master_supplier','prc_master_supplier.sup_id = prc_master_supplier_product.sup_id');
		$this->db->join('prc_master_legality','prc_master_legality.legal_id = prc_master_supplier.legal_id');
		return $this->db->get();
	}
	
	function pro_get_sat($proid) {
		$this->db->select('um_id');
		$this->db->where('pro_id', $proid);
		return $query = $this->db->get('prc_master_product');
	}
	
}
?>
