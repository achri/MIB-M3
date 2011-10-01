<?php
class Entry_retur extends MY_Controller{
	public static $link_view, $link_controller, $ppn_status;
	function Entry_retur(){
		parent::MY_Controller();
		$this->load->model(array('Tbl_goodrelease', 'Tbl_mr', 'Tbl_inventory'));
		$this->load->helper('flexigrid');
		$this->load->library('flexigrid');
		$this->load->config('flexigrid');
		
		$this->lang->load('general','bahasa');
		
		self::$link_controller = 'mod_entry/entry_retur';
		self::$link_view = 'purchase/mod_entry/mod_product/mod_retur';
		$data['link_controller'] = self::$link_controller;
		$data['link_view'] = self::$link_view;
				
		$this->load->vars($data);
	}
	
	function flexigrid_ajax()
	{
		//$usrid = $this->obj->session->userdata('usr_id');
		//$usrid = 1;
		//List of all fields that can be sortable. This is Optional.
		//This prevents that a user sorts by a column that we dont want him to access, or that doesnt exist, preventing errors.
		$valid_fields = array('grl_id', 'grl_realisasi', 'qty_use', 'qty_remain', 'grl_no', 'pro_name', 'pro_id', 'pro_code', 'usr_name', 'satuan_name', 'mr_no');
		
		$this->flexigrid->validate_post('grl_id','asc',$valid_fields);

		$records = $this->Tbl_goodrelease->get_retur_flex();
		
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
				$i,
				$row->mr_no,
				$row->grl_no,
				$row->pro_name.'<br>'.$row->pro_code,
				$row->usr_name,
				$row->satuan_name,
				number_format($row->grl_realisasi,$row->satuan_format),
				number_format($row->qty_use,$row->satuan_format),
				number_format($row->qty_remain,$row->satuan_format),
				'<a href=\'javascript:void(0)\' onclick=\'open_retur('.$row->grl_id.','.$row->pro_id.')\'><img border=\'0\' src=\'./asset/img_source/icon_info.gif\'></a>'
				);
			}
		}else{
			$records['record_count'] += 1;
			$record_items[] = array('empty','empty','empty','empty','empty','empty','empty','empty','empty');
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
		
		$colModel['no'] = array($this->lang->line('retur_flex_col_0'),20,TRUE,'center',0);
		$colModel['mr_no'] = array($this->lang->line('retur_flex_col_1'),90,TRUE,'center',2);
		$colModel['grl_no'] = array($this->lang->line('retur_flex_col_2'),80,TRUE,'center',2);
		$colModel['pro_name'] = array($this->lang->line('retur_flex_col_3'),200,TRUE,'left',2);
		$colModel['usr_name'] = array($this->lang->line('retur_flex_col_4'),150, TRUE,'center',0);
		$colModel['satuan_name'] = array($this->lang->line('retur_flex_col_5'),60, TRUE,'center',0);
		$colModel['grl_realisasi'] = array($this->lang->line('retur_flex_col_6'),60, TRUE,'center',0);
		$colModel['qty_use'] = array($this->lang->line('retur_flex_col_7'),60, TRUE,'center',0);
		$colModel['qty_remain'] = array($this->lang->line('retur_flex_col_8'),60, TRUE,'center',0);
		$colModel['opsi'] = array($this->lang->line('retur_flex_col_9'),25, TRUE,'center',0);
		/*
		 * Aditional Parameters
		 */
		$gridParams = array(
		'width' => 'auto',
		'height' => 271,
		'rp' => 10,
		'rpOptions' => '[15,20,25,40]',
		//'pagestat' => 'Displaying: {from} to {to} of {total} items.',
		'blockOpacity' => 0.5,
		'title' => $this->lang->line('retur_flex_ttl'),
		'showTableToggleBtn' => true
		);
		
		$this->flexigrid->validate_post('grl_id','asc');
		
		$records = $this->Tbl_goodrelease->get_retur_flex();
		if ($records['record_count'] == 0){
			$data['kosong'] = "Belum ada Data untuk diproses";
		}else{
		//Build js
		//View helpers/flexigrid_helper.php for more information about the params on this function
		$grid_js = build_grid_js('flex1',site_url(self::$link_controller."/flexigrid_ajax"),$colModel,'id','asc',$gridParams);
		$data['js_grid'] = $grid_js;
		$data['kosong'] = "";
		}
		$data['content'] = self::$link_view.'/index_retur_view';
		$this->load->view('index',$data);
	
	}
	
	function open_retur($grlid, $proid){
		$data['get_retur'] = $this->Tbl_goodrelease->get_detail_retur($grlid, $proid);
		$this->load->view(self::$link_view.'/retur_pakai_view',$data);
	}
	
	function add_retur(){
		$mrid = $this->input->post('mrid');
		$grlid = $this->input->post('grlid');
		$grlno = $this->input->post('grl');
		$proid = $this->input->post('proid');
		$remain = $this->input->post('remain');
		$sup = $this->input->post('sup');
		
		$satuan = $this->input->post('satuan');
		$pro_satuan = $this->input->post('pro_satuan');
		
		$end = $this->Tbl_inventory->cek_stok($proid, $sup)->row();
		
		// CEK SATUAN
		if ($satuan != $pro_satuan):
			$get_um_val = $this->db->query("select value from prc_satuan_produk where pro_id = $proid and satuan_id = $pro_satuan and satuan_unit_id = $satuan")->row()->value;
			$remain = $get_um_val * $remain;
		endif;
		
		$TEnd = $end->inv_end + $remain;
		//echo $TEnd.'|'.$end->inv_end.'|'.$remain;
		
		//echo $end->inv_id."+".$remain."=".$TEnd."=".$grlno;
		$this->Tbl_inventory->update_inv_retur( $end->inv_id, $end->inv_end, $TEnd, $remain, $grlno);
		$this->Tbl_inventory->update_inv_retur_history( $end->inv_id, $proid, $sup, $end->inv_end, $TEnd, $remain, $grlno);
		$this->Tbl_mr->update_closed($mrid, $grlid, $proid);
	}
	
}
?>