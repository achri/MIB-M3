<?
class tbl_so extends Model {
	function tbl_so(){
		parent::Model();
		$this->CI =& get_instance();
	}
	
	function cek_so ($ses, $sup, $cur, $pay){
		$data = array(
			'session_id' => $ses,
			'sup_id' => $sup,
			'cur_id' => $cur, //==> digunakan untuk sekenario pemisahaan po berdasarkan currency
			'term_id' => $pay
		);
		$this->db->where($data);
		return $query = $this->db->get('prc_so'); 
	}
	
	function insert_so ($ses, $code, $sup, $cur, $pay){
		$data = array(
			'session_id' => $ses,
			'sup_id' => $sup,
			'so_no' => $code,
			'cur_id' => $cur,
			'term_id' => $pay,
			'so_date' => date('Y-m-d')
		);
		$this->db->insert('prc_so',$data);
		return $id = $this->db->insert_id();	
	}
	
	function remove_session($ses){
		$datau = array(
			'session_id' => ''
		);
		$dataw = array(
			'session_id' => $ses
		);
		$this->db->where($dataw);
		return $query = $this->db->update('prc_so',$datau); 
	}
	
	function so_list(){
		$select =  $this->db->query("SELECT p.so_id, p.so_no, date_format( p.so_date, '%d-%m-%Y' ) AS so_date, s.sup_name, l.legal_name
			FROM prc_so AS p
			INNER JOIN prc_master_supplier AS s ON s.sup_id = p.sup_id
			INNER JOIN prc_master_legality AS l ON l.legal_id = s.legal_id
			WHERE so_printStat = '0' and so_status='0'"
		);
		$return['records'] = $select;
		
		$return['record_count'] = $select->num_rows();
		
		return $return;
	}
	
	function get_so_content($id){
		$return['head'] = $this->db->query("SELECT p.so_id, p.so_no, date_format( p.so_date, '%d-%m-%Y' ) AS so_date, p.so_lastprintDate, p.so_printCounter, s.sup_name, s.sup_phone1, s.sup_fax, l.legal_name, cp.per_Fname, cp.per_Lname,
				t.term_days FROM prc_so AS p
				INNER JOIN prc_master_credit_term AS t ON t.term_id = p.term_id
				INNER JOIN prc_master_supplier AS s ON s.sup_id = p.sup_id
				INNER JOIN prc_master_legality AS l ON l.legal_id = s.legal_id
				LEFT JOIN prc_master_contact_person AS cp ON cp.sup_id = s.sup_id
				WHERE so_id ='$id'");
		
		$return['detail'] = $this->db->query("SELECT sr.qty, date_format( sr.srfq_delivery_date, '%d-%m-%Y' ) AS srfq_delivery_date, sr.price, suppro.sup_pro_code,
				(sr.price * sr.qty) AS discount_val, 
				(sr.price * sr.qty) AS amount, 
				(sr.price * sr.qty * 10 / 100 ) AS ppn, 
				(sr.price * sr.qty * 110 / 100) AS sum_ppn,
				pro.pro_name, pro.pro_code, m.satuan_name, cur.cur_id, cur.cur_symbol
				FROM prc_sr_detail AS sr
				INNER JOIN prc_master_product AS pro ON sr.pro_id = pro.pro_id
				INNER JOIN prc_master_satuan AS m ON sr.um_id = m.satuan_id
				INNER JOIN prc_master_currency AS cur ON cur.cur_id = sr.cur_id
				LEFT JOIN prc_master_supplier_product as suppro ON suppro.pro_id = sr.pro_id and suppro.sup_id = sr.sup_id 
				WHERE sr.so_id ='$id'");
					
		//$return['footer'] = $this->db->query("select sum(price * qty * ((100 - discount)/100)) as tot_price, sum(price * qty * (discount/100)) as tot_discount 
				//from prc_pr_detail where po_id='$id'");
		
		$return['footer'] = $this->db->query("SELECT sr.cur_id, cur.cur_symbol, 
				sum( sr.price * sr.qty ) AS tot_price, 
				sum( sr.price * Sr.qty * 10 / 100) AS hrg_ppn,
				sum( sr.price * sr.qty * 110 / 100) AS tot_ppn, 
				sum( sr.price * sr.qty ) AS tot_discount
				FROM prc_sr_detail as sr
				INNER JOIN prc_master_currency AS cur ON cur.cur_id = sr.cur_id
				WHERE so_id ='$id'
				GROUP BY cur_id");
		
		return $return;
	}
	
	function update_so($id, $count, $tgl, $user){
		if ($count == '0'){
			$count = '1';
			$tgl = date('Y-m-d');
		}else{
			$count = $count + 1;
			$date = explode('-',$tgl);
			$tgl = $date[2]."-".$date[1]."-".$date[0];
		}
		$datau = array(
			'so_printStat' => '1',
			'so_printUser' => $user,
			'so_printDate' => $tgl,
			'so_lastprintDate' => date('Y-m-d'),
			'so_printCounter' => $count
		);
		$dataw = array(
			'so_id' => $id
		);
		$this->db->where($dataw);
		$query = $this->db->update('prc_so',$datau);
	}
	
	function get_so_sup() {
		return $this->db->query('select distinct s.sup_id,s.sup_name 
			from prc_so as so
	        inner join prc_master_supplier as s on s.sup_id = so.sup_id
			where so.so_status="0" and so.so_printStat=1 order by s.sup_name');
	}
	
	function get_so($where,$like=''){
		if (is_array($where)):
			foreach($where as $key=>$val):
				$this->db->where($key,$val);
			endforeach;
		endif;
		if (is_array($like)):
			foreach($like as $key=>$val):
				$this->db->like($key,$val,'after');	
			endforeach;
		endif;			
		return $this->db->get($this->config->item('tbl_so'));
	}
	
	function get_so_mr($so_id) {
		$sql_header = "select so.so_id,so.so_no,date_format(so.so_date,'%d-%m-%Y') as so_date, sup.sup_name
		from prc_so as so
		inner join prc_master_supplier as sup on sup.sup_id = so.sup_id
		where so.so_id = $so_id
		";
		
		$sql_detail = "SELECT pro.pro_name,pro.pro_code,mrd.qty,sat.satuan_name,sat.satuan_format
		from prc_mr_detail as mrd
		inner join prc_good_release as grl on grl.grl_id = mrd.grl_id
		inner join prc_master_product as pro on pro.pro_id = mrd.pro_id
		inner join prc_master_satuan as sat on sat.satuan_id = mrd.um_id
		where grl.grl_status = 1 and mrd.so_id = $so_id
		";
		
		$get_so_mr['header'] = $this->db->query($sql_header);
		$get_so_mr['detail'] = $this->db->query($sql_detail);
		
		return $get_so_mr;
	}
	
	function update_so_mr($where,$data) {
		if (is_array($where)):
			foreach ($where as $field => $val):
				$this->db->where($field,$val);
			endforeach;
		endif;
		
		return $this->db->update($this->config->item('tbl_so'),$data);
	}
}
?>