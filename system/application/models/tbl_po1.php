<?php
class Tbl_po extends Model {
	
	function Tbl_po(){
		parent::Model();
		$this->CI =& get_instance();
	}
	
	function cek_po ($ses, $sup, $cur, $pay){
		$data = array(
			'session_id' => $ses,
			'sup_id' => $sup,
			'cur_id' => $cur, //==> digunakan untuk sekenario pemisahaan po berdasarkan currency
			'term_id' => $pay
		);
		$this->db->where($data);
		return $query = $this->db->get('prc_po'); 
	}
	
	function insert_po ($ses, $code, $sup, $cur, $pay){
		$data = array(
			'session_id' => $ses,
			'sup_id' => $sup,
			'po_no' => $code,
			'cur_id' => $cur,
			'term_id' => $pay,
			'po_date' => date('Y-m-d')
		);
		$this->db->insert('prc_po',$data);
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
		return $query = $this->db->update('prc_po',$datau); 
	}
	
	function po_list(){
		$select =  $this->db->query("SELECT p.po_id, p.po_no, date_format( p.po_date, '%d-%m-%Y' ) AS po_date, s.sup_name, l.legal_name
			FROM prc_po AS p
			INNER JOIN prc_master_supplier AS s ON s.sup_id = p.sup_id
			INNER JOIN prc_master_legality AS l ON l.legal_id = s.legal_id
			WHERE po_printStat = '0' and po_status='0'"
		);
		$return['records'] = $select;
		
		$return['record_count'] = $select->num_rows();
		
		return $return;
	}
	
