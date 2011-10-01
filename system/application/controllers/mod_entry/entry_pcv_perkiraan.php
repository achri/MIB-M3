<?php
class Entry_pcv_perkiraan extends MY_Controller{
	public static $link_view, $link_controller, $ppn_status;
	function Entry_pcv_perkiraan(){
		parent::MY_Controller();
		$this->load->model(array('Tbl_pcv', 'Tbl_pr'));
		$this->load->helper('flexigrid');
		$this->load->library('flexigrid');
		$this->config->load('flexigrid');
		
		$this->lang->load('general','bahasa');
		
		self::$link_controller = 'mod_entry/entry_pcv_perkiraan';
		self::$link_view = 'purchase/mod_entry/mod_product/mod_pcv_perkiraan';
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
		$valid_fields = array('pcv_id', 'pcv_no', 'pr_no', 'pr_id', 'pr_date', 'jum_item');
		
		$this->flexigrid->validate_post('pcv_id','asc',$valid_fields);

		$records = $this->Tbl_pcv->get_pcv();
		
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
				$row->pr_date,
				$row->jum_item,
				'<a href=\'javascript:void(0)\' onclick=\'open_perkiraan('.$row->pcv_id.','.$row->pr_id.')\'><img border=\'0\' src=\'./asset/img_source/icon_info.gif\'></a>'
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
		
		//$colModel['no'] = array($this->lang->line('perkiraan_flex_col_0'),20,TRUE,'center',0);
		$colModel['pcv_no'] = array($this->lang->line('perkiraan_flex_col_1'),100,TRUE,'center',2);
		$colModel['pr_no'] = array($this->lang->line('perkiraan_flex_col_2'),100,TRUE,'center',2);
		$colModel['pr_date'] = array($this->lang->line('perkiraan_flex_col_3'),100,TRUE,'center',2);
		$colModel['jum_item'] = array($this->lang->line('perkiraan_flex_col_4'),60, TRUE,'center',0);
		$colModel['opsi'] = array($this->lang->line('perkiraan_flex_col_5'),30, TRUE,'center',0);
		/*
		 * Aditional Parameters
		 */
		$gridParams = array(
		'width' => '620',
		'height' => 271,
		'blockOpacity' => 0.5,
		'title' => $this->lang->line('perkiraan_flex_ttl'),
		'showTableToggleBtn' => false,
		'singleSelect' => true
		);
		
		$this->flexigrid->validate_post('pcv_id','asc');
		
		$records = $this->Tbl_pcv->get_pcv();
		if ($records['record_count'] == 0){
			$data['kosong'] = "Belum ada data Petty Cash untuk di proses";
		}else{
		//Build js
		//View helpers/flexigrid_helper.php for more information about the params on this function
		$grid_js = build_grid_js('flex1',site_url(self::$link_controller."/flexigrid_ajax"),$colModel,'id','asc',$gridParams);
		$data['js_grid'] = $grid_js;
		$data['kosong'] = "";
		}
		$data['content'] = self::$link_view.'/index_perkiraan_view';
		$this->load->view('index',$data);
	}
	
	function open_perkiraan($pcvid, $prid){
		$data['get_pcv'] =  $this->Tbl_pcv->pcv_content($pcvid, $prid);
		$this->load->view(self::$link_view.'/perkiraan_view',$data);
	}
	
	function add_perkiraan(){
		$proid = $this->input->post('proid');
		$proname = $this->input->post('proname');
		$prid = $this->input->post('prid');
		$pcvid = $this->input->post('pcvid');
		$harga = $this->input->post('harga');
		$qty = $this->input->post('qty');
		$cur = '1';
		$error = '';
		$total= '';
		
		for($i=0;$i<sizeof($proid);$i++) {
			if ($harga[$i] == '' ){
				$error[] = "- Produk ".$proname[$i]." ".$this->lang->line('perkiraan_error_value')."<br/>";
			}
		}
		
		if ($error){
			echo implode($error);
		}else{
			for($i=0;$i<sizeof($proid);$i++) {
				$subtotal = $qty[$i] * $harga[$i];
				$total = $total + $subtotal;
				$this->Tbl_pr->update_harga($pcvid, $prid, $proid[$i], $harga[$i], $cur);
			}
			$this->Tbl_pcv->update_status($pcvid, $total);
			echo "ok";
		}
	}
	
}
?>