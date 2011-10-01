<?
class service_complate extends Controller {
	public static $link_view, $link_controller;
	function service_complate() {
		parent::Controller();
		$this->load->model(array('tbl_so'));
		$this->load->helper(array('html'));
		
		$this->config->load('tables');
		
		$this->lang->load('general','bahasa');
		
		$arrayCSS = array (
		'asset/css/themes/start/ui.all.css',
		'asset/javascript/jQuery/autocomplete/jquery.autocomplete.css'
		);
		
		$arrayJS = array (
		'asset/javascript/jQuery/autocomplete/jquery.autocomplete.js',
		'asset/javascript/jQuery/form/jquery.autoNumeric.js',
		'asset/javascript/helper/autoNumeric.js'
		);
		
		$data['extraHeadContent'] = '';
		
		foreach ($arrayCSS as $css):
			$data['extraHeadContent'] .= '<link type="text/css" rel="stylesheet" href="'.base_url().$css.'"/>';
		endforeach;
		foreach ($arrayJS as $js):
			$data['extraHeadContent'] .= '<script type="text/javascript" src="'.base_url().$js.'"/></script>';
		endforeach;
		
		self::$link_controller = 'mod_service/service_complate';
		self::$link_view = 'purchase/mod_entry/mod_service/mod_so_final';
		$data['link_controller'] = self::$link_controller;
		$data['link_view'] = self::$link_view;
		
		$data['page_title'] = 'MENU SERVIS : SERVIS AKHIR ';

		// LANGUAGE
		$data['btn_process'] = 'Proses';
		$data['btn_clear'] = 'Bersihkan';
		
		$this->load->vars($data);
	}
	
	function index() {
		$data['sup_list'] = $this->tbl_so->get_so_sup();
		$data['content'] = self::$link_view.'/service_so_final_main';
		$this->load->view('index',$data);
	}
	
	function list_autocomplate() {
		$q = strtoupper($this->input->get('q'));
		$like_so['so_no'] = $q;
		$where_so['so_cost'] = 0;
		$where_so['so_status'] = 0;
		$where_so['so_printStat'] = 1;
		$qres = $this->tbl_so->get_so($where_so,$like_so);
		
		$limit = strtoupper($this->input->get('limit'));
		
		if ($qres->num_rows() > 0):
			foreach ($qres->result() as $rows):
				if (strpos($rows->so_no, $q) !== false):
					echo "$rows->so_no|$rows->so_id\n";
				endif;
			endforeach;
		endif;
		
		//echo $q.'|'.'|'.$limit;
	}
	
	function list_so() {
		$so_id = $this->input->post('so_id');
		
		$get_so_mr = $this->tbl_so->get_so_mr($so_id);
		$data['so_list'] = $get_so_mr['header'];
		$data['so_det'] = $get_so_mr['detail'];
		$data['content'] = self::$link_view.'/service_so_final_view';
		$this->load->view('index.php',$data);
	}
	
	function so_charge() {
		$where['so_id']		= $this->input->post('so_id');
		$data['so_cost']	= $this->input->post('so_cost');
		
		if ($this->tbl_so->update_so_mr($where,$data)):
			echo $this->tbl_so->get_so($where)->row()->so_no;
		endif;
	}
	
}
?>