<?php
/*
	Tanggal : 17 Maret 2010
	Perbaikan : penambahan status ACC untuk RFQ

*/

class general {
	function general() {
		$this->obj =& get_instance();
	}
	
	function digit_number($satuan_id,$value) {
		$sql = "select satuan_format from prc_master_satuan where satuan_id = $satuan_id";
		$get_sat = $this->obj->db->query($sql)->row();
		$tag = number_format($value,$get_sat->satuan_format);		
		return $tag;
	}


	// bwt ngitung banyaknya data  ($sql isi dari query yg mw dijumlah)
	function hitung_banyak_data($sql)
	{
		$query_hitung=$this->obj->db->query($sql);
		$jum_data = 0;
		foreach ($query_hitung->result() as $rows){
			$jum_data++;		
		}	
		return $jum_data;
	}


	// bwt pilihan filter2 yang ada di combobox  ($tipe = inputan dari tahun, bulan , de-el-el)
	function combo_box($tipe)
	{
		if ($tipe == 'nama_bulan'){
			$pilih = $this->obj->lang->line('combo_box_array_bulan');
		}else if ($tipe == 'id_bulan'){
			$pilih = $this->obj->db->query("select distinct bln from prc_sys_counter");
		}else if ($tipe == 'tahun'){
			$pilih = $this->obj->db->query("select distinct thn from prc_sys_counter");
		}else if ($tipe == 'kategori'){
			$pilih = $this->obj->db->query("select cat_id, cat_name,cat_code from prc_master_category where cat_level=1 order by cat_name ASC");
		}else if ($tipe == 'pemasok'){
			$pilih = $this->obj->db->query("select sup_id, sup_name from prc_master_supplier order by sup_name ASC");
		}else if ($tipe == 'kelas'){
			$pilih = $this->obj->db->query("select cat_id, cat_name,cat_code from prc_master_category where cat_level=2 order by cat_name ASC");
		}else if ($tipe == 'grup'){
			$pilih = $this->obj->db->query("select cat_id, cat_name,cat_code from prc_master_category where cat_level=3 order by cat_name ASC");
		}
		return $pilih;
	}

	// fungsi sementara untuk banyaknya digit dolar
	function digit_dolar()
	{
		//$banyak_digit=5;
		$dg=$this->obj->db->query("SELECT *  FROM `prc_master_currency` WHERE `cur_id` = 2");
		foreach($dg->result() as $row)
		{
			$banyak_digit=$row->cur_digit;
		}		
		return $banyak_digit;
	}

	// fungsi sementara untuk banyaknya digit dolar
	function digit_rp()
	{
		//$banyak_digit=2;		
		$dg=$this->obj->db->query("SELECT *  FROM `prc_master_currency` WHERE `cur_id` = 1");
		foreach($dg->result() as $row)
		{
			$banyak_digit=$row->cur_digit;
		}		
		return $banyak_digit;
	}
	
	function bilangRatusan($x)
	{
	   // function untuk membilang bilangan pada setiap kelompok
	 
	   $kata = array('', 'satu ', 'dua ', 'tiga ' , 'empat ', 'lima ', 'enam ', 'tujuh ', 'delapan ', 'sembilan ');
	 
	   $string = '';
	 
	   $ratusan = floor($x/100);
	   $x = $x % 100;
	   if ($ratusan > 1) $string .= $kata[$ratusan]."ratus "; // membentuk kata '... ratus'
	   else if ($ratusan == 1) $string .= "seratus "; // membentuk kata khusus 'seratus '
	 
	   $puluhan = floor($x/10);
	   $x = $x % 10;
	   if ($puluhan > 1)
	   {
		  $string .= $kata[$puluhan]."puluh "; // membentuk kata '... puluh'
		  $string .= $kata[$x]; // membentuk kata untuk satuan
	   }
	   else if (($puluhan == 1) && ($x == 1)) $string .= "sebelas ";
	   else if (($puluhan == 1) && ($x > 0)) $string .= $kata[$x]."belas "; // kejadian khusus untuk bilangan yang berbentuk kata '... belas'
	   
	   else if (($puluhan == 1) && ($x == 0)) $string .= $kata[$x]."sepuluh "; // kejadian khusus untuk bilangan 10 
	   else if ($puluhan == 0) $string .= $kata[$x];	 // membentuk kata untuk satuan	
	 
	   return $string;
	}
	 
