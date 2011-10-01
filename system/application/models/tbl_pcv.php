<?php
class Tbl_pcv extends Model {
	
	function Tbl_pcv(){
		parent::Model();
		$this->CI =& get_instance();
	}
	
	function cek_pcv($pr){
		$data = array(
			'pr_id' => $pr
		);
		$this->db->where($data);
		return $query = $this->db->get('prc_pcv'); 
	}
	
	function insert_pcv ($code, $pr){
		$data = array(
			'pcv_no' => $code,
			'pcv_date' => date('Y-m-d'),
			'pr_id' => $pr
		);
		$this->db->insert('prc_pcv',$data);
		return $id = $this->db->insert_id();	
	}
	
	function get_pcv() {
		$select1 = "SELECT ";
		$select2 = "pc.pcv_id, pc.pcv_no, pr.pr_no, pr.pr_id, date_format( pr.pr_date, '%d-%m-%Y' ) AS pr_date, 
		   (SELECT count( pro_id )
			FROM prc_pr_detail AS pd
			WHERE pc.pcv_id = pd.pcv_id
			) AS jum_item
			FROM prc_pcv AS pc
			INNER JOIN prc_pr AS pr ON pc.pr_id = pr.pr_id
		WHERE pc.pcv_status = '0' {SEARCH_STR}";
		
		$querys['main_query'] = $select1.$select2;
		$querys['count_query'] = $select1."count(pc.pcv_id) as record_count, ".$select2;
		
		$build_querys = $this->CI->flexigrid->build_querys($querys,FALSE); 
		//Get contents
		$return['records'] = $this->db->query($build_querys['main_query']);
		//Get record count
		$get_record_count = $this->db->query($build_querys['count_query']);
		$row = $get_record_count->row();
		$return['record_count'] = $row->record_count;
		
		return $return;
	}
	
