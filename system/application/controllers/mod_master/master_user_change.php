<?php
class master_user_change extends MY_Controller{
	public static $link_view, $link_controller;
	function master_user_change()
	{
		parent::MY_Controller();
		$this->load->model(array('tbl_departemen','tbl_jabatan','tbl_menu','tbl_user'));
		$this->load->helper('flexigrid');
		$this->lang->load('mod_master/akun','bahasa');
		$this->lang->load('general','bahasa');
		$this->load->library(array('image_lib','pictures','flexigrid'));
		
		self::$link_controller = 'mod_master/master_user_change';
		self::$link_view = 'purchase/mod_master/user';
 		$data['link_controller'] = self::$link_controller;
		$data['link_view'] = self::$link_view;
		
		$data['link_controller_departement'] = 'mod_master/master_departemen';
		$data['link_controller_jabatan'] = 'mod_master/master_jabatan';
		
		$this->load->vars($data);
	}	
	
	function flexigrid_ajax()
	{
		//List of all fields that can be sortable. This is Optional.
		//This prevents that a user sorts by a column that we dont want him to access, or that doesnt exist, preventing errors.
		$valid_fields = array('usr_id','usr_login','usr_name','dep_id','ttl_id');
		
		$this->flexigrid->validate_post('usr_id','asc',$valid_fields);

		$records = $this->tbl_user->get_user_flex();
		
		$this->output->set_header($this->config->item('json_header'));
		
		/*
		 * Json build WITH json_encode. If you do not have this function please read
		 * http://flexigrid.eyeviewdesign.com/index.php/flexigrid/example#s3 to know how to use the alternative
		 */
		if ($records['record_count'] > 0){
		$i = 0;
		foreach ($records['records']->result() as $row)
		{
			//$dep = $this->Tbl_departemen->get_departemen($row->dep_id)->row();
			//jab = $this->Tbl_jabatan->get_jabatan($row->ttl_id)->row();
			
			$i = $i + 1;
			$record_items[] = array($row->usr_id,
			//$i,
			$row->usr_login,
			$row->usr_name,
			'<a href=\'javascript:void(0)\' onclick=\'edituser('.$row->usr_id.',"Normal")\'><img border=\'0\' src=\'./asset/img_source/button_edit.png\'></a>'
			);
		}
		}else{
			$record_items[] = array('empty');
		}
		//Print please
		$this->output->set_output($this->flexigrid->json_build($records['record_count'],$record_items));
	}

	function index($id='') {
		$data['extraHeadContent'] = "<link type=\"text/css\" rel=\"stylesheet\" href=\"". base_url()."asset/css/themes/start/ui.all.css\" />\n";
		$data['extraHeadContent'] .= "<script type=\"text/javascript\" src=\"". base_url()."asset/javascript/jQuery/tables/jquery.jeditable.js\" />\n </script>";
		$data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"".base_url()."asset/css/table/DataView.css\" />\n";
		$data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"".base_url()."asset/css/form/form_view.css\" />\n";
		$data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"". base_url()."asset/css/table/jquery.treeTable.css\" />\n";
		$data['extraHeadContent'] .= "<script type=\"text/javascript\" src=\"". base_url()."asset/javascript/jQuery/tables/jquery.treeTable.js\" />\n </script>";
		$data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"".base_url()."asset/css/flexigrid/flexigrid.css\" />\n";
		$data['extraHeadContent'] .= "<script type=\"text/javascript\" src=\"". base_url()."asset/javascript/flexigrid.js\" />\n </script>";
		$data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"".base_url()."asset/javascript/jQuery/flexigrid/css/flexigrid.css\" />\n";
		$data['extraHeadContent'] .= "<script type=\"text/javascript\" src=\"". base_url()."asset/javascript/jQuery/flexigrid/js/flexigrid.js\" />\n </script>";
		$data['extraHeadContent'] .= "<script type=\"text/javascript\" src=\"". base_url()."asset/javascript/jQuery/ajaxupload/ajaxupload.js\" />\n </script>";
		
		$data['extraHeadContent'] .= "<script type=\"text/javascript\" src=\"". base_url()."asset/javascript/jQuery/tooltip/jquery.tooltip.js\" />\n </script>";
		$data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"". base_url()."asset/javascript/jQuery/tooltip/jquery.tooltip.css\" />\n";
		
		$data['content'] = self::$link_view.'/user';
		$data['log_ids'] = $id;
		$this->load->view('index',$data);
	}
	
	function user_flexigrid($id=''){		
		//$colModel['usr_id'] = array($this->lang->line('user_flex_col_0'),20,TRUE,'left',0);
		$colModel['usr_login'] = array($this->lang->line('user_flex_col_1'),150,TRUE,'left',2);
		$colModel['usr_name'] = array($this->lang->line('user_flex_col_2'),240,TRUE,'left',2);
		$colModel['action'] = array($this->lang->line('user_flex_col_3'),40, TRUE, 'center',0);
		
		/*
		 * Aditional Parameters
		 */
		$gridParams = array(
		'width' => 560,
		'height' => 271,
		'blockOpacity' => 0.5,
		'title' => $this->lang->line('user_flex_ttl'),
		'showTableToggleBtn' => false,
		'singleSelect' => true
		);
		

		//$grid_js = build_grid_js('flex1',site_url(self::$link_controller."/flexigrid_ajax"),$colModel,'id','asc',$gridParams);
		$data['message'] = 'Delete User';
		$data['js_grid'] = '';//$grid_js;
		$data['log_ids'] = $id;
		$this->load->view(self::$link_view.'/user_list',$data);
	}
	
