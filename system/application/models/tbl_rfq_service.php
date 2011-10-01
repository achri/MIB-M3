<?php
class Tbl_rfq_service extends Model {
	
	function Tbl_rfq_service(){
		parent::Model();
		$this->CI =& get_instance();
	}
	
	//data Flex untuk RFQ Final approval
	function srfq_list(){
		$select1 = "SELECT ";
		$select2 = "srfq_id, srfq_no, date_format( srfq_date, '%d-%m-%Y' ) AS srfq_date, date_format( srfq_printDate, '%d-%m-%Y' ) AS srfq_printDate, 
			(SELECT count( pro_id )
				FROM prc_sr_detail AS d
				WHERE d.srfq_id = r.srfq_id 
				AND (d.srfq_stat =0)
			) AS item_waiting, 
			(SELECT count( pro_id )
				FROM prc_sr_detail AS d
				WHERE d.srfq_id = r.srfq_id
				AND (d.srfq_stat =2)
			) AS item_pending,
			(SELECT count( pro_id )
				FROM prc_sr_detail AS d
				WHERE d.srfq_id = r.srfq_id
				AND (d.srfq_stat =3)
			) AS item_reject,
			(SELECT count( pro_id )
				FROM prc_sr_detail AS d
				WHERE d.srfq_id = r.srfq_id
				AND (d.srfq_stat =1 OR d.srfq_stat =1)
			) AS item_ok, 
			(SELECT count( pro_id )
				FROM prc_sr_detail AS d
				WHERE d.srfq_id = r.srfq_id
			) AS item_number
		FROM `prc_rfq_service` AS r
		WHERE (SELECT count( srfq_stat ) 
				FROM prc_sr_detail AS d
				WHERE (d.srfq_stat=0 or d.srfq_stat=2) and d.srfq_id = r.srfq_id
			  ) > 0 
		AND srfq_printStat = '1' {SEARCH_STR}";
		
		$querys['main_query'] = $select1.$select2;
		$querys['count_query'] = $select1."count(srfq_id) as record_count, ".$select2;
		
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
	function srfq_list_appr(){
		$select1 = "SELECT ";
		$select2 = "srfq_id, srfq_no, date_format( srfq_date, '%d-%m-%Y' ) AS srfq_date, date_format( srfq_printDate, '%d-%m-%Y' ) AS srfq_printDate, 
			(SELECT count( pro_id )
				FROM prc_sr_detail AS d
				WHERE d.srfq_id = r.srfq_id
				AND (d.srfq_stat =1)
			) AS item_waiting
		FROM `prc_rfq_service` AS r
		WHERE (SELECT count( srfq_stat )
			FROM prc_sr_detail AS d
			WHERE (d.srfq_stat =1)
			AND d.srfq_id = r.srfq_id) > 0 {SEARCH_STR}";
		
		$querys['main_query'] = $select1.$select2;
		$querys['count_query'] = $select1."count(srfq_id) as record_count, ".$select2;
		
		$build_querys = $this->CI->flexigrid->build_querys($querys,FALSE); 
		//Get contents
		$return['records'] = $this->db->query($build_querys['main_query']);
		//Get record count
		$get_record_count = $this->db->query($build_querys['count_query']);
		$row = $get_record_count->row();
		$return['record_count'] = $row->record_count;
		return $return;
	}
	
	//ini index untuk rfq approval manajemen
	function srfq_manaj($id){
		$rfqcontent = $this->db->query("SELECT r.srfq_id , r.srfq_no , date_format( r.srfq_date , '%d-%m-%Y' ) AS srfq_date , 
											date_format( r.srfq_printDate , '%d-%m-%Y' ) AS srfq_date_print , pd.num_supplier , pd.price,
											pd.qty , pd.um_id , pd.pro_id , pd.sr_id , date_format( pd.srfq_delivery_date , '%d-%m-%Y' ) as rfq_deldate , 
											pd.cur_id , cur.cur_symbol , pro.pro_code , pro.pro_name , 
											s.satuan_name , pro.cat_id , u.usr_name , sup.legal_id , sup.sup_id, sup.sup_name , term.term_id, term.term_id_name , 
											term.term_days , leg.legal_name 
											FROM prc_sr_detail AS pd 
											INNER JOIN prc_rfq_service AS r ON pd.srfq_id = r.srfq_id 
											INNER JOIN prc_sr AS p ON pd.sr_id = p.sr_id 
											INNER JOIN prc_master_credit_term AS term ON pd.term = term.term_id 
											INNER JOIN prc_master_supplier sup ON pd.sup_id = sup.sup_id 
											INNER JOIN prc_master_product AS pro ON pd.pro_id = pro.pro_id 
											INNER JOIN prc_master_satuan AS s ON pd.um_id = s.satuan_id 
											INNER JOIN prc_master_legality AS leg ON leg.legal_id = sup.legal_id 
											INNER JOIN prc_master_currency AS cur ON cur.cur_id = pd.cur_id 
											INNER JOIN prc_sys_user AS u ON r.srfq_printUsr = u.usr_id 
											WHERE pd.srfq_id = '$id' 
											AND pd.srfq_stat = 1
										");
		return $rfqcontent;
	}
	
	function srfq_content($id){
		$srfqcontent = $this->db->query("SELECT r.srfq_id, r.srfq_no, date_format( r.srfq_date, '%d-%m-%Y' ) AS srfq_date, date_format( r.srfq_printDate, '%d-%m-%Y' ) AS srfq_date_print, 
										pd.num_supplier, pd.qty, pd.um_id, pd.pro_id, pd.sr_id, pro.pro_code, pro.pro_name, s.satuan_name, pro.cat_id, u.usr_name, c.cat_name
										FROM prc_sr_detail AS pd
										INNER JOIN prc_rfq_service AS r ON pd.srfq_id = r.srfq_id
										INNER JOIN prc_sr AS p ON pd.sr_id = p.sr_id
										INNER JOIN prc_master_product AS pro ON pd.pro_id = pro.pro_id
										INNER JOIN prc_master_category AS c ON pro.cat_id = c.cat_id
										INNER JOIN prc_master_satuan AS s ON pd.um_id = s.satuan_id
										INNER JOIN prc_sys_user AS u ON r.srfq_printUsr = u.usr_id
										WHERE pd.srfq_id = '$id'
										AND (
										pd.srfq_stat =0
										OR pd.srfq_stat =2
										)");
		return $srfqcontent;
	}
	
	//RFQ FINAL Disetujui
	function srfq_insert_1($rfq, $pr, $pro, $status, $sup, $qty, $sat, $deldate, $harga, $pay, $proname, $kurs, $disc, $cur){
		$date = date('Y-m-d', strtotime($deldate));
		$dataupd1 = array(
					'qty' => $qty,
					'um_id' => $sat,
					'sup_id' => $sup,
					'cur_id' => $cur,
					'price' => $harga,
					'term' => $pay,
					'srfq_delivery_date' => $date,
					//'discount' => $disc,
					//'kurs' => $kurs,
					'srfq_stat' => $status
				);
		$datawhr1 = array('sr_id' => $pr, 'pro_id' => $pro, 'srfq_id' => $rfq);
		$this->db->where($datawhr1);
		$this->db->update('prc_sr_detail', $dataupd1);
		return "<b><font color='red'>".$proname."</font></b> disetujui";
	}
	
	//RFQ FINAL Ditunda dan Ditolak
	function srfq_insert_2_3($rfq, $pr, $pro, $status, $proname){
		$dataupd2 = array(
					'srfq_stat' => $status,
				);
		$datawhr2 = array('sr_id' => $pr, 'pro_id' => $pro, 'srfq_id' => $rfq);
		$this->db->where($datawhr2);
		$this->db->update('prc_sr_detail', $dataupd2);

		if ($status == 2){
			return "<b><font color='red'>".$proname."</font></b> ditunda";
		}else{
			return "<b><font color='red'>".$proname."</font></b> ditolak";
		}
	}
	
	// RFQ manajemen disetujui
	function srfq_appr_1($pro, $pr, $po_id, $status, $produk, $procode){
		$data1 = array('so_id' => $po_id, 'srfq_stat' => $status);
		$data2 = array('sr_id' => $pr, 'pro_id' => $pro );
		$this->db->where($data2);
		$this->db->update('prc_sr_detail', $data1);
		return "<font color='red'>".$produk." (".$procode.")</font> DiSetujui";
	}
	
	// RFQ manajemen ditunda dan ditolak
	function srfq_appr_2_3($proid, $pr, $status, $produk, $procode){
		$data1 = array('srfq_stat' => '0', 'requestStat' => $status);
		$data2 = array('sr_id' => $pr, 'pro_id' => $proid );
		$this->db->where($data2);
		$this->db->update('prc_sr_detail', $data1);
		
		if ($status == 2){
			return "<font color='red'>".$produk." (".$procode.") </font>Ditunda";
		}else{
			return "<font color='red'>".$produk." (".$procode.") </font>Ditolak";
		}
		
	}
	
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
		return $this->db->insert($this->config->item('tbl_rfq_service'),$data);
	}
	
	function update_srfq($where,$data) {
		if (is_array($where)):
			foreach ($where as $key=>$val):
				$this->db->where($key,$val);
			endforeach;
		else:
			$this->db->where('srfq_id',$where);
		endif;
		return $this->db->update($this->config->item('tbl_rfq_service'),$data);
	}

	// SR KE RFQ
	function get_sr_rfq_service() {
		return $this->db->query("SELECT p.sr_no,d.sr_id, d.pro_id, pro.cat_id, pro.pro_code, sat.satuan_name, sat.satuan_format, date_format(p.sr_date,'%d-%m-%Y') as sr_date, d.qty, pro.pro_name,
		   d.service_cat, d.service_type
		   FROM `prc_sr_detail` AS d 
		   inner join prc_sr as p on p.sr_id = d.sr_id
		   inner join prc_master_product as pro on d.pro_id = pro.pro_id 
		   inner join prc_master_satuan as sat on d.um_id = sat.satuan_id 
		   where (d.requestStat='1' or d.requestStat='2' or d.requestStat='3') and d.srfq_id='0' order by p.sr_no");
	}
	
}
?>