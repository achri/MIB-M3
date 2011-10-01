<?php
class Treeview {
	function Treeview() {
		$this->obj =& get_instance();
	}
	
	function parental() {
		$this->obj->db->select('cat_id, cat_code, cat_level, cat_name');
		$this->obj->db->where('cat_level',1);
		$this->obj->db->order_by ('cat_name', 'ASC');
		return $query = $this->obj->db->get('prc_master_category');
	}
	
	function parentsub($cat_id) {
		$this->obj->db->select('cat_id, cat_code, cat_level, cat_name');
		$this->obj->db->where('cat_parent',$cat_id);
		$this->obj->db->order_by ('cat_name', 'ASC');
		return $query = $this->obj->db->get('prc_master_category');
	}
	
	function generate_tree($key='',$root=false,$folder=true,$active=false,$focus=false,$expand=false) {
		if ($key != ''):
			$qlevel = $this->parentsub($key);
		else:		
			$qlevel = $this->parental();
		endif;
		$maxs = $qlevel->num_rows();
		$row = 1;		
		$json = '[';
		if ($root):
		$json .= '{title:"'.$this->obj->lang->line('all').'",key:"all"},'; 
		endif;
		foreach($qlevel->result() as $rows):
			$json .= '{';
			$json .= 'title: "'.htmlspecialchars($rows->cat_name).'"';
			$json .= ',key: "'.$rows->cat_id.'"';
			
			$qlevel2 = $this->parentsub($rows->cat_id);
			
			if ($folder):
				if ($qlevel2->num_rows() > 0):
					$json .= ',isFolder: true';
					$json .= ',isLazy: true';
				endif;
			else:
				$json .= ',isLazy: false';
			endif;
		
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