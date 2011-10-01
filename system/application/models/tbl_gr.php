<?php
class Tbl_gr extends Model {
	function Tbl_gr() {
		$this->obj =& get_instance();	
	}
	
	function insert_gr($data) {
		return $this->db->insert($this->config->item('tbl_gr'),$data);
	}
	
	function insert_gr_det($data) {
		return $this->db->insert($this->config->item('tbl_gr_det'),$data);
	}
	
	function insert_gr_det_his($data) {
		return $this->db->insert($this->config->item('tbl_gr_det_his'),$data);
	}
	
	function get_gr_print_list() {
		return $this->db->query("select g.gr_no, g.gr_id, date_format(g.gr_date, '%d-%m-%Y') as gr_date, g.gr_suratJalan, p.po_no, s.sup_name
            from prc_gr as g inner join prc_po as p on p.po_id = g.po_id
			inner join prc_master_supplier as s on p.sup_id = s.sup_id 
			where g.gr_status=0 and g.gr_printStatus='0' order by gr_no");
	}
	
	function get_gr_print_view($gr_id) {
		
		$return['gr_list'] = $this->db->query("
		select g.gr_printStatus,g.gr_printCount,g.gr_no, g.gr_id, g.gr_suratJalan, date_format(g.gr_date, '%d-%m-%Y') as gr_date, p.po_no, s.sup_name, leg.legal_name 
		 from prc_gr as g 
		 inner join prc_po as p on g.po_id = p.po_id
		 inner join prc_master_supplier as s on p.sup_id = s.sup_id
		 inner join prc_master_legality as leg on leg.legal_id = s.legal_id
		 where g.gr_id='".$gr_id."' and g.gr_type='rec'");
		 
		$return['po_det_list'] = $this->db->query("
			select pd.um_id as sub_um_id,pro.um_id as pro_um_id,d.qty, pro.pro_code, pro.pro_name, um.satuan_name, um.satuan_format, d.keterangan 
			from prc_gr as g
            inner join prc_gr_detail as d on g.gr_id = d.gr_id
			inner join prc_master_product as pro on d.pro_id = pro.pro_id
			inner join prc_pr_detail as pd on pd.po_id = g.po_id and pd.pro_id = d.pro_id
			inner join prc_master_satuan as um on pd.um_id = um.satuan_id 
			where g.gr_id='".$gr_id."' order by g.gr_id");
		
		return $return;
	}
	
	function update_gr($where,$data) {
		if (is_array($where)):
			foreach ($where as $key=>$val):
				$this->db->where($key,$val);
			endforeach;
		endif;
		return $this->db->update($this->config->item('tbl_gr'),$data);
	}
	
	function update_gr_det($where,$data) {
		if (is_array($where)):
			foreach ($where as $key=>$val):
				$this->db->where($key,$val);
			endforeach;
		endif;
		return $this->db->update($this->config->item('tbl_gr_det'),$data);
	}
	
	function update_gr_det_his($where,$data) {
		if (is_array($where)):
			foreach ($where as $key=>$val):
				$this->db->where($key,$val);
			endforeach;
		endif;
		return $this->db->update($this->config->item('tbl_gr_det_his'),$data);
	}
	
	function get_gr_data($where) {
		if (is_array($where)):
			foreach ($where as $key=>$val):
				$this->db->where($key,$val);
			endforeach;
		endif;
		return $this->db->get($this->config->item('tbl_gr'));
	}
	
	function get_gr_prc() {
		return $this->db->query("select g.gr_no, g.gr_id, date_format(g.gr_date, '%d-%m-%Y') as gr_date, g.gr_suratJalan, p.po_no, s.sup_name
            from prc_gr as g inner join prc_po as p on p.po_id = g.po_id
			inner join prc_master_supplier as s on p.sup_id = s.sup_id 
			where g.gr_status=0 and g.gr_printStatus='1' and g.gr_type='rec' order by gr_no");
	}
	
	function get_gr_prc_view($gr_id,$status) {
		$return['get_gr'] = $this->db->query("select gd.kurs, g.gr_id,g.gr_no,g.gr_suratJalan, date_format(g.gr_date,'%d-%m-%Y') as gr_date, leg.legal_name, 
         p.po_no, s.sup_name, s.sup_id, cur.cur_symbol from prc_gr as g
         inner join prc_po as p on g.po_id = p.po_id
		 inner join prc_master_supplier as s on s.sup_id = p.sup_id 
		 inner join prc_master_legality as leg on s.legal_id = leg.legal_id 
		 inner join prc_gr_detail as gd on gd.gr_id = g.gr_id 
		 inner join prc_master_currency as cur on cur.cur_id = p.cur_id 
		 where g.gr_id = '".$gr_id."' and g.gr_type='rec'");
		
		$return['get_gr_det'] = $this->db->query("select d.qty, d.discount, d.price, d.cur_id, cur.cur_symbol,cur.cur_digit,pd.um_id, sat.satuan_format, p.pro_id, p.pro_name, p.pro_code, u.satuan_name, u.satuan_format,   
			(d.price * d.qty * ( d.discount /100 )) AS price_disc, 
			(d.price * d.qty * ( ( 100 - d.discount ) /100 )) as price_tot,
			(d.price * d.qty * ( ( 100 - d.discount ) /100 ) * 10 / 100 ) as price_ppn,
			(d.price * d.qty * ( ( 100 - d.discount ) /100 ) * 110 / 100 ) as price_ppn_tot 
			from prc_gr as g
             inner join prc_gr_detail as d on g.gr_id = d.gr_id
			 inner join prc_master_product as p on p.pro_id = d.pro_id
			 inner join prc_pr_detail as pd on pd.po_id = g.po_id and pd.pro_id = d.pro_id
			 inner join prc_master_satuan as u on pd.um_id = u.satuan_id 
			 inner join prc_master_currency as cur on d.cur_id = cur.cur_id 
			 inner join prc_master_satuan as sat on pd.um_id = sat.satuan_id
			 where g.gr_id='".$gr_id."' and g.gr_type='rec' order by pd.pr_reqTime");
			 
		$return['get_gr_foot'] = $this->db->query("select 
			d.qty, d.discount, d.price, d.cur_id, cur.cur_symbol,cur.cur_digit,pd.um_id, sat.satuan_format, p.pro_id, p.pro_name, p.pro_code, u.satuan_name, u.satuan_format, 
			sum( d.price * d.qty * ( ( 100 - d.discount ) /100 )) AS tot_price_foot,
			sum( d.price * d.qty * ( ( 100 - d.discount ) /100 ) * 110 / 100) AS tot_price_ppn_foot  			
			from prc_gr as g
             inner join prc_gr_detail as d on g.gr_id = d.gr_id
			 inner join prc_master_product as p on p.pro_id = d.pro_id
			 inner join prc_pr_detail as pd on pd.po_id = g.po_id and pd.pro_id = d.pro_id
			 inner join prc_master_satuan as u on pd.um_id = u.satuan_id 
			 inner join prc_master_currency as cur on d.cur_id = cur.cur_id 
			 inner join prc_master_satuan as sat on pd.um_id = sat.satuan_id
			 where g.gr_id='".$gr_id."' and g.gr_type='rec'");
			 
		return $return;
	}
	
	function get_gr_bon($po_id,$gr_id=0) {
		return $this->db->query("select gr.gr_id, gr.gr_no, gr.gr_type, gr.gr_fakturSup, 
			 date_format(gr.gr_date, '%d-%m-%Y') as gr_date, p.po_id, p.po_no, trm.term_name,
			 sum(gd.qty*gd.price*(100 - gd.discount)/100) as gr_value, 
			 sum((gd.qty*gd.price*(100 - gd.discount)/100) * 10 / 100) as gr_ppn_value, 
			 (sum((gd.qty*gd.price*(100 - gd.discount)/100) * 10 / 100)* gd.kurs) as gr_ppn_kurs,
			 cur.cur_symbol, gd.cur_id, cur.cur_digit, gd.kurs 
			 from prc_gr as gr 
			 inner join prc_po as p on gr.po_id = p.po_id
			 inner join prc_gr_detail as gd on gr.gr_id = gd.gr_id
			 inner join prc_master_currency as cur on cur.cur_id = gd.cur_id
			 inner join prc_master_credit_term as trm on trm.term_id = p.term_id
			 where gr.po_id='".$po_id."' and (gr.gr_status = 1 or gr.gr_status = 3) and gr.con_id='0' and gr.gr_parent='".$gr_id."' and gr.gr_fakturSup!=''
			 group by gd.gr_id
			 order by gr.gr_date");
	}
	
	function get_gr_pay($sup_id) {
		return $this->db->query("select k.con_id, k.con_no, 
		k.con_value,k.con_ppn_value, k.con_payVal, k.con_ppn_payVal,
		date_format(k.con_date, '%d-%m-%Y') as con_date, 
		date_format(k.con_dueDate, '%d-%m-%Y') as con_dueDate, cur.cur_symbol,cur.cur_digit, k.cur_id, p.po_no 
		from prc_contrabon as k
		inner join prc_master_currency as cur on k.cur_id = cur.cur_id
		inner join prc_po as p on k.po_id = p.po_id
		where p.sup_id ='".$sup_id."' and (((k.con_value - k.con_payVal) > 0) or (k.con_ppn_value - k.con_ppn_payVal) > 0) and p.po_status='1' and k.con_printStat='1'
		order by k.con_no asc, con_date desc");
	}
	
	function cek_value_gr($gr_parent) {
		return $this->db->query("select sum(gd.qty*gd.price) as gr_value_return
							 from prc_gr as gr 
							 inner join prc_po as p on gr.po_id = p.po_id
							 inner join prc_gr_detail as gd on gr.gr_id = gd.gr_id
							 where gr.gr_parent='".$gr_parent."' and gr.gr_type='ret'
							 group by gd.gr_id");
	}
	
	function get_gr_flexi($flex_stat = true) {
		$this->db->select("g.*, date_format(g.gr_date,'%d-%m-%Y') as gr_date,
		   s.sup_name, p.po_no",FALSE);
        $this->db->from("prc_gr as g");
		$this->db->join("prc_po as p","p.po_id = g.po_id");
		$this->db->join("prc_master_supplier as s","p.sup_id = s.sup_id");
		$this->db->where("g.gr_type = 'rec'");
		$this->db->order_by("g.gr_date");
		if ($flex_stat)
			$this->obj->flexigrid->build_query();
		$return['result'] = $this->db->get();
		if ($flex_stat)
			$this->obj->flexigrid->build_query(FALSE);
		$return['count'] = $return['result']->num_rows();
		return $return;
	}
	
	function get_gr_after_print_list() {
		return $this->db->query("select g.gr_no, g.gr_id, date_format(g.gr_date, '%d-%m-%Y') as gr_date, g.gr_suratJalan, p.po_no, s.sup_name
            from prc_gr as g inner join prc_po as p on p.po_id = g.po_id
			inner join prc_master_supplier as s on p.sup_id = s.sup_id 
			where g.gr_status=1 and g.gr_printStatus='1' order by gr_no");
	}
	
	function get_gr_inventory($gr_id) {
		return $this->db->query ("select grd.gr_id,pro.is_stockJoin, po.sup_id, grd.pro_id, grd.qty, grd.price, grd.cur_id, gr.gr_no from prc_gr as gr 
		inner join prc_gr_detail as grd on gr.gr_id = grd.gr_id 
		inner join prc_po as po on gr.po_id = po.po_id
		inner join prc_master_product as pro on grd.pro_id = pro.pro_id
		where gr.gr_id = '$gr_id'");
	}
}
?>
