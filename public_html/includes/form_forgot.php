<?php

	function printMain($db, $forgot){
		if(isset($_GET['recov']) AND !empty($_GET['recov'])){
			$goodCode = $forgot->verificationCode($db);
			if ($goodCode){		
				$forgot->setNewPassword($db);
				formSetNewPassword($forgot);
			}		
		}else{
			$forgot->sendMailToUser($db);
			formSendMailToUser($forgot);
		}
	}
	
	function formSendMailToUser($forgot){?>
		<div id="form_recov">
			<label>Récuperation mot de passe</label>
			<form method="POST">
				<input type="email" name="login" placeholder="Votre adresse email">
				<input type="submit" name="send"><br>
				<?php 
				printError($forgot, "emailValid");
				printError($forgot, "notExist");
				printNotif($forgot);
				?>
			</form>
		</div>
	<?php
	}

	function formSetNewPassword($forgot){?>
		<div id="form_recov">
			<label>Récuperation mot de passe</label>
			<form method="POST">
				<input type="password" name="pw" placeholder="Tapez votre mot de passe"><br>
				<input type="password" name="rpw" placeholder="Retapez votre mot de passe"><br>
				<input type="submit" name="send_2">
				<?php 
					printError($forgot, "input");
					printError($forgot, "same");
					printError($forgot, "regex");
				?>
			</form>
		</div>
	<?php
	}

	function printError($forgot, $type){
		if (isset($_POST['send']) OR isset($_POST['send_2']) AND isset($forgot->getError()[$type]) AND !is_bool($forgot->getError()[$type])){?>
			<span class="error"><?= $forgot->getError()[$type];?></span><br>
		<?php
		}else{
		?>
			<br>
		<?php
		}
	}

	function printNotif($forgot){
		if (isset($_POST['send']) && !$forgot->getError()['emailValid'] && !$forgot->getError()['notExist']){?>
			<span class="notif">Un mail vient de vous être envoyé pour récupérer votre mot de passe.</span><br>
		<?php
		}
	}
?>