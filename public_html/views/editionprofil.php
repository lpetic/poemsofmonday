<?php
require_once('includes/includes.php');
require_once('src/php/functions.php');
require_once('src/php/db.php');
endSessionIn5H();


if (isset($_SESSION['auth']) AND !empty($_SESSION['auth'])) {
	$userInfo= $_SESSION['auth'];
	redirectionIfBanned($userInfo["id_user"]);
}else{
	header('Location: ../../index.php');
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
		<table style="text-align: center; margin-left: auto; margin-right: auto;"><tr><td  valign="top">
			
			<?php //Code php pour la modification de chaque élément du profil

			//Modification de l'avatar

			if(isset($_FILES["avatar"]) AND !empty($_FILES['avatar']['name'])){
				$poidMax = 2097152;//2 Mo
				$extensionsValides = array('jpg', 'jpeg', 'png');//Extensions valides

				if($_FILES['avatar']['size'] <= $poidMax){//Vérification que poids < 2Mo
					$extensionsUpload = strtolower(substr(strrchr($_FILES['avatar']['name'], "."), 1));//Récupération de l'extension du fichier

					if(in_array($extensionsUpload, $extensionsValides)){//Vérification que l'extension est valide
					$timer = time();
					$chemin = "user/avatar/".$userInfo["id_user"].$timer.".".$extensionsUpload;
						$result = move_uploaded_file($_FILES['avatar']['tmp_name'], $chemin);//Stockage de l'image

						if($result){//Si l'image a bien été stocké

						if($userInfo["avatar"] != "default1.jpg" && $userInfo["avatar"] != "default0.jpg"){
								unlink("user/avatar/".$userInfo["avatar"]);//Suppression de l'image précédente si l'avatar n'est pas celui par défaut
							}

							$updateAvatar = $db->prepare("UPDATE user SET avatar = ? WHERE id_user = ?");//Requete pour modifier l'avatar
							$updateAvatar->execute(array($userInfo["id_user"].$timer.".".$extensionsUpload, $userInfo["id_user"]));
							$_SESSION['auth']['avatar'] = $userInfo["id_user"].$timer.".".$extensionsUpload;
							$userInfo= $_SESSION['auth'];
							$erreur1="Votre avatar à bien été modifié";//Message de confirmation

						} else {
							$erreur1="Il a eu une erreur, veuillez réessayer";
						}
					} else {
						$erreur1="Le format de votre image est invalide ! jpg, jpeg ou png seulement !";
					}
				} else {
					$erreur1="Votre photo de profil est trop lourde ! Limite : 2Mo";
				}
			}

			//Modification du mot de passe

			if(isset($_POST['buttonmdp'])){ 
				if(isset($_POST['oldmdp']) AND isset($_POST['newmdp1']) AND isset($_POST['newmdp2']) AND !empty($_POST['oldmdp']) AND !empty($_POST['newmdp1']) AND !empty($_POST['newmdp2'])){

			    	$nmdp1 = password_hash($_POST["newmdp1"], PASSWORD_BCRYPT);//Encryption du nouveau mot de passe

			    	if(password_verify($_POST['oldmdp'], $userInfo['pw'])){//Vérification que ancien mdp == mdp actuel
			    	if($_POST['oldmdp'] != $_POST['newmdp1']){//Vérification que ancien mdp != nouveau mdp
			    		if($_POST['newmdp1'] == $_POST['newmdp2']){//Vérification que le nouveau mdp est correctement choisis
			    			if(strlen($_POST['newmdp1'])>=5){//Vérification que le nouveau mdp possède au moins 5 caractères
									if(strlen($_POST['newmdp1']<255)){//Vérification que le nouveau mdp possède moins de 255 caracteres
										$boi = $db->prepare("UPDATE user SET pw = ? WHERE id_user = ?");//Requete pour modifier le mot de passe
										$boi->execute(array($nmdp1 ,$userInfo['id_user']));
										$erreur2="Votre mot de passe a bien été modifié !";
									} else {
										$erreur2="Votre nouveau mot de passe est trop long ! Il vout faut moins de 255 caractères.";
									}
								} else {
									$erreur2="Votre nouveau mot de passe est trop court ! Il vous faut plus de 5 caractères.";
								}
							} else {
								$erreur2="Vous n'avez pas confirmé le bon mot de passe !";
							}

						} else {
							$erreur2="Votre ancien mot de passe et votre nouveau mot de passe ne sont pas différents !";
						}
					} else {
						$erreur2="Votre ancien mot de passe est incorrect !";
					}
				} else {
					$erreur2="Vous devez remplir tout les champs !";
				}
			}
			if(isset($erreur2)){//Affichage de l'erreur correspondante en cas de problème
				echo '<tr><td colspan="2" align="center"><font color="red">'.$erreur2."</font></tr></td>";
			}

			//Modification du nom

			if(isset($_POST["editnom"]) AND !empty($_POST["editnom"]) AND $_POST["editnom"] != $userInfo["lname"]){
				if(strlen($_POST["editnom"]) > 2){
					if(strlen($_POST["editnom"])<20){//Taille du nom < 20 caractere et > 2 caracteres
						$modif = $db->prepare("UPDATE user SET lname = ? WHERE id_user = ?");
						$modif->execute(array($_POST["editnom"] ,$userInfo['id_user']));//Mise a jour dans la base de donnée du nom
						$_SESSION["auth"]["lname"] = $_POST["editnom"];
						$userInfo= $_SESSION['auth'];
						$error_confirm="Vos changements ont bien été effectués !";
					} else {
						$erreur3="Votre nouveau nom est trop long";
					}
				} else {
					$erreur3="Votre nouveau nom est trop court";
				}
			}

			//Modifiction du prénom

			if(isset($_POST["editprenom"]) AND !empty($_POST["editprenom"]) AND $_POST["editprenom"] != $userInfo["fname"]){
				if(strlen($_POST["editprenom"]) > 2){
					if(strlen($_POST["editprenom"])<255){
						$modifprenom = $db->prepare("UPDATE user SET fname = ? WHERE id_user = ?");
						$modifprenom->execute(array($_POST["editprenom"] ,$userInfo['id_user']));//Requete pour modifier le prénom dans la base de donnée
						$_SESSION["auth"]["fname"] = $_POST["editprenom"];
						$userInfo= $_SESSION['auth'];
						$error_confirm="Vos changements ont bien été effectués !";
					} else {
						$erreur4="Votre nouveau prenom est trop long";
					}
				} else {
					$erreur4="Votre nouveau prenom est trop court";
				}
			}

			//Modification du pseudo

			if(isset($_POST["editpseudo"]) AND !empty($_POST["editpseudo"]) AND $_POST["editpseudo"] != $userInfo["nname"]){
				if(strlen($_POST["editpseudo"]) > 2){
					if(strlen($_POST["editpseudo"]) < 15){

						$reqpseudo = $db->prepare('SELECT * FROM user WHERE nname=?');
						$reqpseudo->execute(array($_POST["editpseudo"]));
						$nbpseudo = $reqpseudo->rowCount();//Vérification que le pseudo est unique
						if ($nbpseudo==0) {
							$modifpseudo = $db->prepare("UPDATE user SET nname = ? WHERE id_user = ?");//Requete pour modifier le pseudo dans la base de donnée
							$modifpseudo->execute(array($_POST["editpseudo"] ,$userInfo['id_user']));
							$_SESSION["auth"]["nname"] = $_POST["editpseudo"];
							$userInfo= $_SESSION['auth'];
							$error_confirm="Vos changements ont bien été effectués !";
						} else {
							$erreur5="Ce pseudo est déjà utilisé";
						}
					} else {
						$erreur5="Votre nouveau pseudo est trop long";
					}
				} else {
					$erreur5="Votre nouveau pseudo est trop court";
				}
			}

			//Modification du mail

			if(isset($_POST["editmail1"]) AND !empty($_POST["editmail1"])){
				if(isset($_POST["editmail2"]) AND !empty($_POST["editmail2"])){
					if($_POST["editmail2"] == $_POST["editmail1"]){
						$reqmail = $db->prepare('SELECT * FROM user WHERE email=?');
						$reqmail->execute(array($_POST["editmail1"]));
						$nbmail = $reqmail->rowCount();//Vérification que le mail est unique
						if ($nbmail==0) {
							$modif = $db->prepare("UPDATE user SET email = ? WHERE id_user = ?");
							$modif->execute(array($_POST["editmail1"] ,$userInfo['id_user']));
							$_SESSION["auth"]["email"] = $_POST["editmail1"];
							$userInfo= $_SESSION['auth'];
							$error_confirm="Vos changements ont bien été effectués !";
						} else{
							$erreur6 = "L'adresse est déjà utilisée";
						}
					} else {
						$erreur6 = "Les deux adresses emails ne sont pas les mêmes";
					}
				} else {
					$erreur6 = "Merci de remplir les deux champs de l'adresse email";
				}
			}

			//Modification de la date de naissance

			if(isset($_POST["editdate"]) AND !empty($_POST["editdate"]) AND $_POST["editdate"] != $userInfo["date_naissance"]){
				$modif = $db->prepare("UPDATE user SET date_naissance = ? WHERE id_user = ?");
				$modif->execute(array($_POST["editdate"] ,$userInfo['id_user']));
				$_SESSION["auth"]["date_naissance"] = $_POST["editdate"];
				$userInfo= $_SESSION['auth'];
				$error_confirm="Vos changements ont bien été effectués !";
			}

			//Modification du sexe

			if(isset($_POST["editsexe"]) AND $_POST["editsexe"] != $userInfo["sex"]){
				$modif = $db->prepare("UPDATE user SET sex = ? WHERE id_user = ?");
				$modif->execute(array($_POST["editsexe"] ,$userInfo['id_user']));
				$_SESSION["auth"]["sex"] = $_POST["editsexe"];
				if($userInfo["avatar"] == "default0.jpg" || $userInfo["avatar"] == "default1.jpg"){//Si l'avatar est celui par défaut, on e modifie pour qu'il corresponde à un homme ou une femme selon le sexe
					$updateAvatar = $db->prepare("UPDATE user SET avatar = ? WHERE id_user = ?");
					$updateAvatar->execute(array("default".$_SESSION["auth"]["sex"].".jpg", $userInfo["id_user"]));//Changement de l'avatar dans la base de donnée
					$_SESSION['auth']['avatar'] = "default".$_SESSION["auth"]["sex"].".jpg";
				}
				$userInfo= $_SESSION['auth'];
				$error_confirm="Vos changements ont bien été effectués !";
			}

			//Modification de la biographie

			if(isset($_POST["bio"]) AND $_POST["bio"] != $userInfo["bio"]){
				if(strlen($_POST["bio"]) < 1500){//Biographie

					$modifbio = $db->prepare("UPDATE user SET bio = ? WHERE id_user = ?");
					$modifbio->execute(array(treatString($_POST["bio"]) ,$userInfo['id_user']));//On traite le texte afin que seuls des caractères autorisés y soient inscrits
					$_SESSION["auth"]["bio"] = treatString($_POST["bio"]);
					$userInfo= $_SESSION['auth'];
					$error_bio="Vos changements ont bien été effectués !";

				} else {
					$error_bio="Votre nouvelle bio est trop longue, 1500 caractères max";
				}
			}

			//Suppression du compte

			if(isset($_POST['SupprimerCompte'])){ 
				if(isset($_POST['deletePmdp1']) AND isset($_POST['deletePmdp2']) AND !empty($_POST['deletePmdp1']) AND !empty($_POST['deletePmdp2'])){
					if($_POST['deletePmdp1'] == $_POST['deletePmdp2']){
						if(password_verify($_POST['deletePmdp1'], $userInfo['pw'])){//On vérifie que les mots de passes correspondent
							$updateAvatar = $db->prepare("DELETE FROM user WHERE id_user = ?");//Suppression de la ligne dans la table de l'utilisateur
							$updateAvatar->execute(array($userInfo["id_user"]));
							header('Location: logout');//Deconnection afin d'effacer les variables de session
						} else {
							$erreur_supprimer = "Les mots de passes sont incorrect";
						}
					} else {
						$erreur_supprimer = "Les mots de passes de correspondent pas";
					}
				}
			}

			//Suppression de l'avatar

			if(isset($_POST['SupprimerAvatar'])){ 
				if($userInfo["avatar"] != "default1.jpg" && $userInfo["avatar"] != "default0.jpg"){//Si l'avatar n'est pas celui par défaut
					unlink("user/avatar/".$userInfo["avatar"]);//On efface l'avatar
					$updateAvatar = $db->prepare("UPDATE user SET avatar = ? WHERE id_user = ?");//On remet l'avatar correspondant à celui par défaut en prenant en compte homme ou femme
					$updateAvatar->execute(array("default".$userInfo["sex"].".jpg", $userInfo["id_user"]));
					$_SESSION['auth']['avatar'] = "default".$userInfo["sex"].".jpg";
					$userInfo= $_SESSION['auth'];
					$erreur_suppr_avatar="Votre avatar à bien été supprimé";
				}
			}
			?>

			<!--Formulaire pour les actions de la page-->

			<table class="tableCentre" style="float: left; margin-right: 2px;">
				<?php
				//Suppression du compte

				if(isset($_POST["SupprimerCompte"])){//Si l'utilisateur à déja appuyé sur le premier boutton pour supprimer le compte
					?>
					<tr><td style="color: red; border: 1px solid black; padding: 2px;">
						Veuillez rentrer vos identifiants avant de supprimer votre compte
					</td></tr>
					<form method= "POST" onsubmit="return confirm('Voulez-vous vraiment supprimer ce compte ?\nIl est impossible de revenir en arrière une fois cette action effectuée.');">
						<input type="hidden" name="SupprimerCompte"><!--Avertissement quand on essaye de supprimer son compte definitivement-->
						<tr><td style="text-align: right;">
							Entrez votre mot de passe : <input type="password" name="deletePmdp1">
						</td></tr>
						<tr><td style="text-align: right">
							Confirmez votre mot de passe : <input type="password" name="deletePmdp2">
						</td></tr>
						<tr><td style="color: red; border: 1px solid black; padding: 2px;"> 
							<input type="submit" name="TrueSupprimerCompte" value="Supprimer mon compte">
						</td></tr>
					</form>
					<?php
					if(isset($erreur_supprimer)){
						echo '<tr><td align="center"><font color="red">'.$erreur_supprimer."</font></tr></td>";
					}

				} else {//Sinon si l'utilisateur n'a encore rien fait ?>

					<tr><td style="padding: 3px; background-color: LightGrey">
						<form method="POST">
							<input type="submit" name="SupprimerCompte" value="Supprimer mon compte">
						</form>
					</td></tr>
					<tr><td style="background-color: black;">

					</td></tr>

					<!--Suppression de l'avatar-->

					<tr><td style="padding: 3px; background-color: LightGrey">
						<form method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer votre avatar actuel ?\nVous reprendrez un avatar donné par défault.')";>
							<input style="width: 100%" type="submit" name="SupprimerAvatar" value="Supprimer l'avatar">
						</form>
					</td></tr>
					<?php 
					if(isset($erreur_suppr_avatar)){
						echo '<tr><td align="center"><font color="red">'.$erreur_suppr_avatar."</font></tr></td>";
					}
				} ?>
			</table>

			<!--Affichage des formulaires pour la modification du profil-->

			<table class="tableCentre" border="3px">

				<?php
			//Affichage de l'avatar

				if(!empty($userInfo["avatar"])){
					?>
					<tr><td align="center" colspan="2"><img src="../user/avatar/<?php echo $userInfo['avatar']; ?>" width="300" height="300" border="3" align="center"/></td></tr>
					<?php
				}
				?>

				<!--Formulaires pour tout les changements de nom, pseudo, prenom, avatar, etc... et affichage des erreurs en cas de probleme-->

				<!--Formulaire de l'avatar-->

				<form method="POST" action="" enctype="multipart/form-data">
					<tr><td align="right" colspan="2"><label>Modifier avatar : </label><input type='file' name='avatar'> </td></tr>
					<tr><td align="center" colspan="2"><input type="submit" value="Changer l'avatar" name="subi"></tr></td>
				</form>

				<?php 
				if(isset($erreur1)){
					echo '<tr><td colspan="2" align="center"><font color="red">'.$erreur1."</font></tr></td>";
				}	
				?>

				<tr><td align="center" colspan="2">---------------------------------------------</td></tr>

				<!--Formulaire du mot de passe-->

				<form method="POST" action="">
					<tr><td align="right">Ancien mot de passe:</td><td> <input type="password" name="oldmdp"></td></tr>
					<tr><td align="right">Nouveau mot de passe:</td><td> <input type="password" name="newmdp1"></td></tr>
					<tr><td align="right">Confirmer nouveau mot de passe:</td><td> <input type="password" name="newmdp2"></td></tr>
					<tr><td align="center" colspan="2"><input type="submit" value="Modifier mot de passe" name="buttonmdp"></td></tr>
					<tr><td align="center" colspan="2">---------------------------------------------</td></tr>
				</form>

				<!--Formulaire du nom,prénom, pseudo, sexe, date de naissance et adresse mail-->

				<form method="POST" action="">

					<!--Nom-->

					<tr><td align="right">Nom:</td><td> <input type="text" name="editnom" value="<?= $userInfo['lname']?>"></td></tr>

					<?php
					if(isset($erreur3)){
						echo '<tr><td colspan="2" align="center"><font color="red">'.$erreur3."</font></tr></td>";
					}
					?>

					<!--Prenom-->

					<tr><td align="right">Prenom:</td><td> <input type="text" name="editprenom" value="<?= $userInfo['fname']?>"></td></tr>

					<?php
					if(isset($erreur4)){
						echo '<tr><td colspan="2" align="center"><font color="red">'.$erreur4."</font></tr></td>";
					}
					?>

					<!--Pseudo-->

					<tr><td align="right">Pseudonyme:</td><td> <input type="text" name="editpseudo" value="<?= $userInfo['nname']?>"></td></tr>

					<?php
					if(isset($erreur5)){
						echo '<tr><td colspan="2" align="center"><font color="red">'.$erreur5."</font></tr></td>";
					}
					?>

					<!--Sexe-->

					<tr><td align="right">Sexe:</td><td> <select name="editsexe">
						<option value="0" <?php if ($userInfo["sex"] == "0") echo "selected='selected'";?>>Femme</option>
						<option value="1" <?php if ($userInfo["sex"] == "1") echo "selected='selected'";?>>Homme</option>
					</select></td></tr>

					<!--Date de naissance-->

					<tr><td align="right">Date de naissance:</td><td> <input type="date" name="editdate" value="<?= $userInfo['date_naissance']?>"></td></tr>

					<!--Adresse mail-->

					<tr><td align="right">Ancien Email:</td><td> <?= $userInfo['email']?></td></tr>
					<tr><td align="right">Nouvel Email:</td><td> <input type="email" name="editmail1" ></td></tr>
					<tr><td align="right">Confimer nouveau mail:</td><td> <input type="email" name="editmail2"></td></tr>

					<?php
					if(isset($erreur6)){
						echo '<tr><td colspan="2" align="center"><font color="red">'.$erreur6."</font></tr></td>";
					}
					?>

					<!--Boutton de sauvegarde-->

					<tr><td align="center" colspan="2"><input type="submit" value="Sauvegarder changements"></td></tr>
					<?php
					if(isset($error_confirm)){
						echo '<tr><td align="center" colspan="2">';
						echo $error_confirm;
						echo '</tr></td>';
					} 
					?>

				</form>
			</table>

			<!--Formulaire pour la modification de la biographie avec zone de texte et bouton-->

		</td><td valign="top">
			<table class="tableCentre" border="3px">
				<form method="POST" action="">
					<tr><td align="center"><textarea name="bio" maxlength="1497" ><?php 
					$text = $userInfo['bio'];
					$text = printString($text);
					echo $text?></textarea></td></tr>
					<tr><td align="center"><input type="submit" value="Sauvegarder ma bio"></td></tr>
					<?php
					if(isset($error_bio)){
						?>
						<tr><td align="center"> <?php echo $error_bio; ?></td></tr><?php
					}
					?>
				</form>
			</table>
		</td></tr>
	</table>
</div>

<?php
if (isset($_SESSION['auth']) AND !empty($_SESSION['auth'])) { ?>
<ul type="1">
	<a href="logout">Déconnection</a>
	<a href="contact">Nous contacter</a>
</ul>
<?php	
}
?>
</body>
</html>
