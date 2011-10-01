<?php
class Flexigrid_ajax extends Model {
	function Flexigrid_ajax() {
		$this->obj =& get_instance();
	}
	
	function flexigrid_model($record,$record_col) {
		$this->obj->flexigrid->validate_post('pro_id','asc');
		
		$this->obj->output->set_header($this->config->item('json_header'));
		
		if ($records['count'] > 0):		
			$no = 1;
			foreach ($records['result']->result() as $row)
			{
				foreach ($record_col as $rec_col):
					$record_items[][] = $rec_col;
				endforeach;
				$no++;
			}
		else: 
			$records['count'] += 1;
			$record_items[] = array('0','null','null','empty','empty');
		endif;
		
		$this->output->set_output($this->flexigrid->json_build($records['count'],$record_items));
	}
}
?>