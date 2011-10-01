<?php
class Tbl_inventory extends Model {
	
	function Tbl_inventory(){
		parent::Model();
		$this->obj =& get_instance();
	}
	
	function cek_stok($proid, $sup) {
		$data = array('pro_id' => $proid, 'sup_id' => $sup);
		//$this->db->select('inv_end, inv_id');
		$this->db->select('*');
		$this->db->where($data);
		return $query = $this->db->get('prc_inventory');
	}
	
	function update_stok($invid, $begin, $nextend, $real, $grlno, $inv_price='',$bal_price='') {
		/*$data = array('inv_end'=> $nextend,
					  'inv_begin' => $begin,
					  'inv_out' => $real,
					  'inv_document' => $grlno
					 );*/
		$data['inv_in'] = 0;
		$data['inv_end'] = $nextend;
		$data['inv_begin'] = $begin;
		$data['inv_out'] = $real;
		$data['inv_document'] = $grlno;
		if (!empty($inv_price)):
			$data['inv_price'] = $inv_price;
			$data['bal_price'] = $bal_price;
		endif;
		
		$this->db->where('inv_id',$invid);
		$this->db->update('prc_inventory', $data);
	}
	
	function update_stok_history($proid, $begin, $nextend, $real, $invid, $grlno, $inv_price='',$bal_price='',$sup_id='') {
		/*
		$data = array(
				'inv_id'=> $invid,
				'inv_end'=> $nextend,
				'pro_id'=> $proid,
				'inv_begin' => $begin,
				'inv_out' => $real,
				'inv_document' => $grlno
				);
				*/
		$data['inv_id']= $invid;
		$data['pro_id']= $proid;
		$data['inv_end'] = $nextend;
		$data['inv_begin'] = $begin;
		$data['inv_out'] = $real;
		$data['inv_document'] = $grlno;
		if (!empty($inv_price)):
			$data['inv_price'] = $inv_price;
			$data['bal_price'] = $bal_price;
		endif;
		if (!empty($sup_id)):
			$data['sup_id'] = $sup_id;
		endif;
		$this->db->insert('prc_inventory_history', $data);
	}
	
	function update_inv_retur($inv_id, $inv_end, $TEnd, $remain, $grlno) {
		$data = array('inv_end'=> $TEnd,
					  'inv_begin' => $inv_end,
					  'inv_in' => $remain,
					  'inv_document' => $grlno
					 );
		$this->db->where('inv_id',$inv_id);
		$this->db->update('prc_inventory', $data);
	}
	
	function update_inv_retur_history( $inv_id, $proid, $sup, $inv_end, $TEnd, $remain, $grlno){
		$datas = array(
				'inv_id'=> $inv_id,
				'inv_end'=> $TEnd,
				'pro_id'=> $proid,
				'sup_id'=> $sup,
				'inv_begin' => $inv_end,
				'inv_in' => $remain,
				'inv_document' => $grlno
				);
		$this->db->insert('prc_inventory_history', $datas);
	}
	
	function insert_inv_retur($inv_end, $TEnd, $remain, $grlno) {
		$data = array('inv_end'=> $TEnd,
					  'inv_begin' => $inv_end,
					  'inv_in' => $remain,
					  'inv_document' => $grlno
					 );
		$this->db->insert('prc_inventory', $data);
		return $this->db->insert_id();
	}
	
	function insert_inv_retur_history( $inv_id, $proid, $sup, $inv_end, $TEnd, $remain, $grlno){
		$datas = array(
				'inv_id'=> $inv_id,
				'inv_end'=> $TEnd,
				'pro_id'=> $proid,
				'sup_id'=> $sup,
				'inv_begin' => $inv_end,
				'inv_in' => $remain,
				'inv_document' => $grlno
				);
		$this->db->insert('prc_inventory_history', $datas);
	}
	
	function save_inventory($data){
		return $this->db->insert($this->config->item('tbl_inventory'),$data);
	}
	
	function save_inv_history($data){
		return $this->db->insert($this->config->item('tbl_inventory_history'),$data);
	}
	
	function get_inventory($where){
		if (is_array($where)):
			foreach ($where as $key=> $val):
				$this->db->where($key,$val);
			endforeach;
		endif;
		return $this->db->get($this->config->item('tbl_inventory'));
	}
	
	function get_inv_history($where=false,$like=false,$flexigrid=false,$sort="asc",$limit=false) {
		$this->db->select('*');
		if (is_array($where)):
			foreach ($where as $key=>$val):
				$this->db->where($key,$val);
			endforeach;
		endif;
		
		if (is_array($like)):
			foreach ($like as $key=>$val):
				$this->db->like($key,$val.'%');
			endforeach;
		endif;
		
		if ($limit!=false)
			$this->db->limit($limit);
		
		if ($sort!="asc"):
			$this->db->order_by('inv_transDate','desc');
		endif;
			
		if ($flexigrid):
			$this->obj->flexigrid->build_query();		
			$return['result'] = $this->db->get($this->config->item('tbl_inventory_history'));
			if (is_array($where)):
				foreach ($where as $key=>$val):
					$this->db->where($key,$val);
				endforeach;
			endif;
			
			if (is_array($like)):
				foreach ($like as $key=>$val):
					$this->db->like($key,$val.'%');
				endforeach;
			endif;
			
			if ($limit)
				$this->db->limit($limit);
			
			if ($sort!="asc"):
				$this->db->order_by('inv_transDate','desc');
			endif;			
			
			$this->obj->flexigrid->build_query(FALSE);
			$return['count'] = $this->db->get($this->config->item('tbl_inventory_history'))->num_rows();
		else:
			$return = $this->db->get($this->config->item('tbl_inventory_history'));
		endif;
		return $return;
	}
	
