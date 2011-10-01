<?php
class Entry_pakai extends MY_Controller{
	public static $link_view, $link_controller, $ppn_status;
	function Entry_pakai(){
		parent::MY_Controller();
		$this->load->model(array('Tbl_goodrelease', 'Tbl_mr'));
		$this->load->helper('flexigrid');
		$this->load->library('flexigrid');
		$this->load->config('flexigrid');
		
		$this->lang->load('general','bahasa');
		
		self::$link_controller = 'mod_entry/entry_pakai';
		self::$link_view = 'purchase/mod_entry/mod_product/mod_pakai';
		$data['link_controller'] = self::$link_controller;
		$data['link_view'] = self::$link_view;
				
		$this->load->vars($data);
	}
	
	function flexigrid_ajax()
	{
		$usrid = $this->session->userdata('usr_id');
		//$usrid = 1;
		//List of all fields that can be sortable. This is Optional.
		//This prevents that a user sorts by a column that we dont want him to access, or that doesnt exist, preventing errors.
		$valid_fields = array('grl_id', 'grl_realisasi', 'qty_use', 'qty_remain', 'grl_no', 'pro_name', 'pro_id', 'pro_code', 'satuan_name', 'mr_no');
		
		$this->flexigrid->validate_post('grl_id','asc',$valid_fields);

		$records = $this->Tbl_goodrelease->get_pakai_flex($usrid);
		
		$this->output->set_header($this->config->item('json_header'));
		
		/*
		 * Json build WITH json_encode. If you do not have this function please read
		 * http://flexigrid.eyeviewdesign.com/index.php/flexigrid/example#s3 to know how to use the alternative
		 */
		if ($records['record_count'] > 0){
		$i = 0;
			foreach ($records['records']->result() as $row)
			{
				$i = $i + 1;			
				$record_items[] = array($row->grl_id,
				//$i,
				$row->mr_no,
				$row->grl_no,
				$row->pro_name.'<br>'.$row->pro_code,
				$row->satuan_name,
				number_format($row->grl_realisasi,$row->satuan_format),
				number_format($row->qty_use,$row->satuan_format),
				number_format($row->qty_remain,$row->satuan_format),
				'<a href=\'javascript:void(0)\' onclick=\'open_pakai_real('.$row->grl_id.','.$row->pro_id.')\'><img border=\'0\' src=\'./asset/img_source/icon_info.gif\'></a>'
				);
			}
		}else{
			$record_items[] = array('empty');
		}
		//Print please
		$this->output->set_output($this->flexigrid->json_build($records['record_count'],$record_items));
	}
	
	function index() {
		$data['extraHeadContent'] = "<link type=\"text/css\" rel=\"stylesheet\" href=\"". base_url()."asset/css/themes/start/ui.all.css\" />\n";
		$data['extraHeadContent'] .= "<script type=\"text/javascript\" src=\"". base_url()."asset/javascript/jQuery/tables/jquery.jeditable.js\" />\n </script>";
		$data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"".base_url()."asset/css/table/DataView.css\" />\n";
		$data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"".base_url()."asset/css/form/form_view.css\" />\n";
		$data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"".base_url()."asset/javascript/jQuery/flexigrid/css/flexigrid.css\" />\n";
		$data['extraHeadContent'] .= "<script type=\"text/javascript\" src=\"". base_url()."asset/javascript/jQuery/flexigrid/js/flexigrid.js\" />\n </script>";
		
		//$colModel['no'] = array($this->lang->line('usebrg_flex_col_0'),20,TRUE,'center',0);
		$colModel['no_mr'] = array($this->lang->line('usebrg_flex_col_1'),90,TRUE,'center',2);
		$colModel['no_rf'] = array($this->lang->line('usebrg_flex_col_2'),80,TRUE,'center',2);
		$colModel['pro_name'] = array($this->lang->line('usebrg_flex_col_3'),120,TRUE,'center',2);
		$colModel['satuan_name'] = array($this->lang->line('usebrg_flex_col_4'),80, TRUE,'center',0);
		$colModel['grl_realisasi'] = array($this->lang->line('usebrg_flex_col_5'),80, TRUE,'center',0);
		$colModel['qty_use'] = array($this->lang->line('usebrg_flex_col_6'),80, TRUE,'center',0);
		$colModel['qty_remain'] = array($this->lang->line('usebrg_flex_col_7'),80, TRUE,'center',0);
		$colModel['opsi'] = array($this->lang->line('usebrg_flex_col_8'),40, TRUE,'center',0);
		/*
		 * Aditional Parameters
		 */
		$gridParams = array(
		'width' => 'auto',
		'height' => 271,
		'blockOpacity' => 0.5,
		'title' => $this->lang->line('usebrg_flex_ttl'),
		'showTableToggleBtn' => false,
		'singleSelect' =>true
		);
		
		$this->flexigrid->validate_post('grl_id','asc');
		
		$usrid = $this->session->userdata('usr_id');
		$records = $this->Tbl_goodrelease->get_pakai_flex($usrid);
		if ($records['record_count'] == 0){
			$data['kosong'] = "Belum ada data MR untuk Diproses";
		}else{
		//Build js
		//View helpers/flexigrid_helper.php for more information about the params on this function
		$grid_js = build_grid_js('flex1',site_url(self::$link_controller."/flexigrid_ajax"),$colModel,'id','asc',$gridParams);
		$data['message'] = $this->lang->line('contact_confirm_del');
		$data['js_grid'] = $grid_js;
		$data['kosong'] = "";
		}
		$data['content'] = self::$link_view.'/index_pakai_view';
		$this->load->view('index',$data);
	
	}
	
	function open_pakai($grlid, $proid){
		$data['get_pakai'] = $this->Tbl_goodrelease->get_detail_pakai($grlid, $proid);
		$this->load->view(self::$link_view.'/realisasi_pakai_view',$data);
	}
	
	function add_pemakaian(){
		$mrid = $this->input->post('mrid');
		$grlid = $this->input->post('grlid');
		$proid = $this->input->post('proid');
		$tgl = $this->input->post('tgl');
		$jml = $this->input->post('jml');
		$ket = $this->input->post('ket');
		$ttl = $this->input->post('total');
		$realisasi = $this->input->post('realisasi');
		$error = "";
		
		if ($tgl == ''){
			$error[] = "- Tanggal harus Diisi";
		}
		if ($jml == ''){
			$error[] = "- jumlah harus Diisi";
		}
		if ($realisasi - ($jml + $ttl) < 0){
			$error[] = "- jumlah Penggunaan tidak boleh lebih dari penerimaan";
		}
		if ($error){
			echo implode("<br/>",$error);
		}else{
			$real = $this->Tbl_goodrelease->get_real_val($mrid, $grlid, $proid)->row();
			$use = $real->qty_use + $jml;
			if ($real->grl_realisasi == $use){
				$close = 1;
			}else{
				$close = 0;
			}
			
			$this->Tbl_mr->update_pemakaian($mrid, $grlid, $proid, $close, $use, $ket);
			$this->Tbl_mr->update_pemakaian_history($mrid, $grlid, $proid, $close, $jml, $ket, $tgl);
			echo "ok";
		}
	}
	
}
?>