<?php
class report_contrabon extends MY_Controller {
	private static $link_view, $link_controller,
	
		// variabel global filter
		$cari_tahun,$cari_bulan,$cari_status,$cari_no_kb,
		$cari_pemasok,$cari_tanggal_sj,$cari_no_bpb,$cari_no_po, 
		$cari_tanggal_awal, $cari_tanggal_akhir

	
	;
	function report_contrabon() {
		parent::MY_Controller();
		$this->load->model(array('tbl_contrabon','tbl_po'));
		$this->load->library(array('session','pagina_lib','general'));
		$this->config->load('tables');
		
		// =========== untuk bahasa ==========================
		$this->lang->load('mod_entry/contrabon','bahasa');
		$this->lang->load('tables','bahasa');
		$this->lang->load('general','bahasa');
		$this->lang->load('label','bahasa');
		
		$this->lang->load('mod_report/laporan_umum','bahasa');
		$this->lang->load('mod_report/kontra_bon/laporan','bahasa');
		// =========== (akhir) untuk bahasa ==========================		
	
	
	// ================== include (manggil) css & js (pake array) ====================
		$arrayCSS = array (
		'asset/css/themes/start/ui.all.css',
		'asset/css/print_templates.css',
		'asset/css/table/DataView.css'
		);
		
		$arrayJS = array (
		);
		
		$data['extraHeadContent'] = '';
		
		foreach ($arrayCSS as $css):
			$data['extraHeadContent'] .= '<link type="text/css" rel="stylesheet" href="'.base_url().$css.'"/>';
		endforeach;
		foreach ($arrayJS as $js):
			$data['extraHeadContent'] .= '<script type="text/javascript" src="'.base_url().$js.'"/></script>';
		endforeach;
	// ================== akhir include (manggil) css & js (pake array) ====================
		
	// =========== inisialisasi ========
		self::$cari_tahun=0;
		self::$cari_bulan=0;
		self::$cari_tanggal_awal='';
		self::$cari_tanggal_akhir='';


		self::$cari_no_kb='';
		self::$cari_pemasok=0;
		self::$cari_tanggal_sj='';
		self::$cari_no_bpb='';
		self::$cari_no_po='';
		
		



		self::$cari_status='';
	// =========== akhir inisialisasi ========

		// ============ masukin variabil yg diisi / yg dikirim dari view =======
		if ($this->input->post("cari_tahun"))
			self::$cari_tahun  = $this->input->post("cari_tahun");
		if ($this->input->post("cari_bulan"))
			self::$cari_bulan  = $this->input->post("cari_bulan");
		if ($this->input->post("cari_tanggal_awal"))
			self::$cari_tanggal_awal  = $this->input->post("cari_tanggal_awal");
		if ($this->input->post("cari_tanggal_akhir"))
			self::$cari_tanggal_akhir = $this->input->post("cari_tanggal_akhir");
			
			
		if ($this->input->post("cari_no_kb"))
			self::$cari_no_kb  = $this->input->post("cari_no_kb");
		if ($this->input->post("cari_pemasok"))
			self::$cari_pemasok  = $this->input->post("cari_pemasok");
		if ($this->input->post("cari_tanggal_sj"))
			self::$cari_tanggal_sj  = $this->input->post("cari_tanggal_sj");
		if ($this->input->post("cari_no_bpb"))
			self::$cari_no_bpb  = $this->input->post("cari_no_bpb");
		if ($this->input->post("cari_no_po"))
			self::$cari_no_po  = $this->input->post("cari_no_po");

			
		if ($this->input->post("cari"))
			self::$cari_status  = $this->input->post("cari");
		// ============ (akhir) masukin variabil yg diisi / yg dikirim dari view =======
		
		// ============= link untuk manggil viewnya ===================
		self::$link_controller = 'mod_report/report_contrabon';
		self::$link_view = 'purchase/mod_report/contrabon_rep';
		$data['link_controller'] = self::$link_controller;
		$data['link_view'] = self::$link_view;
		
		$data['page_title'] = $this->lang->line('lap_judul');
		// ============= akhir link untuk manggil viewnya ===================
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
		$data['cari_tanggal_awal']=self::$cari_tanggal_awal;
		$data['cari_tanggal_akhir']=self::$cari_tanggal_akhir;


		$data['cari_no_kb']=self::$cari_no_kb;
		$data['cari_tanggal_sj']=self::$cari_tanggal_sj;
		$data['cari_no_bpb']=self::$cari_no_bpb;
		$data['cari_no_po']=self::$cari_no_po;
		$data['cari_pemasok']=self::$cari_pemasok;
		
		$data["nama_bulan"] =$this->general->combo_box('nama_bulan');
		$data["data_bulan"] =$this->general->combo_box('nama_bulan');
		$data["data_tahun"] =$this->general->combo_box('tahun');
		$data["data_pemasok"] =$this->general->combo_box('pemasok');

		if($menu == 'index')
		{
			$data["content"] = self::$link_view.'/report_cb_main';
			$this->load->view('index',$data);
		} else if ($menu == 'excel')
		{
			$this->load->view(self::$link_view.'/report_cb_excel',$data);
		}
	}


