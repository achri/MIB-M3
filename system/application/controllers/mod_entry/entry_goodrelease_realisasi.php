<?php
class Entry_goodrelease_realisasi extends MY_Controller{
	public static $link_view, $link_controller, $ppn_status;
	function Entry_goodrelease_realisasi()
	{
		parent::MY_Controller();
		$this->load->model(array('Tbl_goodrelease','Tbl_user', 'Tbl_mr','Tbl_inventory', 'Tbl_produk', 'Tbl_satuan_pro', 'tbl_counter','tbl_pr'));
		$this->load->helper('flexigrid');
		
		$this->lang->load('general','bahasa');
		
		self::$link_controller = 'mod_entry/entry_goodrelease_realisasi';
		self::$link_view = 'purchase/mod_entry/mod_product/mod_goodrelease_realisasi';
		$data['link_controller'] = self::$link_controller;
		$data['link_view'] = self::$link_view;
				
		$this->load->vars($data);
	}
	
	function index(){
		$data['extraHeadContent'] = "<link type=\"text/css\" rel=\"stylesheet\" href=\"". base_url()."asset/css/themes/start/ui.all.css\" />\n";
		$data['extraHeadContent'] .= "<script type=\"text/javascript\" src=\"". base_url()."asset/javascript/jQuery/tables/jquery.jeditable.js\" />\n </script>";
		$data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"".base_url()."asset/css/table/DataView.css\" />\n";
		$data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"".base_url()."asset/css/form/form_view.css\" />\n";
		$data['content'] = self::$link_view.'/index_grl_realisasi_view';
		$data['list_grlno'] = $this->Tbl_goodrelease->list_grl_realisasi();
		
		$this->load->view('index',$data);
	}
	
	function open_realisasi(){
		$id = $this->input->post('grl');
		$data['grl_content'] = $this->Tbl_goodrelease->get_content_realisasi ($id);
		$data['grl_id'] = $id;
		$this->load->view(self::$link_view.'/grl_realisasi_app_view',$data);
	}
	
	function cek_stok($pro_id,$sup_id = 0) {
	
	}
	
	function gr_adda() {
		$usrid = $this->session->userdata('usr_id');
		$grlno = $this->input->post('grl_no');
		$mr = $this->input->post('mr_id');
		$grl = $this->input->post('grl_id');
		$id = $this->input->post('id');
		
		for($i=0;$i<sizeof($id);$i++) {
			
			$proname[$i] = $this->input->post('proname_'.$id[$i]);
			$qty[$i] = $this->input->post('qty_'.$id[$i]);
			$alasan[$i] = $this->input->post('alasan_'.$id[$i]);
			$proid[$i] = $this->input->post('proid_'.$id[$i]);
			$sup[$i] = $this->input->post('sup_'.$id[$i]);
			$sat[$i] = $this->input->post('sat_'.$id[$i]);
			$real[$i] = $this->input->post('jml_'.$id[$i]);
			
			echo $i.'|'.$sup[$i].'|'.$proid[$i].'|'.$proname[$i].'|'.$qty[$i].'|'.$real[$i].'|'.$alasan[$i];
		}
		echo '<br>'.$usrid.'|'.$grlno.'|'.$mr.'|'.$grl.'|'.$id;
	}
	
