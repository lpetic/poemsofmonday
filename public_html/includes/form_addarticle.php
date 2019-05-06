<?php 

	function setOption($number){
		if (isset($_POST['art_theme']) AND !empty($_POST['art_theme'])) {
			return (intval($_POST['art_theme'])==$number)? 'selected="selected"' : '';
		}
	}

	function printFormAdd($con){
		$data = array('art_name'=>isset($_POST['art_name'])? $_POST['art_name'] : '', 'art_main'=>isset($_POST['art_main'])? $_POST['art_main'] : '');
	?>
	<center>
		<br><br><?=printMessageFlash()?>
		<form method="POST" action="upload" enctype="multipart/form-data">
			<input type="text" name="art_name" placeholder="Nom du poeme" value="<?= printString($data['art_name'])?>" > 
			<select name="art_theme">
				<option value="0" <?= setOption(0)?>>Sans theme</option>
				<option value="1" <?= setOption(1)?>>Amour</option>
				<option value="2" <?= setOption(2)?>>Vie</option>
				<option value="3" <?= setOption(3)?>>Nature</option>
			</select> <br>
			<textarea name="art_main" placeholder="Je cherche la rime parmis des mots ..." rows="20" cols="70" maxlength="5000" id="upload_art"><?= printString($data['art_main'])?></textarea><br>
			<input type="file" name="art_img[]" /> <br>
			<input type="submit" name="send" value="Valider" >
		</form>
	</center>
	<?php
	}

?>