	function get_report($menu) {
		// =========== inisialisasi ==========
	
		$cari_tahun=self::$cari_tahun;
		$cari_bulan=self::$cari_bulan;
		$cari_no_kb=self::$cari_no_kb;
		$cari_pemasok=self::$cari_pemasok;
		$cari_tanggal_sj=self::$cari_tanggal_sj;
		$cari_no_bpb=self::$cari_no_bpb;
		$cari_no_po=self::$cari_no_po;
		
		$cari_tanggal_awal=self::$cari_tanggal_awal;
		$cari_tanggal_akhir=self::$cari_tanggal_akhir;

		
		$total_rp=0;
		$total_dol=0;
		
		$bayar_rp=0;
		$bayar_dol=0;
	
		$nilai_kurs=0;
		// =========== (akhir) inisialisasi ==========

		
			$data['seldate'] =0;
		
		
			
		 $sql = "

select c.con_no, date_format(c.con_date,'%d-%m-%Y') as con_date,
            date_format(c.con_dueDate,'%d/%m') as con_dueDate,po.po_no,sup.sup_id,
            cur.cur_symbol, sup.sup_name, c.con_id, 
          
		    (select sum(gd.qty*gd.price*(100 - gd.discount)/100) from prc_gr_detail as gd
			inner join prc_gr as gr on gd.gr_id = gr.gr_id
             where gr.con_id = c.con_id and cur_id=1 ) as tot_rp,
			 
            (select sum(gd.qty*gd.price*(100 - gd.discount)/100) from prc_gr_detail as gd 
             inner join prc_gr as gr on gd.gr_id = gr.gr_id
			 where gr.con_id = c.con_id and cur_id=2 ) as tot_dol,

            (select sum(bd.con_dibayar) from prc_bkbk_detail as bd 
             where bd.con_id = c.con_id and cur_id=1) as pay_rp,
            (select sum(bd.con_dibayar ) from prc_bkbk_detail as bd 
             where bd.con_id = c.con_id and cur_id=2) as pay_dol

            from prc_contrabon as c 
            inner join prc_master_currency as cur on c.cur_id = cur.cur_id
            inner join prc_po as po on c.po_id = po.po_id
            inner join prc_master_supplier as sup on po.sup_id = sup.sup_id

	   
                        
            where 1=1";
		 if($cari_tahun != 0)
	        $sql .= " and  year(c.con_date)='$cari_tahun'";
	    if($cari_bulan != 0)
	        $sql .= " and month(c.con_date) = '$cari_bulan' ";
	     if($cari_pemasok != 0)
	        $sql .= " and sup.sup_id= '$cari_pemasok' ";
		  if($cari_no_kb != '')
	        $sql .= " and c.con_no LIKE '%$cari_no_kb%' ";
		  if($cari_no_bpb != '')
	        $sql .= " and gr.gr_no LIKE '%$cari_no_bpb%' ";
		  if($cari_no_po != '')
	        $sql .= " and po.po_no LIKE '%$cari_no_po%' ";
		
		//yg ini untuk range tanggalnya
		if ($cari_tanggal_awal !='' && $cari_tanggal_akhir != '' )
			$sql .= " and day(c.con_date) between '$cari_tanggal_awal' and '$cari_tanggal_akhir' ";

	   
	    $sql .= " order by c.con_no";
	

				//  ================ jumlah =====================
	

				
		$report_list=$this->db->query($sql);
		
		foreach($report_list->result() as $row){
			

			$total_rp=$total_rp+$row->tot_rp;
			$total_dol=$total_dol+$row->tot_dol;
			 
			

			$bayar_rp=$bayar_rp+$row->pay_rp;
			$bayar_dol=$bayar_dol+$row->pay_dol;	
			
			/*
			// untuk pengalian kurs nya
			(select grd.kurs from prc_contrabon as c2			
			left join prc_gr as gr on c2.con_id = gr.con_id	
			left join prc_gr_detail as grd on gr.gr_id = grd.gr_id 
			where c2.con_id = c.con_id) as kurs

			
			
			if ($row->cur_symbol == 'Rp'){
				$nilai_kurs = $row->kurs * $row->tot_rp;
			} else if ($row->cur_symbol == 'US$'){
				$nilai_kurs = $row->kurs * $row->tot_dol;			
			}				
		*/
		}
		
				
		$data['total_rp']=$total_rp;
		$data['total_dol']=$total_dol;
		$data['bayar_rp']=$bayar_rp;
		$data['bayar_dol']=$bayar_dol;
		$data['sisa_rp']=$total_rp - $bayar_rp;
		$data['sisa_dol']=$total_dol - $bayar_dol;
		
		//$data['nilai_kurs']=$nilai_kurs;
		// ===============akhir jumlah ===============

		

	// ==================== untuk paging nya ==================
		// lagi ga dipake
		$limit = $this->config->item('limit_report');
		$paging = $this->pagina_lib->pagina(self::$link_controller.'/index/'.$cari_bulan,$sql,$limit,$uri_segment = 5);
		
	//	$data['report_list'] = $paging['result']; // bwt paging
	//	$data['no_pos'] = $paging['pagina_pos'];
	
	// ==================== (akhir) untuk paging nya ==================


		$data['report_list'] = $this->db->query($sql); // eksekusi
		$data['no_pos'] = 0;
	

		$data["jumlah_data"] =$this->general->hitung_banyak_data($sql);  // bwt ngitung banyak nya data	
		
		
		
		$this->load->vars($data); // ngririm data yang ada di fungsi ini
		$this->bwt_view($menu); // bwt ngirim ke view		
		
		
	}


	
	function print_bon_view($con_id) {
		$data['print_con'] = $this->tbl_contrabon->get_bon_print($con_id);
		$data['print_gr'] = $this->tbl_contrabon->get_gr_bon_print($con_id);
		$data['con_id'] = $con_id;
		$data['content'] = self::$link_view.'/print_cb_view';
		$this->load->view('index',$data);
	}
	
	function set_print($con_id) {

		$update['con_printStat']='0';
	
		$where['con_id']=$con_id;
		
		if($this->tbl_contrabon->update_bon($where,$update)):
			anchor(self::$link_controller.'/index');
		endif;
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
