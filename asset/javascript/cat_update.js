var mainid;
$('.edit_kategori').click(function() {
			var id = $(this).attr('id');
			//var code=$(
			var code = document.getElementById('code'+id).value;
			var name = document.getElementById('name'+id).value;
			mainid = id;
			$('#kategori').val(name); 
			//alert (name);
			$('#dialog').dialog('open');
})

$("#dialog").dialog({
			bgiframe: true,
			autoOpen: false,
			height: 170,
			modal: true,
			buttons: {
				'Update': function() {
						var kategori = document.getElementById('kategori').value;
					$.ajax({
						type: 'POST',
						url: 'index.php/Category/cat_update',
						data: "code="+mainid+"&kategori="+kategori,
						success: function(data) {
							$('#main_content').html(data);
							$('#dialog').dialog('close');
							alert (data);
						}
					});
					return false;
				},
				Cancel: function() {
					$(this).dialog('close');
				}
			}
		});