<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class appr_rfq_serviceflexigrid extends MY_Controller {

	function appr_rfq_serviceflexigrid()
	{
		parent::MY_Controller();	
		$this->load->model('tbl_rfq_service');
		$this->load->library('flexigrid');
	}
	
	function index()
	{
		//List of all fields that can be sortable. This is Optional.
		//This prevents that a user sorts by a column that we dont want him to access, or that doesnt exist, preventing errors.
		$valid_fields = array('srfq_id',	'srfq_no', 'srfq_date', 'srfq_printDate', 'item_waiting');
		
		$this->flexigrid->validate_post('srfq_id','asc',$valid_fields);

		$records = $this->tbl_rfq_service->srfq_list_appr();
		
		$this->output->set_header($this->config->item('json_header'));
		
		/*
		 * Json build WITH json_encode. If you do not have this function please read
		 * http://flexigrid.eyeviewdesign.com/index.php/flexigrid/example#s3 to know how to use the alternative
		 */
		if ($records['record_count'] > 0){
		$i = 0;
			foreach ($records['records']->result() as $row)
			{
				$i = $i + 1;			
				$record_items[] = array($row->srfq_id,
				//$i,
				$row->srfq_no,
				$row->srfq_date,
				$row->srfq_printDate,
				$row->item_waiting,
				'<a href=\'javascript:void(0)\' onclick=\'open_srfq_manaj('.$row->srfq_id.')\'><img border=\'0\' src=\'./asset/img_source/icon_info.gif\'></a>'
				);
			}
		}else{
			$record_items[] = array('empty');
		}
		//Print please
		$this->output->set_output($this->flexigrid->json_build($records['record_count'],$record_items));
	}
}
?>