	function pcv_content($pcvid, $prid){
		$return['head'] = $this->db->query("SELECT u.usr_name, pc.pcv_no, pc.pcv_id, pr.pr_id, pr.pr_no, date_format( pr.pr_date, '%d-%m-%Y' ) AS pr_date
									FROM prc_pcv AS pc
									INNER JOIN prc_pr AS pr ON pc.pr_id = pr.pr_id
									INNER JOIN prc_sys_user AS u ON u.usr_id = pr.pr_requestor
									WHERE pcv_id ='$pcvid'");
		
		$return['detail'] = $this->db->query("SELECT pr.qty, pr.pro_id, pro.pro_name, pro.pro_code, um.satuan_name, um.satuan_format 
									FROM prc_pr_detail AS pr
									INNER JOIN prc_master_product AS pro ON pr.pro_id = pro.pro_id
									INNER JOIN prc_master_satuan AS um ON um.satuan_id = pr.um_id
									WHERE pr.pr_id = '$prid'
									AND pcv_id = '$pcvid'");
		
		return $return;
	}
	
	function update_status($pcv, $total){		
		$dataupd = array(
					'pcv_status' => 1,
					'pcv_request' => $total
				);
		$datawhr = array('pcv_id' => $pcv);
		$this->db->where($datawhr);
		$this->db->update('prc_pcv', $dataupd);
	}
	
	function pcv_list(){
		$select1 = "SELECT ";
		$select2 = "p.pcv_no,p.pcv_id,date_format(p.pcv_date,'%d-%m-%Y') as pcv_date_format, 
		   pr.pr_no, (dayofyear(now()) - dayofyear(p.pcv_date)) as tgl_selisih
		   FROM prc_pcv AS p 
		   inner join prc_pr as pr on p.pr_id = pr.pr_id
		   where p.pcv_status=1 {SEARCH_STR}";
		
		$querys['main_query'] = $select1.$select2;
		$querys['count_query'] = $select1."count(p.pcv_id) as record_count, ".$select2;
		
		$build_querys = $this->CI->flexigrid->build_querys($querys,FALSE); 
		//Get contents
		$return['records'] = $this->db->query($build_querys['main_query']);
		//Get record count
		$get_record_count = $this->db->query($build_querys['count_query']);
		$row = $get_record_count->row();
		$return['record_count'] = $row->record_count;
		
		return $return;
	}
	
	function pcv_dtl_content($pcvid){
		$return['head'] = $this->db->query("SELECT p.pcv_id, p.pcv_no, date_format( p.pcv_date, '%d-%m-%Y' ) AS pcv_date, pr.pr_no, pr.pr_id, sum( pd.qty * pd.price_pre ) AS total
			FROM prc_pcv AS p
			INNER JOIN prc_pr AS pr ON p.pr_id = pr.pr_id
			INNER JOIN prc_pr_detail AS pd ON pd.pcv_id = p.pcv_id
			WHERE p.pcv_id ='$pcvid'
			AND p.pcv_status = '1' 
			group by pd.pcv_id"
		);
		
		$return['detail'] = $this->db->query("SELECT d.qty, d.description, d.price_pre, pro.pro_id, pro.pro_code, pro.pro_name, m.satuan_name,m.satuan_format, cur.cur_symbol, cur.cur_digit 
			FROM prc_pr_detail AS d
			INNER JOIN prc_pr AS p ON d.pr_id = p.pr_id
			INNER JOIN prc_master_product AS pro ON d.pro_id = pro.pro_id
			INNER JOIN prc_master_satuan AS m ON d.um_id = m.satuan_id
			LEFT JOIN prc_master_currency AS cur ON cur.cur_id = d.cur_id
			INNER JOIN prc_pcv AS pcv ON pcv.pcv_id = d.pcv_id
			WHERE pcv.pcv_id ='$pcvid'
			AND pcv.pcv_status ='1'"
		);
		
		return $return;
	}
	
	function get_total($pcv){
		return $this->db->query("select sum(qty*price_pre) as total from prc_pr_detail 
		where pcv_id='$pcv' and pcv_stat='1'
		group by pcv_id");
	}
	
	function update_pcv($pcv, $total, $stat){
		$dataupd = array(
					'pcv_request'=> $total,
					'pcv_status'=> $stat
				);
		$datawhr = array('pcv_id' => $pcv);
		$this->db->where($datawhr);
		$this->db->update('prc_pcv', $dataupd);
	}
	
	function list_pcv_print($print_status) {
		$data = array( 'pcv_status' => '2', 
					   'pcv_printStat' => $print_status);
		
		$this->db->select('prc_pcv.pcv_no, prc_pcv.pcv_id, prc_pr.pr_no, prc_sys_user.usr_name');
		$this->db->from('prc_pcv');
		$this->db->join('prc_pr', 'prc_pr.pr_id = prc_pcv.pr_id');
		$this->db->join('prc_sys_user', 'prc_pr.pr_requestor = prc_sys_user.usr_id');
		$this->db->where($data);
		$this->CI->flexigrid->build_query(TRUE);
		$return['records'] = $this->db->get();
		
		$this->db->select('prc_pcv.pcv_no, prc_pcv.pcv_id, prc_pr.pr_no, prc_sys_user.usr_name');
		$this->db->from('prc_pcv');
		$this->db->join('prc_pr', 'prc_pr.pr_id = prc_pcv.pr_id');
		$this->db->join('prc_sys_user', 'prc_pr.pr_requestor = prc_sys_user.usr_id');
		$this->db->where($data);
		$this->CI->flexigrid->build_query(FALSE);
		$return['record_count'] = $this->db->get()->num_rows();
		
		return $return;
	
	}
	
	function get_num_flex($print_status){
		$data = array( 'pcv_status' => '2', 
					   'pcv_printStat' => $print_status);
		$this->db->select('prc_pcv.pcv_no, prc_pcv.pcv_id, prc_pr.pr_no, prc_sys_user.usr_name');
		$this->db->from('prc_pcv');
		$this->db->join('prc_pr', 'prc_pr.pr_id = prc_pcv.pr_id');
		$this->db->join('prc_sys_user', 'prc_pr.pr_requestor = prc_sys_user.usr_id');
		$this->db->where($data);
		$return['record_count'] = $this->db->get()->num_rows();
		
		return $return;
		
	}
	
	function get_pcv_dtlprint ($usrid, $pcvid){
		$return['head1'] = $this->db->query("select u.usr_name, d.dep_name from prc_sys_user as u
								inner join prc_master_departemen as d on u.dep_id = d.dep_id
								where usr_id='$usrid'");
		
		$return['head2'] = $this->db->query("select pcv_id, pcv_no, date_format(pcv_printDate, '%d-%m-%Y' ) AS tgl_print, pcv_printCounter from prc_pcv
		 						where pcv_id='$pcvid' and pcv_status='2'");
		
		$return['detail'] = $this->db->query("SELECT pd.qty, pd.price_pre, pro.pro_code, cur.cur_symbol, cur.cur_digit, pro.pro_name, um.satuan_name, um.satuan_format ,(pd.qty*pd.price_pre) as tot
								FROM prc_pr_detail AS pd
								INNER JOIN prc_master_product AS pro ON pd.pro_id = pro.pro_id
								INNER JOIN prc_master_satuan AS um ON pd.um_id = um.satuan_id
								INNER JOIN prc_master_currency AS cur ON pd.cur_id = cur.cur_id
								WHERE pd.pcv_id = '$pcvid'
								AND pd.pcv_stat = '1'");
		
		$return['total'] = $this->db->query("select sum(qty*price_pre) as total from prc_pr_detail 
								where pcv_id='$pcvid' and pcv_stat='1'
								group by pcv_id");
		return $return;		
	}
	
	function print_pcv($pcv, $count, $tgl, $usr){
		if ($count == '0'){
			$count = '1';
			$tgl = date('Y-m-d');
		}else{
			$count = $count + 1;
			$date = explode ('-', $tgl);
			$tgl = $date[2].'-'.$date[1].'-'.$date[0];	
		}
		
		
		$dataupd = array(
				'pcv_printStat' => '1',
				'pcv_printDate' =>	$tgl,
				'pcv_lastprintDate' => date('Y-m-d'),
				'pcv_printUser'	=> $usr,
				'pcv_printCounter' => $count
				);
		$datawhr = array('pcv_id' => $pcv);
		$this->db->where($datawhr);
		return $this->db->update('prc_pcv', $dataupd);
	}
	
	function list_receive_pcv(){
		return $this->db->query("SELECT pcv_id, pcv_no
				FROM prc_pcv
				WHERE pcv_status = '2'
				AND pcv_printStat = '1'
				ORDER BY pcv_id");
	}
	
	function receive_detail($id){
		$return['head'] = $this->db->query("select pcv_id, pcv_no, date_format(pcv_date, '%d-%m-%Y') as pcv_date 
					from prc_pcv where pcv_id='$id' 
					and pcv_status='2'
					and pcv_printStat='1'");
		
		$return['detail'] = $this->db->query("SELECT pd.qty, pd.pro_id, pro.pro_code, pro.pro_name, pro.cat_id, pro.is_stockJoin, um.satuan_id, um.satuan_name, um.satuan_format 
					FROM prc_pr_detail AS pd
					INNER JOIN prc_master_product AS pro ON pd.pro_id = pro.pro_id
					INNER JOIN prc_master_satuan AS um ON pd.um_id = um.satuan_id
					INNER JOIN prc_pcv AS pc ON pc.pcv_id = pd.pcv_id
					WHERE pd.pcv_id = '$id'
					AND pd.pcv_stat = '1'
					AND pc.pcv_printStat = '1'");
		return $return;
	}
	
	function update_pcvstat($id, $usr){
		$data1 = array(
				'pcv_status' => '5', 
				'pcv_receiveDate' => date('Y-m-d'), 
				'pcv_receiveUser'=> $usr
				);
		$data2 = array('pcv_id' => $id);
		$this->db->where($data2);
		$this->db->update('prc_pcv', $data1);
	}
	
	function get_pcv_realisasi(){
		$select1 = "SELECT ";
		$select2 = "pc.pcv_id, pc.pcv_no, date_format( pc.pcv_printDate, '%d-%m-%Y' ) AS pcv_printDate, usr.usr_name, date_format( pc.pcv_receiveDate, '%d-%m-%Y' ) AS pcv_receiveDate, 
					(SELECT count( pro_id ) 
					FROM prc_pr_detail AS pd
					WHERE pd.pcv_id = pc.pcv_id
					AND pd.pcv_stat = '1') AS jum_item
					FROM prc_pcv AS pc
					INNER JOIN prc_sys_user AS usr ON usr.usr_id = pc.pcv_printUser
					WHERE pc.pcv_status = '5' {SEARCH_STR}";
		
		$querys['main_query'] = $select1.$select2;
		$querys['count_query'] = $select1."count(pc.pcv_id) as record_count, ".$select2;
		
		$build_querys = $this->CI->flexigrid->build_querys($querys,FALSE); 
		//Get contents
		$return['records'] = $this->db->query($build_querys['main_query']);
		//Get record count
		$get_record_count = $this->db->query($build_querys['count_query']);
		$row = $get_record_count->row();
		$return['record_count'] = $row->record_count;
		
		return $return;
	}
	
	function pcv_realisasi_detail($pcvid){
		$return['head'] = $this->db->query("SELECT pc.pcv_id, pc.pcv_no, date_format( pc.pcv_printDate, '%d-%m-%Y' ) AS pcv_printDate, date_format( pc.pcv_receiveDate, '%d-%m-%Y' ) AS pcv_receiveDate, prn.usr_name AS print_name, rec.usr_name AS receive_name
						FROM prc_pcv AS pc
						INNER JOIN prc_sys_user AS prn ON prn.usr_id = pc.pcv_printUser
						INNER JOIN prc_sys_user AS rec ON rec.usr_id = pc.pcv_receiveUser
						WHERE pcv_id = '$pcvid'
						AND pcv_status = '5'
						AND pcv_printStat = '1'"
						);
		
		$return['detail'] = $this->db->query("SELECT um.satuan_format, pd.qty, pd.price_pre, pd.pro_id, pro.pro_code, pro.pro_name, um.satuan_name, rec.qty AS qty_receive
						FROM prc_pr_detail AS pd
						INNER JOIN prc_master_product AS pro ON pd.pro_id = pro.pro_id
						INNER JOIN prc_master_satuan AS um ON pd.um_id = um.satuan_id
						INNER JOIN prc_pcv AS pc ON pc.pcv_id = pd.pcv_id
						INNER JOIN prc_pcv_receive AS rec ON ( pc.pcv_id = rec.pcv_id
						AND rec.pro_id = pd.pro_id )
						WHERE pd.pcv_id = '$pcvid'
						AND pd.pcv_stat = '1'
						AND pc.pcv_status = '5'"
						);
		return $return;
	}
	
	function realisasi_harga($pcvid, $proid, $harga){
		$this->db->query("update prc_pcv_receive set price ='$harga' 
					where pro_id='$proid' 
					and pcv_id='$pcvid'
					");
		
		$this->db->query("update prc_pr_detail set price ='$harga' 
					where pro_id='$proid' 
					and pcv_id='$pcvid'
					");
		
		$this->db->query("update prc_pcv set pcv_status='6' 
					where pcv_id='$pcvid'
					");
	}
	
	function get_datalist_pettycash(){
		return $this->db->query("SELECT pc.pcv_no, pc.pcv_id, pc.pcv_printCounter, pr.pr_no, u.usr_name 
			from prc_pcv as pc
			inner join prc_pr as pr on pc.pr_id = pr.pr_id
			inner join prc_sys_user as u on pr.pr_requestor = u.usr_id
			where pc.pcv_status='2' and pc.pcv_printStat='1'");
	}
}
?>