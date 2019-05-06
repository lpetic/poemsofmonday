<?php

	function printFormLogin($con){ ?>
		<div id="form_login">
			<label>Connexion</label>
			<form method="POST" enctype="multipart/form-data" action="">
				<input type="email" name="clogin" required="required"  placeholder="Login" value="<?=defaultData($con); ?>" maxlength="255" minlength="4"><br>
				<input type="password" name="cpw" required="required"  placeholder="Mot de passe" minlength="6" maxlength="255"><br>
				<?php 
				printError($con, "clogin");
				printError($con, "cpw");
				if ($con->numberOfTriesOverflow()==true) { ?>
					<img src="../src/php/captcha.php"><br>
					<input  type="text" name="captcha" required="required" maxlength="6" minlength="4"/><br>
				<?php printError($con, "captcha");
				}?>
				<input id="cremember" type="checkbox" name="remember">
				<label id="remember" for="remember">Se souvenir de moi</label><br>
				<span id="forgot"><a href="forgot">Mot de passe oublié?</a></span><br><br>
				<input type="submit" name="send"><br>
			</form>
		</div>
		<?php
	}

	function defaultValuesLoginAfterPut($con){
		return (isset($con->getData()["clogin"])) ? $con->getData()["clogin"] : "" ;
	}

	function defaultData($con){
		if(isset($con->getData()['clogin']) AND !empty($con->getData()['clogin'])){
			return $con->getData()['clogin'];
		}elseif (isset($_COOKIE['login']) AND !empty($_COOKIE['login'])) {
			return $_COOKIE['login'];
		}else{
			return "";
		}
	}

	function printError($con, $type){
		if (isset($_POST['send']) AND isset($con->getError()[$type]) AND $con->getError()[$type]!==false){?>
			<span class="error"><?= $con->getError()[$type];?></span><br>
		<?php
		}else{
		?>
			<br>
		<?php
		}
	}
?>