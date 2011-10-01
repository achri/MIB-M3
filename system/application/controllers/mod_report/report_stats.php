<?php
class report_stats extends MY_Controller {
	private static $link_controller, $link_view, $user_id,

	// =========== inisialisasi variabel bwt filter baru ========
		$cari_no, $cari_tanggal,$cari_pemohon, 
		$cari_departemen, $cari_jumlah_barang,$cari_pesan_inbox,		
		
		// status barang
		$cari_disetujui, $cari_ditunda, $cari_ditolak, $cari_belum_diputuskan,
		
		$cari_status,$request_type,$cari_tahun,$cari_bulan

	;		
	// ========== akhir inisialisasi bwt filter ======	
		

	function report_stats() {
		parent::MY_Controller();
		
		$this->load->model(array('tbl_po','tbl_user'));
		$this->load->library(array('pro_code','pagina_lib','general'));
		$this->config->load('tables');
		
		// ============= untuk bahasanya =========================
		$this->lang->load('tables','bahasa');
		$this->lang->load('general','bahasa');
		$this->lang->load('label','bahasa');
		
		$this->lang->load('mod_report/laporan_umum','bahasa');
		$this->lang->load('mod_report/status_barang/laporan','bahasa');
		// ============= (akhir) untuk bahasanya =========================				
		
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

			self::$cari_no = ''; 
			self::$cari_tanggal = ''; 		
			self::$cari_pemohon =''; 		
			self::$cari_departemen = ''; 		
			self::$cari_jumlah_barang = ''; 		
			self::$cari_pesan_inbox = ''; 		
			
			self::$request_type='';
			
			self::$cari_status = ''; 		
			self::$cari_tahun = 0; 		
			self::$cari_bulan = 0; 								
			
		
			// status barang
			self::$cari_disetujui = ''; 		
			self::$cari_ditunda = ''; 		
			self::$cari_ditolak = ''; 
			self::$cari_belum_diputuskan = ''; 		
		// ========== akhir inisialisasi ======
		
		
		// === masukin variabil yg diisi =======
		if ($this->input->post("request_type"))
			self::$request_type = $this->input->post("request_type");
		if ($this->input->post("cari_no"))
			self::$cari_no = $this->input->post("cari_no");
		if ($this->input->post("cari_tanggal"))
			self::$cari_tanggal = $this->input->post("cari_tanggal");
		if ($this->input->post("cari_pemohon"))
			self::$cari_pemohon = $this->input->post("cari_pemohon");
		if ($this->input->post("cari_departemen"))
			self::$cari_departemen = $this->input->post("cari_departemen");
		if ($this->input->post("cari_no"))
			self::$cari_no = $this->input->post("cari_no");			
		
		if ($this->input->post("cari_jumlah_barang"))
			self::$cari_jumlah_barang = $this->input->post("cari_jumlah_barang");
		if ($this->input->post("cari_pesan_inbox"))
			self::$cari_pesan_inbox = $this->input->post("cari_pesan_inbox");
			
				// status barang
		if ($this->input->post("cari_disetujui"))
			self::$cari_disetujui = $this->input->post("cari_disetujui");
		if ($this->input->post("cari_ditunda"))
			self::$cari_ditunda = $this->input->post("cari_ditunda");
		if ($this->input->post("cari_ditolak"))
			self::$cari_ditolak = $this->input->post("cari_ditolak");
		if ($this->input->post("cari_belum_diputuskan"))
			self::$cari_belum_diputuskan = $this->input->post("cari_belum_diputuskan");


		if ($this->input->post("cari"))
			self::$cari_status = $this->input->post("cari");
		if ($this->input->post("cari_tahun"))
			self::$cari_tahun = $this->input->post("cari_tahun");
		if ($this->input->post("cari_bulan"))
			self::$cari_bulan = $this->input->post("cari_bulan");

		// ======== akhir masukin variabel =================
		
		// THIS SESSION USER LOGIN
		self::$user_id = $this->session->userdata("usr_id");
		$user_name	= $this->tbl_user->get_user(self::$user_id)->row()->usr_name;
		$data['usr_name'] = $user_name;

		// ============= link untuk manggil viewnya ===================
		self::$link_controller = 'mod_report/report_stats';
		self::$link_view = 'purchase/mod_report/stats_rep';
		$data['link_controller'] = self::$link_controller;
		$data['link_view'] = self::$link_view;
		// ============= (akhir) link untuk manggil viewnya ===================		
		$data['title_page'] = $this->lang->line("lap_judul");	

		$this->load->vars($data);
	}

