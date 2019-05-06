<?php
	
	function isSelected(&$data, $arg){
		if(isset($data['sex']) AND $data['sex']==$arg){
			return 'selected="selected"';
		}else{
			return "";
		}

	}
	function printError($error){
		if (is_string($error)) {
			$error = treatString($error);
			return "<span class=\"error\">". $error . "</span><br>";
		}
	}

	function printData($array, $key){
		if (isset($array[$key]) AND !empty($array[$key])) {
			return treatString($array[$key]);
		}else{
			return "";
		}
	}

	function printFormSingup($con){
		$data=$_POST;
	?>
	<div id="form_singup">
		<label id="inscrip">Inscription</label>
		<form method="POST" action="">
			<table>
				<tr>
					<td>
						<input required type="text" name="lname" placeholder="Nom" value="<?= printData($data, "lname") ?>" /><br>
					</td>
				</tr>
				<tr>
					<td>
						<input  required type="text" name="fname" placeholder="Prénom" value="<?= printData($data, "fname") ?>" /><br>

					</td>
				</tr>
				<tr>
					<td>
						<input  required type="text" name="nname" placeholder="Pseudo" value="<?= printData($data, "nname") ?>"/><br>
						<?= printError($con->getError()['inUseNname'])?>
						<?= printError($con->getError()['lengthValid'])?>

					</td>
				</tr>
				<tr>
					<td>
						<select name="sex">
							<option value="0" <?=isSelected($data, 0)?>>Féminin</option>
							<option value="1" <?=isSelected($data, 1)?>>Masculin</option>
						</select><br>
						<?php echo printError($con->getError()["sexValid"])?>
					</td>
				</tr>
				<tr>
					<td>
						<label for="dateNaissance">Date de naissance:</label><br>
						<input  required type="date" name="dateNaissance" value="<?= printData($data, "dateNaissance") ?>"/><br>
						<?php echo printError($con->getError()["validDate"])?>
					</td>
				</tr>
				<tr>
					<td>
						<input  required type="email" name="email" placeholder="e-mail" value="<?= printData($data, "email") ?>"/><br>
						<?php echo printError($con->getError()["emailValid"])?>
						<?= printError($con->getError()['inUseEmail'])?>
					</td>
				</tr>
				<tr>
					<td>
						<input  required type="email" name="remail" placeholder="Retapez l'e-mail" value="<?= printData($data, "remail") ?>"/><br>
						<?php echo printError($con->getError()["sameEmail"])?>
					</td>
				</tr>
				<tr>
					<td>
						<input  required type="password" pattern=".{6,}"  name="pw" placeholder="Mot de passe" value="<?= printData($data, "pw") ?>"/><br>
						<?php echo printError($con->getError()["lenghtPw"])?>
						<?php echo printError($con->getError()["regexPw"])?>
					</td>
				</tr>
				<tr>
					<td>
						<input  required type="password" pattern=".{6,}"  name="rpw" placeholder="Repetez le mot de passe" value="<?= printData($data, "rpw") ?>"/><br>
						<?php echo printError($con->getError()["samePw"])?>
					</td>
				</tr>
				<tr>
					<td>
						<input  type="checkbox" name="cond" checked><label for="cond"><br>J'accepte les Conditions <br>Générales d'Utilisation. </label><br>
						<?php echo printError($con->getError()["validCond"])?>
					</td>
				</tr>
				<tr>
					<td>
						<img src="../src/php/captcha.php">
					</td>
				</tr>
				<tr>
					<td>
						<input  required type="text" name="captcha"/><br>
						<?php echo printError($con->getError()["validCaptcha"])?>
					</td>
				</tr>
				<tr>
					<td>
						<input  required type="submit" name="send"/>
					</td>
				</tr>
				
			</table>
		</form> 
	</div>
	<?php
	}
?>