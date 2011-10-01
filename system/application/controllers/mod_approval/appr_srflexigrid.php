<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class appr_srflexigrid extends MY_Controller {

	function appr_srflexigrid ()
	{
		parent::MY_Controller();	
		$this->load->model('tbl_sr');
		$this->load->library('flexigrid');
	}
	
	function index()
	{
		//List of all fields that can be sortable. This is Optional.
		//This prevents that a user sorts by a column that we dont want him to access, or that doesnt exist, preventing errors.
		$valid_fields = array('sr_id','sr_no','sr_date','sr_lastModified','sr_due','sr_waiting','dep_name','sr_pending','sr_ok','sr_reject','sr_emergency');
		
		$this->flexigrid->validate_post('sr_id','asc',$valid_fields);

		$records = $this->tbl_sr->sr_list();
		
		$this->output->set_header($this->config->item('json_header'));
		
		/*
		 * Json build WITH json_encode. If you do not have this function please read
		 * http://flexigrid.eyeviewdesign.com/index.php/flexigrid/example#s3 to know how to use the alternative
		 */
		if ($records['record_count'] > 0){
		$i = 0;
		$d = 0;
		foreach ($records['records']->result() as $row)
		{
			
			$i = $i + 1;
			/*
			if ($row->sr_due >= 3 ){
				$d = $d + 1;
				$record_items[] = array($row->sr_id,
				'<span style=\'color:#ff4400\'>'.addslashes($i).'</span>',
				'<span style=\'color:#ff4400\'>'.addslashes($row->sr_no).'</span>',
				'<span style=\'color:#ff4400\'>'.addslashes($row->sr_date).'</span>',
				'<span style=\'color:#ff4400\'>'.addslashes($row->sr_pending).'</span>',
				'<span style=\'color:#ff4400\'>'.addslashes($row->sr_reject).'</span>',
				'<span style=\'color:#ff4400\'>'.addslashes($row->sr_ok).'</span>',
				'<span style=\'color:#ff4400\'>'.addslashes($row->sr_lastModified).'</span>',
				'<a href=\'javascript:void(0)\' onclick=\'open_sr('.$row->sr_id.')\'><img border=\'0\' src=\'./asset/img_source/s_warn.gif\'></a>'
				);
			}else{	
				*/
				//if ($d == 0){
					$action = 'open_sr('.$row->sr_id.')';
				//}else{
				//	$action = 'alert_sr()';
				//}
				
				$record_items[] = array($row->sr_id,
				//$i,
				$row->sr_no,
				$row->sr_date,
				$row->dep_name,
				$row->sr_pending,
				$row->sr_reject,
				$row->sr_ok,
				$row->sr_lastModified,
				'<a href=\'javascript:void(0)\' onclick='.$action.'><img border=\'0\' src=\'./asset/img_source/icon_info.gif\'></a>'
				);
			//}
		}
		}else{
			$record_items[] = array('empty');	
		}
		//Print please
		$this->output->set_output($this->flexigrid->json_build($records['record_count'],$record_items));
	}
}
?>