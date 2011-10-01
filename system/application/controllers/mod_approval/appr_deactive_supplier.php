<?
class appr_deactive_supplier extends MY_Controller {
	public static $link_view, $link_controller;
	function appr_deactive_supplier() {
		parent::MY_Controller();
		$this->load->model(array('flexi_model','tbl_term','tbl_bank','tbl_category','tbl_legal','tbl_negara','tbl_supplier','tbl_sup_category','tbl_sup_bank','tbl_kota','tbl_provinsi'));
		$this->load->library(array('flexigrid','flexi_engine'));
		$this->load->helper(array('flexigrid','html'));
		$this->load->library('session');
		$this->config->load('tables');
		
		$this->config->load('flexigrid');
		
		// LANGUAGE
		$this->lang->load('mod_master/supplier','bahasa');
		$this->lang->load('mod_master/pemasok','bahasa');
		$this->lang->load('tables','bahasa');
		$this->lang->load('general','bahasa');
		$this->lang->load('label','bahasa');		
		
		$arrayCSS = array (
		'asset/css/themes/start/ui.all.css',
		'asset/javascript/jQuery/flexigrid/css/flexigrid.css',
		'asset/css/supplier/supp_sort.css',
		'asset/css/table/DataView.css',
		'asset/css/form/form_view.css',
		);
		
		$arrayJS = array (
		'asset/javascript/jQuery/flexigrid/js/flexigrid.js',
		'asset/javascript/jQuery/content/jquery.blockui.js',
		);
		
		$data['extraHeadContent'] = '';
		
		foreach ($arrayCSS as $css):
			$data['extraHeadContent'] .= '<link type="text/css" rel="stylesheet" href="'.base_url().$css.'"/>';
		endforeach;
		foreach ($arrayJS as $js):
			$data['extraHeadContent'] .= '<script type="text/javascript" src="'.base_url().$js.'"/></script>';
		endforeach;

		self::$link_controller = 'mod_approval/appr_deactive_supplier';
		self::$link_view = 'purchase/mod_approval/desupplier_appr';
		$data['link_controller'] = self::$link_controller;
		$data['link_view'] = self::$link_view;
		
		// JUDUL HALAMAN
		$data['page_title'] = 'MENU PERSETUJUAN NON AKTIF PEMASOK';
		
		$this->load->vars($data);
	}
	
	// QUERY UNTUK NON FLEXIGRID ATO FLEXIGRID
	function flexigrid_sql($flexi=TRUE,$count='sup_id',$where=FALSE) {
		$sql = "
		select sup.sup_id,sup.sup_name,sup.sup_address,leg.legal_name,sup.sup_status,usr.usr_name {COUNT_STR} 
		from prc_master_supplier as sup
		inner join prc_master_legality as leg on leg.legal_id = sup.legal_id
		inner join prc_sys_user as usr on usr.usr_id = sup.deactive_req
		where sup.sup_status = 2
		{SEARCH_STR}";
		return $this->flexi_model->generate_sql($sql,$count,$where,$flexi);
	}
	
	// MEMPERSIAPKAN DATA YANG AKAN DITAMPILKAN
	function flexigrid_ajax()
	{		
		$valid_fields = array('sup_id','sup_name','sup_address','legal_id','sup_status');
		$this->flexigrid->validate_post('sup_id','asc', $valid_fields);
		
		$records = $this->flexigrid_sql(TRUE);
				
		$this->output->set_header($this->config->item('json_header'));
		
		if ($records['count'] > 0):		
			foreach ($records['result']->result() as $row)
			{
				if ($row->sup_status == 0): $status = 'Non Aktif';
				elseif ($row->sup_status == 1): $status = 'Aktif';
				else: $status = 'Tunggu'; endif;
				
				$record_items[] = array(
				$row->sup_id,
				$row->sup_name.' ,'.$row->legal_name,
				$row->sup_address,
				$row->usr_name,
				$status,
				'<a href=\'javascript:void(0)\' onclick=\'open_sup('.$row->sup_id.')\'><img border=\'0\' src=\'./asset/img_source/icon_info.gif\'></a>'
				);
			}
		else: 
			$record_items[] = array('0','n/a','n/a','n/a','n/a');
		endif;
		
		$this->output->set_output($this->flexigrid->json_build($records['count'],$record_items));
	}
	
