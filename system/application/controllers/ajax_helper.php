<?
class ajax_helper extends Controller {
	function ajax_helper() {
		parent::Controller();
	}
	
	function ajax_satuan($satuan_id = 1) {
		echo $this->db->query("select satuan_format from prc_master_satuan where satuan_id = $satuan_id")->row()->satuan_format;
	}
	
	function ajax_currency($cur_id = 1) {
		echo $this->db->query("select cur_digit from prc_master_currency where cur_id = $cur_id")->row()->cur_digit;
	}
}
?>