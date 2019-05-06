<?php
		require_once('includes/includes.php');
		require_once('src/php/functions.php');
		require_once('src/php/db.php');
		endSessionIn5H();

		if (isset($_SESSION['auth']) AND !empty($_SESSION['auth']) AND $_SESSION['auth']['confirm'] == -1){
			$userInfo= $_SESSION['auth'];
		}else{
			header('Location: index');
		}

	?>
<!DOCTYPE html>
<html>
	<?php printHead("Mon Compte",1);?>
<body>
	<?php printMenu(1); ?>
	<div class="banner"></div>
	<div id="box2">
		<br><br><br><br><br>
		<h2 align="center">
			Votre compte a été banni.<!--Une page qui affiche tout simplement un message de bannissement si l'utilisateur est banni-->
			<br>
			:(
		</h2>
	</div>
		<?php
			if (isset($_SESSION['auth']) AND !empty($_SESSION['auth'])) { ?>
			<ul type="1">
				<a href="logout">Déconnection</a>
			</ul>
		<?php	
			}
		?>
	
</body>
</html>
