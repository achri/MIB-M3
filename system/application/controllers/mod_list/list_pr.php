<?php
class List_pr extends MY_Controller{
	public static $link_view, $link_controller;
	function List_pr(){
		parent::MY_Controller();
		$this->load->model(array('tbl_pr','flexi_model'));
		$this->load->helper('flexigrid');
		//$this->obj =& get_instance();
		$this->config->load('flexigrid');
		$this->load->library(array('general','flexigrid','flexi_engine'));
		$this->lang->load('general','bahasa');
		
		$arrayCSS = array (
		'asset/css/themes/start/ui.all.css',
		'asset/javascript/jQuery/flexigrid/css/flexigrid.css',
		'asset/css/table/DataView.css',
		'asset/css/form/form_view.css',
		);
		
		$arrayJS = array (
		'asset/javascript/jQuery/flexigrid/js/flexigrid.js',
		);
		
		$data['extraHeadContent'] = '';
		
		foreach ($arrayCSS as $css):
			$data['extraHeadContent'] .= '<link type="text/css" rel="stylesheet" href="'.base_url().$css.'"/>';
		endforeach;
		foreach ($arrayJS as $js):
			$data['extraHeadContent'] .= '<script type="text/javascript" src="'.base_url().$js.'"/></script>';
		endforeach;
		
		self::$link_controller = 'mod_list/list_pr';
		self::$link_view = 'purchase/mod_list/mod_pr';
		$data['link_controller'] = self::$link_controller;
		$data['link_view'] = self::$link_view;
		
		$this->load->vars($data);
	}
	
	// QUERY UNTUK NON FLEXIGRID ATO FLEXIGRID
	function flexigrid_sql($flexi=TRUE,$count='pr_id',$where=FALSE) {
		$sql = "SELECT p.pr_no, p.pr_id, p.pr_requestor, u.dep_id, d.dep_name, date_format( p.pr_date, '%d-%m-%Y' ) AS pr_date, date_format( p.pr_lastModified, '%d-%m-%Y' ) AS pr_lastModified, (
				dayofmonth( now( ) ) - dayofmonth( p.pr_lastModified )
				) AS tgl_selisih,  
				
				(
				SELECT count( pro_id ) 
				FROM prc_pr_detail AS d
				WHERE d.pr_id = p.pr_id and (d.emergencyStat=1)
				) AS pr_emergency,
				
				(SELECT count( pro_id )
				FROM prc_pr_detail AS d
				WHERE d.pr_id = p.pr_id
				AND d.requestStat =0
				) AS pr_wait,
				
				(SELECT count( pro_id )
				FROM prc_pr_detail AS d
				WHERE d.pr_id = p.pr_id
				AND d.requestStat =1
				) AS pr_ok, 
				
				(SELECT count( pro_id )
				FROM prc_pr_detail AS d
				WHERE d.pr_id = p.pr_id
				AND d.requestStat =2
				) AS pr_ubah, 
				
				(SELECT count( pro_id )
				FROM prc_pr_detail AS d
				WHERE d.pr_id = p.pr_id
				AND d.requestStat =3
				) AS pr_catat, 
				
				(SELECT count( pro_id )
				FROM prc_pr_detail AS d
				WHERE d.pr_id = p.pr_id
				AND d.requestStat =4
				) AS pr_pending,
				
				(SELECT count( pro_id )
				FROM prc_pr_detail AS d
				WHERE d.pr_id = p.pr_id
				AND d.requestStat = 5
				) AS pr_reject

