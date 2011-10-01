<?php
class report_pcv extends MY_Controller {
	private static $link_controller, $link_view, $user_id,
	
	$cari_status,$cari_bulan,$cari_tahun, $cari_status_pcv, $cari_dicetak_oleh,
	$cari_pcv_no 

	;
	function report_pcv() {
		parent::MY_Controller();
		
		$this->load->model(array('tbl_po','tbl_user'));
		$this->load->library('pro_code');
		$this->load->library(array('session','pagina_lib','general'));
		$this->config->load('tables');
		
		// LANGUAGE
		$this->lang->load('label','bahasa');
		$this->lang->load('tables','bahasa');
		$this->lang->load('general','bahasa');
		
		$this->lang->load('mod_report/laporan_umum','bahasa');
		$this->lang->load('mod_report/kas_kecil/laporan','bahasa');
				
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
		
	// =========== inisialisasi ========
		self::$cari_status='';
		self::$cari_tahun=0;
		self::$cari_bulan=0;
		
		self::$cari_status_pcv=0;
		self::$cari_dicetak_oleh='';
		self::$cari_pcv_no='';		
		
	// =========== (akhir) inisialisasi ========
		
		// ============ masukin variabil yg diisi / yg dikirim dari view =======
		if ($this->input->post("cari_tahun"))
			self::$cari_tahun  = $this->input->post("cari_tahun");
		if ($this->input->post("cari_bulan"))
			self::$cari_bulan  = $this->input->post("cari_bulan");

		if ($this->input->post("cari_pcv_no"))
			self::$cari_pcv_no= $this->input->post("cari_pcv_no");
		if ($this->input->post("cari_dicetak_oleh"))
			self::$cari_dicetak_oleh= $this->input->post("cari_dicetak_oleh");
		if ($this->input->post("cari_status_pcv"))
			self::$cari_status_pcv= $this->input->post("cari_status_pcv");


		if ($this->input->post("cari"))
			self::$cari_status  = $this->input->post("cari");
		// ============ (akhir) masukin variabil yg diisi / yg dikirim dari view =======
		
		
		self::$link_controller = 'mod_report/report_pcv';
		self::$link_view = 'purchase/mod_report/pcv_rep';
		$data['link_controller'] = self::$link_controller;
		$data['link_view'] = self::$link_view;
		
		$data['title_page'] = $this->lang->line('lap_judul');
		
		$this->load->vars($data);
	}
	
	function index() {	
		
		if (self::$cari_status != ''){
			$this->get_report('index');
		} else {		
			$this->bwt_view('index');
		}		
		
	}
	
	// bwt export ke excel
	function excel() {
		$this->get_report('excel');
	}
	
	function bwt_view($menu)
	{
	
		$data['cari_status']=self::$cari_status;
	
		$data['cari_tahun']=self::$cari_tahun;
		$data['cari_bulan']=self::$cari_bulan;

		$data['cari_pcv_no']=self::$cari_pcv_no;
		$data['cari_status_pcv']=self::$cari_status_pcv;
		$data['cari_dicetak_oleh']=self::$cari_dicetak_oleh;

		$data["nama_bulan"] =$this->general->combo_box('nama_bulan');
		$data["data_bulan"] =$this->general->combo_box('nama_bulan');
		$data["data_tahun"] =$this->general->combo_box('tahun');
	
		if($menu == 'index')
		{
			$data["content"] = self::$link_view.'/report_pcv_main';
			$this->load->view('index',$data);
		} else if ($menu == 'excel')
		{
			$this->load->view(self::$link_view.'/report_pcv_excel',$data);
		}
	}
	
