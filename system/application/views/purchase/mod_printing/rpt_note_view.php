<script type="text/javascript">
$(document).ready(function() {

	$('#dialog').dialog({
		autoOpen: false,
		modal: true,
		buttons: { 'OK' : function() {
			$('#dialog').dialog('close');
			}
		}
		});

	$('#dialog1').dialog({
		autoOpen: false,
		modal: true,
		buttons: { 'Cancel' : function() {
			$('#dialog1').dialog('close');
			}, 'Yes' :function(){
				var id = $('#varnote').val();
				$.ajax({
					type: 'POST',
					url: 'index.php/rpt_note/set_clearnotes/'+id,
					data: $(this).serialize(),
					success: function(data) {
						$('#dialog1').dialog('close');
					}
				});
				return false;
			}
		}
		});
	
	$('#frmnote').submit(function() {
			$('#frmnote').ajaxSubmit({
				type: 'POST',
				beforeSubmit: validate,
				url: 'index.php/rpt_note/add_notes',
				cache: false,
				success: function(data) {
					$('#result').text('Note Berhasil Diupdate');
					$('#dialog').dialog('open');
					$('#varnote').val('0');
					clears();
				}
			});
	});

	function validate(formData, jqForm, options) {
		for (var i = 0; i < formData.length; i++) {
			if ($(formData[i]).attr('name') == 'content') {
				$(formData[i]).attr('value', FCKeditorAPI.GetInstance('content').GetHTML());
				var fucking_val = FCKeditorAPI.GetInstance('content').GetHTML();
			}
		}
		var id = $('#varnote').val();
		if (id == 0){
			$('#result').text('Note yang akan diubah belum dipilih');
			$('#dialog').dialog('open');
			return false;
		}else if (fucking_val == ''){
			$('#result').text('Note yg akan ditampilkan belum diisi');
			$('#dialog').dialog('open');
			return false;
		}
	}
});

function clears(){
	var fckEditor = FCKeditorAPI.GetInstance("content");
	//fckEditor.SetHTML removes any current event listeners!
	fckEditor.EditorDocument.body.innerHTML = "";
	$('#varnote').val('0');
}

function clearnote(){
	var id = $('#varnote').val();
	if (id == '0'){
		$('#result').text('Note yang akan diubah belum dipilih');
		$('#dialog').dialog('open');
	}else{
		$('#result1').text('Anda Yakin Note dibersihkan??');
		$('#dialog1').dialog('open');
	}
}
</script>
<h2>Catatan Print</h2>
<form id="frmnote" action="javascript:void();">
<?php
echo "Note Yang Akan Diisi : <select name='varnote' id='varnote'>
		<option value='0'> -[pilih]- </option>";	
	foreach ($varnote->result() as $var): 
		echo "<option value='".$var->id."'>".$var->var_name."</option>";
	endforeach;
echo "</select> &nbsp; &nbsp; <input type='button' value='clear note' onclick='clearnote()'> <br/><br/>";
echo $this->fckeditor->Create() ;
?>
<br/>
<input type="submit" value="submit"></input>
<input type="button" value="reset" onclick="clears()"></input>
</form>

<div id="dialog" title="Konfirmasi">
	<p><div id="result"></div></p>
</div>
<div id="dialog1" title="Konfirmasi">
	<p><div id="result1"></div></p>
</div>