	function index() {				
		if (self::$cari_status != ''){
			$this->get_report('index');
		} else {		
			$this->bwt_view('index');
		}			
	}
	
	function excel() {				
		$this->get_report('excel');
	}
	
	
	
	function bwt_view($menu)
	{
	
		$data['cari_status']=self::$cari_status;
		$data['request_type']=self::$request_type;
		$data['cari_pemohon']=self::$cari_pemohon;
		$data['cari_departemen']=self::$cari_departemen;
		$data['cari_no']=self::$cari_no;
	
	
	
		$data['cari_tahun']=self::$cari_tahun;
		$data['cari_bulan']=self::$cari_bulan;


		$data["nama_bulan"] =$this->general->combo_box('nama_bulan');
		$data["data_bulan"] =$this->general->combo_box('nama_bulan');
		$data["data_tahun"] =$this->general->combo_box('tahun');
	
		if($menu == 'index')
		{
			$data["content"] = self::$link_view.'/report_stats_main';
			$this->load->view('index',$data);
		} else if ($menu == 'excel')
		{
			$this->load->view(self::$link_view.'/report_stats_excel',$data);
		}
	}

	
	function get_report($menu) {				
	// inisialisasi
		$request_type = self::$request_type;					
		$cari_tahun = self::$cari_tahun;					
		$cari_bulan= self::$cari_bulan;					
		$cari_pemohon = self::$cari_pemohon;					
		$cari_departemen= self::$cari_departemen;					
		$cari_no= self::$cari_no;					
			
	//-------------Category----------------------------------------------------------------------
		$i = 0;
		if($request_type=='PR') {
		$sql	= "SELECT p.pr_no as req_no, p.pr_id as req_id, date_format(p.pr_date,'%d-%m-%Y') as req_date, 
				   dep.dep_name, u.usr_name,
						(
							SELECT count( pro_id ) 
							FROM prc_pr_detail AS d
							WHERE d.pr_id = p.pr_id
						) AS req_jumitem,
						
						(
							SELECT count( pro_id ) 
							FROM prc_pr_detail AS d
							WHERE d.pr_id = p.pr_id and d.requestStat=1
						) AS req_disetujui, 
						
						(
							SELECT count( pro_id ) 
							FROM prc_pr_detail AS d
							WHERE d.pr_id = p.pr_id and d.requestStat=2
						) AS req_diubah_disetujui, 
						
						(
							SELECT count( pro_id ) 
							FROM prc_pr_detail AS d
							WHERE d.pr_id = p.pr_id and d.requestStat=3
						) AS req_disetujui_dgn_catatan,
						
						(
							SELECT count( pro_id ) 
							FROM prc_pr_detail AS d
							WHERE d.pr_id = p.pr_id and d.requestStat=4
						) AS req_ditunda,
						
						(
							SELECT count( pro_id ) 
							FROM prc_pr_detail AS d
							WHERE d.pr_id = p.pr_id and d.requestStat=5
						) AS req_ditolak
						
				   FROM `prc_pr` AS p
				   LEFT OUTER JOIN prc_sys_user as u on p.pr_requestor = u.usr_id
				   LEFT OUTER JOIN prc_master_departemen as dep on u.dep_id = dep.dep_id
				   where p.".$request_type."_status = 1";

			if(self::$cari_tahun != 0)
				$sql .= " and  year(p.pr_date) = '$cari_tahun'";
			if(self::$cari_bulan!= 0)
				$sql .= " and  month(p.pr_date) = '$cari_bulan'";
	
			if(self::$cari_departemen != '')
				$sql .= " and  dep.dep_name LIKE '%$cari_departemen%'";
			if(self::$cari_pemohon != '')
				$sql .= " and   u.usr_name LIKE '%$cari_pemohon%'";
			if(self::$cari_no != '')
				$sql .= " and p.pr_no = '$cari_no'";				
			
			$sql .= " ORDER BY p.pr_no ASC";
	
			// ====== akhir seleksi barunya cuyyy ====			
		}
		
		else if($request_type=='MR') {
		$sql	= "SELECT m.mr_no as req_no, m.mr_id as req_id, date_format(m.mr_date,'%d-%m-%Y') as req_date,
					dep.dep_name, u.usr_name,						
						(
							SELECT count( pro_id ) 
							FROM prc_mr_detail AS d
							WHERE d.mr_id = m.mr_id
						) AS req_jumitem,
						
						(
							SELECT count( pro_id ) 
							FROM prc_mr_detail AS d
							WHERE d.mr_id = m.mr_id and d.requestStat=1
						) AS req_disetujui, 
						
						(
							SELECT count( pro_id ) 
							FROM prc_mr_detail AS d
							WHERE d.mr_id = m.mr_id and d.requestStat=2
						) AS req_diubah_disetujui, 
						
						(
							SELECT count( pro_id ) 
							FROM prc_mr_detail AS d
							WHERE d.mr_id = m.mr_id and d.requestStat=3
						) AS req_disetujui_dgn_catatan,
						
						(
							SELECT count( pro_id ) 
							FROM prc_mr_detail AS d
							WHERE d.mr_id = m.mr_id and d.requestStat=4
						) AS req_ditunda,
						
						(
							SELECT count( pro_id ) 
							FROM prc_mr_detail AS d
							WHERE d.mr_id = m.mr_id and d.requestStat=5
						) AS req_ditolak
						
				   FROM `prc_mr` AS m 
				   LEFT OUTER JOIN prc_sys_user as u on m.mr_requestor = u.usr_id
				   LEFT OUTER JOIN prc_master_departemen as dep on u.dep_id = dep.dep_id
				   where m.".$request_type."_status = 1";
			if(self::$cari_tahun != 0)
				$sql .= " and  year(p.pr_date) = '$cari_tahun'";
			if(self::$cari_bulan!= 0)
				$sql .= " and  month(p.pr_date) = '$cari_bulan'";
	
			if(self::$cari_departemen != '')
				$sql .= " and  dep.dep_name LIKE '%$cari_departemen%'";
			if(self::$cari_pemohon != '')
				$sql .= " and   u.usr_name LIKE '%$cari_pemohon%'";
			if(self::$cari_no != '')
				$sql .= " and m.mr_no = '$cari_no'";				

			$sql .= " ORDER BY m.mr_no ASC";
					//where m.is_approved='0' or m.is_approved='1'";
			// ====== akhir seleksi barunya cuyyy ====		
		}
		
		$user_id=self::$user_id;
		//$sql .= " and ".$request_type."_requestor = ".$user_id; // masih belm ngerti bwt apa
	
// =================================================================================================

	// ============ bwt pagingnya =================== (non aktif)
		$limit = $this->config->item('limit_report');
		$paging = $this->pagina_lib->pagina(self::$link_controller.'/index/'.$request_type,$sql,$limit,$uri_segment = 5);

		//$data['request_detail']	= $paging['result']; //pke paging
	//	$data['no_pos'] = $paging['pagina_pos'];
		
	// ============ (Akhir) bwt pagingnya ===================
		
		
		$data["jumlah_data"] =$this->general->hitung_banyak_data($sql);  // bwt ngitung banyak nya data	

		$data['request_detail']	= $this->db->query($sql);
		$data['no_pos'] = 0;
			
		$this->load->vars($data); // ngririm data yang ada di fungsi ini
		$this->bwt_view($menu); // bwt ngirim ke view		
	}

	
}
?>