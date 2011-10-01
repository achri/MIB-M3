<?php
class Printing_contrabon extends MY_Controller {
	public static $link_view, $link_controller, $ppn_status, $print_status;
	function Printing_contrabon() {
		parent::MY_Controller();
		$this->load->model(array('tbl_contrabon','tbl_good_return','tbl_po','tbl_rptnote','flexi_model'));
		$this->load->library(array('flexigrid','flexi_engine'));
		$this->load->helper(array('flexigrid','html'));
		$this->load->library('session');
		$this->config->load('tables');
		
		$this->config->load('flexigrid');
		
		// LANGUAGE
		$this->lang->load('mod_master/supplier','bahasa');
		$this->lang->load('mod_entry/contrabon','bahasa');
		$this->lang->load('tables','bahasa');
		$this->lang->load('general','bahasa');
		$this->lang->load('label','bahasa');
		
		$arrayCSS = array (
		'asset/css/themes/start/ui.all.css',
		'asset/css/print_templates.css',
		'asset/css/table/DataView.css',
		'asset/javascript/jQuery/flexigrid/css/flexigrid.css',
		);
		
		$arrayJS = array (
		'asset/javascript/jQuery/flexigrid/js/flexigrid.js',
		);
		
		$data['extraHeadContent'] = '';
		
		foreach ($arrayCSS as $css):
			$data['extraHeadContent'] .= '<link type="text/css" rel="stylesheet" href="'.base_url().$css.'"/>';
		endforeach;
		foreach ($arrayJS as $js):
			$data['extraHeadContent'] .= '<script type="text/javascript" src="'.base_url().$js.'"/></script>';
		endforeach;

		self::$link_controller = 'mod_printing/printing_contrabon';
		self::$link_view = 'purchase/mod_printing/contrabon_printing';
		$data['link_controller'] = self::$link_controller;
		$data['link_view'] = self::$link_view;
		
		// TITLE
		if ($this->uri->segment(4) == 1)
			$data['page_title'] = $this->lang->line('print_dup_bon_title');
		else
			$data['page_title'] = $this->lang->line('print_bon_title');
		
		$this->load->vars($data);
		
		self::$ppn_status = '';
		if ($this->session->userdata('module_type') == 'PPN')
			self::$ppn_status = 'ppn_';
	}
	
	// QUERY UNTUK NON FLEXIGRID ATO FLEXIGRID
	function flexigrid_sql($flexi=TRUE,$print_status,$count='con_id',$where=FALSE) {
		$sql = "SELECT c.con_id, c.con_no, date_format(c.con_date,'%d-%m-%Y') as con_date, s.sup_name, leg.legal_name {COUNT_STR} 
            FROM prc_contrabon as c
			inner join prc_po as p on c.po_id = p.po_id
			inner join prc_master_supplier as s on s.sup_id = p.sup_id
			inner join prc_master_legality as leg on leg.legal_id = s.legal_id
			where con_printStat='".$print_status."' and con_status='0' {SEARCH_STR}";
		return $this->flexi_model->generate_sql($sql,$count,$where,$flexi);
	}
	
	// MEMPERSIAPKAN DATA YANG AKAN DITAMPILKAN
	function flexigrid_ajax($print_status)
	{		
		$valid_fields = array('con_id','con_no','sup_name','con_date');
		$this->flexigrid->validate_post('con_id','asc', $valid_fields);
		
		$records = $this->flexigrid_sql(TRUE,$print_status);
				
		$this->output->set_header($this->config->item('json_header'));
		
		if ($records['count'] > 0):		
			foreach ($records['result']->result() as $row)
			{
				$record_items[] = array(
				$row->con_id,
				$row->con_no,
				$row->sup_name.', '.$row->legal_name,
				$row->con_date,
				'<a href=\'javascript:void(0)\' onclick=\'open_con('.$row->con_id.','.$print_status.')\'><img border=\'0\' src=\'./asset/img_source/icon_info.gif\'></a>'
				);
			}
		else: 
			$record_items[] = array('0','n/a','n/a','n/a','n/a');
		endif;
		
		$this->output->set_output($this->flexigrid->json_build($records['count'],$record_items));
	}
	
