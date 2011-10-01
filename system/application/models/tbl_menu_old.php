<?php
class Tbl_menu extends Model{

	function Tbl_menu(){
	// call the Model constructor
		parent::Model();
		$this->load->database();
		$this->CI =& get_instance();
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
				$checkmenu = $this->CI->Tbl_user->get_user_menu($usrid, $level->menu_id);
							if ($checkmenu > 0){
								$checked = "checked = checked";
							}else{
								$checked = "";
							}
				echo "<tr id=".$level->menu_id."' class='".$child1." x'>
					 <td><span class='folder'><input type='checkbox' name='menu[]' value='".$level->menu_id."' ".$checked.">".$level->menu_name."</span></td>
				</tr>";
				$this->set_level($level->menu_id, $l, $level->menu_id, $i, $usrid);
				$i = $i + 1;	
				}else{
					$checked = "";
					if ($usrid != ''){
						$checkmenu = $this->CI->Tbl_user->get_user_menu($usrid, $level->menu_id);
							if ($checkmenu > 0){
								$checked = "checked = checked";
							}else{
								$checked = "";
							}
					}
						echo "<tr id='level_".$level->menu_id."' class='child-of-".$id." x'>
					 	<td><span class='file'><input type='checkbox' name='menu[]' value='".$level->menu_id."' ".$checked.">".$level->menu_name."</span></td>
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
	
	function cek_child($usrid, $menuid){
		/*$data = array('usr_id' => $usrid, 'menu_parent' => $menuid);
		$this->db->select('menu_id');
		$this->db->where($data);
		$this->db->from('prc_sys_menu');
		return $this->db->get()->num_rows();*/
		return $this->db->query("SELECT sum.usr_id, sum.menu_id, sm. *
				FROM prc_sys_user_menu AS sum
				INNER JOIN prc_sys_menu AS sm ON sum.menu_id = sm.menu_id
				WHERE sum.usr_id = '$usrid' AND sm.menu_parent = '$menuid'");
	}
	
	function get_menu($usrid, $menuid, $i, $RET_MENU){
		$aa = "";
		$menu = $this->db->query("SELECT sum.usr_id, sum.menu_id, sm.menu_parent, sm.menu_name,	sm.menu_path, sm.menu_icon
				FROM prc_sys_user_menu AS sum
				INNER JOIN prc_sys_menu AS sm ON sum.menu_id = sm.menu_id
				WHERE sum.usr_id = '$usrid' AND sm.menu_parent = '$menuid'");
		
			if($i==0) 
				$comma = ""; 
			else 
				$comma = ",";
		
				foreach ($menu->result() as $result):
					if($i!=0 && $result->menu_parent == 0)
						$cmsplit = "_cmSplit,";
					else
						$cmsplit = "";

					if($result->menu_icon != "")
						$icon = "'<img src=\"asset/javascript/jscookmenu/ThemeOffice/".$result->menu_icon."\">'";
					else
						$icon = "null";
				
					if($result->menu_path != "")
						$url = $result->menu_path;
					else
						$url = "null";

					$RET_MENU .= $comma.$cmsplit."[".$icon.", '".$result->menu_name."', '".$url."', null, '".$result->menu_name."'";			
					$i++;	
					$RET_MENU = $this->get_menu2($usrid, $result->menu_id, $i, $RET_MENU);
					$RET_MENU .= "],";
		
					$aa .=$result->menu_id."-".$usrid."-".$RET_MENU."-".$i."<br/>"; 
				endforeach;
				$return['RET_MENU'] = $RET_MENU;
				$return['test'] = $aa;
				return $return;
	}
	
	function get_menu2($usrid, $menuid, $i, $RET_MENU){
		$aa = "";
		$menu = $this->db->query("SELECT sum.usr_id, sum.menu_id, sm.menu_parent, sm.menu_name,	sm.menu_path, sm.menu_icon
				FROM prc_sys_user_menu AS sum
				INNER JOIN prc_sys_menu AS sm ON sum.menu_id = sm.menu_id
				WHERE sum.usr_id = '$usrid' AND sm.menu_parent = '$menuid'");
		
			if($i==0) 
				$comma = ""; 
			else 
				$comma = ",";
		
				foreach ($menu->result() as $result):
					if($i!=0 && $result->menu_parent == 0)
						$cmsplit = "_cmSplit,";
					else
						$cmsplit = "";

					if($result->menu_icon != "")
						$icon = "'<img src=\"asset/javascript/jscookmenu/ThemeOffice/".$result->menu_icon."\">'";
					else
						$icon = "null";
				
					if($result->menu_path != "")
						$url = $result->menu_path;
					else
						$url = "null";

					$RET_MENU .= $comma.$cmsplit."[".$icon.", '".$result->menu_name."', '".$url."', null, '".$result->menu_name."'";			
					$i++;	
					$RET_MENU = $this->get_menu3($usrid, $result->menu_id, $i, $RET_MENU);
					$RET_MENU .= "],";
		
					$aa .=$result->menu_id."-".$usrid."-".$RET_MENU."-".$i."<br/>"; 
				endforeach;
				return $RET_MENU;
	}
	
function get_menu3($usrid, $menuid, $i, $RET_MENU){
		$aa = "";
		$menu = $this->db->query("SELECT sum.usr_id, sum.menu_id, sm.menu_parent, sm.menu_name,	sm.menu_path, sm.menu_icon
				FROM prc_sys_user_menu AS sum
				INNER JOIN prc_sys_menu AS sm ON sum.menu_id = sm.menu_id
				WHERE sum.usr_id = '$usrid' AND sm.menu_parent = '$menuid'");
		
			if($i==0) 
				$comma = ""; 
			else 
				$comma = ",";
		
				foreach ($menu->result() as $result):
					if($i!=0 && $result->menu_parent == 0)
						$cmsplit = "_cmSplit,";
					else
						$cmsplit = "";

					if($result->menu_icon != "")
						$icon = "'<img src=\"asset/javascript/jscookmenu/ThemeOffice/".$result->menu_icon."\">'";
					else
						$icon = "null";
				
					if($result->menu_path != "")
						$url = $result->menu_path;
					else
						$url = "null";

					$RET_MENU .= $comma.$cmsplit."[".$icon.", '".$result->menu_name."', '".$url."', null, '".$result->menu_name."'";			
					$i++;	
					$this->get_menu($usrid, $result->menu_id, $i, $RET_MENU);
					$RET_MENU .= "],";
		
					$aa .=$result->menu_id."-".$usrid."-".$RET_MENU."-".$i."<br/>"; 
				endforeach;
				return $RET_MENU;
	}
	
	
}
?>