	// MEMBANGUN DATA FLEXIGRID
	function flexigrid_builder($title,$width,$height,$rp) {

		//$colModel['no'] = array($this->lang->line('no'),20,FALSE,'center',0);
		$colModel['sup_name'] = array('Nama Pemasok',200,TRUE,'left',2);
		$colModel['supp_address'] = array('Alamat Pemasok',370,TRUE,'left',1);
		$colModel['pemohon'] = array('Pemohon',80, TRUE,'center',0);
		$colModel['sup_status'] = array('Status',80, TRUE,'center',0);
		$colModel['action'] = array($this->lang->line('action'),50, FALSE, 'center',0);		
		
		$ajax_model = site_url(self::$link_controller."/flexigrid_ajax");
		$flexi_params = $this->flexi_engine->flexi_params($width,$height,$rp,$title);
		
		return build_grid_js('supplier_list',$ajax_model,$colModel,'sup_name','asc',$flexi_params);
		
	}
	
	function index() {
		$cek_data = $this->flexigrid_sql(FALSE);//$this->tbl_contrabon->get_bon_print_list();
		if ($cek_data->num_rows() > 0):
		$data['js_grid'] = $this->flexigrid_builder('Daftar Pemasok','auto',210,8);
		else:
		$data['js_grid'] = $this->lang->line('list_empty');
		endif;
		$data['content'] = self::$link_view.'/appr_desupplier_main';
		$this->load->view('index',$data);
	}
	
	function request_deactive($sup_id) {
		$data['cat_sup'] = $this->tbl_sup_category->get_sup_cat($sup_id);
		$data['data_sup'] = $this->tbl_supplier->get_supplier_deactive($sup_id)->row();
		$this->load->view(self::$link_view.'/appr_desupplier_req',$data);
	}
	
	function appr_form($id) {
		$catParent = '0';
		$data['get_bank']= $this->tbl_bank->get_bank($id);
		$data['cat_sup'] = $this->tbl_sup_category->get_sup_cat($id);
		$data['list_supp'] = $this->tbl_supplier->get_supplier($id);
		$data['list_term'] = $this->tbl_term->list_term();
		$data['list_bank'] = $this->tbl_bank->list_bank();
		$data['list_cat'] = $this->tbl_category->category_get($catParent);
		$data['list_leg'] = $this->tbl_legal->list_legal();
		$data['list_neg'] = $this->tbl_negara->list_negara();
		$data['sup_id'] = $id;
		
		$sql="select sup.deactive_note,usr.usr_name, dep.dep_name, date_format(sup.deactive_date,'%d-%m-%Y') as deactive_date 
		from prc_master_supplier as sup
		inner join prc_sys_user as usr on usr.usr_id = sup.deactive_req
		inner join prc_master_departemen as dep on dep.dep_id = usr.dep_id
		where sup.sup_id = $id
		";
		
		$data['data_sup'] = $this->db->query($sql)->row();
		$this->load->view(self::$link_view.'/appr_desupplier_det',$data);
	}
	
	function appr_deactive() {
		$sup_id = $this->input->post('idsupp');
		$sup_name = $this->input->post('namesupp');
		$status = $this->input->post('deactive_status');
		
		if ($status != ''):
			$sql = "update prc_master_supplier set sup_status = $status where sup_id = $sup_id";
			if ($this->db->query($sql)):
				if ($status == 2): echo 'Non Aktif Pemasok '.$sup_name.' berhasil ditunda !!!';
				elseif ($status == 0): echo 'Non Aktif Pemasok '.$sup_name.' berhasil disetujui !!!';
				endif;
			else:
				echo 'gagal';
			endif;
		else:
			echo 'kosong';
		endif;
		//echo $sup_id.'|'.$alasan;
	}
	
}
?>