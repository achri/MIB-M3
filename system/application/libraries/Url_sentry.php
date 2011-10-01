<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Url_sentry 
{

	function Url_sentry()
	{
		$this->obj =& get_instance();
	}
	
	function is_access() {
		$return = false;
		// AMBIL NAMA CONTROLLER
		$controller = strtolower(get_class($this->obj));
		// SORTIR NAMA CONTROLLER FLEXIGRID
		$controller = str_replace('flexigrid','',$controller);
		// PERMIT UNTUK CORE CONTROLLER
		$arr_permit = array('purchase','login','ci_loader','master_user_change');
		
		if (!in_array($controller,$arr_permit)):
			if ($this->obj->session->userdata('usr_id')):
				$userid = $this->obj->session->userdata('usr_id');
				$ucat_id = $this->obj->session->userdata('ucat_id');
				
				if ($ucat_id != 8):
					$sql = "select usrmenu.menu_id from prc_sys_user_menu as usrmenu 
					inner join prc_sys_menu as menu on usrmenu.menu_id = menu.menu_id 
					where usrmenu.usr_id = '$userid' and menu.menu_path != 'null' 
					and menu.menu_path like '%$controller%'";
					
					if ($this->obj->db->query($sql)->num_rows() == 0):
						$return = true;
						
					endif;
				endif;
			endif;
		endif;
	
		return $return;
	}

}
?>