<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Module_sentry 
{

	function Module_sentry()
	{
		$this->obj =& get_instance();
	}

	function check_version()
	{
		if ($this->obj->session) {
			
			// CEK TABLE JIKA TIDAK ADA CREATE DAN LOGOUT
			if ($this->check_table())
					redirect('login/log_out');
					
			// CEK SESSION MODUL
			if (!$this->obj->session->userdata('client_name')) {
			
				// GET REVISI.TXT
				if (file_exists('revisi.txt')) {
					$rev = file('revisi.txt');
					$revisi_svn = $rev[0];
				} else {
					$revisi_svn = 0;
				}
				
				// GET CLIENT DATABASE
				$sql = "select * from prc_sys_client where client_id = 1";
				$get = $this->obj->db->query($sql);
				if ($get->num_rows() > 0) {
					$versi = $get->row();
					// GET DATA CLIENT
					$revisi = $versi->module_revision;
					$type = $versi->module_type;
					$version = $versi->module_version;
					$program = $versi->module_program;
					$packaged = $versi->module_package;
					$image = $versi->image;
					$name = $versi->client_name;
					$legal = $versi->client_legal;
				} else {
					$revisi = $revisi_svn;
					$type = 'NP';
					$version = '2.0.0.';
					$program = 'MATERIAL MANAGEMENT MODULE';
					$packaged = 'RELEASE';
					$image = '';
					$name = 'DEMO';
					$legal = '';
				}
				
				// JIKA REVISI TIDAK SAMA UPDATE DATABASE
				if ($revisi_svn > $revisi) {
					$sqlup = "update prc_sys_client set module_revision = $revisi_svn where client_id = 1";
					$qup = $this->obj->db->query($sqlup);
					$revisi = $revisi_svn;
				}
				
				if ($legal != '')
					$legal .= '. ';
				
				// SET SESSION
				$session['client_name'] = $legal.$name;
				$session['client_image'] = $image;
				$session['module_program'] = $program;
				$session['module_type'] = $type;
				$session['module_package'] = $packaged;
				$session['module_version'] = $version;
				$session['module_revision'] = $revisi;
				
				$this->obj->session->set_userdata($session);
				
				return true;
			}
			else {
				return true;
			}
		}
		else {
			die();
		}
	}
	
	function check_table() {
		// CEK TABLE 
		if (!$this->obj->db->table_exists('prc_sys_client'))
		{
			$sqlc = "CREATE TABLE `prc_sys_client` (
			  `client_id` int(11) NOT NULL AUTO_INCREMENT,
			  `client_name` varchar(50) NOT NULL,
			  `client_legal` varchar(10) NOT NULL,
			  `module_program` varchar(50) NOT NULL,
			  `module_type` varchar(50) NOT NULL,
			  `module_version` varchar(6) NOT NULL,
			  `module_revision` int(11) NOT NULL,
			  `module_package` varchar(50) NOT NULL DEFAULT 'Development',
			  `image` varchar(50) NOT NULL,
			  PRIMARY KEY (`client_id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2";
			
			$sqlc2 = "INSERT INTO `prc_sys_client` (`client_id`, `client_name`, `client_legal`, `module_program`, `module_type`, `module_version`, `module_revision`, `module_package`, `image`) VALUES
			(1, 'DEMO', '', 'MATERIAL MANAGEMENT MODULE', 'NON PPN', '2.0.0.', 343, 'Development', '')";
			
			$this->obj->db->query($sqlc);
			$this->obj->db->query($sqlc2);
			
			$this->check_database();
			
			return true;
		} 
	}
	
	function check_database() {
		// SYS USER
		if ($this->obj->db->table_exists('prc_sys_user')):
			if (!$this->obj->db->field_exists('offTime_log','prc_sys_user'))
				$this->obj->db->query('ALTER TABLE `prc_sys_user` ADD `offTime_log` DATETIME NULL DEFAULT NULL;');
			if (!$this->obj->db->field_exists('offIP_log','prc_sys_user'))
				$this->obj->db->query('ALTER TABLE `prc_sys_user` ADD `offIP_log` VARCHAR( 50 ) NULL;');
		endif;
				
		// PR DETAIL
		if ($this->obj->db->table_exists('prc_pr_detail')):
			if (!$this->obj->db->field_exists('pr_reqTime','prc_pr_detail'))
				$this->obj->db->query('ALTER TABLE `prc_pr_detail` ADD `pr_reqTime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;');
			if (!$this->obj->db->field_exists('so_id','prc_pr_detail'))
				$this->obj->db->query("ALTER TABLE `prc_pr_detail` ADD `so_id` INT NOT NULL DEFAULT '0' AFTER `po_id`;");
		endif;
		
		// PR DETAIL HISTORY
		if ($this->obj->db->table_exists('prc_pr_detail_history')):
			if (!$this->obj->db->field_exists('so_id','prc_pr_detail_history'))
				$this->obj->db->query("ALTER TABLE `prc_pr_detail_history` ADD `so_id` INT NOT NULL DEFAULT '0' AFTER `rfq_usr_note`;");
		endif;
		
		// MR DETAIL
		if ($this->obj->db->table_exists('prc_mr_detail')):
			if (!$this->obj->db->field_exists('mr_reqTime','prc_mr_detail'))
				$this->obj->db->query('ALTER TABLE `prc_mr_detail` ADD `mr_reqTime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;');		
			if (!$this->obj->db->field_exists('so_id','prc_mr_detail'))
				$this->obj->db->query("ALTER TABLE `prc_mr_detail` ADD `so_id` INT NOT NULL DEFAULT '0' AFTER `po_id`;");
		endif;
		
		// MR DETAIL HISTORY
		if ($this->obj->db->table_exists('prc_mr_detail_history')):
			if (!$this->obj->db->field_exists('so_id','prc_mr_detail_history'))
				$this->obj->db->query("ALTER TABLE `prc_mr_detail_history` ADD `so_id` INT NOT NULL DEFAULT '0' AFTER `mr_usr_note`;");
		endif;
		
		// SUPPLIER
		if ($this->obj->db->table_exists('prc_master_supplier')):
			if (!$this->obj->db->field_exists('deactive_note','prc_master_supplier'))
				$this->obj->db->query("ALTER TABLE `prc_master_supplier` ADD `deactive_note` TEXT NOT NULL DEFAULT '' AFTER `sup_status`;");
			if (!$this->obj->db->field_exists('deactive_req','prc_master_supplier'))
				$this->obj->db->query("ALTER TABLE `prc_master_supplier` ADD `deactive_req` INT NOT NULL DEFAULT '0' AFTER `deactive_note`;");
			if (!$this->obj->db->field_exists('deactive_date','prc_master_supplier'))
				$this->obj->db->query("ALTER TABLE `prc_master_supplier` ADD `deactive_date` DATE NULL DEFAULT NULL AFTER `deactive_req`;");
		endif;
		
		// CURRENCY
		if ($this->obj->db->table_exists('prc_master_currency')):
			if (!$this->obj->db->field_exists('cur_digit','prc_master_currency'))
				$this->obj->db->query("ALTER TABLE `prc_master_currency` ADD `cur_digit` INT( 11 ) NOT NULL DEFAULT '2';");
			if (!$this->obj->db->field_exists('cur_short','prc_master_currency'))
				$this->obj->db->query("ALTER TABLE `prc_master_currency` ADD `cur_short` VARCHAR( 25 ) NOT NULL;");
		endif;
		
		// SO
		if ($this->obj->db->table_exists('prc_so')):
			if (!$this->obj->db->field_exists('so_cost','prc_so'))
				$this->obj->db->query("ALTER TABLE `prc_so` ADD `so_cost` DECIMAL( 20, 5 ) NOT NULL DEFAULT '0.0000' AFTER `cur_id`;");
		endif;
		
		// BKBK
		if ($this->obj->db->table_exists('prc_bkbk')):
			if (!$this->obj->db->field_exists('bkbk_printStatus','prc_bkbk'))
				$this->obj->db->query("ALTER TABLE `prc_bkbk` ADD `bkbk_printStatus` INT NOT NULL AFTER `bkbk_methode`;");
			if (!$this->obj->db->field_exists('bkbk_printUsr','prc_bkbk'))
				$this->obj->db->query("ALTER TABLE `prc_bkbk` ADD `bkbk_printUsr` INT NOT NULL AFTER `bkbk_printStatus`;");
			if (!$this->obj->db->field_exists('bkbk_printDate','prc_bkbk'))
				$this->obj->db->query("ALTER TABLE `prc_bkbk` ADD `bkbk_printDate` DATE NOT NULL AFTER `bkbk_printUsr`;");
			if (!$this->obj->db->field_exists('bkbk_printCountDate','prc_bkbk'))
				$this->obj->db->query("ALTER TABLE `prc_bkbk` ADD `bkbk_printCountDate` DATE NOT NULL AFTER `bkbk_printDate`;");
			if (!$this->obj->db->field_exists('bkbk_printCount','prc_bkbk'))
				$this->obj->db->query("ALTER TABLE `prc_bkbk` ADD `bkbk_printCount` INT NOT NULL AFTER `bkbk_printCountDate`;");
		endif;
		
		// BKBK DETAIL
		if ($this->obj->db->table_exists('prc_bkbk_detail')):
			if (!$this->obj->db->field_exists('ppn_dibayar','prc_bkbk_detail'))
				$this->obj->db->query("ALTER TABLE `prc_bkbk_detail` ADD `ppn_dibayar` DECIMAL( 20, 2 ) NOT NULL DEFAULT '0' AFTER `kurs`;");
		endif;
		
		// PROVINSI
		if ($this->obj->db->table_exists('prc_master_provinsi')):
			if (!$this->obj->db->field_exists('rec_edit','prc_master_provinsi'))
				$this->obj->db->query("ALTER TABLE `prc_master_provinsi` ADD `rec_edit` INT( 1 ) NOT NULL;");
			if (!$this->obj->db->field_exists('rec_editor','prc_master_provinsi'))
				$this->obj->db->query("ALTER TABLE `prc_master_provinsi` ADD `rec_editor` INT( 4 ) NOT NULL;");
			if (!$this->obj->db->field_exists('rec_edited','prc_master_provinsi'))
				$this->obj->db->query("ALTER TABLE `prc_master_provinsi` ADD `rec_edited` DATE NOT NULL;");
		endif;
		
		// KOTA
		if ($this->obj->db->table_exists('prc_master_kota')):
			if (!$this->obj->db->field_exists('rec_edit','prc_master_kota'))
				$this->obj->db->query("ALTER TABLE `prc_master_kota` ADD `rec_edit` INT( 1 ) NOT NULL;");
			if (!$this->obj->db->field_exists('rec_editor','prc_master_kota'))
				$this->obj->db->query("ALTER TABLE `prc_master_kota` ADD `rec_editor` INT( 4 ) NOT NULL;");
			if (!$this->obj->db->field_exists('rec_edited','prc_master_kota'))
				$this->obj->db->query("ALTER TABLE `prc_master_kota` ADD `rec_edited` DATE NOT NULL;");
		endif;

		// CONTRABON
		if ($this->obj->db->table_exists('prc_contrabon')):
			if (!$this->obj->db->field_exists('con_ppn_value','prc_contrabon'))
				$this->obj->db->query("ALTER TABLE `prc_contrabon` ADD `con_ppn_value` DECIMAL( 20, 2 ) NOT NULL DEFAULT '0' AFTER `con_value`;");
			if (!$this->obj->db->field_exists('con_ppn_payVal','prc_contrabon'))
				$this->obj->db->query("ALTER TABLE `prc_contrabon` ADD `con_ppn_payVal` DECIMAL( 20, 2 ) NOT NULL DEFAULT '0' AFTER `con_payVal`;");
		endif;
		
		// GR DETAIL
		if ($this->obj->db->table_exists('prc_gr_detail')):
			if (!$this->obj->db->field_exists('kurs','prc_gr_detail'))
				$this->obj->db->query("ALTER TABLE `prc_gr_detail` CHANGE `kurs` `kurs` DECIMAL( 12, 5 ) NOT NULL DEFAULT '1';");
		endif;
	
		// GR DETAIL HISTORY
		if ($this->obj->db->table_exists('prc_gr_detail_history')):
			if (!$this->obj->db->field_exists('kurs','prc_gr_detail_history'))
				$this->obj->db->query("ALTER TABLE `prc_gr_detail_history` CHANGE `kurs` `kurs` DECIMAL( 12, 5 ) NOT NULL DEFAULT '1';");
		endif;

		// GR DETAIL HISTORY
		if ($this->obj->db->table_exists('prc_satuan_produk')):
			if (!$this->obj->db->field_exists('value','prc_satuan_produk'))
				$this->obj->db->query("ALTER TABLE `prc_satuan_produk` CHANGE `value` `value` DECIMAL( 8, 5 ) NOT NULL;");
		endif;
				
		//	
		if ($this->obj->db->table_exists('prc_sys_user')):
			if (!$this->obj->db->field_exists('modulstat','prc_sys_user')):
				$sql00 = "DROP TABLE IF EXISTS `prc_sys_menu`;";
				$sql11 = "
					CREATE TABLE IF NOT EXISTS `prc_sys_menu` (
					  `menu_id` int(11) NOT NULL AUTO_INCREMENT,
					  `menu_parent` int(11) NOT NULL DEFAULT '0',
					  `menu_name` varchar(255) DEFAULT NULL,
					  `menu_path` mediumtext,
					  `menu_icon` varchar(255) NOT NULL,
					  `sorter` int(11) NOT NULL,
					  `subsorter` int(11) NOT NULL,
					  `modulstat` varchar(1) NOT NULL,
					  PRIMARY KEY (`menu_id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=latin1 PACK_KEYS=0 AUTO_INCREMENT=121 ;";

				$sql22 = "
					INSERT INTO `prc_sys_menu` (`menu_id`, `menu_parent`, `menu_name`, `menu_path`, `menu_icon`, `sorter`, `subsorter`, `modulstat`) VALUES
					(1, 0, 'Administrator', NULL, 'config.png', 1, 0, '0'),
					(2, 1, 'Kategori', 'index.php/mod_master/master_category/index', 'controlpanel.png', 0, 1, '2'),
					(3, 1, 'Kelas', 'index.php/mod_master/master_kelas/index', 'controlpanel.png', 0, 2, '2'),
					(4, 1, 'Grup', 'index.php/mod_master/master_grup/index', 'controlpanel.png', 0, 3, '2'),
					(5, 1, 'Produk', 'index.php/mod_master/master_produk/index', 'controlpanel.png', 0, 4, '2'),
					(6, 1, 'Departemen', 'index.php/mod_master/master_departemen/index', 'controlpanel.png', 0, 6, '2'),
					(7, 1, 'Jabatan', 'index.php/mod_master/master_jabatan/index', 'controlpanel.png', 0, 7, '2'),
					(8, 1, 'User', 'index.php/mod_master/master_user/index', 'controlpanel.png', 0, 8, '2'),
					(9, 1, 'Catatan Print', 'index.php/mod_master/master_rpt_note/fckeditorform/index', 'controlpanel.png', 0, 9, '2'),

					(10, 0, 'Pengaturan', NULL, 'controlpanel.png', 2, 0, '0'),
					(11, 10, 'Satuan', 'index.php/mod_setup/setup_satuan/index', 'controlpanel.png', 0, 1, '2'),
					(12, 10, 'Kredit term', 'index.php/mod_setup/setup_term/index', 'controlpanel.png', 0, 2, '2'),
					(13, 10, 'Bank', 'index.php/mod_setup/setup_bank/index', 'controlpanel.png', 0, 3, '2'),
					(14, 10, 'Lokasi', 'index.php/mod_setup/setup_lokasi/index', 'controlpanel.png', 0, 4, '2'),
					(15, 10, 'Pemasok', 'index.php/mod_setup/setup_supplier/index', 'controlpanel.png', 0, 5, '2'),
					(16, 10, 'Kontak person', 'index.php/mod_setup/setup_contact/index', 'controlpanel.png', 0, 6, '2'),
					(17, 10, 'Aktivasi produk (Stok)', 'index.php/mod_setup/setup_aktivasi/index', 'controlpanel.png', 0, 7, '2'),
					(18, 10, 'Aktivasi produk (Harga)', 'index.php/mod_setup/setup_aktivasi_accounting/index', 'controlpanel.png', 0, 8, '2'),
					(19, 10, 'Penyesuaian stok', 'index.php/mod_setup/setup_adjustment/index', 'controlpanel.png', 0, 9, '2'),
					(20, 10, 'Posting hutang', 'index.php/mod_setup/setup_posting_hutang/index', 'controlpanel.png', 0, 10, '2'),

					(21, 0, 'Laporan', NULL, 'license.png', 3, 0, '0'),
					(22, 21, 'Cek status permohonan', 'index.php/mod_report/report_stats/index', 'license.png', 0, 1, '2'),
					(23, 21, 'Status PO', 'index.php/mod_report/report_po/index', 'license.png', 0, 2, '2'),
					(24, 21, 'Kas Kecil', 'index.php/mod_report/report_pcv/index', 'license.png', 0, 3, '2'),
					(25, 21, 'Kontra bon', 'index.php/mod_report/report_contrabon/index', 'license.png', 0, 4, '2'),
					(26, 21, 'Penerimaan barang', 'index.php/mod_report/report_gr/index', 'license.png', 0, 5, '2'),
					(27, 21, 'Penerimaan barang (Summary)', 'index.php/mod_report/report_penerimaan_summary/index', 'license.png', 0, 6, '2'),
					(28, 21, 'Pemesanan barang', 'index.php/mod_report/report_pesan/index', 'license.png', 0, 7, '2'),
					(29, 21, 'Persediaan barang', 'index.php/mod_report/report_inv/index', 'license.png', 0, 8, '2'),
					(30, 21, 'Pemakaian barang', 'index.php/mod_report/report_inv_mr/index', 'license.png', 0, 9, '2'),
					(31, 21, 'PO perkategori', 'index.php/mod_report/report_poperkategori/index', 'license.png', 0, 10, '2'),
					(32, 21, 'Penelusuran Proses', 'asd', 'license.png', 0, 11, '2'),
					(33, 21, 'Hutang Ke Pemasok', 'index.php/mod_report/report_hutang_ke_pemasok/index', 'license.png', 0, 12, '2'),
					(34, 21, 'Pembayaran ke Pemasok', 'index.php/mod_report/report_pembayaran_ke_pemasok/index', 'license.png', 0, 13, '2'),

					(35, 0, 'Cetak', NULL, 'print.png', 4, 0, '0'),
					(121, 35, 'Original', 'index.php/mod_printing/printing_rfq/index', 'print.png', 0, 1, '2'),
					(36, 121, 'RFQ', 'index.php/mod_printing/printing_rfq/index', 'print.png', 0, 1, '2'),
					(37, 121, 'PO', 'index.php/mod_printing/printing_po/index', 'print.png', 0, 2, '2'),
					(38, 121, 'BPB', 'index.php/mod_printing/printing_bpb/index', 'print.png', 0, 3, '2'),
					(39, 121, 'Kontra bon', 'index.php/mod_printing/printing_contrabon/index', 'print.png', 0, 4, '2'),
					(45, 121, 'Pembayaran', 'index.php/mod_printing/printing_payment/index', 'print.png', 0, 5, '2'),
					(40, 121, 'Form keluar barang ', 'index.php/mod_printing/printing_goodrelease/index', 'print.png', 0, 6, '2'),
					(41, 121, 'Petty cash', 'index.php/mod_printing/printing_pcv/index', 'print.png', 0, 7, '2'),
					(42, 121, 'Retur barang', 'index.php/mod_printing/printing_goodreturn/index', 'print.png', 0, 8, '2'),
					(43, 121, 'RFQ service', 'index.php/mod_printing/printing_rfq_service/index', 'print.png', 0, 9, '2'),
					(44, 121, 'Service order', 'index.php/mod_printing/printing_so/index', 'print.png', 0, 10, '2'),

					(63, 35, 'Duplikasi', NULL, 'print.png', 0, 2, '2'),
					(64, 63, 'RFQ', 'index.php/mod_printing/printing_rfq/index/1', 'print.png', 0, 1, '2'),
					(65, 63, 'PO', 'index.php/mod_printing/printing_po/index/1', 'print.png', 0, 2, '2'),
					(66, 63, 'BPB', 'index.php/mod_printing/printing_bpb/index/1', 'print.png', 0, 3, '2'),
					(68, 63, 'Kontra Bon', 'index.php/mod_printing/printing_contrabon/index/1', 'print.png', 0, 4, '2'),
					(124, 63, 'Pembayaran', 'index.php/mod_printing/printing_payment/index/1', 'print.png', 0, 5, '2'),
					(67, 63, 'Form Keluar Barang', 'index.php/mod_printing/printing_goodrelease/index/1', 'print.png', 0, 6, '2'),
					(69, 63, 'Petty Cash', 'index.php/mod_printing/printing_pcv/index/1', 'print.png', 0, 7, '2'),
					(71, 63, 'Retur Barang', 'index.php/mod_printing/printing_goodreturn/index/1', 'print.png', 0, 8, '2'),
					(122, 63, 'RFQ service', 'index.php/mod_printing/printing_rfq_service/index/1', 'print.png', 0, 9, '2'),
					(123, 63, 'Service order', 'index.php/mod_printing/printing_so/index/1', 'print.png', 0, 10, '2'),

					(46, 0, 'Persetujuan', NULL, 'mainmenu.png', 5, 0, '0'),
					(47, 46, 'PR', 'index.php/mod_approval/appr_pr/index', 'controlpanel.png', 0, 1, '2'),
					(48, 46, 'RFQ', 'index.php/mod_approval/appr_rfq/index', 'controlpanel.png', 0, 2, '2'),
					(49, 46, 'MR', 'index.php/mod_approval/appr_mr/index', 'controlpanel.png', 0, 3, '2'),
					(50, 46, 'Petty cash', 'index.php/mod_approval/appr_pcv/index', 'controlpanel.png', 0, 4, '2'),
					(51, 46, 'Penyesuaian stok', 'index.php/mod_approval/appr_adjustment/index', 'controlpanel.png', 0, 5, '2'),
					(52, 46, 'Penyesuaian harga', 'index.php/mod_approval/appr_adjustment_po/index', 'controlpanel.png', 0, 6, '2'),
					(53, 46, 'Retur Barang', 'index.php/mod_approval/appr_good_return/index', 'controlpanel.png', 0, 7, '2'),
					(54, 46, 'Permintaan servis', 'index.php/mod_approval/appr_sr/index', 'controlpanel.png', 0, 8, '2'),
					(55, 46, 'RFQ servis final', 'index.php/mod_approval/appr_rfq_service/index', 'controlpanel.png', 0, 9, '2'),

					(56, 0, 'Daftar', NULL, 'content.png', 6, 0, '0'),
					(57, 56, 'Produk', NULL, 'controlpanel.png', 0, 1, '2'),
					(58, 57, 'Statistik harga', 'index.php/mod_list/list_statistik/index', 'controlpanel.png', 0, 2, '2'),
					(59, 57, 'PR', 'index.php/mod_list/list_pr/index', 'controlpanel.png', 0, 3, '2'),
					(60, 57, 'PO masih buka', 'index.php/mod_list/list_po_buka/index', 'controlpanel.png', 0, 5, '2'),
					(61, 57, 'Daftar BPB', 'index.php/mod_list/list_good_receive', 'controlpanel.png', 0, 6, '2'),
					(62, 57, 'Daftar BPB Otorisasi', 'index.php/mod_entry/entry_good_receive/index/gr_auth_list', 'controlpanel.png', 0, 7, '2'),

					/*	PINDAH KE CETAK
					(63, 57, 'Daftar Dokumen sudah Cetak', NULL, 'controlpanel.png', 0, 8, '2'),
					(64, 63, 'RFQ', 'index.php/mod_printing/printing_rfq/index/1', 'controlpanel.png', 0, 9, '2'),
					(65, 63, 'PO', 'index.php/mod_printing/printing_po/index/1', 'controlpanel.png', 0, 10, '2'),
					(66, 63, 'BPB', 'index.php/mod_printing/printing_bpb/index/1', 'controlpanel.png', 0, 11, '2'),
					(67, 63, 'Keluar Barang', 'index.php/mod_goodrelease_printing/goodrelease/index/1', 'controlpanel.png', 0, 12, '2'),
					(68, 63, 'Kontra Bon', 'index.php/mod_printing/printing_contrabon/index/1', 'controlpanel.png', 0, 13, '2'),
					(69, 63, 'Petty Cash', 'index.php/mod_printing/printing_pcv/index/1', 'controlpanel.png', 0, 14, '2'),
					*/

					(70, 57, 'RFQ akan di setujui', 'index.php/mod_list/list_rfqtoapp/index', 'controlpanel.png', 0, 4, '2'),

					(72, 0, 'Input data', NULL, 'edit.png', 7, 0, '0'),
					(73, 72, 'Servis', NULL, 'controlpanel.png', 0, 1, '2'),
					(74, 73, 'Cek status barang digudang', 'index.php/mod_service/service_inventory/index', 'controlpanel.png', 0, 2, '2'),
					(75, 73, 'Ubah SR ke RFQ servis', 'index.php/mod_service/service_sr_rfq/index', 'controlpanel.png', 0, 3, '2'),
					(76, 73, 'Input RFQ servis final', 'index.php/mod_service/service_rfq/index', 'controlpanel.png', 0, 4, '2'),

					(77, 72, 'Produk', NULL, 'controlpanel.png', 0, 5, '2'),
					(78, 77, 'Akunting', 'Akunting', 'controlpanel.png', 0, 6, '2'),
					(79, 78, 'Buat kontra bon', 'index.php/mod_entry/entry_contrabon/index', 'controlpanel.png', 0, 7, '2'),
					(80, 78, 'Buat pembayaran', 'index.php/mod_entry/entry_payment/index', 'controlpanel.png', 0, 8, '2'),

					(81, 77, 'Gudang', NULL, 'controlpanel.png', 0, 9, '2'),
					(82, 81, 'Input BPB', 'index.php/mod_entry/entry_good_receive/index/gr_input', 'controlpanel.png', 0, 10, '2'),
					(83, 81, 'Terima barang lewat petty cash', 'index.php/mod_entry/entry_pcv_receive/index', 'controlpanel.png', 0, 11, '2'),
					(84, 81, 'Keluarkan barang', 'index.php/mod_entry/entry_goodrelease_realisasi/index', 'controlpanel.png', 0, 12, '2'),
					(85, 81, 'Input data pemakaian barang', 'index.php/mod_entry/entry_pakai/index', 'controlpanel.png', 0, 13, '2'),
					(86, 81, 'Kembalikan barang sisa pemakaian', 'index.php/mod_entry/entry_retur/index', 'controlpanel.png', 0, 14, '2'),

					(87, 77, 'Pembelian', 'Pembelian', 'controlpanel.png', 0, 15, '2'),
					(88, 87, 'Ubah PR ke RFQ', 'index.php/mod_entry/entry_pr_rfq/index', 'controlpanel.png', 0, 16, '2'),
					(89, 87, 'Input RFQ final', 'index.php/mod_entry/entry_rfq/index', 'controlpanel.png', 0, 17, '2'),
					(90, 87, 'Buat otorisasi BPB', 'index.php/mod_entry/entry_good_receive/index/gr_auth', 'controlpanel.png', 0, 18, '2'),
					(91, 87, 'Cek BPB kurs', 'index.php/mod_entry/entry_good_receive/index_cbpb/kurs', 'controlpanel.png', 0, 19, '2'),
					(92, 87, 'Cek BPB final', 'index.php/mod_entry/entry_good_receive/index_cbpb/final', 'controlpanel.png', 0, 20, '2'),
					(93, 87, 'Input harga realisasi pada petty cash', 'index.php/mod_entry/entry_pcv_realisasi/index', 'controlpanel.png', 0, 21, '2'),
					(94, 87, 'Kembalikan barang ke pemasok', 'index.php/mod_entry/entry_good_receive/index/good_return', 'controlpanel.png', 0, 22, '2'),

					(95, 77, 'User', NULL, 'controlpanel.png', 0, 23, '2'),
					(96, 95, 'Cek status barang digudang', 'index.php/mod_entry/entry_inventory/index', 'controlpanel.png', 0, 24, '2'),
					(97, 95, 'Input harga perkiraan pada petty cash', 'index.php/mod_entry/entry_pcv_perkiraan/index', 'controlpanel.png', 0, 25, '2'),

					(98, 21, 'Daftar pemasok', 'index.php/mod_report/report_daftar_pemasok/index', 'license.png', 0, 16, '2'),
					(99, 21, 'Daftar barang', 'index.php/mod_report/report_daftar_barang/index', 'license.png', 0, 17, '2'),

					/* SALES */
					(100, 73, 'Servis final', 'index.php/mod_service/service_complate/index', 'controlpanel.png', 0, 18, '2'),
					(101, 72, 'Sales', NULL, 'controlpanel.png', 0, 28, '1'),
					(102, 72, 'MD', NULL, 'controlpanel.png', 0, 26, '1'),
					(103, 102, 'Mini marker', 'index.php/sales/entry_minimarker', 'controlpanel.png', 0, 27, '1'),
					(104, 101, 'Buat Simulasi Harga', 'index.php/sales/entry_simulasi', 'controlpanel.png', 0, 29, '1'),
					(105, 101, 'Revisi penawaran Harga', 'index.php/sales/entry_revsimulasi', 'controlpanel.png', 0, 30, '1'),
					(106, 101, 'Konversi ke Proforma Invoice', 'index.php/sales/entry_konversi', 'controlpanel.png', 0, 31, '1'),
					(107, 56, 'Daftar Surat Simulasi Harga Edit', 'index.php/sales/entry_edit_simulasi/', 'controlpanel.png ', 0, 16, '1'),
					(108, 56, 'Daftar Surat Penawaran harga Cetak', 'index.php/sales/daftar_simulasi_print/', 'controlpanel.png', 0, 17, '1'),
					(109, 56, 'Daftar Surat Proforma Invoice', 'index.php/sales/daftar_pi/', 'controlpanel.png', 0, 18, '1'),
					(110, 46, 'Simulasi Harga', 'index.php/sales/approval_simulasi', 'controlpanel.png', 0, 10, '1'),
					(111, 35, 'Surat Penawaran Harga Ke Pembeli', 'index.php/sales/print_simulasi', 'print.png', 0, 11, '1'),
					(112, 35, 'Surat Penawaran Harga Detail', 'index.php/sales/print_simulasi_detail', 'print.png', 0, 12, '1'),
					(113, 35, 'Surat Proforma Invoice', 'index.php/sales/print_pi', 'print.png', 0, 13, '1'),
					(114, 1, 'Buyer', 'index.php/sales/master_buyer', 'controlpanel.png', 0, 10, '1'),
					(115, 1, 'Charge', 'index.php/sales/master_charge', 'controlpanel.png', 0, 11, '1'),
					(116, 1, 'profit', 'index.php/sales/master_profit', 'controlpanel.png', 0, 12, '1'),
					(117, 1, 'template aksesoris', 'index.php/sales/master_tmp_accessories', 'controlpanel.png', 0, 13, '1'),

					(118, 32, 'PR', 'index.php/mod_report/report_tracking/index', 'license.png', 0, 1, '2'),
					(119, 32, 'MR', 'index.php/mod_report/report_tracking_mr/index', 'license.png', 0, 2, '2'),
					(120, 46, 'Non aktif pemasok', 'index.php/mod_approval/appr_deactive_supplier/index', 'controlpanel.png', 0, 16, '2');";
				
				$this->obj->db->query($sql00);
				$this->obj->db->query($sql11);
				$this->obj->db->query($sql22);
			endif;
		endif;
		
		if (!$this->obj->db->table_exists('prc_master_motivation')):
			$sql1 = "CREATE TABLE `prc_master_motivation` (
			  `motiv_id` int(11) NOT NULL AUTO_INCREMENT,
			  `motiv_word` mediumtext NOT NULL,
			  `is_active` int(1) NOT NULL DEFAULT '0',
			  `active_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			  PRIMARY KEY (`motiv_id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT AUTO_INCREMENT=27 ;";

			$sql2 = "INSERT INTO `prc_master_motivation` (`motiv_id`, `motiv_word`, `is_active`, `active_date`) VALUES
			(1, 'I am a slow walker, but I never walk backwards.', 1, '2010-03-19 09:37:19'),
			(2, 'I do not think much of a man who is not wiser today than he was yesterday.', 1, '2010-03-20 09:08:17'),
			(3, 'Whatever you are, be a good one.', 1, '2010-03-22 10:42:47'),
			(4, 'I will study and get ready, and perhaps my chance will come.', 1, '2010-03-23 05:32:19'),
			(6, 'Let not him who is houseless pull down the house of another, but let him work diligently and build one for himself, thus by example assuring that his own shall be safe from violence when built.', 1, '2010-03-24 10:27:09'),
			(7, 'Will springs from the two elements of moral sense and self-interest.', 1, '2010-03-25 15:57:38'),
			(8, 'My great concern is not whether you have failed, but whether you are content with your failure.', 1, '2010-03-26 13:02:47'),
			(9, 'The way for a young man to rise is to improve himself in every way he can, never suspecting that anybody wishes to hinder him.', 1, '2010-03-27 11:34:13'),
			(11, 'I will prepare and some day my chance will come.', 1, '2010-03-28 00:12:17'),
			(12, 'I want it said of me by those who knew me best, that I always plucked a thistle and planted a flower where I thought a flower would grow.', 1, '2010-03-28 23:00:45'),
			(13, 'I never had a policy; I have just tried to do my very best each and every day.', 1, '2010-03-28 23:55:17'),
			(14, 'If there is anything that a man can do well, I say let him do it. Give him a chance.', 1, '2010-03-29 00:05:30'),
			(15, 'You cannot escape the responsibility of tomorrow by evading it today.', 0, '2010-03-19 09:37:19'),
			(16, 'Nearly all men can stand adversity, but if you want to test a man''s character, give him power.', 0, '2010-03-19 09:37:19'),
			(18, 'You can fool some of the people all of the time, and all of the people some of the time, but you can not fool all of the people all of the time.', 0, '2010-03-19 09:37:19'),
			(19, 'People are just as happy as they make up their minds to be', 0, '2010-03-19 09:37:19'),
			(21, 'I am not bound to win, but I am bound to be true. I am not bound to succeed, but I am bound to live by the light that I have. I must stand with anybody that stands right, and stand with him while he is right, and part with him when he goes wrong.', 0, '2010-03-19 09:37:19'),
			(22, 'With malice toward none, with charity for all.', 0, '2010-03-19 09:37:19'),
			(23, 'That some should be rich, shows that others may become rich, and, hence, is just encouragement to industry and enterprise.', 0, '2010-03-19 09:37:19'),
			(24, 'Always bear in mind, that your own resolution to succeed is more important than any other thing.', 0, '2010-03-19 09:37:19'),
			(25, 'Determine that the thing can and shall be done, and then we shall find the way.', 0, '2010-03-19 09:37:19'),
			(26, 'I have noticed that folks are generally about as happy as they', 0, '2010-03-19 09:37:19');";

			$this->obj->db->query($sql1);
			$this->obj->db->query($sql2);
		endif;
	}
	
}
?>