	function get_inv_sup($where){
		if (is_array($where)):
			foreach($where as $key=>$val):
				$this->db->where($key,$val);
			endforeach;
		endif;
		$this->db->from('prc_inventory');
		$this->db->join('prc_master_supplier','prc_inventory.sup_id = prc_master_supplier.sup_id');
		return $this->db->get();		
	}
	
	function get_stok($where){
		if (is_array($where)):
			foreach($where as $key=>$val):
				$this->db->where($key,$val);
			endforeach;
		endif;
		return $this->db->get($this->config->item('tbl_inventory'));
	}
	
	function update_inventory($where,$data) {
		if (is_array($where)):
			foreach ($where as $key=>$val):
				$this->db->where($key,$val);
			endforeach;
		endif;
		return $this->db->update($this->config->item('tbl_inventory'),$data);
	}
	
	function get_supinv($proid){
		return $this->db->query("SELECT i.sup_id, s.sup_name
				FROM `prc_inventory` AS i
				INNER JOIN prc_master_supplier AS s ON i.sup_id = s.sup_id
				WHERE i.pro_id = '$proid'");
	}
	
	function get_inv_his($pro_id,$sup_id,$flexi = false,$limit=false,$field=false,$order=false) {
		$sql1 = "Select * ";
		$sql2 = ",if (inv_his.inv_in != 0 and inv_his.inv_document != 'SETUP',
		(select dep.dep_name from prc_gr as gr
		inner join prc_pr_detail as pr_det on pr_det.po_id = gr.po_id
		inner join prc_pr as pr on pr.pr_id = pr_det.pr_id
		inner join prc_sys_user as usr on pr.pr_requestor = usr.usr_id
		inner join prc_master_departemen as dep on usr.dep_id = dep.dep_id
		where inv_his.inv_document = gr.gr_no and pr_det.pro_id = inv_his.pro_id
		),
		(select dep.dep_name from prc_good_release as grl
		inner join prc_mr_detail as mr_det on mr_det.grl_id = grl.grl_id
		inner join prc_mr as mr on mr.mr_id = mr_det.mr_id
		inner join prc_sys_user as usr on mr.mr_requestor = usr.usr_id
		inner join prc_master_departemen as dep on usr.dep_id = dep.dep_id
		where inv_his.inv_document = grl.grl_no and mr_det.pro_id = inv_his.pro_id
		)) as dep_name
		from prc_inventory_history as inv_his
		left join prc_master_supplier as sup on sup.sup_id = inv_his.sup_id 
		where inv_his.pro_id = $pro_id and inv_his.sup_id = $sup_id 
		";
		//$sql2 = "FROM prc_inventory_history as inv left join prc_master_supplier as sup on sup.sup_id = inv.sup_id where inv.pro_id = $pro_id ";
		//$sql2 = "FROM prc_inventory_history as inv, prc_master_supplier as sup where sup.sup_id = inv.sup_id and inv.pro_id = $pro_id ";
		
		if ($flexi):
			$sql2 .= "{SEARCH_STR}";
			$querys['main_query'] = $sql1.$sql2;
			$querys['count_query'] = $sql1.",count(inv_id) as record_count ".$sql2;
			$build_querys = $this->obj->flexigrid->build_querys($querys,FALSE); 
			$return['result'] = $this->db->query($build_querys['main_query']);
			$return['count'] = $this->db->query($build_querys['count_query'])->row()->record_count;
			return $return; 
		else:
			if ($order != false):
				$sql2 .= "order by $field $order ";
			endif;
			if ($limit != false):
				$sql2 .= "LIMIT $limit";
			endif;
			return $this->db->query($sql1.$sql2);
		endif;
		 
	}
	
	function get_inv_his_new($pro_id,$sup_id='') {
		$sql_his = " select * from prc_inventory_history as ih ";
		if ($sup_id != '')
			$sql_his .= "left join prc_master_supplier as sup on sup.sup_id = ih.sup_id 
			where ih.pro_id = $pro_id and ih.sup_id = $sup_id ";
		else 
			$sql_his .= "where ih.pro_id = $pro_id ";
		$sql_his .= "order by ih.inv_id desc limit 1 ";
		return $this->db->query($sql_his);
	}
	
	function delete_inventory($pro_id) {
		return $this->db->query("
		delete from prc_inventory 
		");
	}

}
?>