	function terbilang($x,$digit = 0,$curr = 'RUPIAH')
	{
		// membentuk format bilangan XXX.XXX.XXX.XXX.XXX
		$x = number_format($x, $digit);
		 
		// memecah kelompok ribuan berdasarkan tanda ','
		$pecah = explode(",", $x);
		
		// memecah kelompok ribuan berdasarkan tanda '.'
		$desimal = explode(".", $x);
		 
		$string = "";
		 
		// membentuk format terbilang '... trilyun ... milyar ... juta ... ribu ...'
		//for($sel = 0; $sel <= 1; $sel++){
		//if ($sel == 0) {
		$set = $pecah;
		//}
		//else {$set = $desimal[1];}
		
		for($i = 0; $i <= count($set)-1; $i++)
		{
		   if ((count($set) - $i == 5) && ($set[$i] != 0)) $string .= $this->bilangRatusan($set[$i])."triliyun "; // membentuk kata '... trilyun'
		   else if ((count($set) - $i == 4) && ($set[$i] != 0)) $string .= $this->bilangRatusan($set[$i])."milyar "; // membentuk kata '... milyar'
		   else if ((count($set) - $i == 3) && ($set[$i] != 0)) $string .= $this->bilangRatusan($set[$i])."juta "; // membentuk kata '... juta'
		   else if ((count($set) - $i == 2) && ($set[$i] == 1)) $string .= "seribu "; // kejadian khusus untuk bilangan dalam format 1XXX (yang mengandung kata 'seribu')
		   else if ((count($set) - $i == 2) && ($set[$i] != 0)) $string .= $this->bilangRatusan($set[$i])."ribu "; // membentuk kata '... ribu'
		   else if ((count($set) - $i == 1) && ($set[$i] != 0)) $string .= $this->bilangRatusan($set[$i]); 
		}
		
		$string .= ' '.$curr;
		
		//}
		return $string;
	}
	
