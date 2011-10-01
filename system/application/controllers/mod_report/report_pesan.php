<?php
class report_pesan extends MY_Controller {
	private static $link_controller, $link_view, $user_id,
	
	
		// =========== inisialisasi variabel bwt filter baru ========
		
		
		$cari_bulan,$cari_tahun, $cari_pemesan,$cari_status,$cari_no_po,$cari_pemasok,
		$cari_no_sj, $cari_alasan,$cari_nama_barang,$cari_kode_barang
		
		 ;
		
	// ========== akhir inisialisasi bwt filter ======	

	
	
	function report_pesan() {
		parent::MY_Controller();
		
		$this->load->model(array('tbl_po','tbl_user'));
		$this->load->library(array('pro_code','pagina_lib','general'));
		$this->config->load('tables');
		
	// =========== untuk bahasa ========================================		
		$this->lang->load('tables','bahasa');
		$this->lang->load('general','bahasa');
		$this->lang->load('label','bahasa');
		
		$this->lang->load('mod_report/laporan_umum','bahasa');
		$this->lang->load('mod_report/pemesanan_barang/laporan','bahasa');		
	// =========== (akhir) untuk bahasa ========================================				
		
		
	// ================== include (manggil) css & js (pake array) ====================		
		$arrayCSS = array (
		'asset/css/themes/start/ui.all.css',
		'asset/css/print_templates.css',
		'asset/css/table/DataView.css'
		);
		
		$arrayJS = array (
		'asset/javascript/jQuery/form/jquery.form.js'
		);
		
		$data['extraHeadContent'] = '';
		
		foreach ($arrayCSS as $css):
			$data['extraHeadContent'] .= '<link type="text/css" rel="stylesheet" href="'.base_url().$css.'"/>';
		endforeach;
		foreach ($arrayJS as $js):
			$data['extraHeadContent'] .= '<script type="text/javascript" src="'.base_url().$js.'"/></script>';
		endforeach;
		
	// ================== (akhir) include (manggil) css & js (pake array) ====================		

		// =========== inisialisasi ========
			self::$cari_status = 0; 	
			self::$cari_bulan = 0; 
			self::$cari_tahun = 0; 			
			self::$cari_pemasok = 0; 		
			self::$cari_pemesan =''; 	
			self::$cari_no_po =''; 	
			self::$cari_no_sj='';
			self::$cari_alasan='';
			self::$cari_nama_barang='';
			self::$cari_kode_barang='';
	
		// ========== akhir inisialisasi ======

		// ============ masukin variabil yg diisi / yg dikirim dari view =======
		if ($this->input->post("cari_tahun"))
			self::$cari_tahun  = $this->input->post("cari_tahun");
		if ($this->input->post("cari_bulan"))
			self::$cari_bulan  = $this->input->post("cari_bulan");
		if ($this->input->post("cari_pemasok"))
			self::$cari_pemasok  = $this->input->post("cari_pemasok");
		if ($this->input->post("cari_pemesan"))
			self::$cari_pemesan = $this->input->post("cari_pemesan");
		if ($this->input->post("cari_no_po"))
			self::$cari_no_po  = $this->input->post("cari_no_po");
		if ($this->input->post("cari_no_sj"))
			self::$cari_no_sj  = $this->input->post("cari_no_sj");
			

		if ($this->input->post("cari_alasan"))
			self::$cari_alasan= $this->input->post("cari_alasan");
		if ($this->input->post("cari_nama_barang"))
			self::$cari_nama_barang = $this->input->post("cari_nama_barang");
		if ($this->input->post("cari_kode_barang"))
			self::$cari_kode_barang= $this->input->post("cari_kode_barang");


		if ($this->input->post("cari"))
			self::$cari_status  = $this->input->post("cari");


		// ============ (akhir) masukin variabil yg diisi / yg dikirim dari view =======


		// ============= link untuk manggil viewnya ===================
		self::$link_controller = 'mod_report/report_pesan';
		self::$link_view = 'purchase/mod_report/pesan_rep';
		$data['link_controller'] = self::$link_controller;
		$data['link_view'] = self::$link_view;
		$data['title_page'] = $this->lang->line('lap_judul');
		// ============= akhir link untuk manggil viewnya ===================
				
		$this->load->vars($data); // ngirim variabel data ke view
	}
	function index() {
	
		if (self::$cari_status != '')
		{
			$this->pemesanan('index');
		}else {
			$this->bwt_view('index');
		}
		
		
	}
			// bwt exkport ke excelnya
	function excel() {
		$this->pemesanan('excel');
	}