	function get_report($menu) {
		// ============ inisialisasi variabel =============
		$cari_bulan=self::$cari_bulan;
		$cari_tahun=self::$cari_tahun;
		
		$cari_status_pcv=self::$cari_status_pcv;
		$cari_dicetak_oleh=self::$cari_dicetak_oleh;
		$cari_pcv_no =self::$cari_pcv_no;		
		// ========= (akhir) inisialisasi variabel ============
		
		//-------------Category----------------------------------------------------------------------
		$i = 0;
		
		//----year----
		$sql	 = "SELECT pc.pcv_id, pc.pcv_no, date_format(pc.pcv_printDate,'%d-%m-%Y') as pcv_printDate, usr.usr_name,
					   date_format(pc.pcv_receiveDate,'%d-%m-%Y') as pcv_receiveDate, pcv_request, pcv_realisasi, pcv_status,
					   prd.pcv_note as ket_acc, prd.price_pre as harga_perkiraan, prd.price,
					   cur.cur_symbol, cur.cur_digit, prd.qty as permintaan_barang, prd.um_id as satuan_id,
					   sat.satuan_name, pcr.qty as realisasi_barang, pcr.price as realisasi_harga,
					   (pcr.qty * pcr.price) as realisasi_tot_harga
				   from prc_pcv as pc
				   inner join prc_sys_user as usr on usr.usr_id = pc.pcv_printUser
				   left join prc_pr_detail as prd on prd.pr_id = pc.pr_id
				   left join prc_master_currency as cur on cur.cur_id = prd.cur_id
				   left join prc_master_satuan as sat on sat.satuan_id = prd.um_id
				   left join prc_pcv_receive as pcr on pcr.pcv_id = pc.pcv_id
				   where pc.pcv_printStat='1'";
		if($cari_tahun != 0)
			$sql .= " and year(pc.pcv_printDate) = '$cari_tahun'";
		if($cari_bulan != 0)
			$sql .= " and month(pc.pcv_date) = '$cari_bulan'";
		
		if($cari_pcv_no != '')
			$sql .= " and pc.pcv_no like '%$cari_pcv_no%'";				
		
		if($cari_status_pcv != 0){
		//	$sql .= " and pc.pcv_status='2' or pc.pcv_status='5' or pc.pcv_status='6'";		
			 if($cari_status_pcv == 2){
				$sql .= " and pc.pcv_status='2'";				
			}else if($cari_status_pcv == 5){
				$sql .= " and pc.pcv_status='5'";				
			}else if($cari_status_pcv == 6){
				$sql .= " and pc.pcv_status='6'";				
			}
		}
		
		if($cari_dicetak_oleh != '')
			$sql .= " and usr.usr_name like '%$cari_dicetak_oleh%'";				

			
		$sql .= "order by pc.pcv_no";
		
		$arr_pcv = $this->db->query($sql);
		
	// =========== bwt pagingnya =================
		$limit = $this->config->item('limit_report');
		$paging = $this->pagina_lib->pagina(self::$link_controller.'/index/'.$cari_bulan.'/'.	$cari_tahun,$sql,$limit,$uri_segment = 6);
		// ============= akhir pagingnya ===========
			
		// ======= jumlah ===================
			$total_diminta = 0;
			$total_realisasi = 0;
			foreach($this->db->query($sql)-> result() as $rows)
			{
			
				$total_diminta = $total_diminta +$rows->pcv_request;
				$total_realisasi = $total_realisasi +$rows->realisasi_tot_harga;
			
			}
			$data['total_diminta']=$total_diminta;			
			$data['total_realisasi']=$total_realisasi;
	
		// ========== akhir jumlah =============
	
		$data["jumlah_data"] =$this->general->hitung_banyak_data($sql);  // bwt ngitung banyak nya data	
		$data["data_pcv"] = $arr_pcv; //tanpa pagiong
		$data['no_pos'] = 0;
		
	//	$data['data_pcv']= $paging['result']; // pake paging
		//$data['no_pos'] = $paging['pagina_pos']; // atrib paging
	
		$this->load->vars($data); // ngririm data yang ada di fungsi ini
		$this->bwt_view($menu); // bwt ngirim ke view		
		
	}
	
	

}
?>