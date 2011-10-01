<?php
class Tbl_rfq_service extends Model {
	
	function Tbl_rfq_service(){
		parent::Model();
		$this->CI =& get_instance();
	}
	
	//data Flex untuk RFQ Final approval
	function rfq_service_list(){
		$select1 = "SELECT ";
		$select2 = "rfq_id, rfq_no, date_format( rfq_date, '%d-%m-%Y' ) AS rfq_date, date_format( rfq_printDate, '%d-%m-%Y' ) AS rfq_printDate, 
			(SELECT count( pro_id )
				FROM prc_pr_detail AS d
				WHERE d.rfq_id = r.rfq_id 
				AND (d.rfq_stat =0)
			) AS item_waiting, 
			(SELECT count( pro_id )
				FROM prc_pr_detail AS d
				WHERE d.rfq_id = r.rfq_id
				AND (d.rfq_stat =2)
			) AS item_pending,
			(SELECT count( pro_id )
				FROM prc_pr_detail AS d
				WHERE d.rfq_id = r.rfq_id
				AND (d.rfq_stat =3)
			) AS item_reject,
			(SELECT count( pro_id )
				FROM prc_pr_detail AS d
				WHERE d.rfq_id = r.rfq_id
				AND (d.rfq_stat =1 OR d.rfq_stat =1)
			) AS item_ok, 
			(SELECT count( pro_id )
				FROM prc_pr_detail AS d
				WHERE d.rfq_id = r.rfq_id
			) AS item_number
		FROM `prc_rfq` AS r
		WHERE (SELECT count( rfq_stat ) 
				FROM prc_pr_detail AS d
				WHERE (d.rfq_stat=0 or d.rfq_stat=2) and d.rfq_id = r.rfq_id
			  ) > 0 
		AND rfq_printStat = '1' {SEARCH_STR}";
		
		$querys['main_query'] = $select1.$select2;
		$querys['count_query'] = $select1."count(rfq_id) as record_count, ".$select2;
		
		$build_querys = $this->CI->flexigrid->build_querys($querys,FALSE); 
		//Get contents
		$return['records'] = $this->db->query($build_querys['main_query']);
		//Get record count
		$get_record_count = $this->db->query($build_querys['count_query']);
		$row = $get_record_count->row();
		$return['record_count'] = $row->record_count;
		return $return;
	}
	
	//data Flex untuk RFQ manajemen approval
	function rfq_service_list_appr(){
		$select1 = "SELECT ";
		$select2 = "rfq_id, rfq_no, date_format( rfq_date, '%d-%m-%Y' ) AS rfq_date, date_format( rfq_printDate, '%d-%m-%Y' ) AS rfq_printDate, 
			(SELECT count( pro_id )
				FROM prc_pr_detail AS d
				WHERE d.rfq_id = r.rfq_id
				AND (d.rfq_stat =1)
			) AS item_waiting
		FROM `prc_rfq` AS r
		WHERE (SELECT count( rfq_stat )
			FROM prc_pr_detail AS d
			WHERE (d.rfq_stat =1)
			AND d.rfq_id = r.rfq_id) >0 {SEARCH_STR}";
		
		$querys['main_query'] = $select1.$select2;
		$querys['count_query'] = $select1."count(rfq_id) as record_count, ".$select2;
		
		$build_querys = $this->CI->flexigrid->build_querys($querys,FALSE); 
		//Get contents
		$return['records'] = $this->db->query($build_querys['main_query']);
		//Get record count
		$get_record_count = $this->db->query($build_querys['count_query']);
		$row = $get_record_count->row();
		$return['record_count'] = $row->record_count;
		return $return;
	}
	
