<?php
class Tbl_contrabon extends Model {
	function get_bon($where='',$like='',$from='',$join='',$order='',$distinct=false) {
		
		if (is_array($where)):
			foreach ($where as $key=>$val):
				$this->db->where($key,$val);
			endforeach;
		else:
			
		endif;
		
		if (is_array($like)):
			foreach ($where as $key=>$val):
				$this->db->like($key,$val);
			endforeach;
		else:
			
		endif;
		
		if ($order):
			$this->db->order_by($order);
		endif;
		
		if (is_array($join)&&($from!='')):
			if ($distinct)
				$this->db->distinct();
			$this->db->from($from);
			foreach ($where as $key=>$val):
				$this->db->join($key,$val);
			endforeach;
			return $this->db->get();
		else:
			return $this->db->get($this->config->item('tbl_bon'));	
		endif;
		
	}
	
	function get_contrabon() {
		return $this->db->query("select distinct s.sup_id, s.sup_name, leg.legal_name, s.sup_status from prc_gr as g
			inner join prc_po as p on g.po_id = p.po_id
			inner join prc_master_supplier as s on s.sup_id = p.sup_id 
			inner join prc_master_legality as leg on s.legal_id = leg.legal_id 
			where g.con_id='0' and (gr_status='1' or gr_status='3') and g.gr_type='rec' order by s.sup_name");
	}
	
	function get_po($sup_id) {
		return $this->db->query("select distinct s.sup_id,s.sup_name,p.po_id, p.po_no, leg.legal_name,  
		 CASE WHEN p.po_status = 0 THEN 'BUKA'  
		 WHEN p.po_status = 1 THEN 'TUTUP'
		 END status from prc_gr as g
		 inner join prc_po as p on g.po_id = p.po_id
		 inner join prc_master_supplier as s on s.sup_id = p.sup_id 
		 inner join prc_master_legality as leg on s.legal_id = leg.legal_id 
		 where g.con_id='0' and (gr_status='1' or gr_status='3') and g.gr_type='rec' and p.sup_id='".$sup_id."'");
	} 
	
	function insert_bon($data) {
		return $this->db->insert($this->config->item('tbl_bon'),$data);
	}
	
	function update_bon($where,$data) {
		if (is_array($where)):
			foreach ($where as $key=>$val):
				$this->db->where($key,$val);
			endforeach;
		endif;
		return $this->db->update($this->config->item('tbl_bon'),$data);
	}
	
	function get_bon_print_list() {
		return $this->db->query("SELECT c.con_id, c.con_no, date_format(c.con_date,'%d-%m-%Y') as con_date, s.sup_name 
            FROM prc_contrabon as c
			inner join prc_po as p on c.po_id = p.po_id
			inner join prc_master_supplier as s on s.sup_id = p.sup_id
			where con_printStat='0' and con_status='0'");
	}
	
	function get_bon_print($con_id,$gr_id=0) {
		$return['content'] = $this->db->query("select c.*,date_format(c.con_date,'%d-%m-%Y') as con_date, s.sup_name, leg.legal_name, p.po_status, p.po_id, trm.term_days, 
			(select count(gr_id) from prc_gr as g
			  where g.con_id = c.con_id
			)as jumlah_gr,
			cur.cur_symbol, cur.cur_digit, p.po_no, p.cur_id  
			from prc_contrabon as c
			inner join prc_po as p on p.po_id = c.po_id
			inner join prc_master_supplier as s on s.sup_id = p.sup_id
			inner join prc_master_currency as cur on c.cur_id = cur.cur_id
			inner join prc_master_credit_term as trm on p.term_id = trm.term_id
			inner join prc_master_legality as leg on leg.legal_id = s.legal_id
			where c.con_id='$con_id'");
		$return['detail'] = $this->db->query("select gr.con_id,gr.gr_id, gr.gr_no, gr.gr_type, date_format(gr.gr_date, '%d-%m-%Y') as gr_date, gr.gr_suratJalan,
			 gr.gr_fakturSup, p.po_id, p.po_no, 
			 sum(gd.qty*gd.price*(100 - gd.discount)/100) as gr_value, 
			 (sum(gd.qty*gd.price*(100 - gd.discount)/100) * gd.kurs) as kurs_value, 
			 sum(gd.qty*gd.price*((100 - gd.discount)/100) * 10 / 100) as ppn_value, 
			 sum(gd.qty*gd.price*((100 - gd.discount)/100) * 110 / 100) as tot_ppn_value, 
			 (sum(gd.qty*gd.price*((100 - gd.discount)/100) * 110 / 100) * gd.kurs) as ppn_kurs_value, 
			 cur.cur_symbol, cur.cur_id, cur.cur_digit, gd.kurs 
			 from prc_gr as gr 
			 inner join prc_po as p on gr.po_id = p.po_id
			 inner join prc_gr_detail as gd on gr.gr_id = gd.gr_id
			 inner join prc_master_currency as cur on cur.cur_id = gd.cur_id
			 where gr.con_id='$con_id' and gr.gr_parent='$gr_id'
			 group by gr.gr_id
			 order by gr.gr_date");
		$return['footer'] = $this->db->query("select gr.con_id,gr.gr_id, gr.gr_no, gr.gr_type, date_format(gr.gr_date, '%d-%m-%Y') as gr_date, gr.gr_suratJalan,
			 gr.gr_fakturSup, p.po_id, p.po_no, 
			 sum(gd.qty*gd.price*(100 - gd.discount)/100) as tot_gr_value, 
			 sum(gd.qty*gd.price*(100 - gd.discount)/100) * gd.kurs as tot_kurs_value, 
			 sum(gd.qty*gd.price*((100 - gd.discount)/100) * 10 / 100) as tot_ppn_value, 
			 sum(gd.qty*gd.price*((100 - gd.discount)/100) * 110 / 100) as tot_seluruh_value, 
			 sum(gd.qty*gd.price*((100 - gd.discount)/100) * 110 / 100) * gd.kurs as tot_seluruh_kurs_value, 
			 cur.cur_symbol, cur.cur_id, cur.cur_digit 
			 from prc_gr as gr 
			 inner join prc_po as p on gr.po_id = p.po_id
			 inner join prc_gr_detail as gd on gr.gr_id = gd.gr_id
			 inner join prc_master_currency as cur on cur.cur_id = gd.cur_id
			 where gr.con_id='$con_id' and gr.gr_parent='$gr_id'
			 order by gr.gr_date");
		return $return;
	}
	
	function get_bon_payment($con_id) {
		/*return $this->db->query("select c.con_id, c.con_no, (c.con_value - c.con_payVal) as con_remain, c.cur_id, cur.cur_symbol 
			from prc_contrabon as c
			inner join prc_master_currency as cur on cur.cur_id = c.cur_id
			where con_id='".$con_id."'");
			*/
		$sql = "select distinct c.con_id, c.con_no, 
		(c.con_value - c.con_payVal) as con_remain, 
		(c.con_ppn_value - c.con_ppn_payVal) as con_ppn_remain, 
		c.cur_id, cur.cur_symbol,cur.cur_digit 
			from prc_contrabon as c
			inner join prc_master_currency as cur on cur.cur_id = c.cur_id 
			inner join prc_gr as gr on gr.po_id = c.po_id 
			inner join prc_gr_detail as gd on gr.gr_id = gd.gr_id 
			where c.con_id='".$con_id."'";
		return $this->db->query($sql);
	}
	
	function get_con_term($con_id) {
		return $this->db->query("select * from prc_contrabon as bon 
		inner join prc_po as po on bon.po_id=po.po_id
		inner join prc_master_credit_term as trm on po.term_id=trm.term_id
		where bon.con_id = '".$con_id."'");
	}
	
	function get_rep_bon($search_month,$limit='',$pos='') {
		$this_year	  = date("Y");
		$sql = "select c.con_no, c.con_value, c.con_payVal, date_format(c.con_date,'%d/%m') as con_date,
			date_format(c.con_dueDate,'%d/%m') as con_dueDate,
			cur.cur_symbol, sup.sup_name, date_format(gr.gr_suratJalanTgl,'%d/%m') as gr_suratJalanTgl,
			gr.gr_no
			from prc_contrabon as c 
			inner join prc_master_currency as cur on c.cur_id = cur.cur_id
			inner join prc_po as po on c.po_id = po.po_id
			inner join prc_master_supplier as sup on po.sup_id = sup.sup_id
			inner join prc_gr as gr on gr.con_id = c.con_id
			where 1=1";	
			
		if($search_month != 0)
			$sql .= " and month(c.con_date) = '$search_month' and year(c.con_date)='$this_year'";
			
		$sql .= " order by c.con_no ";
		
		if($limit!='')
			$sql .= "LIMIT $pos, $limit";
			
		return $this->db->query($sql);
	}
	
	function get_bon_after_print_list() {
		return $this->db->query("SELECT c.con_id, c.con_no, date_format(c.con_date,'%d-%m-%Y') as con_date, s.sup_name 
            FROM prc_contrabon as c
			inner join prc_po as p on c.po_id = p.po_id
			inner join prc_master_supplier as s on s.sup_id = p.sup_id
			where con_printStat='1' ");//and con_status='0'");
	}
}
?>