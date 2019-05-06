<?php 

	function setOption($con, $number){
		return ($con->getTheme()==$number)? 'selected="selected"' : '';
	}

	function printFormEdit($con){
		$data = array('art_name'=>isset($_POST['art_name'])? $_POST['art_name'] : '', 'art_main'=>isset($_POST['art_main'])? $_POST['art_main'] : '');
	?>
	<center>
		<br><br><?=printMessageFlash()?>
		<h2>Edition de poème</h2>
		<form method="POST" action="" enctype="multipart/form-data" onsubmit="return confirm('Êtes vous sûr(e) des modifications à effectuer?');">
			<input type="text" name="art_name" placeholder="Nom du poem" value="<?php echo treatString($con->getArticle()->getArticle()['name_article'])?>" > 
			<select name="art_theme">
				<option value="1" <?=setOption($con, 1)?>>Sans theme</option>
				<option value="2" <?=setOption($con, 2)?>>Amour</option>
				<option value="3" <?=setOption($con, 3)?>>Vie</option>
				<option value="4" <?=setOption($con, 4)?>>Nature</option>
			</select> <br>
			<textarea name="art_main" placeholder="Je cherche la rime parmis des mots ..." sentences maxlength="5000" class="upload_art" ><?php echo printString($con->getArticle()->getArticle()['main_article'])?></textarea><br>
			<input type="submit" name="delete_img" value="Supprimer l'image">
			<input type="file" name="art_img[]" /> <br>
			<input type="submit" name="delete" value="Supprimer le poème">
			<input type="submit" name="send" value="Mettre à jour">
		</form>
	</center>
	<?php
	}

?>