<?php
class Purchase extends MY_Controller{
	
	function Purchase()
	{		
		parent::MY_Controller();
		$this->load->model('tbl_menu');
	}

	function index()
	{
		$usr_id = $this->session->userdata("usr_id");
		$data["USR_ID"] = $usr_id;

		//--get user name--
		$sql = "select usr_name, date_format(lastTime_log, '%d-%m-%Y') as lastDate_log,
				date_format(lastTime_log, '%H : %i') as lastTime_log,
				lastIP_log, date_format(newTime_log, '%d-%m-%Y') as newDate_log,
				date_format(newTime_log, '%H : %i') as newTime_log,
				newIP_log,
				date_format(offTime_log, '%d-%m-%Y') as offDate_log,
				date_format(offTime_log, '%H : %i') as offTime_log,
				offIP_log
				from prc_sys_user where usr_id='$usr_id'";
		$rs  = $this->db->query($sql);
		foreach($rs->result() as $row_log) {
			$gnewip = $row_log->newIP_log;
			if (strpbrk($gnewip,',')):
				$snewip = explode(',',$gnewip);
				$newip = $snewip[0];
			else:
				$newip = $gnewip;
			endif;
			$glastip = $row_log->lastIP_log;
			if (strpbrk($glastip,',')):
				$slastip = explode(',',$glastip);
				$lastip = $slastip[0];
			else:
				$lastip = $glastip;
			endif;
			$goffip = $row_log->offIP_log;
			if (strpbrk($goffip,',')):
				$soffip = explode(',',$goffip);
				$offip = $soffip[0];
			else:
				$offip = $goffip;
			endif;
			
			$usr_name = strtoupper($row_log->usr_name);
			$data["USR_NAME"] = $usr_name;
			$data["NEWIP_LOG"] = $newip;
			$data["NEWTIME_LOG"] = $row_log->newTime_log;
			$data["NEWDATE_LOG"] = $row_log->newDate_log;
			$data["LASTIP_LOG"] = $lastip;
			$data["LASTTIME_LOG"] = $row_log->lastTime_log;
			$data["LASTDATE_LOG"] = $row_log->lastDate_log;
			$data["OFFTIME_LOG"] = $row_log->offTime_log;
			$data["OFFDATE_LOG"] = $row_log->offDate_log;
			$data["OFFIP_LOG"] = $offip;
		}

		$this_day = date('Y-m-d');
		$sql = "select motiv_id, motiv_word from prc_master_motivation where is_active='1' and date_format(active_date,'%Y-%m-%d')='$this_day'";
		$rs  = $this->db->query($sql);
		if($rs->num_rows() > 0) {
			$data["MOTIVATION"] = $rs->row()->motiv_word;
		}
		else {
			$sql = "select motiv_id, motiv_word from prc_master_motivation where is_active='0' limit 1";
			$rs  = $this->db->query($sql);
			if($rs->num_rows() > 0) {
				$sql = "update prc_master_motivation set is_active='1' where motiv_id='".$rs->row()->motiv_id."'";
				if ($this->db->query($sql))
					$data["MOTIVATION"] = $rs->row()->motiv_word;
			}
			else {
				$sql = "update prc_master_motivation set is_active='0'";
				$this->db->query($sql);
				$sql = "select motiv_id, motiv_word from prc_master_motivation where is_active='0' limit 1";
				$rs  = $this->db->query($sql);
				if($rs->num_rows() > 0) {
					$sql = "update prc_master_motivation set is_active='1' where motiv_id='".$rs->row()->motiv_id."'";
					if ($this->db->query($sql))
					$data["MOTIVATION"] = $rs->row()->motiv_word;	
				}
			}
		}
		
		$data['content'] = 'home/main';
		$this->load->view('index',$data);
	}

}
?>