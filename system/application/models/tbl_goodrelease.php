<?php
class Tbl_goodrelease extends Model {
	
	function Tbl_goodrelease(){
		parent::Model();
		$this->CI =& get_instance();
	}
	
	function insert_code($code, $mr){
		$data = array(
			'grl_no' => $code,
			'mr_id' => $mr,
			'grl_date' => date ('Y-m-d')
		);
		$this->db->insert('prc_good_release',$data);
		return $id = $this->db->insert_id(); 
	}
	
	function goodrelease_list($ucat, $usrid,$print_status){
		if ($ucat == 1){
			$select =  $this->db->query("SELECT g. * , m.mr_no, u.usr_name
				FROM prc_good_release AS g
				INNER JOIN prc_mr AS m ON g.mr_id = m.mr_id
				INNER JOIN prc_sys_user AS u ON m.mr_requestor = u.usr_id
				WHERE grl_printStat = '".$print_status."' and grl_status='0'
				AND m.mr_requestor = '$usrid'"
			);
			$return['records'] = $select;
		}else{
			$select =  $this->db->query("SELECT g. * , m.mr_no, u.usr_name
				FROM prc_good_release AS g
				INNER JOIN prc_mr AS m ON g.mr_id = m.mr_id
				INNER JOIN prc_sys_user AS u ON m.mr_requestor = u.usr_id
				WHERE grl_printStat = '".$print_status."' and grl_status='0'
				
				" 
			);
			$return['records'] = $select;
		}
		
		$return['record_count'] = $select->num_rows();
		
		return $return;
	}
	
