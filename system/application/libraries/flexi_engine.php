<?php
class flexi_engine {
	function flexi_engine() {
		$this->CI =& get_instance();
	}
	function flexi_params($width,$height,$rp,$title,$resize = false) {
		return $arr = array(
			'height'=> $height, //default height
			'width'=> $width, //auto width
			'striped'=> true, //apply odd even stripes
			'novstripe'=> false,
			'minwidth'=> 30, //min width of columns
			'minheight'=> 80, //min height of columns
			'resizable'=> false, //resizable table
			//'url'=> false, //ajax url
			'method'=> 'POST', // data sending method
			'dataType'=> 'json', // type of data loaded
			'errormsg'=> $this->CI->lang->line('flex_error'),
			'usepager'=> true, //
			'nowrap'=> false, //
			//'page'=> 1, //current page
			//'total'=> 1, //total pages
			'useRp'=> false, //use the results per page select box
			'rp'=> $rp, // results per page
			'rpOptions'=> '[5,8,10,15,20,25,30,40]',
			'title'=> $title,
			'pagestat'=> $this->CI->lang->line('flex_info'),
			'procmsg'=> $this->CI->lang->line('flex_loading'),
			//'query'=> '',
			//'qtype'=> '',
			'nomsg'=> $this->CI->lang->line('flex_empty'),
			//'minColToggle'=> 1, //minimum allowed column to be hidden
			'showToggleBtn'=> false, //show or hide column toggle popup
			'hideOnSubmit'=> false,
			'autoload'=> true,
			'blockOpacity'=> 0.5,
			'onToggleCol'=> false,
			'onChangeSort'=> false,
			'onSuccess'=> false,
			'onSubmit'=> false, // using a custom populate function
			'draggable'=> false, // drag column by @HR13 ^^
			'resizableCol'=> $resize, // make column resizable by @HR13 ^^
			//'multisel'=> false,
			'singleSelect'=> true,
			'showButtons' => false,
			'searchText' => $this->CI->lang->line('flex_search'),
			'clearText' => $this->CI->lang->line('flex_clear'),
			'pageText' => $this->CI->lang->line('flex_page'),
			'ofText' => $this->CI->lang->line('flex_of'),
			//'showSearch' => false
			//'onRowSelect' => 'function(e,r){alert(r[0].id);}'
		);
	}
	
}
?>