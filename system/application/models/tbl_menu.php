<?php
class Tbl_menu extends Model{
	public static $RET_MENU,$WEWENANG,$PARENT;
	function Tbl_menu(){
	// call the Model constructor
		parent::Model();
		$this->load->database();
		$this->CI =& get_instance();
	}
	
	function get_Ret() {
		return self::$RET_MENU;
	}
	
	function set_level($menu_id, $l, $x, $i, $usrid) {
		if ($menu_id == ''){
			$menu_id = 0;
		}
		$l = $l + 1;
		$id = $x;
		$this->db->select('menu_id, menu_name');
		$this->db->where('menu_parent',$menu_id);
		$this->db->order_by ('menu_id', 'ASC');
		$get = $this->db->get('prc_sys_menu');
		foreach ($get->result() as $level) :
			//$child = $level->menu_id - $i;
			$cek = $this->cek_level($level->menu_id);
			if ($l > 1){
				$child1 = "child-of-".$id;
			}else{
				$child1 = '';
			}
			if ($cek > 0){
				$checkmenu = $this->CI->tbl_user->get_user_menu($usrid, $level->menu_id);
							if ($checkmenu > 0){
								$checked = "checked = checked";
							}else{
								$checked = "";
							}
				echo "<tr id=".$level->menu_id."' class='".$child1." x'>
					 <td><span class='folder'><input type='checkbox' name='menu[]' value='".$level->menu_id."' ".$checked." class='parent_check check_parent_".$i." tree' prnt_no='".$i."' check_stats='sub'>".$level->menu_name."</span></td>
				</tr>";
				$this->set_level($level->menu_id, $l, $level->menu_id, $i, $usrid);
				$i = $i + 1;	
			}else{
				$checked = "";
				if ($usrid != ''){
					$checkmenu = $this->CI->tbl_user->get_user_menu($usrid, $level->menu_id);
						if ($checkmenu > 0){
							$checked = "checked = checked";
						}else{
							$checked = "";
						}
				}
					echo "<tr id='level_".$level->menu_id."' class='child-of-".$id." x'>
				 	<td><span class='file'><input type='checkbox' name='menu[]' value='".$level->menu_id."' ".$checked." class='sub_parent_check check_sub_".$i." tree' prnt_no='".$i."' check_stats='parent'>".$level->menu_name."</span></td>
					</tr>";
			}				
		endforeach;
	}
	
	function cek_level($menu_id) {
		$this->db->select('count(menu_id) as record_count');
		$this->db->where('menu_parent',$menu_id);
		$this->db->from('prc_sys_menu');
		return $this->db->get()->row()->record_count;
	}
	
	function cek_child($menuid){
		$data = array('menu_parent' => $menuid);
		//$this->db->select('menu_id');
		$this->db->where($data);
		$this->db->from('prc_sys_menu');
		return  $this->db->count_all_results();
	}
	
	function get_allMenu($parentid = "",$i=0){
		//global $RET_MENU ;
		$usrid = $this->session->userdata('usr_id');
		$ucat_id = $this->session->userdata('ucat_id');
		
        if($parentid==""){
			$parentid=0;
		}
		
		if ($ucat_id != 8):
		$menu = $this->db->query("SELECT sum.usr_id, sum.menu_id, sm.sorter, sm.menu_parent, sm.menu_name,	sm.menu_path, sm.menu_icon
				FROM prc_sys_user_menu AS sum
				INNER JOIN prc_sys_menu AS sm ON sum.menu_id = sm.menu_id
				WHERE sum.usr_id = '$usrid' AND sm.menu_parent = '$parentid' AND (sm.modulstat = '2' OR sm.modulstat = '0')
				ORDER BY sm.sorter desc,sm.subsorter, sum.menu_id");
		else:
		$menu = $this->db->query("SELECT sm.menu_id, sm.sorter, sm.menu_parent, sm.menu_name,	sm.menu_path, sm.menu_icon
				FROM prc_sys_menu AS sm
				WHERE sm.menu_parent = '$parentid' AND (sm.modulstat = '2' OR sm.modulstat = '0')
				ORDER BY sm.sorter desc,sm.subsorter, sm.menu_id");
		endif;
		//$i++;
		
        if($i==0) $comma = ""; else $comma = ",";
        foreach($menu->result() as $rows):
			if($i!=0 && $rows->menu_parent==0)
				$cmsplit = "_cmSplit,";
			else
				$cmsplit = "";
			if($rows->menu_icon!="")
				$icon = "'<img src=\"asset/javascript/jscookmenu/ThemeOffice/".$rows->menu_icon."\">'";
			else
				$icon = "null";
			if(!$this->cek_child($rows->menu_id))
				$url = "'".base_url().$rows->menu_path."'";
			else
				$url = "null";
		
			self::$RET_MENU .= $comma.$cmsplit."[".$icon.", '".$rows->menu_name."', $url, null, '".$rows->menu_name."'";
			
			$i++;
			$this->get_allMenu($rows->menu_id,$i);
			self::$RET_MENU .= "],";
		endforeach;
	}
	
	function wewenang($usrid, $menu_parent = 0, $lvl = 0, $slvl = 0) {
		$br = '';
		$lvl++;	
		$sql_p = "select * from prc_sys_menu where menu_parent = $menu_parent AND (modulstat = '2' OR modulstat = '0')";
		$get_p = $this->db->query($sql_p);
		foreach ($get_p->result() as $r_p):
			$set_child = '';
			$folder = '';
			if ($this->cek_level($r_p->menu_id) > 0 ):
				$set_child = ', children: ['.$br;
				$slvl = $r_p->menu_id;
				$folder = ', isFolder: true';
			else:
				$slvl = 0;
			endif;
			
			$selected = '';
			$sql_u = "select * from prc_sys_user_menu where usr_id = '".$usrid."' and menu_id = '".$r_p->menu_id."'";
			$get_u = $this->db->query($sql_u);
			if($get_u->num_rows() > 0):
				$selected = 'select: true, ';
			endif;

			self::$WEWENANG .= '{'.$selected.'title: "'.$r_p->menu_name.'", key: "'.$r_p->menu_id.'"'.$folder.$set_child;
			$this->wewenang($usrid, $r_p->menu_id, $lvl, $slvl);
			if ($r_p->menu_id == $slvl):
			self::$WEWENANG .= ']},'.$br;
			else:
			self::$WEWENANG .= '},'.$br;
			endif;
		endforeach;
	}
	
	function get_wewenang() {
		return self::$WEWENANG;
	}
}
?>
