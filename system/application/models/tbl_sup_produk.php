<?php
class Tbl_sup_produk extends Model {
	function Tbl_sup_produk(){
	// call the Model constructor
		parent::Model();
	}
	
	function pro_supp_cat($proid,$catid){	
		/*
		return $this->db->query("SELECT sc.cat_id, sc.sup_id, s.sup_name, s.sup_address, s.term_id, l.legal_name, t.term_id_name, t.term_days
			FROM prc_master_supplier_category AS sc
			INNER JOIN prc_master_supplier AS s ON sc.sup_id = s.sup_id
			INNER JOIN prc_master_legality AS l ON s.legal_id = l.legal_id
			INNER JOIN prc_master_credit_term AS t ON s.term_id = t.term_id
			WHERE sc.cat_id ='$catid'");
			*/
		/*
		return $this->db->query("
		select inv.cur_id, procat.cat_id, procat.sup_id, sup.sup_name, sup.sup_address, sup.term_id, leg.legal_name, term.term_id_name, term.term_days
		from prc_master_supplier_category as procat
		inner join prc_master_supplier_product as prosup on prosup.pro_id = $proid
		inner join prc_master_supplier as sup on sup.sup_id = procat.sup_id
		left JOIN prc_master_legality AS leg ON sup.legal_id = leg.legal_id
		left JOIN prc_master_credit_term AS term ON sup.term_id = term.term_id
		inner JOIN prc_inventory as inv on inv.pro_id = $proid and procat.sup_id = inv.sup_id
		where procat.cat_id = $catid group by procat.sup_id			
		");
		*/
		return $this->db->query("
		select procat.cat_id, procat.sup_id, sup.sup_name, sup.sup_address, 
		sup.term_id, leg.legal_name, term.term_id_name, term.term_days, inv.cur_id, cur.cur_symbol,sup.sup_status  
		from prc_master_supplier_category as procat
		inner join prc_master_supplier as sup on sup.sup_id = procat.sup_id
		inner JOIN prc_master_legality AS leg ON sup.legal_id = leg.legal_id
		inner JOIN prc_master_credit_term AS term ON sup.term_id = term.term_id
		left JOIN prc_inventory as inv on inv.pro_id = '$proid' and procat.sup_id = inv.sup_id 
		left join prc_master_currency as cur on cur.cur_id = inv.cur_id 
		where procat.cat_id = '$catid' 
		order by sup.sup_name 
		");
		// and sup.sup_status > 0
	}
	
	function get_pro_supp($pro_id){
		return $this->db->query('select * from prc_master_supplier_product where pro_id='.$pro_id);
	}
}
?>