<?
/*
*	file name   : list_statistik.php
*	path        : system\application\controller\mod_list
*	Date        : 12-02-2010
*	Copyright (c) 2010 Achri-MIB
*	licensed under the MIB licenses
*/
class list_statistik extends MY_Controller {
	public static $link_view, $link_controller;
	function list_statistik() {
		parent::MY_Controller();
		$this->load->model(array('tbl_produk','tbl_rfq'));
		$this->config->load('tables');
		
		$this->lang->load('mod_master/produk','bahasa');
		
		$this->lang->load('tables','bahasa');
		$this->lang->load('general','bahasa');
		$this->lang->load('label','bahasa');
		
		$arrayCSS = array (
		'asset/css/themes/start/ui.all.css',
		'asset/javascript/jQuery/dataTables/css/jquery.dataTables_q.css',
		'asset/css/table/DataView.css',
		'asset/css/form/form_view.css',
		'asset/css/product.css',
		'asset/javascript/jQuery/autocomplete/jquery.autocomplete.css',
		'asset/css/table/DataView.css'
		);
		
		$arrayJS = array (
		'asset/javascript/jQuery/dataTables/js/jquery.dataTables.js',
		'asset/javascript/jQuery/autocomplete/lib/jquery.bgiframe.min.js',
		'asset/javascript/jQuery/autocomplete/lib/jquery.ajaxQueue.js',
		'asset/javascript/jQuery/autocomplete/lin/thickbox-compressed.js',
		'asset/javascript/jQuery/autocomplete/jquery.autocomplete.js',
		);
		
		$data['extraHeadContent'] = '';
		
		foreach ($arrayCSS as $css):
			$data['extraHeadContent'] .= '<link type="text/css" rel="stylesheet" href="'.base_url().$css.'"/>';
		endforeach;
		foreach ($arrayJS as $js):
			$data['extraHeadContent'] .= '<script type="text/javascript" src="'.base_url().$js.'"/></script>';
		endforeach;
		
		self::$link_controller = 'mod_list/list_statistik';
		self::$link_view = 'purchase/mod_list/statistik_list';
		$data['link_controller'] = self::$link_controller;
		$data['link_view'] = self::$link_view;
		
		$data['page_title'] = $this->lang->line('rep_statistik_title');
				
		$this->load->vars($data);
	}
	
	function index() {
		$data['content'] = self::$link_view.'/list_statistik_main';
		$this->load->view('index',$data);
	}
	
	function list_autocomplate($stats) {
	
		$q = strtoupper($this->input->get('q'));
		
		if ($stats == 'name'):
			$like_pro['pro_name']=$q;
		else:
			$like_pro['pro_code']=$q;
		endif;
		
		$where_pro['pro_status']='active';
		$qres = $this->tbl_produk->get_product($where_pro,$like_pro);
		
		if ($qres->num_rows() > 0):
			foreach ($qres->result() as $rows):
					if ($stats == 'name'):
						if (strpos($rows->pro_name, $q) !== false):
							echo "$rows->pro_name|$rows->pro_code|$rows->pro_id\n";
						endif;
					else:
						if (strpos($rows->pro_code, $q) !== false):
							echo "$rows->pro_code|$rows->pro_name|$rows->pro_id\n";
						endif;
					endif;
			endforeach;
		endif;
	}
	
	function get_statistik($pro_id) {
		//echo $pro_id;
		$get_price = $this->tbl_rfq->rfq_content_price($pro_id);
		$data['qget_price'] = $get_price['price1'];
		$data['qlast_buy'] = $get_price['price2'];//$this->db->query("select price from prc_pr_detail where pro_id = '$pro_id' and po_id !=0 order by po_id desc");
		$this->load->view(self::$link_view.'/list_statistik_view',$data);
	}
}
?>