	//ini index untuk frq Final 
	function rfq_service_content($id){
		$rfqcontent = $this->db->query("SELECT r.rfq_id, r.rfq_no, date_format( r.rfq_date, '%d-%m-%Y' ) AS rfq_date, date_format( r.rfq_printDate, '%d-%m-%Y' ) AS rfq_date_print, 
										pd.num_supplier, pd.qty, pd.um_id, pd.pro_id, pd.pr_id, pro.pro_code, pro.pro_name, s.satuan_name, pro.cat_id, u.usr_name, c.cat_name
										FROM prc_pr_detail AS pd
										INNER JOIN prc_rfq AS r ON pd.rfq_id = r.rfq_id
										INNER JOIN prc_pr AS p ON pd.pr_id = p.pr_id
										INNER JOIN prc_master_product AS pro ON pd.pro_id = pro.pro_id
										INNER JOIN prc_master_category AS c ON pro.cat_id = c.cat_id
										INNER JOIN prc_master_satuan AS s ON pd.um_id = s.satuan_id
										INNER JOIN prc_sys_user AS u ON r.rfq_printUsr = u.usr_id
										WHERE pd.rfq_id = '$id'
										AND (
										pd.rfq_stat =0
										OR pd.rfq_stat =2
										)");
		return $rfqcontent;
	}
	
	function rfq_service_content_price($pro_id){
		$rfqprice = $this->db->query("SELECT MIN(inv_price) as min, MAX(inv_price) as max, AVG(inv_price) as rata
										FROM prc_inventory where pro_id = '$pro_id'");
		return $rfqprice;
		
	}
	
	//ini index untuk rfq approval manajemen
	function rfq_service_manaj($id){
		$rfqcontent = $this->db->query("SELECT r.rfq_id , r.rfq_no , date_format( r.rfq_date , '%d-%m-%Y' ) AS rfq_date , 
											date_format( r.rfq_printDate , '%d-%m-%Y' ) AS rfq_date_print , pd.num_supplier , pd.price,
											pd.qty , pd.um_id , pd.pro_id , pd.pr_id , date_format( pd.rfq_delivery_date , '%d-%m-%Y' ) as rfq_deldate , 
											pd.discount , pd.kurs , pd.cur_id , cur.cur_symbol , pro.pro_code , pro.pro_name , 
											s.satuan_name , pro.cat_id , u.usr_name , sup.legal_id , sup.sup_id, sup.sup_name , term.term_id, term.term_id_name , 
											term.term_days , leg.legal_name 
											FROM prc_pr_detail AS pd 
											INNER JOIN prc_rfq AS r ON pd.rfq_id = r.rfq_id 
											INNER JOIN prc_pr AS p ON pd.pr_id = p.pr_id 
											INNER JOIN prc_master_credit_term AS term ON pd.term = term.term_id 
											INNER JOIN prc_master_supplier sup ON pd.sup_id = sup.sup_id 
											INNER JOIN prc_master_product AS pro ON pd.pro_id = pro.pro_id 
											INNER JOIN prc_master_satuan AS s ON pd.um_id = s.satuan_id 
											INNER JOIN prc_master_legality AS leg ON leg.legal_id = sup.legal_id 
											INNER JOIN prc_master_currency AS cur ON cur.cur_id = pd.cur_id 
											INNER JOIN prc_sys_user AS u ON r.rfq_printUsr = u.usr_id 
											WHERE pd.rfq_id = '$id' 
											AND pd.rfq_stat = 1
										");
		return $rfqcontent;
	}
	
	//RFQ FINAL Disetujui
	function rfq_insert_1($rfq, $pr, $pro, $status, $sup, $qty, $sat, $deldate, $harga, $pay, $proname, $kurs, $disc, $cur){
		$date = date('Y-m-d', strtotime($deldate));
		$dataupd1 = array(
					'qty' => $qty,
					'um_id' => $sat,
					'sup_id' => $sup,
					'cur_id' => $cur,
					'price' => $harga,
					'term' => $pay,
					'rfq_delivery_date' => $date,
					'discount' => $disc,
					'kurs' => $kurs,
					'rfq_stat' => $status
				);
		$datawhr1 = array('pr_id' => $pr, 'pro_id' => $pro, 'rfq_id' => $rfq);
		$this->db->where($datawhr1);
		$this->db->update('prc_pr_detail', $dataupd1);
		return "<b><font color='red'>".$proname."</font></b> disetujui";
	}
	
	//RFQ FINAL Ditunda dan Ditolak
	function rfq_insert_2_3($rfq, $pr, $pro, $status, $proname){
		$dataupd2 = array(
					'rfq_stat' => $status,
				);
		$datawhr2 = array('pr_id' => $pr, 'pro_id' => $pro, 'rfq_id' => $rfq);
		$this->db->where($datawhr2);
		$this->db->update('prc_pr_detail', $dataupd2);

		if ($status == 2){
			return "<b><font color='red'>".$proname."</font></b> ditunda";
		}else{
			return "<b><font color='red'>".$proname."</font></b> ditolak";
		}
	}
	
	// RFQ manajemen disetujui
	function rfq_appr_1($pro, $pr, $po_id, $status, $produk, $procode){
		$data1 = array('po_id' => $po_id, 'rfq_stat' => $status);
		$data2 = array('pr_id' => $pr, 'pro_id' => $pro );
		$this->db->where($data2);
		$this->db->update('prc_pr_detail', $data1);
		return "<font color='red'>".$produk." (".$procode.")</font> DiSetujui";
	}
	
	// RFQ manajemen ditunda dan ditolak
	function rfq_appr_2_3($proid, $pr, $status, $produk, $procode){
		$data1 = array('rfq_stat' => '0', 'requestStat' => $status);
		$data2 = array('pr_id' => $pr, 'pro_id' => $proid );
		$this->db->where($data2);
		$this->db->update('prc_pr_detail', $data1);
		
		if ($status == 2){
			return "<font color='red'>".$produk." (".$procode.") </font>Ditunda";
		}else{
			return "<font color='red'>".$produk." (".$procode.") </font>Ditolak";
		}
		
	}

	// BY AHRIE
	
	function get_sr_srfq() {
		return $this->db->query("SELECT p.sr_no,d.sr_id, d.pro_id, pro.pro_code, pro.cat_id, date_format(p.sr_date,'%d-%m-%Y') as sr_date, d.qty, d.emergencyStat, 
		   pro.pro_name, u.usr_name, dep.dep_name, cat.cat_name, sat.satuan_name
		   FROM `prc_sr_detail` AS d 
		   inner join prc_sr as p on p.sr_id = d.sr_id
		   inner join prc_master_product as pro on d.pro_id = pro.pro_id
		   inner join prc_sys_user as u on u.usr_id = p.pr_requestor
		   inner join prc_master_departemen as dep on u.dep_id = dep.dep_id
		   inner join prc_master_category as cat on pro.cat_id = cat.cat_id
		   inner join prc_master_satuan as sat on d.um_id = sat.satuan_id
		   where (d.requestStat='1' or d.requestStat='2' or d.requestStat='3') and d.rfq_service_id='0' and d.pcv_id='0' order by p.sr_no");
	}
	
	function insert_srfq($data) {
		return $this->db->insert($this->config->item('tbl_srfq'),$data);
	}
	
	function update_srfq($where,$data) {
		if (is_array($where)):
			foreach ($where as $key=>$val):
				$this->db->where($key,$val);
			endforeach;
		else:
			$this->db->where('rfq_service_id',$where);
		endif;
		return $this->db->update($this->config->item('tbl_srfq'),$data);
	}
	
	function get_rfq($where) {
		if (is_array($where)):
			foreach ($where as $key=>$val):
				$this->db->where($key,$val);
			endforeach;
		else:
			$this->db->where('rfq_id',$where);
		endif;
		return $this->db->get($this->config->item('tbl_rfq'));
	}
	
	function get_rfq_print_list() {
		return $this->db->query("SELECT rfq_id, rfq_no, date_format(rfq_date,'%d-%m-%Y') as rfq_date,
			DATEDIFF(now(), rfq_date) as tgl_selisih,
			 (
			  SELECT count( pro_id ) 
			  FROM prc_pr_detail AS d
			  WHERE d.rfq_id = r.rfq_id
			 ) AS jum_item,
			 (
			  SELECT count( pro_id ) 
		      FROM prc_pr_detail AS d
			  WHERE d.rfq_id = r.rfq_id and d.emergencyStat=1
			 ) AS emergency
			FROM `prc_rfq` as r
			where rfq_printStat='0'");
	}
	
	function get_rfq_print($rfq_id) {
		return $this->db->query("select r.rfq_printStat,p.pr_no, date_format(p.pr_date,'%d-%m-%Y') as pr_date, r.rfq_no, pd.num_supplier, pd.qty, pd.cur_id,
			 pd.emergencyStat, pro.pro_code, pro.pro_name, m.satuan_name from prc_pr_detail as pd
             inner join prc_rfq as r on pd.rfq_id = r.rfq_id
             inner join prc_pr as p on pd.pr_id = p.pr_id
			 inner join prc_master_product as pro on pd.pro_id = pro.pro_id 
			 inner join prc_master_satuan as m on pd.um_id = m.satuan_id
			 where pd.rfq_id='".$rfq_id."'");
	}
	
	function get_rfq_flexi() {
		
		$return['result'] = $this->db->query("SELECT rfq_id, rfq_no, date_format(rfq_date,'%d-%m-%Y') as rfq_date,
            (dayofyear(now()) - dayofyear(rfq_date)) as tgl_selisih,
			 (
			  SELECT count( pro_id ) 
			  FROM prc_pr_detail AS d
			  WHERE d.rfq_id = r.rfq_id
			 ) AS jum_item,
			 (
			  SELECT count( pro_id ) 
		      FROM prc_pr_detail AS d
			  WHERE d.rfq_id = r.rfq_id and d.emergencyStat=1
			 ) AS emergency
			FROM `prc_rfq` as r
			where rfq_printStat='1'");
		$return['count'] = $return['result']->num_rows();
		
		return $return;
	}
	
	function get_rfq_list_det($rfq_id) {
		return $this->db->query("select p.pr_no, date_format(p.pr_date,'%d/%m') as pr_date, r.rfq_no, pd.num_supplier, pd.qty, pd.cur_id,
			 pd.emergencyStat, pro.pro_code, pro.pro_name, m.satuan_name from prc_pr_detail as pd
             inner join prc_rfq as r on pd.rfq_id = r.rfq_id
             inner join prc_pr as p on pd.pr_id = p.pr_id
			 inner join prc_master_product as pro on pd.pro_id = pro.pro_id 
			 inner join prc_master_satuan as m on pd.um_id = m.satuan_id
			 where pd.rfq_id='".$rfq_id."' and r.rfq_printStat='1'");
	}
	
	function get_history($pro_id){
		return $this->db->query("SELECT pro_id, inv_id, inv_in, inv_out, inv_end
			FROM prc_inventory
			where pro_id = '".$pro_id."'
			GROUP BY inv_transDate = '2009'");
	}
	
	function get_detail_history($detail, $pro_id){
		$now = date('Y-m-d');
		$date = explode('-',$now);
		
		if ($detail == $date[0]){
			return $this->db->query("SELECT inv_transDate, inv_id, inv_in, inv_out, inv_end
				FROM prc_inventory
				WHERE pro_id = '".$pro_id."'
				AND inv_transDate LIKE '".$detail."%'");
		}else{
		$nmonth = $date[1] - $detail;
		$ndate = $date[0].'-'.$nmonth.'-1';	
			return $this->db->query("SELECT inv_transDate, inv_id, inv_in, inv_out, inv_end
				FROM prc_inventory
				WHERE pro_id = '".$pro_id."'
				AND inv_transDate >= '".$ndate."'
				AND inv_transDate <= '".$now."'");
		
		}
	}
	
}
?>