	function gr_add(){
		$usrid = $this->session->userdata('usr_id');
		$grlno = $this->input->post('grl_no');
		$mr = $this->input->post('mr_id');
		$grl = $this->input->post('grl_id');
		$id = $this->input->post('id');
		
		for($i=0;$i<sizeof($id);$i++) {
			$qty[$i] = $this->input->post('qty_'.$id[$i]);
			$real[$i] = $this->input->post('jml_'.$id[$i]);
			$alasan[$i] = $this->input->post('alasan_'.$id[$i]);
			$proname[$i] = $this->input->post('proname_'.$id[$i]);
			$cek[$i] = $qty[$i] - $real[$i];
			$now = date('Y-n-d');
			$time = explode("-",$now);
			$res='';
			
			if ($real[$i] == ''){
				$res[$i] = $this->lang->line('gr_error_form_1');	
				echo $id[$i].". ".$proname[$i]." - ".$res[$i]."<br/>";
				exit();
			}else{	
				if ($cek[$i] > 0 and $alasan[$i]== '') {
					$res[$i] = $this->lang->line('gr_error_form_2');
					echo $id[$i].". ".$proname[$i]." - ".$res[$i]."<br/>
					<br/><textarea cols='40' rows='4' id='reason_".$id[$i]."'></textarea>
					<br/><input type='button' value='OK' onclick='set_alasan(".$id[$i].")'>
					<input type='button' value='Cancel' onclick='closedialog()'>";
					exit();
				}else if ($cek[$i] < 0){
					$res[$i] = $this->lang->line('gr_error_form_3');
					echo $id[$i].". ".$proname[$i]." - ".$res[$i]."<br/>";
					exit();
				}
			}	
		}
		if ($res == ''){
			$this->Tbl_goodrelease->update_grl_head($grl, $usrid);
			for($i=0;$i<sizeof($id);$i++) {
				$qty[$i] = $this->input->post('qty_'.$id[$i]); // QTY PERMINTAAN
				$alasan[$i] = $this->input->post('alasan_'.$id[$i]);
				$proid[$i] = $this->input->post('proid_'.$id[$i]);
				$sup[$i] = $this->input->post('sup_'.$id[$i]);
				$sat[$i] = $this->input->post('sat_'.$id[$i]);
				$real[$i] = $this->input->post('jml_'.$id[$i]); // QTY REALISASI
				
				//cek satuan
				$ceksat = $this->Tbl_produk->pro_get_sat($proid[$i])->row();
				if ($ceksat->um_id != $sat[$i]){
					$satconf = $this->Tbl_satuan_pro->cek_satuan($proid[$i], $sat[$i])->row();
					$totalconfert = $real[$i] * $satconf->value;
				}else{
					$totalconfert = $real[$i];
				}
				
				//insert ke mr / mr history
				$this->Tbl_mr->add_grl_dtl($grl, $mr, $proid[$i], $real[$i], $alasan[$i]);
				$this->Tbl_mr->update_grl_history($grl, $mr, $proid[$i], $sup[$i], $sat[$i], $real[$i] , $qty[$i], $alasan[$i], $usrid);
			
				//update invventory / inventory history
				//if ($sup[$i] == '0'){
					$cek = $this->Tbl_inventory->cek_stok($proid[$i], $sup[$i])->row();
					$begin = $cek->inv_end;
					$invid = $cek->inv_id;
					$nextend = $begin - $totalconfert;
					
					// BALANCE PRICE
					$inv_price = $cek->inv_price;
					$bal_price = $inv_price * $nextend;
					
					//echo "ADD_GRL = $grl, $mr, $proid[$i], $real[$i], $alasan[$i]"."<BR>";
					//echo "UPDATE_GRL_HIST = $grl, $mr, $proid[$i], $sup[$i], $sat[$i], $real[$i] , $qty[$i], $alasan[$i], $usrid"."<BR>";
					
					//echo "UPDATE_STOK = $invid, $begin, $nextend, $totalconfert, $grlno, $inv_price, $bal_price"."<BR>";
					//echo "UPDATE_STOK_HIST = $proid[$i], $begin, $nextend, $totalconfert, $invid, $grlno, $inv_price, $bal_price, $sup[$i]"."<BR>";
					//$proid[$i]
					$this->Tbl_inventory->update_stok($invid, $begin, $nextend, $totalconfert, $grlno, $inv_price, $bal_price);
					$this->Tbl_inventory->update_stok_history($proid[$i], $begin, $nextend, $totalconfert, $invid, $grlno, $inv_price, $bal_price, $sup[$i]);
					/*
				}else{
					$ceks = $this->Tbl_inventory->cek_stok($proid[$i], $sup[$i])->row();
					$begin = $ceks->inv_end;
					$invid = $ceks->inv_id;
					$nextend = $begin - $totalconfert;
					
					// BALANCE PRICE
					$inv_price = $ceks->inv_price;
					$bal_price = $inv_price * $nextend;
					
					$this->Tbl_inventory->update_stok($invid, $begin, $nextend, $totalconfert, $grlno, $inv_price, $bal_price);
					$this->Tbl_inventory->update_stok_history($proid[$i], $begin, $nextend, $totalconfert, $invid, $grlno, $inv_price, $bal_price);
				}*/
				
				//=================================== auto PR ====================================
				
					$cekreorder = $this->Tbl_goodrelease->cek_reorder($proid[$i])->row();
					if ($cekreorder->pro_is_reorder = 1 && $nextend < $cekreorder->pro_min_reorder){
							$timecode = date('y-m-d');
							$timecode = explode ("-", $timecode);
							$counter = $this->tbl_counter->cek_counter($time[0], $time[1])->row();
							$dtl_code = str_pad($counter->pr_no, 4, "0", STR_PAD_LEFT);
							$head_code = $timecode[0].'/'.$timecode[1];
							$code = $head_code.'/'.$dtl_code;
							$maxstok = $cekreorder->pro_min_reorder * 2;
							
							$autoid = $this->tbl_pr->auto_pr($code);
							$this->tbl_pr->auto_pr_det($autoid, $maxstok, $proid[$i], $sat[$i]);			
					}
					
				//=================================================================================
					
			}
			echo "Proses keluar barang oleh gudang berhasil<br/><br/>
				 <input type='button' value='OK' onclick='batal()'>";
		}
	}
}
?>