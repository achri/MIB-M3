<style type="text/css">
	.set_ul{
		margin:0; 
		border:0; 
		list-style:none; 
		padding:0;
	}
	
	.set_li{
		margin:0; 
		border:0; 
		list-style:none; 
		padding:0;
		height:21px;
	}

	/*ul, li{margin:0; border:0; list-style:none; padding:0;}
	ul{height:21px;}*/

	h1 { font-size:18px; }
	p { line-height:18px; }

	#nicemenu { margin:0px; width:100%; margin-top:0px; border-bottom:dotted 1px #E5E5E5; }
	#nicemenu a { color:#0066CC; text-decoration:none; }
	#nicemenu a:hover { text-decoration:underline; }	
	#nicemenu li { display:inline; position:relative; }
	#nicemenu li span { position:relative; z-index:10; padding:4px 4px 4px 6px;  border-bottom:none; line-height:18px; }	
	#nicemenu li span a { font-weight:bold; padding:0 6px 0px 2px;  }	
	#nicemenu li span.over { padding:4px 3px 4px 5px;  border-top:solid 1px #E5E5E5; border-left:solid 1px #E5E5E5;  border-right:solid 1px #999999; border-bottom:solid 1px #fff;  }
	*+html #nicemenu li span.over {  border-top:solid 2px #E5E5E5; padding-bottom:3px; } /* IE6 */
	#nicemenu li span.over a { }
	#nicemenu li span.over a:hover { text-decoration:none; }
	#nicemenu li span.active { padding:4px 3px 4px 5px;  border-top:solid 1px #E5E5E5; border-left:solid 1px #E5E5E5;  border-right:solid 1px #999999; border-bottom:solid 1px #fff;  }
	*+html #nicemenu li span.active {  border-top:solid 2px #E5E5E5; padding-bottom:3px; }
	#nicemenu li span.active a { }
	#nicemenu li span.active a:hover { text-decoration:none; }	
	#nicemenu img.arrow { /*margin-left:4px;*/ cursor:pointer; }
	#nicemenu div.sub_menu { z-index: 100; display:none; position:absolute; left:0; top:0px; margin-top:18px; border-top:solid 1px #E5E5E5; border-left:solid 1px #E5E5E5; border-right:solid 1px #999999; border-bottom:solid 1px #999999; padding:4px; top:2px; width:160px; background:#FFFFFF; }
	* html #nicemenu div.sub_menu { margin-top:23px; } /* IE6 */
	*+html #nicemenu div.sub_menu { margin-top:23px; } /* IE7 */
	#nicemenu div.sub_menu a:link, 
	#nicemenu div.sub_menu a:visited, 
	#nicemenu div.sub_menu a:hover{ display:block; font-size:11px; padding:4px;}	
	#nicemenu a.item_line { border-top:solid 1px #E5E5E5; padding-top:6px !important; margin-top:3px; }
	
</style>

<script type="text/javascript">

$(document).ready(function(){

	$("#nicemenu img.arrow").mouseover(function(){ 
								
		$("span.head_menu").removeClass('active');
		
		submenu = $(this).parent().parent().find("div.sub_menu");
		
		if(submenu.css('display')=="block"){
			$(this).parent().removeClass("active"); 	
			submenu.hide(); 		
			$(this).attr('src','./asset/img/arrow_hover.png');									
		}else{
			$(this).parent().addClass("active"); 	
			submenu.fadeIn(); 		
			$(this).attr('src','./asset/img/arrow_select.png');	
		}
		
		$("div.sub_menu:visible").not(submenu).hide();
		$("#nicemenu img.arrow").not(this).attr('src','./asset/img/arrow.png');
						
	})
	.mouseover(function(){ $(this).attr('src','./asset/img/arrow_hover.png'); })
	.mouseout(function(){ 
		if($(this).parent().parent().find("div.sub_menu").css('display')!="block"){
			$(this).attr('src','./asset/img/arrow.png');
		}else{
			$(this).attr('src','./asset/img/arrow_select.png');
		}
	});

	$("#nicemenu span.head_menu").mouseover(function(){ $(this).addClass('over')})
								 .mouseout(function(){ $(this).removeClass('over') });
	
	$("#nicemenu div.sub_menu").mouseover(function(){ $(this).fadeIn(); })
							   .blur(function(){ 
							   		$(this).hide();
									$("span.head_menu").removeClass('active');
								});		
								
	$(document).click(function(event){ 		
			var target = $(event.target);
			if (target.parents("#nicemenu").length == 0) {				
				$("#nicemenu span.head_menu").removeClass('active');
				$("#nicemenu div.sub_menu").hide();
				$("#nicemenu img.arrow").attr('src','./asset/img/arrow.png');
			}
	});			   
							   
								   
});
</script>

