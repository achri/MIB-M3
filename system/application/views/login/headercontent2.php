<?php
	// MASTER JQUERY CORE
	echo '<script type="text/javascript" src="' . base_url() . 'js/jQuery/core/jquery-1.3.2.js"></script>';
			
	if (isset($extraHeadContent)) {
		echo $extraHeadContent;
	}
?>