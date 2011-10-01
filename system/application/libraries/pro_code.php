<?php
class Pro_code {
	function Pro_code() {
		$this->obj =& get_instance();		
	}
		
	/* SPLIT 01.02.03 KE ARAY [LVL][PARENT][CAT_CODE][CAT_NAME] */
	function split_pro_code($pro_code) {
		$split_code = explode('.',$pro_code);
		$cat_code_id = '';
		$split_data = array();
		$lvl_hight = count($split_code);
		for ($lvl = 1;$lvl<=$lvl_hight;$lvl++):
			$cat_code_id .= ($lvl>1)?('.'.$split_code[$lvl-1]):($split_code[$lvl-1]);
			$this->obj->db->where('cat_code',$cat_code_id);
			$get_name = $this->obj->db->get($this->obj->config->item('tbl_kategori'));
			if($get_name->num_rows()>0):
				$get_names = $get_name->row();
				$split_data[$lvl][$split_code[$lvl-1]][$cat_code_id]=$get_names->cat_name;
			endif;
		endfor;
		return $split_data;
	}
	
	/* USE TYPE LEVEL/PARENT/CAT_CODE/CAT_NAME to retive array data*/
	function set_split_code($pro_code,$type){
		$split_data = $this->split_pro_code($pro_code);
		
		foreach ($split_data as $lvl=>$array_lvl):
			$type1[$lvl]=$lvl;
			foreach ($array_lvl as $single_code=>$code):
				$type2[$lvl]=$single_code;
				foreach ($code as $long_code=>$cat_name):
					$type3[$lvl]=$long_code;
					$type4[$lvl]=$cat_name;
				endforeach;
			endforeach;
		endforeach;
		
		switch($type):
			case 'level' : return $type1; break;
			case 'parent' : return $type2; break;
			case 'cat_code' : return $type3; break;
			case 'cat_name' : return $type4; break;
		endswitch;
		
	}
	
	function set_json_view($cat_id) {
		if ($cat_id!=''):
			$pro_code = $this->tbl_produk->get_cat_code($cat_id);
		endif;
		
		// JSON STRUKTUR
		$json = '[{"parent":"parent"';		
		$level = $this->pro_code->set_split_code($pro_code,'level');
		$parent = $this->pro_code->set_split_code($pro_code,'parent');
		$cat_code = $this->pro_code->set_split_code($pro_code,'cat_code');
		$cat_name = $this->pro_code->set_split_code($pro_code,'cat_name');
		foreach($level as $lvl):
			$json .= ',"lv'.$lvl.'_code":"'.$parent[$lvl].'"';
			$json .= ',"lv'.$lvl.'_name":"'.$cat_name[$lvl].'"';
			$json .= ',"lv'.$lvl.'_catcode":"'.$cat_code[$lvl].'"';
		endforeach;
		
		if (count($level)>=3):
			$like['pro_code']=$cat_code[3];
			$get_pro = $this->tbl_produk->get_product(false,$like,$flexigrid=false,$sort='DESC');
			if ($get_pro->num_rows()>0):
				$pro_id = substr($get_pro->row()->pro_code,9,3)+1;
				$zero='';
				if(strlen($pro_id)>=1):
					$zero='00';	
				elseif (strlen($pro_id)==2):
					$zero='0';
				endif;
				$json .= ',"pro_idcode":"'.str_pad($pro_id,3,$zero,STR_PAD_LEFT).'"';
			else:
				$json .= ',"pro_idcode":"001"';
			endif;
			
		endif;
		$json .= '}]';
		return $json;
	}
	
}
?>