	// MEMBANGUN DATA FLEXIGRID
	function flexigrid_builder($title,$width,$height,$rp,$print_status) {

		//$colModel['no'] = array($this->lang->line('no'),20,FALSE,'center',0);
		$colModel['con_no'] = array($this->lang->line('con_no'),100,TRUE,'center',2);
		$colModel['supp_name'] = array('Pemasok',200,TRUE,'left',1);
		$colModel['con_date'] = array($this->lang->line('con_date'),100, TRUE,'center',0);
		$colModel['action'] = array($this->lang->line('action'),50, FALSE, 'center',0);		
		
		$ajax_model = site_url(self::$link_controller."/flexigrid_ajax/".$print_status);
		$flexi_params = $this->flexi_engine->flexi_params($width,$height,$rp,$title);
		
		return build_grid_js('print_bon_list',$ajax_model,$colModel,'con_id','desc',$flexi_params);
		
	}
	
	function index($print_status=0) {
		$cek_data = $this->flexigrid_sql(FALSE,$print_status);//$this->tbl_contrabon->get_bon_print_list();
		if ($cek_data->num_rows() > 0):
		$data['js_grid'] = $this->flexigrid_builder($this->lang->line('flex_print_bon'),520,210,8,$print_status);
		else:
		$data['js_grid'] = $this->lang->line('list_empty');
		endif;
		$data['content'] = self::$link_view.'/print_cb_main';
		$this->load->view('index',$data);
	}
	
	function print_bon_view($con_id,$print_status) {
		$idnote = 4; // untuk print kontra bon
		$note = $this->tbl_rptnote->get_note($idnote);
		$data['notes']='';
		if ($note->num_rows() > 0)
			$data['notes']=$note->row()->note;
		
		
		$get_gr_print = $this->tbl_contrabon->get_bon_print($con_id);
		
		$data['print_con'] = $get_gr_print['content'];
		$data['print_gr'] = $get_gr_print['detail'];
		$data['print_tot'] = $get_gr_print['footer'];
		$data['con_id'] = $con_id;
		
		// MATA UANG
		$data['cur_symbol'] = $this->db->query("select cur_symbol from prc_master_currency as cur inner join prc_contrabon as con on con.cur_id = cur.cur_id and con.con_id = $con_id and con.cur_id != 0")->row()->cur_symbol;
		
		
		$data['print_status'] = $print_status;

		$this->load->view(self::$link_view.'/print_cb_'.self::$ppn_status.'view',$data);

	}
	
	function after_print($con_id,$print_status,$print_count) {
		$print_date		= date('Y-m-d');
		$user_id		= $this->session->userdata("usr_id");
		//$user_id = '1';

		if ($print_status == 0):	
			$update['con_printStat']='1';
			$update['con_printDate']=$print_date; 
			$update['con_printUsr'] =$user_id;
		else:
			$update['con_printCount'] = $print_count;
			$update['con_printCountDate'] = $print_date;
		endif;
	
		$where['con_id']=$con_id;
		
		if($this->tbl_contrabon->update_bon($where,$update)):
			if ($print_status == 0):
				$get_po = $this->tbl_contrabon->get_con_term($con_id);
				if ($get_po->num_rows() > 0):
					if ($get_po->row()->po_status==1):
						$due_date      = mktime(0, 0, 0, date("m")  , date("d")+$get_po->row()->term_days, date("Y"));
						$str_due_date  = date("Y-m-d", $due_date);
						
						//$update
						$update_con['con_dueDate']=$str_due_date;
						$where_con['con_id']=$con_id;
						
						if ($this->tbl_contrabon->update_bon($where_con,$update_con)):
							
						endif;
					endif;
					echo 'ok';
				endif;
			else:
				$this->print_bon_view($con_id,$print_status);
			endif;
		endif;
	}
}
?>
