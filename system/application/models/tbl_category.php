<?php
class Tbl_category extends Model {
	public static $KATEGORI;
	function Tbl_category(){
	// call the Model constructor
		parent::Model();
	// load database class and connect to MySQL
		$this->load->database();
		$this->CI =& get_instance();
	}
	
	function category_get ($catParent) {
		$this->db->where('cat_parent',$catParent);
		$this->db->order_by('cat_id','ASC');
		return $this->db->get('prc_master_category');
	}

	function get_category ($where,$like=false) {
		if (is_array($where)):
			foreach ($where as $key=>$val):
				if ($like):
					$this->db->like($key,$val);
				else:
					$this->db->where($key,$val);
				endif;
			endforeach;
		endif;	
		$this->db->order_by('cat_id');
		return $this->db->get($this->config->item('tbl_kategori'));
	}

	
	function category_set() {
		$query = $this->db->query('SELECT cat_id, cat_parent FROM prc_master_category WHERE cat_parent = 0');
		return $query->num_rows();
	}
	
	function category_cek_kelas($name, $level) {
		$query = $this->db->query("SELECT cat_name FROM prc_master_category WHERE cat_name = '$name' AND cat_level='$level'");
		return $query->num_rows();
	}
	
	function category_num($catParent) {
		$this->db->select_max('cat_code','numcode');
		$this->db->where('cat_parent',$catParent);
		$query = $this->db->get('prc_master_category');
		$query_rows = $query->row();
		return $query_rows->numcode;
	}
	
	function category_insert($cat_code, $cat_parent, $cat_level, $cat_name, $detail, $usrid) {
		$data = array(
               'cat_code' => $cat_code, 
			   'cat_parent' => $cat_parent, 
			   'cat_level' => $cat_level,
			   'cat_name' => $cat_name, 
			   'need_realization' => $detail,
			   'rec_created' => date('Y-m-d'),
			   'rec_creator' => $usrid
            );
		$this->db->insert('prc_master_category', $data);
	}
	
	function category_delete($cat_id) {
		$this->db->where('cat_id', $cat_id);
		$this->db->delete('prc_master_category'); 
	}
	
	function category_update($cat_id,$cat_name,$usrid) {
		$data = array(
			   'cat_name' => $cat_name,
			   'rec_edit' => 1,
			   'rec_editor' => $usrid,
			   'rec_edited' => date('Y-m-d')
				
			);
		$this->db->where('cat_id', $cat_id);
		$this->db->update('prc_master_category', $data);
	}

	//set_class
	function category_num_kelas($catParent) {
		$query = $this->db->query("SELECT cat_id, cat_parent FROM prc_master_category WHERE cat_parent = '$catParent'");
		return $query->num_rows();
	}
	
	function set_level1() {
		$this->db->select('cat_id, cat_code, cat_level, cat_name');
		$this->db->where('cat_level',1);
		$this->db->order_by ('cat_id', 'ASC');
		return $query = $this->db->get('prc_master_category');
		//return $this->db->query("SELECT cat_id, cat_code, cat_level, cat_name FROM tbl_prc_category WHERE cat_level = 1");
	}

	function set_level2($cat_id) {
		$this->db->select('cat_id, cat_code, cat_level, cat_name');
		$this->db->where('cat_parent',$cat_id);
		$this->db->order_by ('cat_id', 'ASC');
		return $query = $this->db->get('prc_master_category');
		//return $this->db->query("SELECT cat_id, cat_code, cat_level, cat_name FROM tbl_prc_category WHERE cat_parent = '$cat_id'");
	}

	function set_level3($cat_id) {
		$this->db->select('cat_id, cat_code, cat_level, cat_name');
		$this->db->where('cat_parent',$cat_id);
		$this->db->order_by ('cat_id', 'ASC');
		return $query = $this->db->get('prc_master_category');
		//return $this->db->query("SELECT cat_id, cat_code, cat_level, cat_name FROM tbl_prc_category WHERE cat_parent = '$cat_id'");
	}
	
	function get_sup_cat_rest($id){
		$this->db->where_not_in('cat_id',$id);
		$this->db->where('cat_level',1);
		return $query = $this->db->get('prc_master_category');
	}

	function get_catid($code) {
		$this->db->select('cat_id');
		$this->db->where('cat_code',$code);
		return $query = $this->db->get('prc_master_category');
	}
	
	function cek_produk($id) {
		$this->db->select('pro_id');
		$this->db->where('cat_id',$id);
		return $query = $this->db->get('prc_master_product');
	}
	
	function dynatree_model($cat_id,$cat_level) {
		$this->db->select('cat_id, cat_code, cat_level, cat_name');
		$this->db->where('cat_parent',$cat_id);
		$this->db->where_in('cat_level',$cat_level);
		$this->db->order_by ('cat_name', 'ASC');
		return $query = $this->db->get('prc_master_category');
	}
	
	function dynatree_set($cat_id,$cat_level=array('1','2','3')) {		
		$qlevel = $this->dynatree_model($cat_id,$cat_level);
		$maxs = $qlevel->num_rows();
		$row = 1;		
		$json = '[';
		foreach($qlevel->result() as $rows):
			$json .= '{';
			$json .= 'title: "'.htmlspecialchars($rows->cat_name).'"';
			$json .= ',key: "'.$rows->cat_id.'"';
			
			$qlevel2 = $this->dynatree_model($rows->cat_id,$cat_level);
			
			if ($qlevel2->num_rows() > 0):
				$json .= ',isFolder: true';
				$json .= ',isLazy: true';
			else:
				$json .= ',isFolder: false';
				$json .= ',isLazy: false';
			endif;
			
			$json .= ',addClass: "edit_group"';
					
			$json .= '}';
			if ($row < $maxs):
				$json .= ',';
			endif;
			$row++;
		
		endforeach;
		$json .= ']';
		return $json;
	}
}
?>