				{COUNT_STR}
				FROM `prc_pr` AS p
				INNER JOIN prc_sys_user AS u ON p.pr_requestor = u.usr_id
				INNER JOIN prc_master_departemen AS d ON u.dep_id = d.dep_id
				where pr_status > 0 {SEARCH_STR}";
		return $this->flexi_model->generate_sql($sql,$count,$where,$flexi);
	}
	
	function flexigrid_ajax()
	{		
		$valid_fields = array('pr_id','pr_no','pr_date','dep_name','pr_pending','pr_reject','pr_ok','pr_lastModified');
		
		$this->flexigrid->validate_post('pr_id','asc',$valid_fields);

		$records = $this->flexigrid_sql();
		
		$this->output->set_header($this->config->item('json_header'));
		
		if ($records['count'] > 0):		
			foreach ($records['result']->result() as $row)
			{
				$stat = array("Normal","<font color=red>Darurat</font>");
				$restat = array("NO Proses","Disetujui","Diubah & Disetujui","Disetujui dgn Cat","Ditunda","Ditolak");
				$record_items[] = array(
				$row->pr_id, // TABLE ID
				$row->pr_no,
				$row->pr_date,
				$stat[$row->pr_emergency],
				$row->dep_name,
				$row->pr_wait,
				$row->pr_pending,
				$row->pr_reject,
				$row->pr_ok,
				$row->pr_ubah,
				$row->pr_catat,
				$row->pr_lastModified,
				'<a href=\'javascript:void(0)\' onclick=\'open_pr('.$row->pr_id.')\'><img border=\'0\' src=\'./asset/img_source/icon_info.gif\'></a>'
				);
			}
		else: 
			$record_items[] = array('0','null','null','empty','empty','null','null');
		endif;
		
		$this->output->set_output($this->flexigrid->json_build($records['count'],$record_items));
	}
	
	function flexigrid_builder($title,$width,$height,$rp) {
		$colModel['pr_no'] = array('No PR',80,TRUE,'center',1);
		$colModel['pr_date'] = array('Tgl PR',60,TRUE,'center',2);
		$colModel['pr_darurat'] = array('Status',50,TRUE,'center',1);
		$colModel['dep_name'] = array('Departemen',150,TRUE,'left',1);
		$colModel['pr_wait'] = array('Belum<br>ditentukan',50,TRUE,'center',1);
		$colModel['pr_pending'] = array('Ditunda',50,TRUE,'center',1);
		$colModel['pr_reject'] = array('Ditolak',50,TRUE,'center',1);
		$colModel['pr_ok'] = array('Disetujui',50,TRUE,'center',1);
		$colModel['pr_ubah'] = array('Diubah&<br>Disetujui',50,TRUE,'center',1);
		$colModel['pr_catat'] = array('Disetujui<br>dgn catatan',50,TRUE,'center',1);
		$colModel['pr_lastModified'] = array('Terakhir<br>diubah',80,TRUE,'center',1);
		$colModel['opsi'] = array('Opsi',50,TRUE,'center',0);
		
		$ajax_model = site_url(self::$link_controller."/flexigrid_ajax");
		
		return build_grid_js('pr_list',$ajax_model,$colModel,'pr_no','desc',$this->flexi_engine->flexi_params($width,$height,$rp,$title));
		
	}
	
	function index() {
		//$data['extraHeadContent'] = "<link type=\"text/css\" rel=\"stylesheet\" href=\"". base_url()."asset/css/themes/start/ui.all.css\" />\n";
		//$data['extraHeadContent'] .= "<script type=\"text/javascript\" src=\"". base_url()."asset/javascript/jQuery/tables/jquery.jeditable.js\" />\n </script>";
		//$data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"".base_url()."asset/css/table/DataView.css\" />\n";
		//$data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"".base_url()."asset/css/form/form_view.css\" />\n </script>";
		
		//$data['datalist'] = $this->tbl_pr->get_datalist_pr();
		$records = $this->flexigrid_sql(false);
		if ($records->num_rows() > 0):		
			$data['js_grid'] = $this->flexigrid_builder('Daftar PR','auto',219,8);
		else:
			$data['js_grid'] = $this->lang->line('list_empty');
		endif;
		$data['content'] = self::$link_view.'/index_pr_list_view';
		$this->load->view('index',$data);
	}
	
	function open_pr($id){
		$data['pr'] =  $this->tbl_pr->pr_detail($id);
		$this->load->view(self::$link_view.'/pr_view',$data);
	}
	
}
?>