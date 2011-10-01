<?php
class Tbl_user extends Model {
	
	function Tbl_user(){
	// call the Model constructor
		parent::Model();
		$this->load->database();
		$this->CI =& get_instance();
	}
	
	function get_user_flex(){
		$this->db->select('usr_id, usr_login, usr_name, dep_id, ttl_id');
		$this->db->from('prc_sys_user');
		$this->CI->flexigrid->build_query(TRUE);
		$return['records'] = $this->db->get();
				
		//Build count query
		$this->db->select('count(usr_id) as record_count');
		$this->db->from('prc_sys_user');
		$this->CI->flexigrid->build_query(FALSE);
		$return['record_count'] = $this->db->get()->row()->record_count;
		
		return $return;
	}
	
	function insert_user($login, $nama, $dep, $jab, $pas1, $pas2){
		$data = array(
				'usr_login' => $login,
				'usr_pwd1' => md5($pas1),
				'usr_pwd2' => md5($pas2),
				'usr_name' => $nama,
				'ttl_id' => $jab,
				'dep_id' => $dep
		);
		$this->db->insert('prc_sys_user',$data);
		return $id = $this->db->insert_id(); 
	}
	
	function insert_user_menu($id, $menu){
		$set_menu = explode(",",$menu);
		for($i=0;$i<sizeof($set_menu);$i++) {
				$data = array(
	               	'usr_id' => $id,
					'menu_id' => $set_menu[$i]
				);
			$this->db->insert('prc_sys_user_menu', $data);
		}
	}
	
	function delete_user_menu($id){
		$this->db->where('usr_id', $id);	
		$this->db->delete('prc_sys_user_menu');
	}
	
	function update_user($id, $login, $nama, $dep, $jab){
		$data = array(
				'usr_login' => $login,
				'usr_name' => $nama,
				'ttl_id' => $jab,
				'dep_id' => $dep
		);
		$this->db->where('usr_id', $id);
		$this->db->Update('prc_sys_user',$data);
	}
	
	function update_pass($id, $pas1, $pas2){
		$data = array(
				'usr_pwd1' => md5($pas1),
				'usr_pwd2' => md5($pas2)
		);
		$this->db->where('usr_id', $id);
		$this->db->Update('prc_sys_user',$data);
	}
	
	function update_pict($id, $pict){
		$data = array(
				'usr_image' => $pict
		);
		$this->db->where('usr_id', $id);
		$this->db->update('prc_sys_user',$data);
	}
	
	function get_user($id){
		$this->db->select('usr_id, usr_login, usr_name, dep_id, ttl_id, usr_image');
		$this->db->where('usr_id', $id);
		return $this->db->get('prc_sys_user');
	}
	
	function get_user_menu($id, $menu){
		$data = array(
				'usr_id' => $id,
				'menu_id' => $menu
		);
		$this->db->select('usr_id, menu_id');
		$this->db->where($data);
		return $this->db->get('prc_sys_user_menu')->num_rows();
	}
	
	function delete_user($id){
		$this->db->where('user_id', $id);
		$this->db->delete('prc_sys_user');
	}

	function cek_user($login) {
		$query = $this->db->query("SELECT usr_login FROM prc_sys_user WHERE usr_login = '$login'");
		return $query->num_rows();
	}




}
?>