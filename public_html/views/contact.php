<?php
require_once('includes/includes.php');
require_once('src/php/functions.php');
require_once('src/php/db.php');
endSessionIn5H();


if (isset($_SESSION['auth']) AND !empty($_SESSION['auth'])) {
	$userInfo= $_SESSION['auth'];
	redirectionIfBanned($userInfo["id_user"]);
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

		<?php
		if($userInfo["grade"] > 1){
			header("Location: administration");
		}

		if(isset($_POST["message"]) && isset($_POST["objet"]) && !empty($_POST["message"]) && !empty($_POST["objet"] )){//Si l'utilisateur a renseigné un objet et un message

		$message = encryptData(treatString($_POST['message']));//Encryption du message
		$objet = encryptData(treatString($_POST['objet']));//Encryption de l'objet

		$envoi_topic=$db->prepare("INSERT INTO plainte_topic (id_sender, objet, message, date_creation) VALUES (?,?,?,NOW())");//Preparation de la requete d'insertion
		$envoi_topic->execute(array($userInfo["id_user"], $objet, $message));//Execution de la requete d'insertion du topic

		$id=$db->query("SELECT id_topic FROM plainte_topic WHERE id_sender=".$userInfo['id_user']." AND date_creation = NOW()");//Création de la variable $id pour l'id du topic crée
		$resultat = $id->fetch();
		$id = $resultat['id_topic'];

		$envoi_message =$db->prepare("INSERT INTO plainte_message (id_sender, message, id_topic, date_message) VALUES (?,?,?,NOW())");//On insert dans la deuxieme table un message correspondant
		$envoi_message->execute(array($userInfo["id_user"], $message, $id));

		$erreur_confirm ="Votre message à bien été envoyé, vous recevrez une réponse sous peu";

	} else {
		if(empty($_POST["message"]) && isset($_POST["message"])){
			$erreur1="Veuillez entrer un message";//Messages d'erreurs
		} if(empty($_POST["objet"]) && isset($_POST["objet"])){
			$erreur2="Veuillez entrer un objet";
		}
	}

	if(!isset($_GET["id"]) || empty($_GET["id"])){ //Si l'utilisateur n'a pas encore selectionné de topic existant
	?>

	<pre>
		<table style="margin-left: auto; margin-right: auto;"><tr><td><!--Affichage des boites de texte, objet, et boutton pour l'envoi d'un topic-->
			<form method="POST" action="">
				<table border="3px" bgcolor="#404040" style="border-style:solid; border-color:#0f0f0a; border-collapse: collapse;">
					<tr><td><input type="text" name="objet" placeholder="Objet"></td></tr>
					<tr><td><textarea placeholder="Message" name="message"></textarea></td></tr>
					<tr><td><input type="submit" value="Soumettre la requete"></td></tr>
					<?php
					if(isset($erreur1)){?>
					<tr><td align="center" style="color: red"><?php echo $erreur1; ?></td></tr><!--Affichage des erreurs si les champs sont incorrects-->
					<?php
				}
				if(isset($erreur2)){?>
				<tr><td align="center" style="color: red"><?php echo $erreur2; ?></td></tr>
				<?php
			}
			?>
		</table>
	</form>
</td>
<td>
	<table border="3px" bgcolor="#404040" style="border-style:solid; border-color:#0f0f0a; border-collapse: collapse;">

	<?php
	$requeteIsEmpty = $db->query("SELECT * FROM plainte_topic WHERE id_sender=".$userInfo['id_user']." ORDER BY date_creation");//On regarde si il existe déja des topics existant crées par cet utilisateur
	$nbEmpty = $requeteIsEmpty->rowCount();//Comptage du nombre de topic
		
	if($nbEmpty != 0){//Si un topic existe
		echo '<tr>
		<td align="center">Auteur</td>
		<td align="center">Objet</td>
		<td align="center">Date de création</td>
		<td align="center">Etat du post</td>
		</tr>';
	} else {//Sinon
		?>
		<tr>
			<td>Vous n'avez pas encore créé de ticket</td>
		</tr>
		<?php
	}

	//Affichage des topics existants, si il n'y en a pas, le code suivant n'affiche rien
	$requeteAffichage = $db->query("SELECT * FROM plainte_topic WHERE id_sender=".$userInfo['id_user']." ORDER BY date_creation");

	while($resultat = $requeteAffichage->fetch()){
		?>
		<tr>
			<td align="center"><?php echo pseudoFromId($resultat["id_sender"]); ?></td>
			<?php echo '<td align="center"><a href="contact?id='.$resultat["id_topic"].'">'.decryptData($resultat["objet"]).'</a></td>';?><!--Lien $_GET permettant de selectionner un topic-->
			<td align="center"><?php echo $resultat['date_creation']; ?></td>
			<?php
			if($resultat["solved"]==0){
				echo '<td align="center">Ce post est encore en cours de résolution</td>';
			} else {
				echo '<td align="center">Ce post à été marqué comme résolu</td>';
			}
			?>
		</tr>
		<?php
	}
	?>

</table>
</table>
</td>
</pre>

<?php 
} else { //Si l'utilisateur a déja selectionné un topic existant

	//Verification que le topic accedé est un bien à bien été crée par l'utilisateur en cours
	$verificationid = $db->query("SELECT id_sender,solved FROM plainte_topic WHERE id_topic=".$_GET['id']);
	$resultatverif = $verificationid->fetch();
	$etatTopic = $resultatverif['solved'];
	$verificationid = $resultatverif['id_sender'];

	if($verificationid == $userInfo["id_user"]){//Si le topic appartient bien a l'utilisateur 

		if(isset($_POST["messageSuite"]) && !empty($_POST["messageSuite"] && $etatTopic==0)){//Si le champ de message est rempli et envoyé
			$envoi_message =$db->prepare("INSERT INTO plainte_message (id_sender, message, id_topic, date_message) VALUES (?,?,?,NOW())");
			$envoi_message->execute(array($userInfo["id_user"], encryptData(treatString($_POST["messageSuite"])), $_GET["id"]));//Insertion du message dans la table plainte_message
		} else {
			if(empty($_POST["messageSuite"]) && isset($_POST["messageSuite"]))//Messages d'erreur en cas de probleme
				$erreur3 = "Veuillez entrer un message";
			if($etatTopic==1)
				$erreur3 = "Ce sujet à été marqué comme résolu, vous ne pouvez plus y ajouter de messages";//Si un haut gradé a déja noté le topic comme résolu
		}
		?>

		<pre>
			<table style="margin-left: auto; margin-right: auto;">
				<tr><td valign="top">
					<form method="POST" action=""><!--Formulaire pour l'envoi de message supplémentaire dans un topic-->
						<table border="3px" bgcolor="#404040" style="border-style:solid; border-color:#0f0f0a; border-collapse: collapse;">
							<tr><td><textarea placeholder="Message" name="messageSuite"></textarea></td></tr>
							<tr><td><input type="submit" value="Envoyer le message"></td></tr>
							<?php
							if(isset($erreur3)){?>
								<tr><td align="center" style="color: red"><?php echo $erreur3; ?></td></tr>
							<?php
							}
							?>
						</table>
					</form>
				</td><td valign="top">
				<table border="3px" bgcolor="#404040" style="border-style:solid; border-color:#0f0f0a; border-collapse: collapse;">
					<?php 
					if($userInfo["grade"] < 2){//Affichage de la discussion dans le topic si l'utilisateur n'est pas haut gradé
						?>
						<tr>
							<td align="center">Auteur</td>
							<td align="center">Messages</td>
							<td align="center">Date de création</td>
						</tr>

						<?php

						$requete = $db->query("SELECT * FROM plainte_message WHERE id_topic=".$_GET["id"]." ORDER BY date_message");//Récupération des messages du topic

						while($resultat = $requete->fetch()){//Affichage des messages du topic
							?>
							<tr>
								<?php
								if($resultat['id_sender'] == $userInfo['id_user']){
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
					} else {//Si l'utilisateur est haut gradé, il est redirigé vers la page d'administration
						header("Location: administration");
					}
					?>

				</table>
			</table></td>
		</pre>

		<?php
	} else {
		header("Location: index");
	}
}
?>

</div>
<?php
if (isset($_SESSION['auth']) AND !empty($_SESSION['auth'])) { ?>
<ul type="1">
	<a href="logout">Déconnection</a>
	<a href="contact">Nous contacter</a>
</ul>
<?php } ?>
</body>
</html>
