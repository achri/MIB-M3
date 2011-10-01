<?php
class Tbl_pcv_receive extends Model {
	
	function Tbl_pcv_receive(){
		parent::Model();
		$this->CI =& get_instance();
	}
	
	function add_receive($pcv, $pro, $jml){		
		$data = array(
					'pcv_id' => $pcv,
					'pro_id' => $pro,
					'qty' => $jml
				);
		$this->db->insert('prc_pcv_receive',$data);
	}
}
?>