	function user_frm ($stats = 'Normal') {
		$data['proses'] = 'frm';
		$data['list_dep'] = $this->tbl_departemen->list_dep();
		$data['list_jab'] = $this->tbl_jabatan->list_jabatan();
		$data['error'] = '';
		$data['expandable'] = 'true';
		$data['action'] = "index.php/".self::$link_controller."/user_add";
		
		$data['log_ids'] = '';
		if ($stats != 'Normal'):
			if ($this->session):
				if ($this->session->userdata('ucat_id') == 8):
					$this->load->view(self::$link_view.'/user_add',$data);
				elseif ($this->session->userdata('usr_id') == $id):
					$data['log_ids'] = $id;
					$this->load->view(self::$link_view.'/user_add',$data);
				else:
					$this->load->view(self::$link_view.'/user_corupt');
				endif;
			endif;
		else:
			$this->load->view(self::$link_view.'/user_add',$data);
		endif;
		
		//$this->load->view('user/user_add',$data);
	}
	
	function user_frm_edit ($id,$stats = 'Normal') {
		$data['proses'] = 'Edit';
		$data['list_dep'] = $this->tbl_departemen->list_dep();
		$data['list_jab'] = $this->tbl_jabatan->list_jabatan();
		$data['list_user'] = $this->tbl_user->get_user($id);
		$data['error'] = '';
		$data['action'] = "index.php/".self::$link_controller."/user_update";
		$data['expandable'] = 'false';
		
		$data['log_ids'] = '';
		if ($stats != 'Normal'):
			if ($this->session):
				if ($this->session->userdata('ucat_id') == 8):
					$this->load->view(self::$link_view.'/user_add',$data);
				elseif ($this->session->userdata('usr_id') == $id):
					$data['log_ids'] = $id;
					$this->load->view(self::$link_view.'/user_add',$data);
				else:
					$this->load->view(self::$link_view.'/user_corupt');
				endif;
			endif;
		else:
			$this->load->view(self::$link_view.'/user_add',$data);
		endif;
		
	}
	
	function user_add()
	{
		$login = $this->input->post('logid');
		$nama = $this->input->post('nama');
		$dep = $this->input->post('departemen');
		$jab = $this->input->post('jabatan');
		$pas1 = $this->input->post('pas1');
		$pas2 = $this->input->post('pas2');
		$menu = $this->input->post('menu');
		$usrimage = $this->input->post('usrimage');
		$error = '';
		
		if ($login == ''){
			$error[] = $this->lang->line('user_input_error_1').', ';
		}
		if ($nama == ''){
			$error[] = $this->lang->line('user_input_error_2').', ';
		}
		if ($dep == ''){
			$error[] = $this->lang->line('user_input_error_3').', ';
		}
		if ($jab == ''){
			$error[] = $this->lang->line('user_input_error_4').', ';
		}
		if ($pas1 == ''){
			$error[] = $this->lang->line('user_input_error_5').', ';
		}
		if ($pas2 == ''){
			$error[] = $this->lang->line('user_input_error_6').', ';
		}
		if ($pas1 == $pas2){
			$error[] = $this->lang->line('user_input_error_7');
		}
		if ($error){
			echo $this->lang->line('user_input_error_0')." : \n".implode("\n",$error);
		}else{
				
			$cek = $this->tbl_user->cek_user($login);
			if ($cek > 0){
				echo "ada";
			}else{
								
				$id = $this->tbl_user->insert_user($login, $nama, $dep, $jab, $pas1, $pas2);
				
				if ($menu != ''){
					$this->tbl_user->insert_user_menu($id, $menu);
				}
				
				$this->tbl_user->update_pict($id, $usrimage);
				/*
					$img['upload_path'] = './uploads/temp/';
					$img['allowed_types'] = 'gif|jpg|png';
					$img['overwrite'] = TRUE;
					$img['max_size'] = '150';
					$img['max_width'] = '1024';
					$img['max_height'] = '768';
					
					$this->load->library('upload', $img);
					$image = 'usrimg';
				
				if ($this->upload->do_upload($image)){
					$dataup = $this->upload->data();
					$path1 = './uploads/img/'.$dataup['file_name'];
					$path2 = './uploads/temp/'.$dataup['file_name'];
					$config['image_library'] = 'gd2';
					$config['source_image'] = $path2;
					$config['create_thumb'] = TRUE;
					$config['new_image'] = $path1;
					$config['maintain_ratio'] = TRUE;
					$config['thumb_marker'] = '';
					$config['width'] = 75;
					$config['height'] = 100;
					$this->load->library('image_lib', $config); 
					if ($this->image_lib->resize()){
						unlink($path2);
						$this->tbl_user->update_pict($id, $dataup['file_name']);
					}
				}
				*/
				echo "sukses";
			}				
		}
	}
	