	function bwt_view($menu)
	{
	
		$data['cari_status']=self::$cari_status;
	
		$data['cari_tahun']=self::$cari_tahun;
		$data['cari_bulan']=self::$cari_bulan;


		$data['cari_pemesan']=self::$cari_pemesan;
		$data['cari_no_po']=self::$cari_no_po;
		$data['cari_pemasok']=self::$cari_pemasok;
		$data['cari_no_sj']=self::$cari_no_sj;
		
		$data['cari_alasan']=self::$cari_alasan;
		$data['cari_nama_barang']=self::$cari_nama_barang;
		$data['cari_kode_barang']=self::$cari_kode_barang;

		
		$data["nama_bulan"] =$this->general->combo_box('nama_bulan');
		$data["data_bulan"] =$this->general->combo_box('nama_bulan');
		$data["data_tahun"] =$this->general->combo_box('tahun');
		$data["data_pemasok"] =$this->general->combo_box('pemasok');

		if ($menu == 'index'){
			$data["content"] = self::$link_view.'/report_pesan_main';
		$this->load->view('index',$data);
		} else 		if ($menu == 'excel'){
				$this->load->view(self::$link_view.'/report_pesan_excel',$data);

		}
	}

	
	function pemesanan($menu) {
		// inisialisai				
		$cari_status=self::$cari_status; 
		$cari_bulan=self::$cari_bulan; 
		$cari_tahun=self::$cari_tahun; 
		$cari_pemasok=self::$cari_pemasok; 
		$cari_pemesan=self::$cari_pemesan; 
		$cari_no_po=self::$cari_no_po; 
		$cari_no_sj=self::$cari_no_sj; 
		$cari_alasan=self::$cari_alasan; 
		$cari_nama_barang=self::$cari_nama_barang; 
		$cari_kode_barang=self::$cari_kode_barang; 
		// (akhir) inisialisai						
		
		$sql	 = "select pd.pcv_id, pd.po_id, pd.qty, pd.description, pd.pro_id,
				   date_format(pr.pr_date,'%d-%m-%Y') as pr_date, usr.usr_name, um.satuan_name, pd.um_id, po.po_no, pc.pcv_no, pro.pro_name, pro.pro_code, gr.gr_suratJalan
				   from prc_pr_detail as pd
				   inner join prc_pr as pr on pr.pr_id = pd.pr_id
				   left join prc_po as po on pd.po_id = po.po_id
				   left join prc_pcv as pc on pc.pcv_id = pd.pcv_id
				   inner join prc_master_product as pro on pro.pro_id = pd.pro_id
				   inner join prc_master_satuan as um on um.satuan_id = pd.um_id
				   inner join prc_sys_user as usr on usr.usr_id = pr.pr_requestor
				   inner join prc_gr as gr on gr.po_id = po.po_id
				   where pd.requestStat=1";
		
		if($cari_tahun!= 0)
			$sql .= " and year(pr.pr_date)='$cari_tahun'";
		if($cari_bulan!= 0)
			$sql .= " and month(pr.pr_date) = '$cari_bulan'";
			
		if($cari_no_sj != '')
			$sql .= " and gr.gr_suratJalan LIKE '%$cari_no_sj%'";			
		if($cari_alasan != '')
			$sql .= " and pd.description LIKE '%$cari_alasan%'";			
		if($cari_nama_barang != '')
			$sql .= " and pro.pro_name LIKE '%$cari_nama_barang%'";			
		if($cari_kode_barang != '')
			$sql .= " and pro.pro_code LIKE '%$cari_kode_barang%'";		
		if($cari_pemesan != '')
			$sql .= " and usr.usr_name LIKE '%$cari_pemesan%'";				
		if($cari_no_po != '')
			$sql .= " and po.po_no LIKE '%$cari_no_po%' or pc.pcv_no LIKE '%$cari_no_po%'";						


	
		$jumlah_data=$this->hitung_banyak_data($sql); //hitung jumlah data
		
		// PAGINATION
	//	$limit = $this->config->item('limit_report');
	//	$paging = $this->pagina_lib->pagina(self::$link_controller.'/index/'.$search_month.'/'.$search_year,$sql,$limit,$uri_segment = 6);
			
		//$arr_pcv = $paging['result'];
		//$data['no_pos'] = $paging['pagina_pos'];
		
		
		$arr_pcv = $this->db->query($sql);
		$data['no_pos'] = 0;
	
		$arr_bpb = array();
		$i = 0;
		
		foreach ($arr_pcv->result() as $arr) {
			$arr_bpb[$i]['pr_date']			=  $arr->pr_date; 
			$arr_bpb[$i]['po_id']			=  $arr->po_id;
			$arr_bpb[$i]['pcv_id']			=  $arr->pcv_id;
			$arr_bpb[$i]['po_no']			=  $arr->po_no;
			$arr_bpb[$i]['pcv_no']			=  $arr->pcv_no;
			$arr_bpb[$i]['usr_name']		=  $arr->usr_name;
			$arr_bpb[$i]['pro_name']		=  $arr->pro_name;
			$arr_bpb[$i]['um_id']			=  $arr->um_id;

			$arr_bpb[$i]['pro_code']		=  $arr->pro_code;
			$arr_bpb[$i]['description']		=  $arr->description;
			$arr_bpb[$i]['gr_suratJalan']	=  $arr->gr_suratJalan;
			$arr_bpb[$i]['qty']				=  $arr->qty;
			$arr_bpb[$i]['satuan_name']			=  $arr->satuan_name;
			if($arr->po_id != 0) {
				$str_po = $this->get_gr($arr->po_id, $arr->pro_id);
				$arr_po = explode(";", $str_po);
				if (!empty($arr_po[0]) && !empty($arr_po[1])){
				$arr_bpb[$i]['rec_date']	=  $arr_po[0];
				$arr_bpb[$i]['rec_sj']		=  $arr_po[1];
				$arr_bpb[$i]['rec_qty']		=  $arr_po[2];
				}else{
				$arr_bpb[$i]['rec_date']	=  '';
				$arr_bpb[$i]['rec_sj']		= '';
				$arr_bpb[$i]['rec_qty']		=  '';
				
				}
				
			}
			else if($arr->pcv_id != 0) {
				$str_pcv  = $this->get_pcv($arr->pcv_id, $arr->pro_id);
				$arr_pcv  = explode(";", $str_pcv);
				$arr_bpb[$i]['rec_date']	=  $arr_pcv[0];
				$arr_bpb[$i]['rec_sj']		=  $arr_pcv[1];
				$arr_bpb[$i]['rec_qty']		=  $arr_pcv[2];
			}
			$i++;
		}
		
		$data["jumlah_data"] =$this->general->hitung_banyak_data($sql);  // bwt ngitung banyak nya data		
		$data["data_bpb"] = $arr_bpb;
		$data['data_bpbs'] = $arr_pcv;		
		
		
		$this->load->vars($data); // ngirim variabel data ke view
		$this->bwt_view($menu); // bwt ngirim ke menu
		
		
	}
	
	
	function get_gr($po_id, $pro_id) {
		//global $db;
		$sql = "select date_format(gr.gr_date,'%d-%m-%Y') as gr_date, gr.gr_suratJalan, gd.qty
				from prc_gr_detail as gd
				inner join prc_gr as gr on gr.gr_id = gd.gr_id
				where gr.gr_type='rec' and gr.po_id='$po_id' and gd.pro_id='$pro_id'";
		$rs_po = $this->db->query($sql);
		if($rs_po->num_rows() > 0) {
			$arr_po = array($rs_po->row()->gr_date,$rs_po->row()->gr_suratJalan,$rs_po->row()->qty);
			$str_ret_po = implode(";", $arr_po);
			return $str_ret_po;
		}
	}
	
	function get_pcv($pcv_id, $pro_id) {
		//global $db;
		$sql = "select date_format(pc.pcv_date,'%d-%m-%Y') as pcv_date, rec.qty
				from prc_pcv_receive as rec
				inner join prc_pcv as pc on pc.pcv_id = rec.pcv_id
				where pc.pcv_id='$pcv_id' and rec.pro_id='$pro_id'";
		$rs_pcv = $this->db->query($sql);
		if($rs_pcv->num_rows() > 0) {
			$arr_pcv = array($rs_pcv->row()->pcv_date,'-',$rs_pcv->row()->qty);
			$str_ret_pcv = implode(";", $arr_pcv);
			return $str_ret_pcv;
		}
		
	}

		// bwt ngitung banyaknya datas
	function hitung_banyak_data($sql)
	{
		$query_hitung=$this->db->query($sql);
		$jum_data = 0;
		foreach ($query_hitung->result() as $rows){
			$jum_data++;		
		}	
		return $jum_data;
	}





}
?>