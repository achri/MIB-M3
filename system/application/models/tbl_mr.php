<?php
class Tbl_mr extends Model {
	
	function Tbl_mr(){
		parent::Model();
		$this->CI =& get_instance();
	}
	
	function mr_list(){
		$select1 = "SELECT "; 
		$select2 = "dep.dep_name, m.mr_no, m.mr_id, date_format( m.mr_date, '%d-%m-%Y' ) AS mr_date, 
		date_format( m.mr_lastModified, '%d-%m-%Y' ) AS mr_lastModified, (dayofyear( now( ) ) - dayofyear( m.mr_lastModified )
		) AS tgl_selisih, 
		(SELECT count( pro_id )
			FROM prc_mr_detail AS d
			WHERE d.mr_id = m.mr_id
			AND (d.requestStat =0)) AS mr_waiting, 
		(SELECT count( pro_id )
			FROM prc_mr_detail AS d
			WHERE d.mr_id = m.mr_id
			AND (d.requestStat =4)) AS mr_pending, 
		(SELECT count( pro_id )
			FROM prc_mr_detail AS d
			WHERE d.mr_id = m.mr_id
			AND (d.requestStat =1)
			) AS mr_ok, 
		(SELECT count( pro_id )
			FROM prc_mr_detail AS d
			WHERE d.mr_id = m.mr_id
			AND d.requestStat =5
			) AS mr_reject,
			(
				SELECT count( pro_id ) 
				FROM prc_mr_detail AS d
				WHERE d.mr_id = m.mr_id and (d.requestStat=2)
				) AS mr_ubah,
				(
				SELECT count( pro_id ) 
				FROM prc_mr_detail AS d
				WHERE d.mr_id = m.mr_id and (d.requestStat=3)
				) AS mr_catatan
		FROM `prc_mr` AS m
		INNER JOIN prc_sys_user AS u ON u.usr_id = m.mr_requestor
		INNER JOIN prc_master_departemen AS dep ON u.dep_id = dep.dep_id
		WHERE (SELECT count( requestStat ) 
				FROM prc_mr_detail AS d
				WHERE (d.requestStat=0 or d.requestStat=4) and d.mr_id = m.mr_id
				) > 0 AND mr_status = '1' {SEARCH_STR}";
		
		$querys['main_query'] = $select1.$select2;
		$querys['count_query'] = $select1."count(m.mr_id) as record_count, ".$select2;
		
		$build_querys = $this->CI->flexigrid->build_querys($querys,FALSE); 
		//Get contents
		$return['records'] = $this->db->query($build_querys['main_query']);
		//Get record count
		$get_record_count = $this->db->query($build_querys['count_query']);
		$row = $get_record_count->row();
		$return['record_count'] = $row->record_count;
		
		return $return;
	}
	
