<?php
class Tbl_sr extends Model {
	
	function Tbl_sr(){
		parent::Model();
		$this->CI =& get_instance();
	}
	
	function sr_list(){
		$select1 = "SELECT ";
		
		$select2 = "
		dep.dep_name,p.sr_no,p.sr_id,date_format(p.sr_date,'%d-%m-%Y') as sr_date, date_format(p.sr_lastModified,'%d-%m-%Y') as sr_lastModified,
           (dayofmonth(now()) - dayofmonth(p.sr_lastModified)) as tgl_selisih,
				(
				SELECT count( pro_id ) 
				FROM prc_sr_detail AS d
				WHERE d.sr_id = p.sr_id and (d.requestStat=4 or d.requestStat=0)
				) AS sr_pending, (
				SELECT count( pro_id ) 
				FROM prc_sr_detail AS d
				WHERE d.sr_id = p.sr_id and d.requestStat=1
				) AS sr_ok, (
				SELECT count( pro_id ) 
				FROM prc_sr_detail AS d
				WHERE d.sr_id = p.sr_id and d.requestStat=5
				) AS sr_reject
		   FROM `prc_sr` AS p 
		   inner join prc_sys_user as u on u.usr_id = p.sr_requestor
		   inner join prc_master_departemen as dep on u.dep_id = dep.dep_id
		   WHERE (SELECT count( requestStat ) 
				FROM prc_sr_detail AS d
				WHERE (d.requestStat=0 or d.requestStat=4) and d.sr_id = p.sr_id
				) > 0 AND sr_status = '1' {SEARCH_STR}
		";
		$querys['main_query'] = $select1.$select2;//"SELECT * FROM prc_inventory inner join prc_ {SEARCH_STR}";
		$querys['count_query'] = $select1."count(p.sr_no) as record_count, ".$select2;//"SELECT count(inv_id) as record_count FROM prc_inventory {SEARCH_STR}";
		
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
	
	function sr_content($id){
		$return['head'] = $this->db->query("SELECT p.sr_id, p.sr_no, date_format(p.sr_date,'%d-%m-%Y') as srdate, p.plan_id, u.usr_name, dep.dep_name
									FROM prc_sr AS p
									INNER JOIN prc_sys_user AS u ON u.usr_id = p.sr_requestor
									INNER JOIN prc_master_departemen AS dep ON dep.dep_id = u.dep_id
									WHERE p.sr_id ='$id'");
		
		$return['detail'] = $this->db->query("
			select d.sr_id, d.pro_id, d.qty, d.description, d.service_cat, d.service_type,
             pro.pro_code, pro.pro_name, m.satuan_name, m.satuan_id, m.satuan_format 
			 from prc_sr_detail as d 
             inner join prc_sr as p on d.sr_id = p.sr_id
			 inner join prc_master_product as pro on d.pro_id = pro.pro_id 
			 inner join prc_master_satuan as m on d.um_id = m.satuan_id
			 where p.sr_id='$id' and (d.requestStat=0 or d.requestStat=4)
		");
		
		return $return;
	}
	
	
	
	function get_sr($where) {
		if(is_array($where)):
			foreach($where as $key=>$val):
				$this->db->where($key,$val);
			endforeach;
		endif;
		return $this->db->get($this->config->item('tbl_sr'));
	}
	
	function get_sr_detail($where) {
		if(is_array($where)):
			foreach($where as $key=>$val):
				$this->db->where($key,$val);
			endforeach;
		endif;
		return $this->db->get($this->config->item('tbl_sr_det'));
	}
	
	function insert_sr($data) {
		return $this->db->insert($this->config->item('tbl_sr'),$data);
	}
	
	function insert_sr_detail($data) {
		return $this->db->insert($this->config->item('tbl_sr_det'),$data);
	}
	
	function insert_sr_history($data) {
		return $this->db->insert($this->config->item('tbl_sr_det_his'),$data);
	}
	
	function update_sr($where,$data) {
		if(is_array($where)):
			foreach($where as $key=>$val):
				$this->db->where($key,$val);
			endforeach;
		endif;
		return $this->db->update($this->config->item('tbl_sr'),$data);
	}
	
	function update_sr_detail($where,$data) {
		if(is_array($where)):
			foreach($where as $key=>$val):
				$this->db->where($key,$val);
			endforeach;
		endif;
		return $this->db->update($this->config->item('tbl_sr_det'),$data);
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
	
	function sr_history($proid){
		return $this->db->query("SELECT sr.so_id, cur.cur_symbol, sr.price, sat.satuan_name, sr.qty, sr.qty_terima, sr.qty_retur, (
			sr.qty - sr.qty_terima + sr.qty_retur
			) AS sisa, DATE_FORMAT(so.so_date,'%d-%m-%Y') as date, so.so_no, sup.sup_name, leg.legal_name
			FROM `prc_sr_detail` AS sr
			INNER JOIN prc_so AS so ON sr.so_id = so.so_id 
			INNER JOIN prc_master_satuan AS sat ON sr.um_id = sat.satuan_id 
			INNER JOIN prc_master_supplier AS sup ON so.sup_id = sup.sup_id 
			INNER JOIN prc_master_currency AS cur ON sr.cur_id = cur.cur_id 
			INNER JOIN prc_master_legality AS leg ON sup.legal_id = leg.legal_id 
			WHERE sr.pro_id = $proid ");
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
	
	function get_datalist_sr(){
		return $this->db->query("SELECT p.sr_no, p.sr_id, p.sr_requestor, u.dep_id, d.dep_name, date_format( p.sr_date, '%d-%m-%Y' ) AS sr_date, date_format( p.sr_lastModified, '%d-%m-%Y' ) AS sr_lastModified, (
				dayofmonth( now( ) ) - dayofmonth( p.sr_lastModified )
				) AS tgl_selisih, (
				
				SELECT count( pro_id )
				FROM prc_sr_detail AS d
				WHERE d.sr_id = p.sr_id
				AND (
				d.requestStat =2
				OR d.requestStat =0
				)
				) AS sr_pending, (
				
				SELECT count( pro_id )
				FROM prc_sr_detail AS d
				WHERE d.sr_id = p.sr_id
				AND d.requestStat =1
				) AS sr_ok, (
				
				SELECT count( pro_id )
				FROM prc_sr_detail AS d
				WHERE d.sr_id = p.sr_id
				AND d.requestStat =3
				) AS sr_reject
				FROM `prc_sr` AS p
				INNER JOIN prc_sys_user AS u ON p.sr_requestor = u.usr_id
				INNER JOIN prc_master_departemen AS d ON u.dep_id = d.dep_id
				where sr_status > 0
				");
	}
	
	function pr_detail($prid){
		$return['header'] = $this->db->query("SELECT u.usr_name, d.dep_name, p.pr_no, date_format( p.pr_date, '%d-%m-%Y' ) AS pr_date
					FROM prc_pr AS p
					INNER JOIN prc_sys_user AS u ON u.usr_id = p.pr_requestor
					INNER JOIN prc_master_departemen AS d ON u.dep_id = d.dep_id
					WHERE p.pr_id ='$prid'");
		$return['detail'] = $this->db->query("SELECT d.pr_id, d.pro_id, d.emergencyStat, d.qty, d.description, d.requestStat, date_format( d.delivery_date, '%d-%m-%Y' ) AS delivery_date, pro.pro_code, pro.pro_name, m.satuan_name
					FROM prc_pr_detail AS d
					INNER JOIN prc_pr AS p ON d.pr_id = p.pr_id
					INNER JOIN prc_master_product AS pro ON d.pro_id = pro.pro_id
					LEFT JOIN prc_master_satuan AS m ON d.um_id = m.satuan_id
					WHERE p.pr_id = '$prid' ");
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
	
	function sr_update($where,$data) {
		if (is_array($where)):
			foreach($where as $field=>$value):
				$this->db->where($field,$value);
			endforeach;
		endif;
		return $this->db->update('prc_sr',$data);
	}
	
	function sr_update_detail($where,$data) {
		if (is_array($where)):
			foreach($where as $field=>$value):
				$this->db->where($field,$value);
			endforeach;
		endif;
		return $this->db->update('prc_sr_detail',$data);
	}
	
	function sr_insert_history($data) {
		return $this->db->insert('prc_sr_detail_history',$data);
	}
}
?>
