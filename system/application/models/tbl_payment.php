<?php
class Tbl_payment extends Model {
	
	function Tbl_payment (){
	// call the Model constructor
		parent::Model();
	// load database class and connect to MySQL
		$this->load->database();
		$this->CI =& get_instance();
	}
	
	function list_payment() {
		$this->db->order_by('pay_name','ASC');
		return $this->db->get('prc_master_payment');
	}

	function get_sup_payment() {
		return $this->db->query("select distinct s.sup_id,s.sup_name, leg.legal_name, s.sup_status 
			from prc_contrabon as c 
			inner join prc_po as p on p.po_id = c.po_id
	        inner join prc_master_supplier as s on s.sup_id = p.sup_id
			inner join prc_master_legality as leg on leg.legal_id = s.legal_id
			where (((c.con_value - c.con_payVal) > 0) or (c.con_ppn_value - c.con_ppn_payVal) > 0) and p.po_status='1' and c.con_status='0' and c.con_printStat='1'
			order by s.sup_name");
	}
	
	function update_bkbk($where,$data) {
		if (is_array($where)):
			foreach ($where as $field => $val):
				$this->db->where($field,$val);
			endforeach;
		endif;
		
		return $this->db->update('prc_bkbk',$data);
	}

}
?>