	function user_update()
	{
		$id = $this->input->post('usrid');
		$login = $this->input->post('logid');
		$nama = $this->input->post('nama');
		$dep = $this->input->post('departemen');
		$jab = $this->input->post('jabatan');
		$reset = $this->input->post('reset');
		$pas1 = $this->input->post('pas1');
		$pas2 = $this->input->post('pas2');
		$menu = $this->input->post('menu');
		$usrimage = $this->input->post('usrimage');
		$usrimage_awal = $this->input->post('usrimage_awal');
		$error = '';
		
		if ($login == ''){
			$error[] = $this->lang->line('user_input_error_1').', ';
		}
		if ($nama == ''){
			$error[] = $this->lang->line('user_input_error_2').', ';
		}
		if ($dep == ''){
			$error[] = $this->lang->line('user_input_error_3').', ';
		}
		if ($jab == ''){
		$error[] = $this->lang->line('user_input_error_4').', ';
		}
		if ($reset == 'reset'){
			if ($pas1 == ''){
				$error[] = $this->lang->line('user_input_error_5').', ';
			}
			if ($pas2 == ''){
				$error[] = $this->lang->line('user_input_error_6').', ';
			}
			if ($pas1 == $pas2){
				$error[] = $this->lang->line('user_input_error_7').', ';
			}
		}
		if ($error){
			echo $this->lang->line('user_input_error_0')." : \n".implode("\n",$error);
		}else{
			//primary upd		
			$this->tbl_user->update_user($id, $login, $nama, $dep, $jab);
			//pass upd
			if ($reset == 'reset'){	
					$this->tbl_user->update_pass($id, $pas1, $pas2);
			}
			
			// UPDATE IMG
			if ($usrimage != $usrimage_awal){
				//if (file_exists('uploads/user/'.$usrimage_awal)):
					//unlink('uploads/user/'.$usrimage_awal);
				//endif;
				$this->tbl_user->update_pict($id, $usrimage);
			}
			
			//img upd
			/*
				$img['upload_path'] = './uploads/temp/';
				$img['allowed_types'] = 'gif|jpg|png';
				$img['overwrite'] = TRUE;
				$img['max_size'] = '150';
				$img['max_width'] = '1024';
				$img['max_height'] = '768';
				
				$this->load->library('upload', $img);
				$image = 'usrimg';
				
				if ($this->upload->do_upload($image)){
					$dataup = $this->upload->data();
					$path1 = './uploads/img/'.$dataup['file_name'];
					$path2 = './uploads/temp/'.$dataup['file_name'];
					$config['image_library'] = 'gd2';
					$config['source_image'] = $path2;
					$config['create_thumb'] = TRUE;
					$config['new_image'] = $path1;
					$config['maintain_ratio'] = TRUE;
					$config['thumb_marker'] = '';
					$config['width'] = 75;
					$config['height'] = 100;
					if ($dataup['file_name'] != ''){
						$this->load->library('image_lib', $config); 
						if ($this->image_lib->resize()){
							unlink($path2);
							$this->tbl_user->update_pict($id, $dataup['file_name']);
							if ($usrimage != ''){
								unlink('./uploads/img/'.$usrimage);
							}
						}
					}
				}
				*/
			$this->tbl_user->delete_user_menu($id);
			if ($menu != ''){
				$this->tbl_user->insert_user_menu($id, $menu);
			}
			echo "sukses";
		}
	}
	
	function delete_user($id) {
		//$this->Tbl_user->delete_user($id);
		//$this->Tbl_user->delete_user_menu($id);
		$img = $this->tbl_user->get_user($id)->row();
		$userimage = $img->usr_image;
		echo $userimage;
		//$this->load->view('satuan/satuan');
	}
	
	function ajaxupload(){
		$temp_folder	= 'uploads/temp/';
		$thumb_folder	= 'uploads/user/';
		
		$filebefore = $this->input->post('gambar');
		$filename = basename($_FILES['userfile']['name']);
		
		$ext = strrchr($filename,'.');
		
		$rand = mktime();
		$md = md5($rand);
		$filename = substr($md,rand(0,strlen($md)-10),10).$ext;

		if ($filebefore != '')
			@unlink($thumb_folder.$filebefore);
		
		if (@move_uploaded_file($_FILES['userfile']['tmp_name'], $thumb_folder.$filename)) {
			/*
			$config['source_image'] = $temp_folder.$filename;
			$config['new_image'] = $thumb_folder.$filename ;
			$this->image_lib->initialize($config);
			if ($this->image_lib->resize()):
				unlink($temp_folder.$filename);*/
				echo $filename;	
			//endif;
		} 
		
	}
	
	function show_photo($filename,$thumb_folder	= 'uploads/user/') {
		echo $this->pictures->thumbs_ajax($filename,110,110,$thumb_folder);
	}
}
?>