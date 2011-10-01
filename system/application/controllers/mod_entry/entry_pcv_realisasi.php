<?php
class Entry_pcv_realisasi extends MY_Controller{
	public static $link_controller,$link_view;
	function Entry_pcv_realisasi(){
		parent::MY_Controller();
		$this->load->model(array('Tbl_pcv', 'Tbl_pr'));
		$this->load->helper('flexigrid');
		$this->load->library('flexigrid');
		$this->config->load('flexigrid');
		
		$this->lang->load('general','bahasa');
		
		// LINK CONTROLLER DAN VIEW
		self::$link_controller = 'mod_entry/entry_pcv_realisasi';
		self::$link_view = 'purchase/mod_entry/mod_product/mod_pcv_realisasi';
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
		$valid_fields = array('pcv_id', 'pcv_no', 'pcv_printDate', 'usr_name', 'pcv_receiveDate', 'jum_item');
		
		$this->flexigrid->validate_post('pcv_id','asc',$valid_fields);

		$records = $this->Tbl_pcv->get_pcv_realisasi();
		
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
				$record_items[] = array($row->pcv_id,
				$i,
				$row->pcv_no,
				$row->pcv_printDate,
				$row->usr_name,
				$row->pcv_receiveDate,
				$row->jum_item,
				'<a href=\'javascript:void(0)\' onclick=\'open_pcv_realisasi('.$row->pcv_id.')\'><img border=\'0\' src=\'./asset/img_source/icon_info.gif\'></a>'
				);
			}
		}else{
			$records['record_count'] += 1;
			$record_items[] = array('','','','empty','','','');
		}
		//Print please
		$this->output->set_output($this->flexigrid->json_build($records['record_count'],$record_items));
	}
	
	function index() {
		$data['extraHeadContent'] = "<link type=\"text/css\" rel=\"stylesheet\" href=\"". base_url()."asset/css/themes/start/ui.all.css\" />\n";
		$data['extraHeadContent'] .= "<script type=\"text/javascript\" src=\"". base_url()."asset/javascript/jQuery/tables/jquery.jeditable.js\" />\n </script>";
		$data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"".base_url()."asset/css/table/DataView.css\" />\n";
		$data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"".base_url()."asset/css/form/form_view.css\" />\n";
		$data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"".base_url()."asset/css/flexigrid/flexigrid.css\" />\n";
		$data['extraHeadContent'] .= "<script type=\"text/javascript\" src=\"". base_url()."asset/javascript/flexigrid.js\" />\n </script>";
		
		$colModel['no'] = array($this->lang->line('realisasi_flex_col_0'),20,TRUE,'center',0);
		$colModel['pcv_no'] = array($this->lang->line('realisasi_flex_col_1'),100,TRUE,'center',2);
		$colModel['pcv_printDate'] = array($this->lang->line('realisasi_flex_col_2'),100,TRUE,'center',2);
		$colModel['usr_name'] = array($this->lang->line('realisasi_flex_col_3'),150,TRUE,'center',2);
		$colModel['pcv_receiveDate'] = array($this->lang->line('realisasi_flex_col_4'),100, TRUE,'center',2);
		$colModel['jum_item'] = array($this->lang->line('realisasi_flex_col_5'),60, TRUE,'center',0);
		$colModel['opsi'] = array($this->lang->line('realisasi_flex_col_6'),30, TRUE,'center',0);
		/*
		 * Aditional Parameters
		 */
		$gridParams = array(
		'width' => 650,
		'height' => 271,
		'rp' => 10,
		'rpOptions' => '[15,20,25,40]',
		'pagestat' => 'Displaying: {from} to {to} of {total} items.',
		'blockOpacity' => 0.5,
		'title' => $this->lang->line('realisasi_flex_ttl'),
		'showTableToggleBtn' => false,
		'singleSelect' => true
		);
		
		$this->flexigrid->validate_post('pcv_id','asc');
		
		$records = $this->Tbl_pcv->get_pcv_realisasi();
		if ($records['record_count'] == 0){
			$data['kosong'] = "Belum ada data Petty Cash untuk di proses";
		}else{
		//Build js
		//View helpers/flexigrid_helper.php for more information about the params on this function
		$grid_js = build_grid_js('flex1',site_url(self::$link_controller."/flexigrid_ajax"),$colModel,'id','asc',$gridParams);
		$data['message'] = $this->lang->line('contact_confirm_del');
		$data['js_grid'] = $grid_js;
		$data['kosong'] ="";
		}
		$data['content'] = self::$link_view.'/index_pcvrealisasi_view';
		$this->load->view('index',$data);
	
	}
	
	function open_pcv($pcvid){
		$data['realisasi'] = $this->Tbl_pcv->pcv_realisasi_detail($pcvid);
		$this->load->view(self::$link_view.'/pcv_realisasi_view',$data);
	}
	
	function add_realisasi(){
		$pcvid = $this->input->post('pcvid');
		$proid = $this->input->post('proid');
		$proname = $this->input->post('proname');
		$harga = $this->input->post('harga');
		$error = "";
		
		for($i=0;$i<sizeof($proid);$i++) {
			if ($harga[$i] == ''){
				$error[] = "- Harga ".$proname[$i]." belum diisi <br/>";
			}
		}
		
		if ($error){
			echo implode($error);
		}else{
			for($i=0;$i<sizeof($proid);$i++) {
				$this->Tbl_pcv->realisasi_harga($pcvid, $proid[$i], $harga[$i]);
				
			}
			echo "ok";
		}
		
		
	}
	
}
?>