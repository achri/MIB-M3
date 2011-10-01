<?php
class Rfq extends MY_Controller{
	
	function Rfq(){
		parent::MY_Controller();
		$this->load->model(array('Tbl_rfq','Tbl_purchase_type','Tbl_satuan','Tbl_currency','Tbl_term','Tbl_supplier','Tbl_legal','Tbl_sup_produk','Tbl_category','tbl_satuan_pro'));
		$this->load->helper('flexigrid');
		$this->load->library('flexigrid');
		$this->config->load('flexigrid');
		
		$this->lang->load('general','bahasa');
		
		$this->obj =& get_instance();
	}
	
	function index() {
		$data['extraHeadContent'] = "<link type=\"text/css\" rel=\"stylesheet\" href=\"". base_url()."asset/css/themes/start/ui.all.css\" />\n";
		$data['extraHeadContent'] .= "<script type=\"text/javascript\" src=\"". base_url()."asset/javascript/jQuery/tables/jquery.jeditable.js\" />\n </script>";
		$data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"".base_url()."asset/css/table/DataView.css\" />\n";
		$data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"".base_url()."asset/css/form/form_view.css\" />\n";
		$data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"".base_url()."asset/javascript/jQuery/flexigrid/css/flexigrid.css\" />\n";
		$data['extraHeadContent'] .= "<script type=\"text/javascript\" src=\"". base_url()."asset/javascript/jQuery/flexigrid/js/flexigrid.js\" />\n </script>";
		
		//$colModel['no'] = array($this->lang->line('rfq_flex_col_0'),20,TRUE,'center',0);
		$colModel['rfq_no'] = array($this->lang->line('rfq_flex_col_1'),90,TRUE,'center',2);
		$colModel['rfq_date'] = array($this->lang->line('rfq_flex_col_2'),80,TRUE,'center',2);
		$colModel['rfq_printDate'] = array($this->lang->line('rfq_flex_col_3'),80,TRUE,'center',2);
		$colModel['item_number'] = array($this->lang->line('rfq_flex_col_4'),60, TRUE,'center',0);
		$colModel['item_waiting'] = array($this->lang->line('rfq_flex_col_5'),60, TRUE,'center',0);
		$colModel['item_pending'] = array($this->lang->line('rfq_flex_col_6'),60, TRUE,'center',0);
		$colModel['item_reject'] = array($this->lang->line('rfq_flex_col_7'),60, TRUE,'center',0);
		$colModel['item_ok'] = array($this->lang->line('rfq_flex_col_8'),60, TRUE,'center',0);
		$colModel['opsi'] = array($this->lang->line('pr_flex_col_11'),60, TRUE,'center',0);
		/*
		 * Aditional Parameters
		 */
		$gridParams = array(
		'width' => 'auto',
		'height' => 271,
		'blockOpacity' => 0.5,
		'title' => $this->lang->line('rfq_flex_ttl'),
		'showTableToggleBtn' => false,
		'singleSelect' => true
		);
		
		$this->flexigrid->validate_post('rfq_id','asc');
		
		$records = $this->Tbl_rfq->rfq_list();
		if ($records['record_count'] == 0){
			$data['kosong'] = "Belum ada data RFQ untuk Diproses";
		}else{
		//Build js
		//View helpers/flexigrid_helper.php for more information about the params on this function
		$grid_js = build_grid_js('flex1',site_url("/rfqflexigrid"),$colModel,'id','asc',$gridParams);
		$data['message'] = $this->lang->line('contact_confirm_del');
		$data['js_grid'] = $grid_js;
		$data['kosong'] = "";
		}
		$data['content'] = 'rfq/rfq';
		$this->load->view('index',$data);
	
	}
	
	function open_rfq($id){
		$data['get_rfq'] =  $this->Tbl_rfq->rfq_content($id);
		$data['list_sat'] = $this->Tbl_satuan->list_satuan();
		$data['list_cur'] = $this->Tbl_currency->list_currency();
		$data['list_term'] = $this->Tbl_term->list_term();
		//$data['list_sup'] = $this->Tbl_supplier->list_supp();
		$this->load->view('rfq/rfq_app_cek',$data);
	}
	
