<?php
class Tbl_pr extends Model {
	
	function Tbl_pr(){
		parent::Model();
		$this->CI =& get_instance();
	}
	
	function pr_list(){
		//$this->CI->flexigrid->build_query(TRUE);
		$select1 = "SELECT ";
		$select2 = "dep.dep_name, p.pr_no,p.pr_id,date_format(p.pr_date,'%d-%m-%Y') as pr_date, 
           date_format(p.pr_lastModified,'%d-%m-%Y') as pr_lastModified,
		   DATEDIFF(now(), p.pr_lastModified) as pr_due,
				(
				SELECT count( pro_id ) 
				FROM prc_pr_detail AS d
				WHERE d.pr_id = p.pr_id and (d.requestStat=0)
				) AS pr_waiting, (
				SELECT count( pro_id ) 
				FROM prc_pr_detail AS d
				WHERE d.pr_id = p.pr_id and (d.requestStat=4)
				) AS pr_pending, (
				SELECT count( pro_id ) 
				FROM prc_pr_detail AS d
				WHERE d.pr_id = p.pr_id and (d.requestStat=1)
				) AS pr_ok, (
				SELECT count( pro_id ) 
				FROM prc_pr_detail AS d
				WHERE d.pr_id = p.pr_id and (d.requestStat=5)
				) AS pr_reject, (
				SELECT count( pro_id ) 
				FROM prc_pr_detail AS d
				WHERE d.pr_id = p.pr_id and (d.emergencyStat=1)
				) AS pr_emergency,
				(
				SELECT count( pro_id ) 
				FROM prc_pr_detail AS d
				WHERE d.pr_id = p.pr_id and (d.requestStat=2)
				) AS pr_ubah,
				(
				SELECT count( pro_id ) 
				FROM prc_pr_detail AS d
				WHERE d.pr_id = p.pr_id and (d.requestStat=3)
				) AS pr_catatan
		   FROM `prc_pr` AS p 
		   inner join prc_sys_user as u on u.usr_id = p.pr_requestor
		   inner join prc_master_departemen as dep on u.dep_id = dep.dep_id
		   WHERE (SELECT count( requestStat ) 
				FROM prc_pr_detail AS d
				WHERE (d.requestStat=0 or d.requestStat=4) and d.pr_id = p.pr_id
				) > 0 AND pr_status = '1' {SEARCH_STR}"; 
		
		$querys['main_query'] = $select1.$select2;//"SELECT * FROM prc_inventory inner join prc_ {SEARCH_STR}";
		$querys['count_query'] = $select1."count(p.pr_no) as record_count, ".$select2;//"SELECT count(inv_id) as record_count FROM prc_inventory {SEARCH_STR}";
		
		$build_querys = $this->CI->flexigrid->build_querys($querys,FALSE); 
		//Get contents
		$return['records'] = $this->db->query($build_querys['main_query']);
		//Get record count
		$get_record_count = $this->db->query($build_querys['count_query']);
		$row = $get_record_count->row();
		$return['record_count'] = $row->record_count;
		
		//$return['records'] = $select;
		
		//$return['record_count'] = $select->num_rows();
		
		return $return;
	}
	
