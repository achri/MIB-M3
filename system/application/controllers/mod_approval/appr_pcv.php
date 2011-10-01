<?php
class appr_pcv extends MY_Controller{
	public static $link_controller,$link_view,$ppn_status,$print_status;
	function appr_pcv(){
		parent::MY_Controller();
		$this->load->model(array('Tbl_pcv','Tbl_pr'));
		$this->load->helper('flexigrid');
		$this->load->library('flexigrid');
		$this->config->load('flexigrid');
		
		$this->lang->load('general','bahasa');
		
		// LINK CONTROLLER DAN VIEW
		self::$link_controller = 'mod_approval/appr_pcv';
		self::$link_view = 'purchase/mod_approval/pcv_appr';
		$data['link_controller'] = self::$link_controller;
		$data['link_view'] = self::$link_view;
		
		$this->load->vars($data);
	}
	
	function flexigrid_ajax()
	{
		//List of all fields that can be sortable. This is Optional.
		//This prevents that a user sorts by a column that we dont want him to access, or that doesnt exist, preventing errors.
		$valid_fields = array('pcv_id',	'pcv_no', 'pcv_date_format', 'pr_no', 'tgl_selisih');
		
		$this->flexigrid->validate_post('pcv_id','asc',$valid_fields);

		$records = $this->Tbl_pcv->pcv_list();
		
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
				//$i,
				$row->pcv_no,
				$row->pr_no,
				$row->tgl_selisih,
				'<a href=\'javascript:void(0)\' onclick=\'open_pcv('.$row->pcv_id.')\'><img border=\'0\' src=\'./asset/img_source/icon_info.gif\'></a>'
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
		
		
		//$colModel['no'] = array($this->lang->line('pcvapp_flex_col_0'),20,TRUE,'center',0);
		$colModel['pcv_no'] = array($this->lang->line('pcvapp_flex_col_1'),90,TRUE,'center',2);
		$colModel['pr_no'] = array($this->lang->line('pcvapp_flex_col_2'),80,TRUE,'center',2);
		$colModel['tgl_selisih'] = array($this->lang->line('pcvapp_flex_col_3'),80,TRUE,'center',2);
		$colModel['opsi'] = array($this->lang->line('pcvapp_flex_col_4'),30, TRUE,'center',0);
		/*
		 * Aditional Parameters
		 */
		$gridParams = array(
		'width' => 600,
		'height' => 271,
		'blockOpacity' => 0.5,
		'title' => $this->lang->line('pcvapp_flex_ttl'),
		'showTableToggleBtn' => false,
		'singleSelect' => true
		);
		
		$this->flexigrid->validate_post('pcv_id','asc');
		
		$records = $this->Tbl_pcv->pcv_list();
		if ($records['record_count'] == 0){
			$data['kosong'] = "Belum ada daftar petty cash untuk di proses";
		}else{
		//Build js
		//View helpers/flexigrid_helper.php for more information about the params on this function
		$grid_js = build_grid_js('flex1',site_url(self::$link_controller."/flexigrid_ajax"),$colModel,'pcv_id','desc',$gridParams);
		$data['js_grid'] = $grid_js;
		$data['kosong'] = "";
		}
		$data['content'] = self::$link_view.'/index_pcv_view';
		$this->load->view('index',$data);
	}
	
	function open_pcv($id){
		$data['get_pcv'] =  $this->Tbl_pcv->pcv_dtl_content($id);
		$this->load->view(self::$link_view.'/pcv_app_view',$data);
	}
	
	function app_pcv(){
		$note = $this->input->post('note');
		$pcvid = $this->input->post('pcvid');
		$proid = $this->input->post('proid');
		$proname = $this->input->post('proname');
		$status = $this->input->post('status');
		$error = '';
		
		
		for($i=0;$i<sizeof($proid);$i++) {
			if ($status[$i] == 0){
				$error[] = "status <b>".$proname[$i]."</b> ".$this->lang->line('pcvapp_form_error')."<br/>";
			}
		}	
			
		if ($error){
			echo "Error : <br/>".implode($error);
		}else{		
			//echo $pcvid."-".$proid[$i]."-".$note[$i]."<br/>";
			for($i=0;$i<sizeof($proid);$i++) {
				$this->Tbl_pr->update_pcvstat($pcvid, $proid[$i], $status[$i], $note[$i]);
				$hist = $this->Tbl_pr->get_detail($pcvid, $proid[$i])->row();
				$this->Tbl_pr->update_pcvstat_history($hist->pro_id, $hist->pr_id, $hist->buy_via, $hist->pty_id, $hist->proj_no, $hist->emergencyStat, 
				$hist->qty, $hist->qty_terima, $hist->um_id, $hist->delivery_date, $hist->description, $hist->num_supplier, $hist->sup_id,
				$hist->cur_id, $hist->price, $hist->term, $hist->rfq_delivery_date, $hist->rfq_id, $hist->requestStat, 
				$hist->rfq_stat, $hist->pcv_id, $hist->pcv_stat, $hist->pcv_note);
			}
			$total = $this->Tbl_pcv->get_total($pcvid)->row();
			$stat = '2';
			$this->Tbl_pcv->update_pcv($pcvid, $total->total, $stat);
			echo "ok";
		}
	}
}
?>