	function get_term($id){
		$term = $this->Tbl_term->get_term($id)->row();
		echo $term->term_days;
	}
	
	function get_sup($pro_id,$cat_code){
		$data['suppro'] = $this->Tbl_sup_produk->pro_supp_cat($pro_id,$cat_code);
		$this->load->view('rfq/pro_supp',$data);
	}
	
	function rfq_add(){
		$usrid = $this->obj->session->userdata('usr_id');
		$pro = $this->input->post('pro_id');
		$triger = 0;
		
		//chek zero value......
		for($i=0;$i<sizeof($pro);$i++) {
			$status[$i] = $this->input->post('status_'.$pro[$i]);
			if ($status[$i] == '0' ){
				$no = $i + 1;
				echo "- status rfq ".$no." belum diisi <br/>";
				$triger = $triger + $no;
			}
		}
		if ($triger != 0){
			echo "<br/><div style='text-align : right;'><input type='button' value='ok' onclick='closedialog()'></div>";
		}
		if ($triger == 0){
			for($i=0;$i<sizeof($pro);$i++) {
				$status[$i] = $this->input->post('status_'.$pro[$i]);
				$sup[$i] = $this->input->post('id_sup_'.$pro[$i]);
				$qty[$i] = $this->input->post('qty_'.$pro[$i]);
				$sat[$i] = $this->input->post('satuan_'.$pro[$i]);
				$deldate[$i] = $this->input->post('deldate_'.$pro[$i]);
				$harga[$i] = $this->input->post('harga_'.$pro[$i]);
				$rfq = $this->input->post('rfq_id');
				$pay[$i] = $this->input->post('pay_'.$pro[$i]);
				$proname[$i] = $this->input->post('pro_'.$pro[$i]);
				$kurs[$i] = $this->input->post('kurs_'.$pro[$i]);
				$hari[$i] = $this->input->post('hari_'.$pro[$i]);
				$disc[$i] = $this->input->post('disc_'.$pro[$i]);
				$cur[$i] = $this->input->post('cur_'.$pro[$i]);
				$pr[$i] = $this->input->post('pr_'.$pro[$i]);
				$err ='';
				if ($status[$i] == 1){
					if ($sup[$i] == ''){
						$err[] = $this->lang->line('rfq_error_sup')."<b>".$proname[$i]."</b>".$this->lang->line('rfq_error_require');
					}
					if ($qty[$i] == ''){
						$err[] = $this->lang->line('rfq_error_qty')."<b>".$proname[$i]."</b>".$this->lang->line('rfq_error_require');
					}
					if ($deldate[$i] == ''){
						$err[] = $this->lang->line('rfq_error_delivery')."<b>".$proname[$i]."</b>".$this->lang->line('rfq_error_require');
					}
					if ($harga[$i] == ''){
						$err[] = $this->lang->line('rfq_error_harga')."<b>".$proname[$i]."</b>".$this->lang->line('rfq_error_require');
					}
					if ($pay[$i] == ''){
						$err[] = $this->lang->line('rfq_error_pay')."<b>".$proname[$i]."</b>".$this->lang->line('rfq_error_require');
					}
					if ($err){
						echo "Error : <br/> - ".implode("<br/> - ",$err);
						exit;
					}else{
					//Disetujui
					$result[] = $this->Tbl_rfq->rfq_insert_1($rfq, $pr[$i], $pro[$i], $status[$i], $sup[$i], $qty[$i], $sat[$i], $deldate[$i], $harga[$i], $pay[$i], $proname[$i], $kurs[$i], $disc[$i], $cur[$i]);
					}
				} else {
					//Diubah dan disetujui
					$result[] = $this->Tbl_rfq->rfq_insert_2_3($rfq, $pr[$i], $pro[$i], $status[$i], $proname[$i]);
				} 
			}
			if ($result){
				for($i=0;$i<sizeof($result);$i++) {
					echo $this->lang->line('rfq_succes_alert').$result[$i]."<br/>";
				}
				echo "<br/><div style='text-align : right;'><input type='button' value='ok' onclick='batal()'></div>";
			}
		}
	}
	
}
?>