<div id="nicemenu">
	<ul class="set_ul">		        
    <li class="set_li"><span class="head_menu"><a href="index.html">Home</a></span></li>
		        
    <li class="set_li"><span class="head_menu"><a href="index.html">Input Data</a><img src="./asset/img/arrow.png" width="18" height="15" align="top" class="arrow" /></span> 
      <div class="sub_menu"> <a href="index.html">Cek status barang di gudang</a> 
        <a href="index.html">Ubah PR ke RFQ</a> <a href="index.html">Input harga 
        perkiraan pada PETTY CASH</a> <a href="index.html">Input harga realisasi 
        pada PETTY CASH</a> <a href="index.html">Input RFQ Final</a> <a href="index.html">Your 
        Favorites</a> <a href="index.html">Your Stats</a> <a href="index.html" class="item_line">Recent 
        Activity</a> <a href="index.html">Comments You've Made</a> <a href="index.html" class="item_line">Upload 
        Photos</a> <a href="index.html" class="item_line">Your Account</a> <a href="index.html">Your 
        Profile</a> <a href="index.html" class="item_line">FlickrMail</a> </div>
    </li>
		        
    <li class="set_li"><span class="head_menu"><a href="index.html">Daftar</a><img src="./asset/img/arrow.png" width="18" height="15" align="top" class="arrow" /></span> 
      <div class="sub_menu"> 
	    <a href="index.php/Purchase/index"'>test</a> 
        <a href="index.php/Category/index">Kategory</a> 
        <a href="index.php/Kelas/index">Kelas</a> 
        <a href="index.php/Grup/index">Grup</a> 
        <a href="index.php/Departemen/index">Depatemen</a> 
        <a href="index.php/Jabatan/index">Jabatan</a> 
      </div>
    </li>
		        
    <li class="set_li"><span class="head_menu"><a href="index.html">Persetujuan</a><img src="./asset/img/arrow.png" width="18" height="15" align="top" class="arrow" /></span> 
      <div class="sub_menu"> <a href="index.html">Latest Photos</a> <a href="index.html">Contact 
        List</a> <a href="index.html">People Search</a> <a href="index.html" class="item_line">Invite 
        your Friends</a> <a href="index.html">Invite History</a> <a href="index.html">Guest 
        Pass History</a> <a href="index.html" class="item_line">Give the gift 
        of Flickr</a> </div>
    </li>
		        
    <li class="set_li"><span class="head_menu"><a href="index.html">Cetak</a><img src="./asset/img/arrow.png" width="18" height="15" align="top" class="arrow" /></span> 
      <div class="sub_menu"> <a href="index.html">Your Groups</a> <a href="index.html">Recent 
        Changes</a> <a href="index.html" class="item_line">Search for a Group</a> 
        <a href="index.html" class="item_line">Create a New Group</a> </div>
    </li>
		        
    <li class="set_li"><span class="head_menu"><a href="index.html">Laporan</a><img src="./asset/img/arrow.png" width="18" height="15" align="top" class="arrow" /></span> 
      <div class="sub_menu"> <a href="index.html">Explore Page</a> <a href="index.html">Last 
        7 Days Interesting</a> <a href="index.html">Calendar</a> <a href="index.html">A 
        Year Ago Today</a> <a href="index.html" class="item_line">World Map</a> 
        <a href="index.html">Places</a> <a href="index.html">Camera Finder</a> 
        <a href="index.html" class="item_line">Popular Tags</a> <a href="index.html">Most 
        Recent Photos</a> <a href="index.html">Creative Commons</a> <a href="index.html" class="item_line">FlickrBlog</a> 
        <a href="index.html" class="item_line">Do More with Your Photos</a> <a href="index.html">Flickr 
        Services</a> </div>
    </li>
		        
    <li class="set_li"><span class="head_menu"><a href="index.html">Keluar</a></span> </li>
		    </ul>
		</div>
<div id="main_content">