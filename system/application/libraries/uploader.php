<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Uploader {
	function go_upload($config,$user_file){
		$CI = & get_instance();
		$CI->load->library('upload');
		$CI->upload->initialize($config);
		
		if(!$CI->upload->do_upload($user_file)){
			$error    = $CI->upload->display_errors();
			return $error;
		}else{
			$datas    = array('data_upload' => $CI->upload->data());
			return $datas;
		}
	
	}
}
?>