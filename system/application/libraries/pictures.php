<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Pictures {
	function Pictures() {
		$this->obj =& get_instance();
	}
	
	function thumbs ($product_id,$w = '',$h = '') {
		$folder	 = './uploads/produk/';
		
		if ($w == '' AND $h == ''):
			$set_lebar  = '200';
			$set_tinggi = '200';
		else:
			$set_lebar	= $w;
			$set_tinggi	= $h;
		endif;
		
		$where = array('pro_id'=>$product_id);
		$qget_product = $this->obj->tbl_produk->get_product($where);
		if ($qget_product->num_rows() > 0):
			$product = $qget_product->row();	
			$link_gbr = $product->pro_image;
			if ((file_exists($folder.$link_gbr))&&(!empty($link_gbr))):
				$ukuran = getimagesize($folder.$link_gbr);
				
				if ($ukuran[0]>$ukuran[1]): 
					$opsi['width'] = $set_lebar;						
				elseif ($ukuran[1]>$ukuran[0]):
					$opsi['height'] = $set_tinggi;
				else: 
					$opsi['width']=$set_lebar;
					$opsi['height']=$set_tinggi; 
				endif;
				$opsi['src'] = $folder.$link_gbr;
				
			else:
				$opsi['src'] = $folder.'na.jpg';
				$opsi['height'] = $set_tinggi;
			endif;
			//$opsi['class'] = "ui-widget-header ui-corner-all";
			//return $set_lebar.'-'.$set_tinggi.'<br>'.$ukuran[0].'-'.$ukuran[1];
			return img($opsi);
		endif;
	}
	
	function thumbs_ajax ($product_name,$w = '',$h = '', $folder = './uploads/produk/') {
		
		if ($w == '' AND $h == ''):
			$set_lebar  = '200';
			$set_tinggi = '200';
		else:
			$set_lebar	= $w;
			$set_tinggi	= $h;
		endif;
		
		$link_gbr = $product_name;
		if ((file_exists($folder.$link_gbr))&&(!empty($link_gbr))):
			$ukuran = getimagesize($folder.$link_gbr);
			
			if ($ukuran[0]>$ukuran[1]): 
				$opsi['width'] = $set_lebar;						
			elseif ($ukuran[1]>$ukuran[0]):
				$opsi['height'] = $set_tinggi;
			else: 
				$opsi['width']=$set_lebar;
				$opsi['height']=$set_tinggi; 
			endif;
			$opsi['src'] = $folder.$link_gbr;
			
		else:
			$opsi['src'] = $folder.'na.jpg';
			$opsi['height'] = $set_tinggi;
		endif;
		//$opsi['class'] = "ui-widget-header ui-corner-all";
		//return $set_lebar.'-'.$set_tinggi.'<br>'.$ukuran[0].'-'.$ukuran[1];
		return img($opsi);
	}
	
	function images_delete($image) {
		$thumb_folder	= $this->obj->config->item('thumb_folder');
		//$retur = FALSE;
		if ($image != ''):
			if (read_file($thumb_folder.$image)):
				unlink($thumb_folder.$image);
			endif;
			//$retur = TRUE;
			
		endif;
		return $thumb_folder.$image;
		//return $retur;
	}
	
	function delete_file($file) {
		if ($file != '') {
			if (read_file($file)):
				unlink($file);
			endif;
		}	
	}
	
}
?>