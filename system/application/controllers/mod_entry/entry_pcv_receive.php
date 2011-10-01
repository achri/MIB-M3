<?php
class Entry_pcv_receive extends MY_Controller{
	public static $link_controller,$link_view;
	function Entry_pcv_receive()
	{
		parent::MY_Controller();
		$this->load->model(array('Tbl_pcv', 'Tbl_inventory', 'Tbl_pcv_receive', 'Tbl_satuan_pro', 'Tbl_produk'));
		$this->load->helper('flexigrid');
		
		$this->lang->load('general','bahasa');
		
		// LINK CONTROLLER DAN VIEW
		self::$link_controller = 'mod_entry/entry_pcv_receive';
		self::$link_view = 'purchase/mod_entry/mod_product/mod_pcv_receive';
		$data['link_controller'] = self::$link_controller;
		$data['link_view'] = self::$link_view;
		
		$this->load->vars($data);
	}
	
	function index(){
		$data['extraHeadContent'] = "<link type=\"text/css\" rel=\"stylesheet\" href=\"". base_url()."asset/css/themes/start/ui.all.css\" />\n";
		$data['extraHeadContent'] .= "<script type=\"text/javascript\" src=\"". base_url()."asset/javascript/jQuery/tables/jquery.jeditable.js\" />\n </script>";
		$data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"".base_url()."asset/css/table/DataView.css\" />\n";
		$data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"".base_url()."asset/css/form/form_view.css\" />\n";
		$data['content'] = self::$link_view.'/index_receive_pcv_view';
		$data['list_pcv'] = $this->Tbl_pcv->list_receive_pcv();
		
		$this->load->view('index',$data);
	}
	
	function open_receive(){
		$id = $this->input->post('pcv');
		$data['get_receive'] = $this->Tbl_pcv->receive_detail($id);
		$this->load->view(self::$link_view.'/receive_pcv_view',$data);
	}
	
	function receive_add(){
		$usrid = $this->session->userdata('usr_id');
		$pcvid = $this->input->post('pcvid');
		$pcvno = $this->input->post('pcvno');
		$proid = $this->input->post('proid');
		$proname = $this->input->post('proname');
		$sat = $this->input->post('satuan');
		$jml = $this->input->post('jml');
		//$sup = 0;
		$sup = $this->input->post('sup_id');
		$error = '';
		
		for($i=0;$i<sizeof($proid);$i++) {
			if ($jml[$i] == ''){
				$error[] = "- jumlah <b>".$proname[$i]."</b> belum diisi <br/>";
			}
		}
		
		if ($error){
			echo "Error : <br/>".implode($error);
		}else{
			/*
			for($i=0;$i<sizeof($proid);$i++) {
				echo $i.'|'.$proid[$i].'|'.$proname[$i].'|'.$sat[$i].'|'.$sup[$i].'|'.$jml[$i].'<br>';
			}
			*/
			
			$this->Tbl_pcv->update_pcvstat($pcvid, $usrid);
			for($i=0;$i<sizeof($proid);$i++) {
				$ceksat = $this->Tbl_produk->pro_get_sat($proid[$i])->row();
				//$sup = $this->input->post('supplier_'.$proid[$i]); //penambahan supplier
				if ($ceksat->um_id != $sat[$i]){
					$satconf = $this->Tbl_satuan_pro->cek_satuan($proid[$i], $sat[$i])->row();
					$totalconfert = $jml[$i] * $satconf->value;
				}else{
					$totalconfert = $jml[$i];
				} 
				
				$chk = $this->Tbl_inventory->cek_stok($proid[$i],$sup[$i]);
				if ($chk->num_rows() > 0):
				
					$end = $chk->row();
					$TEnd = $end->inv_end + $totalconfert;
						
					//echo $proid[$i]."--".$sup.'<br/>';
					$this->Tbl_inventory->update_inv_retur( $end->inv_id, $end->inv_end, $TEnd, $totalconfert, $pcvno);
					$this->Tbl_inventory->update_inv_retur_history( $end->inv_id, $proid[$i], $sup[$i], $end->inv_end, $TEnd, $totalconfert, $pcvno);
				else:
					$ids = $this->Tbl_inventory->insert_inv_retur( $totalconfert, $TEnd, $totalconfert, $pcvno);
					$this->Tbl_inventory->insert_inv_retur_history( $ids, $proid[$i], $sup[$i], $totalconfert, $TEnd, $totalconfert, $pcvno);
				endif;
				$this->Tbl_pcv_receive->add_receive($pcvid, $proid[$i], $totalconfert);
			}
			echo "ok";
			
		}
		
	}
}
?>