<?
class Imgupload {
	function Imgupload() {
		$this->obj =& get_instance();
	}
	
	function upload_this($imgname,$img_path) {
		$thumb_folder	= $img_path;
		$temp_folder	= './uploads/temp/';
		if ($this->obj->upload->do_upload()):
			$image = $this->obj->upload->data();
			$imgfile = $imgname.$image['file_ext'];
			$config['source_image'] = $temp_folder.$image['file_name'];
			$config['new_image'] = $thumb_folder.$imgfile ;
			$this->obj->image_lib->initialize($config);
			if ($this->obj->image_lib->resize()):
				unlink($temp_folder.$image['file_name']);
				return $imgfile;
			endif;
		endif;
	}

}
?>