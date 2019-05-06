<?php
require_once('includes/includes.php');
require_once('src/php/functions.php');
require_once('src/php/db.php');
endSessionIn5H();

		//Redirection vers l'index si la personne tentant d'acceder a la page n'est pas connectée ou ne possède pas les droits requis
if (isset($_SESSION['auth']) AND !empty($_SESSION['auth'])) {
	$userInfo= $_SESSION['auth'];
	redirectionIfBanned($userInfo["id_user"]);
}else{
	header('Location: index');
}if($userInfo['grade'] < 2){
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
		<table style="margin-left: auto; margin-right: auto;">
			<tr><td>

				<!--Code php pour la logique de chaque élément de la page de modération-->

				<?php

		if(isset($_GET["ban"])){//Si ban est passé en GET et que l'utilisateur passé en parametre est moins gradé que l'utilisateur en cours, il est banni
			$graderequete = $db->prepare("SELECT grade FROM user WHERE id_user=?");
			$graderequete->execute(array($_GET['ban']));
			$result = $graderequete->fetch();
			$graderequete = $result["grade"];

			if($graderequete < $userInfo["grade"]){//Vérification du grade
				$bannir = $db->prepare("UPDATE user SET confirm = ? WHERE id_user = ?");
				$bannir->execute(array(-1, $_GET["ban"]));
			}
		}

		if(isset($_GET["unban"])){//Même chose mais avec le débanissement							
			$graderequete = $db->prepare("SELECT grade FROM user WHERE id_user=?");
			$graderequete->execute(array($_GET['unban']));
			$result = $graderequete->fetch();
			$graderequete = $result["grade"];

			if($graderequete < $userInfo["grade"]){
				$debannir = $db->prepare("UPDATE user SET confirm = ? WHERE id_user = ?");
				$debannir->execute(array(1, $_GET["unban"]));
			}
		}		

		if(isset($_GET["id"]) && !empty($_GET["id"])){//Si l'utilisateur a selectionné un topic crée par un autre utilisateur, avec $_GET["id"] l'id du topic

			if(isset($_POST["messageSuite"]) && !empty($_POST["messageSuite"])){//Si l'utilisateur a rentré un message
				$envoi_message =$db->prepare("INSERT INTO plainte_message (id_sender, message, id_topic, date_message) VALUES (?,?,?,NOW())");
				$envoi_message->execute(array($userInfo["id_user"], encryptData(treatString($_POST["messageSuite"])), $_GET["id"]));//On l'insert dans la table du topic
			} else {
				if(empty($_POST["messageSuite"]) && isset($_POST["messageSuite"]))
					$erreur3 = "Veuillez entrer un message";//Message d'erreur
			}

			if(isset($_GET["s"]) && !empty($_GET["s"])){//Marquage de la résolution d'un topic avec $_GET["s"]
				$bannir = $db->prepare("UPDATE plainte_topic SET solved = 1 WHERE id_topic = ?");//On insere la valeur dans la table
				$bannir->execute(array($_GET["id"]));
				header("Location: administration");//Redirection vers la page d'administration car le topic est désormais fermé
			}

//---------------------------------------------------------------------------------------- Formulaires

				?>
				<form method="POST" action=""><!--Formulaire pour envoyer des messages dans des topics-->
					<table class="tableCentre" border="3px" ">
						<tr><td><textarea placeholder="Message" name="messageSuite"></textarea></td></tr>
						<tr><td><input type="submit" value="Envoyer le message"></td></tr>

						<?php
						if(isset($erreurMessage)){?>
							<tr><td align="center" style="color: red"><?php echo $erreurMessage; ?></td></tr>
						<?php
					}
					?>
					</table>
				</form>
				</td>
				<td valign="top">
				<table class="tableCentre" border="3px" ><!--Affichages des topics non résolus-->
					<tr>
						<td align="center">Auteur</td>
						<td align="center">Messages</td>
						<td align="center">Date de création</td>
					</tr>
				<?php

				$requete = $db->query("SELECT * FROM plainte_message WHERE id_topic=".$_GET["id"]." ORDER BY date_message");//Récupération des messages du topic selectionné

				while($resultat = $requete->fetch()){
					?>
					<tr>
						<?php
						if($resultat['id_sender'] == $userInfo['id_user']){//Affichage des messages avec couleur pour plus de clarté
							?>
							<td align="center"><?php echo $userInfo["nname"]; ?></td>
							<?php
						} else {
							?>
							<td align="center"><?php echo pseudoFromId($resultat["id_sender"]); ?></td>
							<?php
						}
						if($resultat['id_sender'] == $userInfo['id_user']){
							?><td align="right" style="max-width: 500px; padding-left: 100px; word-break: break-all; white-space: pre-wrap;"><div style="background-color: #bad4ff; padding: 4px; border: 1px solid; display: inline-block;"><?php echo decryptData($resultat["message"]); ?><div></td><?php
						} else {
							?><td align="left" style="max-width: 500px; padding-right: 100px; word-break: break-all; white-space: pre-wrap;"><div style="background-color: #bad4ff; padding: 4px; border: 1px solid;display: inline-block;"><?php echo decryptData($resultat["message"]); ?></div></td>
							<?php
						}
						?>

						<td align="center"><?php echo $resultat['date_message']; ?></td>
					</tr>
					<?php
				}
				?>
				<tr>
					<?php echo '<td align="center" colspan="3"><div style="background-color:#bad4ff; width:250px; margin:2px; border: 1px solid"><a style="text-decoration: none; color:Black;" href="administration?id='.$_GET["id"].'&s=1">';echo "Marquer l'article comme résolu</div></a></td>'";?><!--Lien pour marquer l'article comme résolu--> 
					</tr>
				</table>
			</table>
			</td>
			</pre>
<?php

		} else { //Si l'utilisateur n'a PAS selectionné un topic parmis la liste des topics non résolus

		//On affiche la liste des utilisateurs
			?>

			<table class="tableCentre" border="3px">
				<tr> <!--Affichage des noms des colonnes-->
					<td align="center">Pseudo</td>
					<td align="center">id</td>
					<td align="center">Grade</td>
					<td align="center">Date d'inscription</td>
					<td align="center">Bannir utilisateur</td>
				</tr>
				<br>
				<form method="POST" action="">

					<?php

				//Récupération des données de la liste des utilisateurs
					$requete = $db->query("SELECT * FROM user");

					while($resultat = $requete->fetch()){

					//Modification du pseudo d'un utilisateur
						if(isset($_POST["newpseudo".$resultat["id_user"]]) AND !empty($_POST["newpseudo".$resultat["id_user"]]) AND $_POST["newpseudo".$resultat["id_user"]] != $resultat["nname"]){
							if(strlen($_POST["newpseudo".$resultat["id_user"]]) > 2){
								if(strlen($_POST["newpseudo".$resultat["id_user"]]) < 15){
									$reqpseudo = $db->prepare('SELECT * FROM user WHERE nname=?');
									$reqpseudo->execute(array($_POST["newpseudo".$resultat["id_user"]]));
									$nbpseudo = $reqpseudo->rowCount();
									if ($nbpseudo==0) {
										$modifpseudo = $db->prepare("UPDATE user SET nname = ? WHERE id_user = ?");
										$modifpseudo->execute(array($_POST["newpseudo".$resultat["id_user"]] ,$resultat['id_user']));
										$resultat["nname"] = $_POST["newpseudo".$resultat["id_user"]];
										$error_confirm="Vos changements ont bien été effectués !";
									} else {
										$erreur1="Ce pseudo est déjà utilisé";
									}
								} else {
									$erreur1="Ce nouveau pseudo est trop long";
								}
							} else {
								$erreur1="Ce nouveau pseudo est trop court";
							}
						}

					//Modification du grade d'un utilisateur
						if(isset($_POST["newgrade".$resultat["id_user"]]) AND $_POST["newgrade".$resultat["id_user"]] != $resultat["grade"]){
							$modifgrade = $db->prepare("UPDATE user SET grade = ? WHERE id_user = ?");
							$modifgrade->execute(array($_POST["newgrade".$resultat["id_user"]] ,$resultat['id_user']));
							$resultat["grade"] = $_POST["newgrade".$resultat["id_user"]];
							$error_confirm="Vos changements ont bien été effectués !";
						}
						?>

						<tr>
							<!--Affichage des données récupérées précédement-->
							<?php
							if($resultat["grade"] < $userInfo["grade"]){ ?>
							<td align="center"><input type="text" name="<?php echo "newpseudo".$resultat["id_user"];?>" value="<?php echo $resultat["nname"];?>"></td><?php
						} else { ?>
						<td align="left" style="background-color: #8c8c8c; border-color:#0f0f0a; border-collapse: collapse; border-width: 2px"><?php echo $resultat["nname"];?></td><?php
					}
					?>

					<td align="center"><?php echo $resultat["id_user"];?></td>


					<!--Affichage des grades des utilisateurs, il est impossible de modifier le grade d'un utilisateur mieux gradé que soit-->
					<?php if($resultat['grade'] < $userInfo['grade']){ ?>
					<td align="center"><select name="<?php echo "newgrade".$resultat["id_user"];?>">

						<option value="0" <?php if ($resultat["grade"] == "0") echo "selected='selected'";?>>Utilisateur</option>
						<option value="1" <?php if ($resultat["grade"] == "1") echo "selected='selected'";?>>Auteur</option>
						<?php if($userInfo['grade'] == 3){?>
						<option value="2" <?php if ($resultat["grade"] == "2") echo "selected='selected'";?>>Modérateur</option>
						<?php }

					} else {
						if($resultat['grade'] == 0){ echo '<td align="center">Utilisateur</td>';} else if ($resultat['grade'] == 1){ echo '<td align="center">Auteur</td>';} else if($resultat['grade'] == 2){ echo '<td align="center">Modérateur</td>';} else if($resultat['grade'] == 3){ echo '<td align="center">Administrateur</td>';}?></td><?php
					} ?>

					<td align="center"><?php echo $resultat["date_inscription"];?></td>

					<!--Bannir un utilisateur-->
					<?php 
					if($resultat["grade"] < $userInfo["grade"] && ($resultat["confirm"] == 0 || $resultat["confirm"] == 1)){
						echo '<td align="center" style="background-color: #339933;"><a href="administration?ban='.$resultat["id_user"].'" style="color: black;">BANNIR</a></td>';

					} else if($resultat["grade"] < $userInfo["grade"]) {
						echo '<td align="center" style="background-color: #ff3333;"><a href="administration?unban='.$resultat["id_user"].'" style="color: black;">DEBANNIR</a></td>';

					} else if($resultat["confirm"] == 0 || $resultat["confirm"] == 1){
						echo '<td align="center" style="background-color: #339933; color: black;">NON BANNI</a></td>';
					} else {
						echo '<td align="center" style="background-color: #ff3333; color: black;">BANNI</a></td>';
					} 
					?>
				</tr>

				<?php
					} //Fin de la boucle while de récupération des données des utilisateurs
					?>

					<tr><td align="center" colspan="5"><input type="submit" value="Sauvegarder les changements"></td></tr>
				</form>

				<!--Affichage des erreurs en cas de problème avec les changements-->
				<?php
				if(isset($erreur1)){
					echo '<tr><td colspan="5" align="center"><font color="red">'.$erreur1."</font></tr></td>";
				}
				if(isset($error_confirm)){
					echo '<tr><td colspan="5" align="center"><font color="red">'.$error_confirm."</font></tr></td>";
				}
				?>
			</table>
		</td>
		<td>
			<table class="tableCentre" border="3px"> 
				
				<?php
				$requete = $db->query("SELECT * FROM plainte_topic WHERE solved=0 ORDER BY date_creation");//Récupération de la liste de topics non résolus
				$nbRow = $requete->rowCount();
				if($nbRow > 0){//Si il existe des topics non résolus, on affiche les titres et la liste des topics un part un
					?>
					<tr>
						<td align="center">Auteur</td>
						<td align="center">Objet</td>
						<td align="center">Date de création</td>
					</tr>

					<?php
					while($resultat = $requete->fetch()){
						?>

						<tr>
							<td align="center"><?php echo pseudoFromId($resultat["id_sender"]); ?></td>
							<?php echo '<td align="center"><a href="administration?id='.$resultat["id_topic"].'">'.decryptData($resultat["objet"]).'</a></td>';?><!--Lien permettant de selection un topic-->
							<td align="center"><?php echo $resultat['date_creation']; ?></td>
						</tr>
						<?php
					}
				} else {
					?><tr>
						<td align="center" rowspan="3">Il n'y a pas de ticket en attente</td><!--Message s'affichant si il n'existe pas de topic non résolu-->
						</tr><?php
					}
					?>
			</table>
		</td>
		<?php
		} 
		?>
	</tr>
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