	function get_grl_content($id){
		$return['head'] = $this->db->query("SELECT grl.grl_id, grl.grl_no, grl.grl_lastprintDate, grl.grl_printCounter, date_format( grl.grl_date, '%d-%m%-%Y' ) AS grl_date, usr.usr_name, dep.dep_name
				FROM prc_good_release AS grl
				INNER JOIN prc_mr AS mr ON grl.mr_id = mr.mr_id
				INNER JOIN prc_sys_user AS usr ON mr.mr_requestor = usr.usr_id
				INNER JOIN prc_master_departemen AS dep ON usr.dep_id = dep.dep_id
				WHERE grl.grl_id = '$id' 
				");
		
		$return['detail'] = $this->db->query("SELECT d.pro_id, d.sup_id, d.qty, d.description, d.um_id, pro.pro_name, pro.pro_code, sup.sup_name, leg.legal_name, sat.satuan_name, sat.satuan_format 
				FROM prc_mr_detail AS d
				INNER JOIN prc_master_product AS pro ON d.pro_id = pro.pro_id
				LEFT JOIN prc_master_supplier AS sup ON d.sup_id = sup.sup_id
				LEFT JOIN prc_master_legality AS leg ON sup.legal_id = leg.legal_id
				INNER JOIN prc_master_satuan AS sat ON sat.satuan_id = d.um_id
				INNER JOIN prc_good_release AS grl ON grl.grl_id = d.grl_id
				INNER JOIN prc_mr AS mr ON grl.mr_id = mr.mr_id
				WHERE d.grl_id ='$id' and (d.requestStat = 1 or d.requestStat = 2 or d.requestStat = 3) 
				");
					
		return $return;
	}
	
	function update_grl($id, $count, $tgl, $user){
		if ($count == '0'){
			$count = '1';
			$tgl = date('Y-m-d');
		}else{
			$count = $count + 1;
			$date = explode('-',$tgl);
			$tgl = $date[2]."-".$date[1]."-".$date[0];
		}
		
		$datau = array(
			'grl_printStat' => '1',
			'grl_printUser' => $user,
			'grl_lastprintDate' => date('Y-m-d'),
			'grl_printDate' => $tgl,
			'grl_printCounter' => $count
		);
		$dataw = array(
			'grl_id' => $id
		);
		$this->db->where($dataw);
		return $this->db->update('prc_good_release',$datau);
	}

	
	function list_grl_realisasi(){
		return $this->db->query("SELECT grl_id, grl_no
				FROM prc_good_release
				WHERE grl_status = '0'
				AND grl_printStat = '1'
				ORDER BY grl_id"
		);
	}
	
	function get_content_realisasi ($id){
		return $this->db->query("SELECT d.pro_id, p.pro_code, p.pro_name, d.sup_id, s.sup_name, l.legal_name, d.qty, d.um_id, u.satuan_name,u.satuan_format, m.mr_requestor, usr.usr_name, g.grl_no, m.mr_id, inv.inv_end, 
					(select value from prc_satuan_produk where pro_id = d.pro_id and satuan_id = p.um_id and satuan_unit_id = d.um_id) as satuan_konversi 
					FROM prc_mr_detail AS d
					INNER JOIN prc_master_product AS p ON p.pro_id = d.pro_id
					INNER JOIN prc_master_satuan AS u ON u.satuan_id = d.um_id
					LEFT JOIN prc_master_supplier AS s ON s.sup_id = d.sup_id
					LEFT JOIN prc_master_legality AS l ON l.legal_id = s.legal_id
					INNER JOIN prc_mr AS m ON m.mr_id = d.mr_id
					INNER JOIN prc_good_release AS g ON g.grl_id = d.grl_id
					INNER JOIN prc_sys_user AS usr ON usr.usr_id = m.mr_requestor
					left join prc_inventory as inv on inv.pro_id = d.pro_id and inv.sup_id = d.sup_id 
					where d.grl_id = '$id' and (d.requestStat = 1 or d.requestStat = 2 or d.requestStat = 3)");
							
	}
	
	function update_grl_head($id, $user){
		$data1 = array(
			'grl_status' => '1',
			'grl_releaseUser' => $user,
			'grl_releaseDate' => date('Y-m-d')
		);
		$data2 = array(
			'grl_id' => $id
		);
		$this->db->where($data2);
		$query = $this->db->update('prc_good_release',$data1);
	}
	
	function get_pakai_flex($req){
		$select1 = "SELECT ";
		$select2 = "rd.grl_id, rd.grl_realisasi, rd.qty_use, (
				rd.grl_realisasi - rd.qty_use
				) AS qty_remain, r.grl_no, pro.pro_name, pro.pro_id, pro.pro_code, um.satuan_name, um.satuan_format, mr.mr_no
				FROM prc_mr_detail AS rd
				INNER JOIN prc_good_release AS r ON r.grl_id = rd.grl_id
				INNER JOIN prc_mr AS mr ON r.mr_id = mr.mr_id
				INNER JOIN prc_master_product AS pro ON rd.pro_id = pro.pro_id
				INNER JOIN prc_master_category AS cat ON cat.cat_id = pro.cat_id
				INNER JOIN prc_master_satuan AS um ON rd.um_id = um.satuan_id
				WHERE r.grl_status = '1'
				AND mr.mr_requestor = '$req'
				AND cat.need_realization = '1'
				AND rd.is_closed = '0'
				AND rd.grl_realisasi <> '0' {SEARCH_STR}";
		
		$querys['main_query'] = $select1.$select2;
		$querys['count_query'] = $select1."count(rd.grl_id) as record_count, ".$select2;
		
		$build_querys = $this->CI->flexigrid->build_querys($querys,FALSE); 
		//Get contents
		$return['records'] = $this->db->query($build_querys['main_query']);
		//Get record count
		$get_record_count = $this->db->query($build_querys['count_query']);
		$row = $get_record_count->row();
		$return['record_count'] = $row->record_count;
		
		return $return;
	}
	
	function get_detail_pakai($grlid, $proid){
		return $this->db->query("SELECT rd.grl_id, rd.grl_realisasi, rd.qty_use, date_format( r.grl_releaseDate, '%d-%m-%Y' ) AS grl_releaseDate, r.grl_no, pro.pro_id,
				pro.pro_name, pro.pro_code, um.satuan_name, um.satuan_format, mr.mr_id, mr.mr_no, usr.usr_name, rd.sup_id
				FROM prc_mr_detail AS rd
				INNER JOIN prc_good_release AS r ON r.grl_id = rd.grl_id
				INNER JOIN prc_mr AS mr ON r.mr_id = mr.mr_id
				INNER JOIN prc_master_product AS pro ON rd.pro_id = pro.pro_id
				INNER JOIN prc_master_category AS cat ON cat.cat_id = pro.cat_id
				INNER JOIN prc_master_satuan AS um ON rd.um_id = um.satuan_id
				INNER JOIN prc_sys_user AS usr ON usr.usr_id = mr.mr_requestor
				WHERE r.grl_status = '1'
				AND cat.need_realization = '1'
				AND rd.is_closed = '0'
				AND pro.pro_id = '$proid'
				AND rd.grl_id = '$grlid'
				AND rd.grl_realisasi <> '0'");
	}
	
	function get_real_val($mrid, $grlid, $proid){
		return $this->db->query("SELECT * FROM prc_mr_detail
				WHERE mr_id = '$mrid'
				AND pro_id = '$proid'
				AND grl_id = '$grlid'");
	}
	
	function get_retur_flex(){
		$select1 = "SELECT ";
		$select2 = "rd.grl_id, rd.grl_realisasi, rd.qty_use, (
				rd.grl_realisasi - rd.qty_use
				) AS qty_remain, r.grl_no, pro.pro_id, pro.pro_name, pro.pro_code, um.satuan_name, um.satuan_format,mr.mr_no, usr.usr_name
				FROM prc_mr_detail AS rd
				INNER JOIN prc_good_release AS r ON r.grl_id = rd.grl_id
				INNER JOIN prc_mr AS mr ON r.mr_id = mr.mr_id
				INNER JOIN prc_sys_user AS usr ON mr.mr_requestor = usr.usr_id
				INNER JOIN prc_master_product AS pro ON rd.pro_id = pro.pro_id
				INNER JOIN prc_master_category AS cat ON cat.cat_id = pro.cat_id
				INNER JOIN prc_master_satuan AS um ON rd.um_id = um.satuan_id
				WHERE r.grl_status = '1'
				AND cat.need_realization = '1'
				AND rd.is_closed = '0'
				AND (
				rd.grl_realisasi - rd.qty_use
				) <> '0' {SEARCH_STR}";
		
		$querys['main_query'] = $select1.$select2;
		$querys['count_query'] = $select1."count(rd.grl_id) as record_count, ".$select2;
		
		$build_querys = $this->CI->flexigrid->build_querys($querys,FALSE); 
		//Get contents
		$return['records'] = $this->db->query($build_querys['main_query']);
		//Get record count
		$get_record_count = $this->db->query($build_querys['count_query']);
		$row = $get_record_count->row();
		$return['record_count'] = $row->record_count;
		
		return $return;
	}
	
	function get_detail_retur($grlid, $proid){
		return $this->db->query("SELECT rd.grl_id, rd.grl_realisasi, rd.qty_use, date_format( r.grl_releaseDate, '%d-%m-%Y' ) AS grl_releaseDate, 
				(rd.grl_realisasi - rd.qty_use) AS qty_remain, r.grl_no, pro.pro_name, pro.pro_code, pro.pro_id, um.satuan_name,um.satuan_format, mr.mr_id, mr.mr_no, usr.usr_name, sup.sup_name, rd.sup_id
				, pro.um_id as pro_satuan, um.satuan_id as satuan 
				FROM prc_mr_detail AS rd
				INNER JOIN prc_good_release AS r ON r.grl_id = rd.grl_id
				INNER JOIN prc_mr AS mr ON r.mr_id = mr.mr_id
				LEFT JOIN prc_master_supplier AS sup ON sup.sup_id = rd.sup_id
				INNER JOIN prc_master_product AS pro ON rd.pro_id = pro.pro_id
				INNER JOIN prc_master_category AS cat ON cat.cat_id = pro.cat_id
				INNER JOIN prc_master_satuan AS um ON rd.um_id = um.satuan_id
				INNER JOIN prc_sys_user AS usr ON usr.usr_id = mr.mr_requestor
				WHERE r.grl_status = '1'
				AND cat.need_realization = '1'
				AND rd.is_closed = '0'
				AND (rd.grl_realisasi - rd.qty_use) <> '0'
				AND rd.grl_id = '$grlid'
				AND pro.pro_id = '$proid'");
	}
	
	function get_datalist_grl (){
		return $this->db->query("SELECT g.*, m.mr_no, u.usr_name from prc_good_release as g 
			inner join prc_mr as m on g.mr_id=m.mr_id
			inner join prc_sys_user as u on m.mr_requestor = u.usr_id
			where grl_status='1' and grl_printStat='1'");
	}
	
	function cek_reorder($proid){
		return $this->db->query("SELECT pro_is_reorder, pro_min_reorder FROM prc_master_product WHERE pro_id='$proid'");
	}
}
?>