	function mr_content($id){
		$return['head'] = $this->db->query("select u.usr_name, d.dep_name, m.mr_id, m.mr_no, date_format(m.mr_date,'%d-%m-%Y') as mr_date from prc_mr as m
						         inner join prc_sys_user as u on u.usr_id = m.mr_requestor
								 inner join prc_master_departemen as d on u.dep_id = d.dep_id
						         where m.mr_id='$id'");
		
		$return['detail'] = $this->db->query("select d.mr_id, d.sup_id, d.pro_id, d.qty, d.description, d.requestStat,
             date_format(d.delivery_date,'%d-%m-%Y') as delivery_date,
             pro.pro_code, pro.pro_name, sat.satuan_name, sat.satuan_id, sat.satuan_format, s.sup_name from prc_mr_detail as d 
             inner join prc_mr as m on d.mr_id = m.mr_id
			 inner join prc_master_product as pro on d.pro_id = pro.pro_id 
			 inner join prc_master_satuan as sat on d.um_id = sat.satuan_id
			 left join prc_master_supplier as s on d.sup_id = s.sup_id
			 where m.mr_id='$id' and (d.requestStat=0 or d.requestStat=4)");
		return $return;
	}
	
	function mr_insert_1($mr, $pro, $status, $defqty, $defsat, $defdeldate, $usrid, $pro_name, $desc, $grl_id, $procode){
		$date2 = date('Y-m-d', strtotime($defdeldate));
		$dataupd1 = array(
					'requestStat' => $status,
					'grl_id' => $grl_id
				);
		$datawhr1 = array('mr_id' => $mr, 'pro_id' => $pro);
		$this->db->where($datawhr1);
		$this->db->update('prc_mr_detail', $dataupd1);
		
		//============================= lastmodified =========
		$data = array(
					   'mr_lastModified' => date('Y-m-d  h-m-s')
					); 
		$this->db->where('mr_id', $mr);
		$this->db->update('prc_mr', $data);
		//=====================================================
		
		$datains1 = array(
					'mr_id' => $mr,
					'pro_id' => $pro,
					'qty' => $defqty,
					'um_id' => $defsat,
					'delivery_date' => $date2,
					'requestStat' => $status,
					'description' => $desc,
					'mr_usr' => $usrid,
					'grl_id' => $grl_id
				);
		$this->db->insert('prc_mr_detail_history', $datains1);
		return "<b><font color='red'>".$pro_name." (".$procode.")</font></b> disetujui";
	}
	
	function mr_insert_2($mr, $pro, $status, $note, $qty, $sat, 
		$deldate, $defqty, $defsat, $defdeldate, $usrid, $pro_name, $desc, $grl_id, $procode){
		
		$date1 = date('Y-m-d', strtotime($deldate));
		$dataupd2 = array(
					'qty' => $qty,
					'um_id' => $sat,
					'delivery_date' => $date1,
					'requestStat' => $status,
					'grl_id' => $grl_id
				);
				$datawhr2 = array('mr_id' => $mr, 'pro_id' => $pro);
				$this->db->where($datawhr2);
				$this->db->update('prc_mr_detail', $dataupd2);
				
		//============================= lastmodified =========
		$data = array(
					   'mr_lastModified' => date('Y-m-d  h-m-s')
					); 
		$this->db->where('mr_id', $mr);
		$this->db->update('prc_mr', $data);
		//=====================================================
				
		$datains2 = array(
					'mr_id' => $mr,
					'pro_id' => $pro,
					'qty' => $qty,
					'um_id' => $sat,
					'delivery_date' => $date1,
					'description' => $desc,
					'requestStat' => $status,
					'mr_usr_note' => $note,
					'mr_usr' => $usrid,
					'grl_id' => $grl_id
				);
		$this->db->insert('prc_mr_detail_history', $datains2);
		return "<b><font color='red'>".$pro_name." (".$procode.")</font></b> diubah dan disetujui";
	}
	
	function mr_insert_3_4($mr, $pro, $status, $note, $defqty, $defsat, $defdeldate, $usrid, $pro_name, $desc, $grl_id, $procode){
		$date2 = date('Y-m-d', strtotime($defdeldate));
		$dataupd3 = array(
					'requestStat' => $status,
					'grl_id' => $grl_id
				);
		$datawhr3 = array('mr_id' => $mr, 'pro_id' => $pro);
		$this->db->where($datawhr3);
		$this->db->update('prc_mr_detail', $dataupd3);
		
		//============================= lastmodified =========
		$data = array(
					   'mr_lastModified' => date('Y-m-d  h-m-s')
					); 
		$this->db->where('mr_id', $mr);
		$this->db->update('prc_mr', $data);
		//=====================================================
		
		$datains3 = array(
					'mr_id' => $mr,
					'pro_id' => $pro,
					'qty' => $defqty,
					'um_id' => $defsat,
					'delivery_date' => $date2,
					'description' => $desc,
					'requestStat' => $status,
					'mr_usr' => $usrid,
					'mr_usr_note' => $note,
					'grl_id' => $grl_id
				);
		$this->db->insert('prc_mr_detail_history', $datains3);
		if ($status == 3){
			return "<b><font color='red'>".$pro_name." (".$procode.")</font></b> disetujui dengan catatan";
		}else{
			return "<b><font color='red'>".$pro_name." (".$procode.")</font></b> ditunda";
		}
	}
	
	function mr_insert_5($mr, $pro, $status, $note, $defqty, $defsat, $defdeldate, $usrid, $pro_name, $desc, $grl_id, $procode){		
		$date2 = date('Y-m-d', strtotime($defdeldate));
		$dataupd4 = array(
					'requestStat' => $status
				);
		$datawhr4 = array('mr_id' => $mr, 'pro_id' => $pro);
		$this->db->where($datawhr4);
		$this->db->update('prc_mr_detail', $dataupd4);
		
		//============================= lastmodified =========
		$data = array(
					   'mr_lastModified' => date('Y-m-d  h-m-s')
					); 
		$this->db->where('mr_id', $mr);
		$this->db->update('prc_mr', $data);
		//=====================================================
		
		$datains4 = array(
					'mr_id' => $mr,
					'pro_id' => $pro,
					'qty' => $defqty,
					'um_id' => $defsat,
					'delivery_date' => $date2,
					'description' => $desc,
					'requestStat' => $status,
					'mr_usr_note' => $note,
					'mr_usr' => $usrid
				);
		$this->db->insert('prc_mr_detail_history', $datains4);
		return "<b><font color='red'>".$pro_name." (".$procode.")</font></b> ditolak";
	}
	
	function add_grl_dtl($grl, $mr, $proid, $qty, $desc){
		$data1 = array(
			'grl_realisasi' => $qty,
			'grl_description' => $desc,
		);
		$data2 = array(
			'grl_id' => $grl,
			'mr_id' => $mr,
			'pro_id' => $proid
		);
		$this->db->where($data2);
		$this->db->update('prc_mr_detail',$data1);
	}
	
	function update_grl_history($grl, $mr, $proid, $sup, $sat, $qty, $real, $alasan, $usrid){
		$data = array(
			'grl_id' => $grl,
			'mr_id' => $mr,
			'pro_id' => $proid,
			'grl_realisasi' => $qty,
			'grl_description' => $alasan,
			'mr_usr' => $usrid,
			'qty' => $real,
			'sup_id' => $sup,
			'um_id' => $sat
		);
		$this->db->insert('prc_mr_detail_history',$data);
	}
	
	function update_pemakaian($mrid, $grlid, $proid, $close, $use, $ket){
		$data1 = array(
			'qty_use' => $use,
			'note' => $ket,
			'is_closed' => $close
		);
		$data2 = array(
			'grl_id' => $grlid,
			'mr_id' => $mrid,
			'pro_id' => $proid
		);
		$this->db->where($data2);
		$this->db->update('prc_mr_detail',$data1);
	}
	
	function update_pemakaian_history($mrid, $grlid, $proid, $close, $jml, $ket, $tgl){
		$date = date('Y-m-d', strtotime($tgl));
		$data = array(
			'grl_id' => $grlid,
			'mr_id' => $mrid,
			'pro_id' => $proid,
			'qty_use' => $jml,
			'note' => $ket,
			'date_use' => $date,
			'is_closed' => $close
			
		);
		$this->db->insert('prc_mr_detail_history',$data);
	}
	
	function get_history_pemakaian($mrid, $grlid, $proid){
		return $this->db->query("SELECT qty_use, note, date_format(date_use,'%d-%m-%Y') as date FROM prc_mr_detail_history
				WHERE mr_id = '$mrid'
				AND pro_id = '$proid'
				AND grl_id = '$grlid'
				AND qty_use <> 0");
	}
	
	function update_closed($mrid, $grlid, $proid) {
		$data1 = array('is_closed'=> 1
					 );
		$data2 = array('mr_id'=> $mrid,
					   'grl_id'=> $grlid,
					   'pro_id'=> $proid
					 );
		$this->db->where($data2);
		$this->db->update('prc_mr_detail', $data1);
	}
	
	// BY AHRIE
	
	function get_mr($where) {
		if(is_array($where)):
			foreach($where as $key=>$val):
				$this->db->where($key,$val);
			endforeach;
		endif;
		return $this->db->get($this->config->item('tbl_mr'));
	}
	
	function get_mr_detail($where) {
		if(is_array($where)):
			foreach($where as $key=>$val):
				$this->db->where($key,$val);
			endforeach;
		endif;
		return $this->db->get($this->config->item('tbl_mr_det'));
	}
	
	function insert_mr($data) {
		return $this->db->insert($this->config->item('tbl_mr'),$data);
	}
	
	function insert_mr_detail($data) {
		return $this->db->insert($this->config->item('tbl_mr_det'),$data);
	}
	
	function insert_mr_history($data) {
		return $this->db->insert($this->config->item('tbl_mr_det_his'),$data);
	}
	
	function update_mr($where,$data) {
		if(is_array($where)):
			foreach($where as $key=>$val):
				$this->db->where($key,$val);
			endforeach;
		endif;
		return $this->db->update($this->config->item('tbl_mr'),$data);
	}
	
	function update_mr_detail($where,$data) {
		if(is_array($where)):
			foreach($where as $key=>$val):
				$this->db->where($key,$val);
			endforeach;
		endif;
		return $this->db->update($this->config->item('tbl_mr_det'),$data);
	}
	
	function get_mr_category($where,$like=false){
		if(is_array($where)):
			foreach ($where as $key=>$val):
				if ($like):
					$this->db->like($key,$val.'%');
				else:
					$this->db->where($key,$val);
				endif;
			endforeach;	
		endif;
		$this->db->order_by('mct_id');
		return $this->db->get($this->config->item('tbl_mr_cat'));
	}
	
	// END
}
?>