	function pr_content($id){
		$return['head'] = $this->db->query("SELECT p.pr_id, p.pr_no, date_format(p.pr_date,'%d-%m-%Y') as prdate, p.plan_id, u.usr_name, dep.dep_name
									FROM prc_pr AS p
									INNER JOIN prc_sys_user AS u ON u.usr_id = p.pr_requestor
									INNER JOIN prc_master_departemen AS dep ON dep.dep_id = u.dep_id
									WHERE p.pr_id ='$id'");
		
		$return['detail'] = $this->db->query("select d.pr_id, d.pro_id, d.buy_via, d.pty_id, d.qty, d.um_id, d.description, date_format(d.delivery_date,'%d-%m-%Y') as deldate, 
									u.satuan_id, u.satuan_name, u.satuan_format, pro.pro_code, pro.pro_name, pty.pty_name, d.emergencyStat
									FROM prc_pr_detail AS d
									INNER JOIN prc_master_satuan AS u ON u.satuan_id = d.um_id
									INNER JOIN prc_master_product AS pro ON pro.pro_id = d.pro_id
									INNER JOIN prc_master_purchase_type AS pty ON pty.pty_id = d.pty_id
									WHERE d.pr_id ='$id' and (d.requestStat=0 or d.requestStat=4)
									order by d.pr_reqTime
									");
		return $return;
	}
	
	function pr_insert_1($pr, $pro, $status, $buy, $sup, $defpty, $defqty, $defsat, $defdeldate, $usrid, $pro_name, $pcv_id, $pro_code){
		$date2 = date('Y-m-d', strtotime($defdeldate));
		$dataupd1 = array(
					'num_supplier' => $sup,
					'buy_via' => $buy,
					'requestStat' => $status,
					'pcv_id' => $pcv_id
				);
		$datawhr1 = array('pr_id' => $pr, 'pro_id' => $pro);
		$this->db->where($datawhr1);
		$this->db->update('prc_pr_detail', $dataupd1);
		
		//============================= lastmodified =========
		$data = array(
					   'pr_lastModified' => date('Y-m-d  h-m-s')
					); 
		$this->db->where('pr_id', $pr);
		$this->db->update('prc_pr', $data);
		//=====================================================	
		
		
		$datains1 = array(
					'pr_id' => $pr,
					'pro_id' => $pro,
					'buy_via' => $buy,
					'pty_id' => $defpty,
					'qty' => $defqty,
					'um_id' => $defsat,
					'delivery_date' => $date2,
					'num_supplier' => $sup,
					'requestStat' => $status,
					'pr_appr_user' => $usrid,
					'pcv_id' => $pcv_id
				);
		$this->db->insert('prc_pr_detail_history', $datains1);
		return "<b><font color='red'>".$pro_name." (".$pro_code.")</font> </b> disetujui";
	}
	
	function pr_insert_2($pr, $pro, $status, $buy, $note, $type, $qty, $sat, 
		$deldate, $emergency, $sup, $defpty, $defqty, $defsat, $defdeldate, $usrid, $pro_name, $pcv_id, $pro_code){
		
		$date1 = date('Y-m-d', strtotime($deldate));
		$dataupd2 = array(
					'buy_via' => $buy,
					'pty_id' => $type,
					'emergencyStat' => $emergency,
					'qty' => $qty,
					'um_id' => $sat,
					'delivery_date' => $date1,
					'num_supplier' => $sup,
					'requestStat' => $status,
					'pr_appr_note' => $note,
					'pcv_id' => $pcv_id
				);
				$datawhr2 = array('pr_id' => $pr, 'pro_id' => $pro);
				$this->db->where($datawhr2);
				$this->db->update('prc_pr_detail', $dataupd2);

		//============================= lastmodified =========
		$data = array(
					   'pr_lastModified' => date('Y-m-d  h-m-s')
					); 
		$this->db->where('pr_id', $pr);
		$this->db->update('prc_pr', $data);
		//=====================================================			
				
		$datains2 = array(
					'pr_id' => $pr,
					'pro_id' => $pro,
					'buy_via' => $buy,
					'pty_id' => $type,
					'emergencyStat' => $emergency,
					'qty' => $qty,
					'um_id' => $sat,
					'delivery_date' => $date1,
					'num_supplier' => $sup,
					'requestStat' => $status,
					'pr_appr_note' => $note,
					'pr_appr_user' => $usrid,
					'pcv_id' => $pcv_id
				);
		$this->db->insert('prc_pr_detail_history', $datains2);
		return "<b><font color='red'>".$pro_name." (".$pro_code.")</font></b> diubah dan disetujui";
	}
	
	function pr_insert_3_4($pr, $pro, $status, $buy, $note, $sup, $defpty, $defqty, $defsat, $defdeldate, $usrid, $pro_name, $pcv_id, $pro_code){
		$date2 = date('Y-m-d', strtotime($defdeldate));
		$dataupd3 = array(
					'num_supplier' => $sup,
					'buy_via' => $buy,
					'requestStat' => $status,
					'pr_appr_note' => $note,
					'pcv_id' => $pcv_id
				);
		$datawhr3 = array('pr_id' => $pr, 'pro_id' => $pro);
		$this->db->where($datawhr3);
		$this->db->update('prc_pr_detail', $dataupd3);
		
		//============================= lastmodified =========
		$data = array(
					   'pr_lastModified' => date('Y-m-d  h-m-s')
					); 
		$this->db->where('pr_id', $pr);
		$this->db->update('prc_pr', $data);
		//=====================================================	
		
		$datains3 = array(
					'pr_id' => $pr,
					'pro_id' => $pro,
					'buy_via' => $buy,
					'pty_id' => $defpty,
					'qty' => $defqty,
					'um_id' => $defsat,
					'delivery_date' => $date2,
					'num_supplier' => $sup,
					'requestStat' => $status,
					'pr_appr_user' => $usrid,
					'pr_appr_note' => $note,
					'pcv_id' => $pcv_id
				);
		$this->db->insert('prc_pr_detail_history', $datains3);
		if ($status == 3){
			return "<b><font color='red'>".$pro_name." (".$pro_code.")</font></b> disetujui dengan catatan";
		}else{
			return "<b><font color='red'>".$pro_name." (".$pro_code.")</font></b> ditunda";
		}
	}
	
	function pr_insert_5($pr, $pro, $status, $buy, $sup, $defpty, $defqty, $defsat, $defdeldate, $usrid, $pro_name, $pro_code, $note=''){		
		$date2 = date('Y-m-d', strtotime($defdeldate));
		$dataupd4 = array(
					'requestStat' => $status,
					'pr_appr_note' => $note
				);
		$datawhr4 = array('pr_id' => $pr, 'pro_id' => $pro);
		$this->db->where($datawhr4);
		$this->db->update('prc_pr_detail', $dataupd4);
		
		//============================= lastmodified =========
		$data = array(
					   'pr_lastModified' => date('Y-m-d  h-m-s')
					); 
		$this->db->where('pr_id', $pr);
		$this->db->update('prc_pr', $data);
		//=====================================================	
		
		$datains4 = array(
					'pr_id' => $pr,
					'pro_id' => $pro,
					'buy_via' => $buy,
					'pty_id' => $defpty,
					'qty' => $defqty,
					'um_id' => $defsat,
					'delivery_date' => $date2,
					'num_supplier' => $sup,
					'requestStat' => $status,
					'pr_appr_note' => $note,
					'pr_appr_user' => $usrid
				);
		$this->db->insert('prc_pr_detail_history', $datains4);
		return "<b><font color='red'>".$pro_name." (".$pro_code.")</font></b> ditolak";
	}
	
	function update_harga($pcv, $pr, $pro, $harga, $cur){		
		$dataupd = array(
					'price_pre' => $harga,
					'cur_id' => $cur
				);
		$datawhr = array('pcv_id' => $pcv, 'pr_id' => $pr, 'pro_id' => $pro);
		$this->db->where($datawhr);
		$this->db->update('prc_pr_detail', $dataupd);
	}
	
	function update_pcvstat($pcvid, $proid, $stat, $note){		
		$dataupd = array(
					'pcv_stat' => $stat,
					'pcv_note' => $note
				);
		$datawhr = array('pcv_id' => $pcvid, 'pro_id' => $proid);
		$this->db->where($datawhr);
		$this->db->update('prc_pr_detail', $dataupd);
	}
	
	function get_detail($pcvid, $proid){
		$data = array(
					'pcv_id'=>$pcvid,
					'pro_id'=>$proid
				);
		$this->db->where($data);
		return $this->db->get('prc_pr_detail');
	}
	
	function update_pcvstat_history($pro_id, $pr_id, $buy_via, $pty_id, $proj_no, $emergencyStat, 
				$qty, $qty_terima, $um_id, $delivery_date, $description, $num_supplier, $sup_id,
				$cur_id, $price, $term, $rfq_delivery_date, $rfq_id, $requestStat, 
				$rfq_stat, $pcv_id, $pcv_stat, $pcv_note){
		$data = array(
				'pro_id'=> $pro_id,	
				'pr_id'=> $pr_id,
				'buy_via'=> $buy_via,	
				'pty_id'=> $pty_id,	
				'proj_no'=> $proj_no,	
				'emergencyStat'=> $emergencyStat,
				'qty'=> $qty,
				'qty_remain'=> $qty_terima,
				'um_id'=> $um_id,
				'delivery_date'=> $delivery_date,
				'description'=> $description,
				'num_supplier'=> $num_supplier,
				'sup_id'=> $sup_id,
				'cur_id'=> $cur_id,
				'price'=> $price,
				'term'=> $term,
				'rfq_delivery_date'=> $rfq_delivery_date,
				'rfq_id'=> $rfq_id,
				'requestStat'=> $requestStat,
				'rfq_stat'=> $rfq_stat,
				'pcv_id'=> $pcv_id,
				'pcv_stat'=> $pcv_stat, 
				'pcv_note' => $pcv_note
			);
		$this->db->insert('prc_pr_detail_history', $data); 
	}
	
	function get_pr($where) {
		if(is_array($where)):
			foreach($where as $key=>$val):
				$this->db->where($key,$val);
			endforeach;
		endif;
		return $this->db->get($this->config->item('tbl_pr'));
	}
	
	function get_pr_detail($where) {
		if(is_array($where)):
			foreach($where as $key=>$val):
				$this->db->where($key,$val);
			endforeach;
		endif;
		return $this->db->get($this->config->item('tbl_pr_det'));
	}
	
	function insert_pr($data) {
		return $this->db->insert($this->config->item('tbl_pr'),$data);
	}
	
	function insert_pr_detail($data) {
		return $this->db->insert($this->config->item('tbl_pr_det'),$data);
	}
	
	function insert_pr_history($data) {
		return $this->db->insert($this->config->item('tbl_pr_det_his'),$data);
	}
	
	function update_pr($where,$data) {
		if(is_array($where)):
			foreach($where as $key=>$val):
				$this->db->where($key,$val);
			endforeach;
		endif;
		return $this->db->update($this->config->item('tbl_pr'),$data);
	}
	
	function update_pr_detail($where,$data) {
		if(is_array($where)):
			foreach($where as $key=>$val):
				$this->db->where($key,$val);
			endforeach;
		endif;
		return $this->db->update($this->config->item('tbl_pr_det'),$data);
	}

	function get_prmr($where,$stat) {
		$stat = strtolower($stat);
		if(is_array($where)):
			foreach($where as $key => $val):
				$this->db->where($key,$val);
			endforeach;
		else:
			$this->db->where($stat.'_id',$where);
		endif;
		
		return $this->db->get($this->config->item('tbl_'.$stat));
	}

	function get_prmr_det($where,$stat) {
		$stat = strtolower($stat);
		if(is_array($where)):
			foreach($where as $key => $val):
				$this->db->where($key,$val);
			endforeach;
		else:
			$this->db->where($stat.'_id',$where);
		endif;
		
		return $this->db->get($this->config->item('tbl_'.$stat.'_det'));
	}
	
	function delete_prmr_det($where,$stat) {
		$stat = strtolower($stat);
		if(is_array($where)):
			foreach($where as $key => $val):
				$this->db->where($key,$val);
			endforeach;
		else:
			$this->db->where($stat.'_id',$where);
		endif;
		
		return $this->db->delete($this->config->item('tbl_'.$stat.'_det'));
	}

	function delete_prmr($where,$stat) {
		$stat = strtolower($stat);
		if(is_array($where)):
			foreach($where as $key => $val):
				$this->db->where($key,$val);
			endforeach;
		else:
			$this->db->where($stat.'_id',$where);
		endif;
		
		return $this->db->delete($this->config->item('tbl_'.$stat));
	}
	
	function pr_history($proid){
		return $this->db->query("SELECT pr.po_id, cur.cur_symbol,cur.cur_digit, pr.price, sat.satuan_name, sat.satuan_format, pr.qty, pr.qty_terima, pr.qty_retur, (
			pr.qty - pr.qty_terima + pr.qty_retur
			) AS sisa, DATE_FORMAT(po.po_date,'%d-%m-%Y') as date, po.po_no, sup.sup_name, leg.legal_name
			FROM `prc_pr_detail` AS pr
			INNER JOIN prc_po AS po ON pr.po_id = po.po_id 
			INNER JOIN prc_master_satuan AS sat ON pr.um_id = sat.satuan_id 
			INNER JOIN prc_master_supplier AS sup ON po.sup_id = sup.sup_id 
			INNER JOIN prc_master_currency AS cur ON pr.cur_id = cur.cur_id 
			INNER JOIN prc_master_legality AS leg ON sup.legal_id = leg.legal_id 
			WHERE pr.pro_id = $proid ");
			//AND pr.po_id <> ''");
		/*
		return $this->db->query("select date_format(p.po_date,'%d-%m-%Y') as po_date, p.po_no, d.qty, s.sup_name, (
		SELECT sum(qty) 
		FROM prc_gr_detail AS r 
		 inner join prc_gr as g on r.gr_id = g.gr_id
		WHERE r.pro_id = d.pro_id and g.po_id = d.po_id
		) AS terima, (
		SELECT (d.qty - sum(qty)) 
		FROM prc_gr_detail AS r 
		 inner join prc_gr as g on r.gr_id = g.gr_id
		WHERE r.pro_id = d.pro_id and g.po_id = d.po_id
		) AS sisa    
		from prc_pr_detail as d
        inner join prc_po as p on d.po_id = p.po_id
		inner join prc_master_supplier as s on p.sup_id = s.sup_id
		where d.pro_id = '$proid' order by d.po_id desc limit 0,7");
		*/
	}
	
	function get_datalist_pr(){
		return $this->db->query("SELECT p.pr_no, p.pr_id, p.pr_requestor, u.dep_id, d.dep_name, date_format( p.pr_date, '%d-%m-%Y' ) AS pr_date, date_format( p.pr_lastModified, '%d-%m-%Y' ) AS pr_lastModified, (
				dayofmonth( now( ) ) - dayofmonth( p.pr_lastModified )
				) AS tgl_selisih, (
				
				SELECT count( pro_id )
				FROM prc_pr_detail AS d
				WHERE d.pr_id = p.pr_id
				AND (
				d.requestStat =2
				OR d.requestStat =0
				)
				) AS pr_pending, (
				
				SELECT count( pro_id )
				FROM prc_pr_detail AS d
				WHERE d.pr_id = p.pr_id
				AND d.requestStat =1
				) AS pr_ok, (
				
				SELECT count( pro_id )
				FROM prc_pr_detail AS d
				WHERE d.pr_id = p.pr_id
				AND d.requestStat =3
				) AS pr_reject
				FROM `prc_pr` AS p
				INNER JOIN prc_sys_user AS u ON p.pr_requestor = u.usr_id
				INNER JOIN prc_master_departemen AS d ON u.dep_id = d.dep_id
				where pr_status > 0
				");
	}
	
	function pr_detail($prid){
		$return['header'] = $this->db->query("SELECT u.usr_name, d.dep_name, p.pr_no, date_format( p.pr_date, '%d-%m-%Y' ) AS pr_date
					FROM prc_pr AS p
					INNER JOIN prc_sys_user AS u ON u.usr_id = p.pr_requestor
					INNER JOIN prc_master_departemen AS d ON u.dep_id = d.dep_id
					WHERE p.pr_id ='$prid'");
		$return['detail'] = $this->db->query("SELECT m.satuan_format, d.pr_id, d.pro_id, d.emergencyStat, d.qty, d.description, d.requestStat, date_format( d.delivery_date, '%d-%m-%Y' ) AS delivery_date, pro.pro_code, pro.pro_name, m.satuan_name, d.pr_appr_note, d.description  
					FROM prc_pr_detail AS d
					INNER JOIN prc_pr AS p ON d.pr_id = p.pr_id
					INNER JOIN prc_master_product AS pro ON d.pro_id = pro.pro_id
					LEFT JOIN prc_master_satuan AS m ON d.um_id = m.satuan_id
					WHERE p.pr_id = '$prid' order by d.pr_reqTime");
		return $return;
	}
	
	//======================= auto pr =============================
	function auto_pr($code){
		$data = array(
					'pr_no' => $code,
					'pr_date' => date('Y-m-d'),
					'planStat' => '1',
					'pr_requestor' => '7',
					'plan_id' => '1',
					'pr_status' => '1'
				);
		$this->db->insert('prc_pr', $data);
		return $this->db->insert_id();
	}
	
	function auto_pr_det($autoid, $maxstok, $proid, $sat){
		$data = array(
					'pr_id' => $autoid,
					'pty_id' => '1',
					'pro_id' => $proid,
					'qty' => $maxstok,
					'um_id' => $sat,
					'delivery_date' => date('Y-m-d')
				);
		$this->db->insert('prc_pr_detail', $data);
	}
}
?>