	function get_po_content($id){
		$return['head'] = $this->db->query("SELECT p.po_id, p.po_no, date_format( p.po_date, '%d-%m-%Y' ) AS po_date, p.po_lastprintDate, p.po_printCounter, s.sup_name, s.sup_phone1, s.sup_fax, l.legal_name, cp.per_Fname, cp.per_Lname,
				t.term_days FROM prc_po AS p
				INNER JOIN prc_master_credit_term AS t ON t.term_id = p.term_id
				INNER JOIN prc_master_supplier AS s ON s.sup_id = p.sup_id
				INNER JOIN prc_master_legality AS l ON l.legal_id = s.legal_id
				LEFT JOIN prc_master_contact_person AS cp ON cp.sup_id = s.sup_id
				WHERE po_id ='$id'");
		
		$return['detail'] = $this->db->query("SELECT pr.qty, date_format( pr.rfq_delivery_date, '%d-%m-%Y' ) AS rfq_delivery_date, pr.price, pr.discount, suppro.sup_pro_code,
				(pr.price * pr.qty * ( pr.discount /100 )) AS discount_val, 
				(pr.price * pr.qty * ( ( 100 - pr.discount ) /100 )) AS amount, 
				pro.pro_name, pro.pro_code, m.satuan_name, cur.cur_id, cur.cur_symbol
				FROM prc_pr_detail AS pr
				INNER JOIN prc_master_product AS pro ON pr.pro_id = pro.pro_id
				INNER JOIN prc_master_satuan AS m ON pr.um_id = m.satuan_id
				INNER JOIN prc_master_currency AS cur ON cur.cur_id = pr.cur_id
				LEFT JOIN prc_master_supplier_product as suppro ON suppro.pro_id = pr.pro_id and suppro.sup_id = pr.sup_id 
				WHERE pr.po_id ='$id'");
					
		//$return['footer'] = $this->db->query("select sum(price * qty * ((100 - discount)/100)) as tot_price, sum(price * qty * (discount/100)) as tot_discount 
				//from prc_pr_detail where po_id='$id'");
		
		$return['footer'] = $this->db->query("SELECT pr.cur_id, cur.cur_symbol, sum( pr.price * pr.qty * ( ( 100 - pr.discount ) /100 ) ) AS tot_price, sum( pr.price * pr.qty * ( pr.discount /100 ) ) AS tot_discount
				FROM prc_pr_detail as pr
				INNER JOIN prc_master_currency AS cur ON cur.cur_id = pr.cur_id
				WHERE po_id ='$id'
				GROUP BY cur_id");
		
		return $return;
	}
	
	function footer_po($id, $cur){
		$return = $this->db->query("select sum(price * qty * ((100 - discount)/100)) as tot_price, sum(price * qty * (discount/100)) as tot_discount 
				from prc_pr_detail where po_id='$id' and cur_id ='$cur'");
	}
	
	function update_po($id, $count, $tgl, $user){
		if ($count == '0'){
			$count = '1';
			$tgl = date('Y-m-d');
		}else{
			$count = $count + 1;
			$date = explode('-',$tgl);
			$tgl = $date[2]."-".$date[1]."-".$date[0];
		}
		$datau = array(
			'po_printStat' => '1',
			'po_printUser' => $user,
			'po_printDate' => $tgl,
			'po_lastprintDate' => date('Y-m-d'),
			'po_printCounter' => $count
		);
		$dataw = array(
			'po_id' => $id
		);
		$this->db->where($dataw);
		$query = $this->db->update('prc_po',$datau);
	}


	// BY AHRIE	
	function get_bpb_po($sup_id) {
		return $this->db->query('select s.sup_name, p.po_id, p.po_no, (
			select count(pro_id) from prc_pr_detail as d
			where p.po_id = d.po_id
		 )as jum_item
		 from prc_po as p 
		 inner join prc_master_supplier as s 
		 where p.sup_id=s.sup_id and p.sup_id='.$sup_id.' and p.po_status=0 and p.po_printStat=1 order by s.sup_name');
	}
	
	function get_bpb_po_grt($po_no) {
		return $this->db->query('select sup.sup_name, p.po_id, p.po_no, (
			select count(pro_id) from prc_pr_detail as d
			where p.po_id = d.po_id
		 )as jum_item
		 from prc_po as p 
		 inner join prc_master_supplier as sup on sup.sup_id = p.sup_id 
		 where p.po_no like "'.$po_no.'%" and p.po_id in (select po_id from prc_gr) order by sup.sup_name'); //p.po_status=1 and 
	}
	
	function get_bpb_sup() {
		return $this->db->query('select distinct s.sup_id,s.sup_name from prc_po as p 
	        inner join prc_master_supplier as s on s.sup_id = p.sup_id
			where p.po_status="0" and p.po_printStat=1');
	}
	
	function get_bpb_po_det($po_id,$stats='') {
		//$this->db->where('po_id',$po_id);
		//$get_po_id=$this->db->get('prc_po');
		//if ($get_po_id->num_rows() > 0):
			//$po_id=$get_po_id->row()->po_id;
			$sql_po_det = 'select pr.auth_no,pr.pr_id,pro.pro_id,sup.sup_name,pr.qty, pr.qty_terima, pr.qty_retur, abs((pr.qty_terima - pr.qty_retur - pr.qty)) as qty_remain,
	             CASE WHEN(pr.qty_terima - pr.qty_retur - pr.qty) < 0 THEN "KURANG"  
				 WHEN (pr.qty_terima - pr.qty_retur - pr.qty) > 0 THEN "LEBIH"
				 ELSE "O.K"
				 END as qty_status,
	             pr.price, (pr.price * pr.qty) as amount, pro.pro_code, pro.pro_name,
	             m.satuan_name from prc_pr_detail as pr
				 inner join  prc_master_product as pro on pr.pro_id = pro.pro_id 
				 inner join prc_master_satuan as m on pr.um_id = m.satuan_id
				 inner join prc_master_supplier as sup on pr.sup_id = sup.sup_id
				 where pr.po_id="'.$po_id.'"';
			
			if ($stats == 'good_return')
				$sql_po_det .= " and pr.qty_terima > 0";
				 
			$return['bpb_po_det'] = $this->db->query($sql_po_det);
			
			$sql_po_id = 'select p.po_no, date_format(p.po_date,"%d-%m-%Y") as po_date, sup_name from  prc_po as p
	         inner join  prc_master_supplier as s on s.sup_id = p.sup_id
	         where po_id="'.$po_id.'"';
			
			$return['bpb_po_id'] = $this->db->query($sql_po_id);
			
			$sql_gr = 'select distinct d.*, p.pro_code, p.pro_name, g.*, date_format(g.gr_date,"%d-%m-%Y") as gr_date, pr.auth_no from prc_gr_detail as d
			   inner join prc_gr as g on g.gr_id = d.gr_id
			   inner join prc_master_product as p on p.pro_id = d.pro_id
			   inner join prc_pr_detail as pr on pr.po_id = g.po_id
			   where g.po_id = "'.$po_id.'"
			   order by g.gr_date';
			
			$return['bpb_gr'] = $this->db->query($sql_gr);
			
			$sql_ret = 'select distinct d.pro_id,pr.qty_terima,p.pro_code, p.pro_name, g.*, date_format(g.gr_date,"%d-%m-%Y") as gr_date, pr.auth_no, 
			   pr.price, pr.cur_id, pr.discount, pr.kurs
			   from prc_gr_detail as d
			   inner join prc_gr as g on g.gr_id = d.gr_id
			   inner join prc_master_product as p on p.pro_id = d.pro_id
			   inner join prc_pr_detail as pr on pr.po_id = g.po_id and pr.pro_id = d.pro_id
			   where g.po_id = "'.$po_id.'"
			   order by g.gr_date';
			
			$return['ret_bpb'] = $this->db->query($sql_ret);
			
			return $return;
		//else:
		//	return false;
		//endif;
	}
	
	function get_bpb_gr_form($po_no) {
		$this->db->where('po_no',$po_no);
		$get_po_id=$this->db->get('prc_po');
		if ($get_po_id->num_rows() > 0):
			$po_id=$get_po_id->row()->po_id;
			$return['bpb_po'] = $this->db->query('select p.sup_id, p.po_id, p.po_no, po_date, sup_name from  prc_po as p
	         inner join  prc_master_supplier as s on s.sup_id = p.sup_id
	         where po_id="'.$po_id.'"');
			$return['bpb_po_det'] = $this->db->query('select pr.pr_id,pr.qty, (pr.qty - pr.qty_terima + pr.qty_retur) as qty_remain, pr.price, pr.discount, 
			 pr.cur_id,(pr.price * pr.qty) as amount,
             pro.pro_code, pro.pro_name, pro.pro_id, m.satuan_name, pr.um_id as pr_um_id, pro.um_id as pro_um_id
             from  prc_pr_detail as pr
			 inner join  prc_master_product as pro on pr.pro_id = pro.pro_id 
			 inner join prc_master_satuan as m on pr.um_id = m.satuan_id
			 where pr.po_id="'.$po_id.'"');
			return $return;
		else:
			return false;
		endif;
	}
	
	function update_bpb_po($where,$data){
		if (is_array($where)):
			foreach($where as $key=>$val):
				$this->db->where($key,$val);
			endforeach;
		endif;
		return $this->db->update($this->config->item('tbl_po'),$data);
	}
	
	function get_po($where,$like=''){
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
		return $this->db->get($this->config->item('tbl_po'));
	}
	
	function get_po_bon($po_id) {
		return $this->db->query("select pr.qty, pr.qty_terima, pr.qty_retur, abs((pr.qty_terima - pr.qty_retur - pr.qty)) as qty_remain,
             CASE WHEN(pr.qty_terima - pr.qty_retur - pr.qty) < 0 THEN 'KURANG'  
			 WHEN (pr.qty_terima - pr.qty_retur - pr.qty) > 0 THEN 'LEBIH'
			 ELSE 'O.K'
			 END as qty_status,
             pr.price, (pr.price * pr.qty) as amount, pro.pro_code, pro.pro_name, 
             m.satuan_name from prc_pr_detail as pr
			 inner join prc_master_product as pro on pr.pro_id = pro.pro_id 
			 inner join prc_master_satuan as m on pr.um_id = m.satuan_id
			 where pr.po_id='".$po_id."'");
	}
	
	function get_po_pay($sup_id) {
		return $this->db->query("select sup.sup_name, p.sup_id, p.po_id, p.po_no, 
		 CASE WHEN p.po_status = 0 THEN 'BUKA'  
		 WHEN p.po_status = 1 THEN 'TUTUP'
		 END status
		 from prc_po as p 
		 inner join prc_master_supplier as sup 
		 where sup.sup_id = p.sup_id and p.sup_id='$sup_id' and (p.po_status=0 or p.po_status=1)");
	}
	
	function get_full_po ($where,$like=false,$fromtbl=false,$join=false,$order=false) {
		if (is_array($where)):
			foreach ($where as $key=>$val):
				$this->db->where($key,$val);
			endforeach;
		else:
			$this->db->where('po_id',$where);
		endif;
		
		if (is_array($like)):
			foreach ($like as $key=>$val):
				$this->db->like($key,'%'.$val.'%');
			endforeach;
		endif;
		
		if (is_array($join)):
			$this->db->from($fromtbl);
			foreach ($join as $tbl=>$relasi):
				$this->db->join($tbl,$relasi);
			endforeach;
			$this->db->get();
		else:
			$this->db->get($this->config->item('tbl_po'));
		endif;
			
	}
	
	function get_po_flexi($flex_stat = true, $po_id = '') {			
		$this->db->select("p.po_id, p.po_no, date_format(p.po_date,'%d-%m-%Y') as po_date, s.sup_name",FALSE);
        $this->db->from("prc_po as p");
		$this->db->join("prc_master_supplier as s","s.sup_id = p.sup_id");
		if ($po_id == ''):
			$this->db->where("p.po_status",0);
			$this->db->where("p.po_printStat",1);
			if ($flex_stat)
				$this->CI->flexigrid->build_query();
			$return['result'] = $this->db->get();
			if ($flex_stat)
				$this->CI->flexigrid->build_query(FALSE);
			$return['count'] = $return['result']->num_rows();
		else:
			$this->db->where("p.po_id",$po_id);
			$return = $this->db->get();	
		endif;
		
		return $return;
	}
	
	function get_po_det_list($po_id) {				 		
		$return['get_po_det'] = $this->db->query ("select pr.qty, pr.qty_terima, pr.qty_retur, abs((pr.qty_terima - pr.qty_retur - pr.qty)) as qty_remain,
             CASE WHEN(pr.qty_terima - pr.qty_retur - pr.qty) < 0 THEN 'KURANG'  
			 WHEN (pr.qty_terima - pr.qty_retur - pr.qty) > 0 THEN 'LEBIH'
			 ELSE 'O.K'
			 END as qty_status,
             pr.price, (pr.price * pr.qty) as amount, pro.pro_code, pro.pro_name, 
             m.satuan_name from prc_pr_detail as pr
			 inner join prc_master_product as pro on pr.pro_id = pro.pro_id 
			 inner join prc_master_satuan as m on pr.um_id = m.satuan_id
			 where pr.po_id='$po_id'");
		$return['get_gr_grl'] = $this->db->query ("select d.*, p.pro_code, p.pro_name, g.*, date_format(g.gr_date,'%d-%m-%Y') as gr_date from prc_gr_detail as d
		   inner join prc_gr as g on g.gr_id = d.gr_id
		   inner join prc_master_product as p on p.pro_id = d.pro_id
		   where g.po_id = '$po_id'
		   order by g.gr_date");
		return $return;
	}
	
	
	function get_datalist_po(){
		return $this->db->query("SELECT p.po_id, p.po_no, date_format( p.po_date, '%d-%m-%Y' ) AS po_date, s.sup_name, s.legal_id, l.legal_name
			FROM prc_po AS p
			INNER JOIN prc_master_supplier AS s ON s.sup_id = p.sup_id
			INNER JOIN prc_master_legality AS l ON s.legal_id = l.legal_id
			WHERE po_status = '0'
			AND po_printStat = '1'");
	}
	
	
	function get_openpo ($po){
		$return['headpo'] = $this->db->query("select p.po_id, p.po_no, date_format(p.po_date,'%d-%m-%Y') as po_date, sup_name from prc_po as p
	         inner join prc_master_supplier as s on s.sup_id = p.sup_id
	         where po_id='$po'");
		
		$return['detailpo'] = $this->db->query("SELECT pr.qty, pr.qty_terima, pr.qty_retur, abs( (
			pr.qty_terima - pr.qty_retur - pr.qty
			) ) AS qty_remain,
			CASE WHEN (
			pr.qty_terima - pr.qty_retur - pr.qty
			) <0
			THEN 'KURANG'
			WHEN (
			pr.qty_terima - pr.qty_retur - pr.qty
			) >0
			THEN 'LEBIH'
			ELSE 'O.K'
			END AS qty_status, pr.price, (
			pr.price * pr.qty
			) AS amount, pro.pro_code, pro.pro_name, m.satuan_name
			FROM prc_pr_detail AS pr
			INNER JOIN prc_master_product AS pro ON pr.pro_id = pro.pro_id
			INNER JOIN prc_master_satuan AS m ON pr.um_id = m.satuan_id
			WHERE pr.po_id = '$po'");
		
		$return['detailgr'] =$this->db->query("select d.*, p.pro_code, p.pro_name, g.*, date_format(g.gr_date,'%d-%m-%Y') as gr_date from prc_gr_detail as d
		   inner join prc_gr as g on g.gr_id = d.gr_id
		   inner join prc_master_product as p on p.pro_id = d.pro_id
		   where g.po_id = '$po'
		   order by g.gr_date");
		
		return $return;
	}
	
	function close_po($po, $reas){
		$data = array(
			'po_status' => '1',
			'po_closeDate' => date('Y-m-d'),
			'po_note' => $reas
		);

		$this->db->where('po_id',$po);
		$query = $this->db->update('prc_po',$data);
	}	
//========================= Report po per kategori =========================
	function get_code($ind) {
		$this->db->select('cat_id ,cat_code, cat_name');
		$this->db->where('cat_level',$ind);
		return $query = $this->db->get('prc_master_category');
	}
	
	function get_cat($code) {
		return $this->db->query("SELECT d.cur_id, c.cur_symbol, SUM( d.price ) ttl_harga
			FROM prc_pr_detail AS d
			INNER JOIN prc_master_product AS p ON p.pro_id = d.pro_id
			INNER JOIN prc_master_currency AS c ON d.cur_id = c.cur_id
			WHERE pro_code LIKE '$code.%'
			AND qty_terima <> ''");
	}
	
	function get_code2($ind, $code, $eof) {
	$i = 0;	
	$in3 = 3;
		$this->db->select('cat_id, cat_code, cat_name');
		$this->db->where('cat_level',$ind);
		$this->db->like('cat_code', $code.'.%');
		
		$query = $this->db->get('prc_master_category');
		
		if ($query->num_rows() > 0){
		echo "<table width='100%'>";
			foreach ($query->result() as $row2):
			$i =  $i+ 1;
				$datacat = $this->tbl_po->get_cat($row2->cat_code);
				echo "<tr class='ui-widget-header'><td width='150'><a href='javascript:void(0)' onclick=open_2('".$in3."','".$row2->cat_code."','".$row2->cat_id."')>$row2->cat_name</a></td>";
				foreach ($datacat->result() as $cat):
				 	
				 	echo "<td>$cat->cur_symbol. ".number_format($cat->ttl_harga,2,',','.')."</td>";
				 	
				endforeach;
				echo "</tr>";
				if ($eof == 3){
					
				}else{
				echo "<tr style='display:none' id='tr3_$row2->cat_id'><td></td><td><div id='r3_".$row2->cat_id."'></div><input type='hidden' id='t3_".$row2->cat_id."' value='0'></td></tr>";
				}
			endforeach;
		echo "</table>";
		}else{
			echo "Empty";
		}
	}
	//======================================================================

}
?>