	// pilih keterangan status
	function status($namaStatus,$noStatus)
	{
		//$this->language->bahasa('bahasa_lang');
		/////////////////////////////////////////////////////////////
		// $namaStatus (nama status yang mw diseleksi)
		// bisa untuk status
		// 1. $namaStatus = 'keperluan'
		//	  keperluan di cek status barang di gudang PR/MR
		//    untuk statusnya ($noStatus): 
		//    1 = Pembelian Rutin
		//    2 = Proyek
		//    3 = Asset Baru
		//    4 = Asset Tambahan
		//    5 = Asset Upgrade
		//    6 = Service Order
		//
		// 2. $namaStatus = 'acc_pr'
		//	  menu persetujuan pr
		//    untuk statusnya ($noStatus): 
		//    1 = Disetujui
		//    2 = Diubah dan Disetujui
		//    3 = Disetujui dengan catatan
		//    4 = Ditunda
		//    5 = Ditolak
		// 3. $namaStatus = 'acc_rfq'
		//	  menu persetujuan 
		//    untuk statusnya ($noStatus): 
		//    1 = Disetujui
		//    2 = Diubah dan Disetujui
		//    3 = Ditolak
		// 4. $namaStatus = 'status_emergency'
		//	  untuk status emergency atau normal
		//    untuk statusnya ($noStatus): 
		//    0 = Normal
		//    1 = Emergency
		// 5. $namaStatus = 'pcv_status'
		//    untuk statusnya ($noStatus): 
		//    2 = Barang Belum Diterima
		//    5 = Barang Belum Realisasi
		//    6 = Sudah Tutup
		// 6. $namaStatus = 'buka_tutup'
		//    untuk statusnya ($noStatus): 
		//    0 = Buka
		//    1 = Tutup
		// 7. $namaStatus = 'rec'
		//	  rec = penerimaan barang / pengembalian barang
		//    untuk statusnya ($noStatus): 
		//    rec = Penerimaan Barang
		//    ret = Tutup
		// 8. $namaStatus = 'keperluan_mr'
		//	  keperluan_mr = keperluan pesanan mr
		//    untuk statusnya ($noStatus): 
		//    0 = Produksi
		//    1 = Permintaan Servis
		/////////////////////////////////////////////////////////////
		
		$ketStatus = $this->obj->lang->line('status_tidak_diketahui'); //default
		
		switch ($namaStatus) :
			case 'keperluan' : // point 1
					switch ($noStatus) :
						case '1' : $ketStatus = $this->obj->lang->line('pembelian_rutin');
							break;
							
						case '2' : $ketStatus = $this->obj->lang->line('proyek');
							break;
							
						case '3' : $ketStatus = $this->obj->lang->line('aset_baru');
							break;
						
						case '4' : $ketStatus = $this->obj->lang->line('aset_tambahan');
							break;
							
						case '5' : $ketStatus = $this->obj->lang->line('aset_pembaruan');
							break;
							
						case '6' : $ketStatus = $this->obj->lang->line('permintaan_servis');
							break;							
					endswitch;
				break;
			
			case 'acc_pr' : // point 2
					switch ($noStatus) :
						case '1' : $ketStatus = $this->obj->lang->line('disetujui');
							break;
							
						case '2' : $ketStatus = $this->obj->lang->line('diubah_disetujui');
							break;
							
						case '3' : $ketStatus = $this->obj->lang->line('disetujui_dgn_catatan');
							break;
						
						case '4' : $ketStatus = $this->obj->lang->line('ditunda');
							break;
							
						case '5' : $ketStatus = $this->obj->lang->line('ditolak');
							break;					
					endswitch;
				break;
			case 'acc_rfq' : // point 3
					switch ($noStatus) :
						case '1' : $ketStatus = $this->obj->lang->line('diterima');
							break;
							
						case '2' : $ketStatus = $this->obj->lang->line('ditunda');
							break;
							
						case '3' : $ketStatus = $this->obj->lang->line('ditolak');
							break;
							
						case '5' : $ketStatus = $this->obj->lang->line('diterima');
							break;
						
					endswitch;
				break;
				
			case 'status_emergency' : // point 4
					switch ($noStatus) :
						case '0' : $ketStatus = $this->obj->lang->line('keperluan').' '.$this->obj->lang->line('normal');
							break;
							
						case '1' : $ketStatus = $this->obj->lang->line('keperluan').' '.$this->obj->lang->line('emergency');
							break;
					endswitch;
				break;
			
			case 'pcv_status' :
					switch ($noStatus) :
						case '2' : $ketStatus = '<font color=red>'.$this->obj->lang->line('lap_brg_blm_terima').'</font>';
							break;
							
						case '5' : $ketStatus = '<font color=red>'.$this->obj->lang->line('lap_belum_realisasi').'</font>';
							break;
							
						case '6' : $ketStatus = $this->obj->lang->line('lap_sudah_tutup');
							break;	
							
					endswitch;					
				break;			
			
			case 'buka_tutup' : // point 6
					switch ($noStatus) :
						case '0' : $ketStatus = '<font color=red>'.$this->obj->lang->line('combo_box_status_buka').'</font>';
							break;
							
						case '1' : $ketStatus = $this->obj->lang->line('combo_box_status_tutup');
							break;
					endswitch;
				break;			
			
			case 'rec' : // point 7
					switch ($noStatus) :
						case 'rec' : $ketStatus = $this->obj->lang->line('terima');
							break;							
						case 'ret' : $ketStatus = '<font color=red>'.$this->obj->lang->line('retur').'</font>';
							break;
					endswitch;
				break;			
			
			case 'keperluan_mr' : // point 8
					switch ($noStatus) :
						case '0' : $ketStatus = $this->obj->lang->line('produk');
							break;							
						case '1' : $ketStatus = $this->obj->lang->line('permintaan_servis');
							break;
					endswitch;
				break;			
			
		endswitch;
		
		return $ketStatus;
	}
}
?>