<?php
class pagina_lib {
	function pagina_lib() {
		$this->obj =& get_instance();
	}
	
	function pagina($site,$sql,$limit,$uri) {
        $pos = $this->pagina_pos($uri);
        //$pos = $this->obj->uri->segment($uri);
        $num_rows = $this->obj->db->query($sql)->num_rows();
   
        $return['pagina_pos'] = $this->pagina_list($site,$num_rows,$pos,$limit);
        
        $sql .= " LIMIT ".$return['pagina_pos'].", $limit";
        $return['result'] = $this->obj->db->query($sql);
        
        return $return;
    }
    
    function pagina_list($site,$num_rows,$pos,$limit) {
    	$this->obj->load->library('pagination');
    	
        $config['base_url']     = site_url($site);
        $config['total_rows']   = $num_rows;
        $config['per_page']     = $limit;
        $config['cur_page']     = $pos-1;
        //$config['enable_query_strings'] = FALSE;
                    
        $this->obj->pagination->initialize($config); 
       	
        return ($pos);
    } 

    function pagina_pos($uri=5) {
        $pos=0;
        if (is_numeric($this->obj->uri->segment($uri))):
            $pos = $this->obj->uri->segment($uri);
        else:
            if (is_numeric($this->obj->input->post("pos"))):
                $pos = $this->obj->input->post("pos");
            endif; 
        endif;

        return ($pos);
    }
    
    
}
?>