<?
class tbl_good_return extends Model {
	function tbl_good_return() {
		parent::Model();
	}
	
	function insert_return($data) {
		return $this->db->insert('prc_good_return',$data);	
	}
	
	function insert_return_detail($data) {
		return $this->db->insert('prc_good_return_detail',$data);	
	}
	
	function update_return($where,$data) {
		if(is_array($where)):
			foreach ($where as $field=>$key):
				$this->db->where($field,$key);
			endforeach;
		endif;
		return $this->db->update('prc_good_return',$data);
	}
	
	function update_return_detail($where,$data) {
		if(is_array($where)):
			foreach ($where as $field=>$key):
				$this->db->where($field,$key);
			endforeach;
		endif;
		return $this->db->update('prc_good_return_detail',$data);
	}
	
	function get_retur($ret_id) {
		return $this->db->query("select gd.ret_id,pro.is_stockJoin, po.sup_id, gd.pro_id, gd.qty, pr.price, pr.cur_id, g.ret_no, 
		pr.pr_id, pr.po_id, pr.qty_retur, pro.um_id as pro_sat, pr.um_id as pr_sat
		from prc_good_return as g 
		inner join prc_good_return_detail as gd on g.ret_id = gd.ret_id 
		inner join prc_po as po on g.po_id = po.po_id
		inner join prc_pr_detail as pr on gd.pro_id = pr.pro_id and g.po_id = pr.po_id
		inner join prc_master_product as pro on gd.pro_id = pro.pro_id
		where g.ret_id = '$ret_id'");
	}
	
	function get_retur_bon($po_id) {
		return $this->db->query("select pro.pro_name,pro.pro_code, g.ret_no, pr.qty_terima, gd.qty, sat.satuan_name, gd.price, gd.keterangan 
		from prc_good_return as g
		inner join prc_good_return_detail as gd on gd.ret_id = g.ret_id 
		inner join prc_master_product as pro on pro.pro_id = gd.pro_id
		inner join prc_pr_detail as pr on pr.po_id = g.po_id and pr.pro_id = gd.pro_id
		inner join prc_master_satuan as sat on sat.satuan_id = pr.um_id
		where g.po_id = $po_id and g.bkbk_id = '0' and (g.ret_status = 1 or g.ret_status = 2)");
	}
	
	function get_retur_bon_price($ret_id) {
		return $this->db->query("select sum(gd.qty * gd.price) as price_retur
		from prc_good_return as g
		inner join prc_good_return_detail as gd on gd.ret_id = g.ret_id
		where g.ret_id = $ret_id and g.con_id = 0 and (g.ret_status = 1 or g.ret_status = 2)");
	}
	
	function get_retur_pay ($sup_id) {
		return $this->db->query(" select sum(gd.price * gd.qty) as total, g.ret_no, date_format(g.ret_date,'%d-%m-%Y') as ret_date, po.po_no, cur.cur_symbol 
		from prc_good_return as g
		inner join prc_good_return_detail as gd on gd.ret_id = g.ret_id
		inner join prc_po as po on po.po_id = g.po_id 
		inner join prc_master_currency as cur on cur.cur_id = po.cur_id 
		where po.sup_id = $sup_id and g.bkbk_id = 0
		");
	}
	
	function get_retur_contrabon ($po_id) {
		return $this->db->query(" select cur.cur_digit,g.ret_id, g.ret_id,sum(gd.price * gd.qty) as total, g.ret_no, date_format(g.ret_date,'%d-%m-%Y') as ret_date, po.po_no, cur.cur_symbol 
		from prc_good_return as g
		inner join prc_good_return_detail as gd on gd.ret_id = g.ret_id
		inner join prc_po as po on po.po_id = g.po_id 
		inner join prc_master_currency as cur on cur.cur_id = po.cur_id 
		where g.po_id = $po_id and g.con_id = 0 and (g.ret_status = 1 or g.ret_status = 2)
		");
	}
	
	function get_print_retur_bon ($con_id,$po_id) {
		return $this->db->query("select distinct sum(gd.qty * gd.price) as total_retur, g.ret_no, date_format(g.ret_date,'%d-%m-%Y') as ret_date, cur.cur_symbol
		from prc_good_return as g
		inner join prc_good_return_detail as gd on gd.ret_id = g.ret_id
		inner join prc_master_currency as cur on cur.cur_id = gd.cur_id
		where g.con_id = $con_id and g.po_id = $po_id and (g.ret_status = 1 or g.ret_status = 2)");
	}
}
?>