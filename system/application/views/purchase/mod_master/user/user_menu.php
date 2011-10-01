<link type="text/css" rel="stylesheet" href="<?=base_url()?>asset/javascript/jQuery/dynatree/skin-vista/ui.dynatree.css" />
<script type="text/javascript" src="<?=base_url()?>asset/javascript/jQuery/dynatree/jquery.dynatree.js" ></script>

<script type='text/javascript'>
  $(function(){
	var treeData = [
	<?php
	$this->tbl_menu->wewenang($usrid);
	echo $this->tbl_menu->get_wewenang();
	?>
	];
	
	$("#tree").dynatree({
      checkbox: true,
      selectMode: 3,
      children: treeData,
	  //minExpandLevel: 4,
      onSelect: function(select, dtnode) {
     
        var selKeys = $.map(dtnode.tree.getSelectedNodes(), function(node){
          return node.data.key;
        });
        $("#menu").val(selKeys.join(", "));
		
      },
      onDblClick: function(dtnode, event) {
        dtnode.toggleSelect();
      },
      onKeydown: function(dtnode, event) {
        if( event.which == 32 ) {
          dtnode.toggleSelect();
          return false;
        }
      },
	  onActivate: function(dtnode) {
                // A DynaTreeNode object is passed to the activation handler
                // Note: we also get this event, if persistence is on, and the page is reloaded. 
                //alert("You activated " + dtnode.data.title);
            },

      // The following options are only required, if we have more than one tree on one page: 
//        initId: "treeData",
      cookieId: "ui-dynatree-Cb3",
      idPrefix: "ui-dynatree-Cb3-"
    });

  });
</script>

<div id="tree">
</div>

<div id="rep"></div>
