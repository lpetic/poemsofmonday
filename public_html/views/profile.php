<?php
require_once('includes/includes.php');
require_once('src/php/functions.php');
require_once('src/php/db.php');

//Cette page a pour but de présenter le profil de l'utilisateur. 
//Celui-ci contiendra la plupart des informations que l'utilisateur aura renseigné lors de son inscription, ainsi que d'autre données telles que la photo de profil ou la biographie

if (isset($_SESSION['auth']) AND !empty($_SESSION['auth'])) {
	$userInfo = $_SESSION['auth'];//Raccourcis pour moins avoir a taper $_SESSION['auth']
	redirectionIfBanned($userInfo["id_user"]);//Redirection si l'utilisateur est banni
	endSessionIn5H();
}else if(!isset($_GET["p"])){
	header('Location: index');//Redirection vers la page principale si l'utilisateur n'est pas connecté
}

if(isset($_GET["p"]) && !empty($_GET["p"])){//La variable $_GET["p"] correspond a l'id du profil que l'utilisateur souhaite regarder, si cette variable existe.
	if(isset($userInfo) AND $_GET["p"] == $userInfo["id_user"]){
		header("Location: profile");//Redirection vers le profil de l'utilisateur si l'id de "p" est égale celle de l'utilisateur
	}
	$own = false;
} else {
	$own = true;
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

		<div>
			<table style="margin-left: auto; margin-right: auto;"><tr><td valign="top">
				
				<?php
				//Si le profil appartient a l'utilisateur
				if($own == true){
					?>
					<table class="tableCentre" border="3px">
						<?php
						if(!empty($userInfo["avatar"]))//Affichage de l'avatar
						?>
						<tr><td><img src="../user/avatar/<?php echo $userInfo['avatar']; ?>" width="300" height="300" border="3" align="left"/></td></tr>

						<!--Affichage des informations du profil-->

						<tr><td align="center"><a href="editionprofil">Editer profil</a></td></tr>
						<tr><td align="center">Nom: <?=$userInfo['lname']?></td></tr>
						<tr><td align="center">Prenom: <?= $userInfo['fname']?></td></tr>
						<tr><td align="center">Pseudonyme: <?=$userInfo['nname']?></td></tr>
						<tr><td align="center">Email: <?=$userInfo['email']?></td></tr>
						<tr><td align="center">Date de naissance: <?=$userInfo['date_naissance']?></td></tr>
						<tr><td align="center">Sexe: <?php if($userInfo['sex'] == 1){ echo "Homme";}else{ echo "Femme";}?></td></tr>
						<tr><td align="center">Grade: <?php if($userInfo['grade'] == 0){ echo "Utilisateur";} else if ($userInfo['grade'] == 1){ echo "Auteur";} else if($userInfo['grade'] == 2 ){echo "Modérateur";} else if($userInfo['grade'] == 3){ echo "Administrateur";}?></td></tr>

						<?php
						if($userInfo['grade'] > 1)//Ligne optionnelle s'affichant uniquement si l'utilisateur est modérateur/administrateur qui redirige vers admnistration
						echo '<tr><td align="center"><a href="administration">Gérer les utilisateurs</a></td></tr>';
						?>
					</table></td>

					<!--Affichage de la biographie-->

					<td valign="top"><table border="3px" bgcolor=Gainsboro style="border-style:solid; border-color:#0f0f0a; border-collapse: collapse;">

						<?php
						if(!empty($userInfo["bio"]))
							echo "<tr><td align='center' style='min-width: 300px; max-width: 500px; padding-top: 10px; padding-bottom: 10px; padding-right: 10px; padding-left: 10px; word-break: break-all; white-space: pre-wrap'>".treatBr($userInfo["bio"])."</tr></td>";
						?>
					</table></td>

					<!--Affichage des articles crées par l'utilisateurs si ils existent-->

					<?php
					//Requete permettant de savoir si un article existe dans la liste des articles crées par cet utilisateur
					$EmptyArticle = $db->query("SELECT * FROM article WHERE id_author=".$userInfo["id_user"]." ORDER BY publish_date DESC");
					$nbEmpty = $EmptyArticle->rowCount();//Nombre d'articles de l'utilisateur

					if($nbEmpty != 0){?><!--Si un article existe-->

					<!--On affiche la liste des articles un part un-->
					<td valign="top"><table border="3px" bgcolor=Gainsboro  style="border-style:solid; border-color:#0f0f0a; border-collapse: collapse;">

						<tr>
							<td>Numéro de l'article</td>
							<td>Nom de l'article</td>
							<td>Date de création</td>
							<td>Modifier l'article</td>
						</tr>

						<?php
						$reqarticle = $db->query("SELECT * FROM article WHERE id_author=".$userInfo["id_user"]." ORDER BY publish_date DESC");//On récupere les articles crées par l'utilisateur
						$nbtotal = 1;

						while($resultatArticles = $reqarticle->fetch()){//On parcourt cette liste
							

								?>

								<tr>
									<td align="center">
										<?php
										echo $nbtotal;
										$nbtotal++
										?>
									</td>
									<td align="center">
										<?php echo "<a href='article?author=".$resultatArticles["id_author"]."&article=".$resultatArticles["id_article"]."'> ".$resultatArticles["name_article"]." </a>";?>
									</td>

									<td align="center">
										<?php echo strchr($resultatArticles["publish_date"], " ", true);?>
									</td>

									<td align="center">
										<form action="update"> 
  											  <input type="submit" value="Editer cet article">
  											  <input type="hidden" name="author" value=<?php echo $resultatArticles['id_author'] ?>>
  											  <input type="hidden" name="article" value=<?php echo $resultatArticles['id_article'] ?>>
										</form>
									</td>
								</tr>
								<?php
							}
						}
						?>
					</table></td>
					<?php

			//Si le profil n'appartient pas a l'utilisateur car own == false;
			} else {
				?>
				<table class="tableCentre" border="3px">
					<?php

				//Récupération des informations du profil que l'on souhaite visionner
					$requete = $db->prepare("SELECT * FROM user WHERE id_user=?");
					$requete->execute(array($_GET["p"]));

					$nbRow = $requete->rowCount();//On vérifie que le profil que l'on souhaite visionner existe bien
					if($nbRow == 0){
						header("Location: error");//Sinon on affiche la page d'erreur
					}

					$resultat = $requete->fetch();

				//La suite est similaire au visionnage de profil normal, avec quelques modifications pour s'assurer que l'affichage reste correct

					if(!empty($resultat["avatar"]))
						?>
					<tr><td><img src="../user/avatar/<?php echo $resultat['avatar']; ?>" width="300" height="300" border="3" align="left"/></td></tr>

						<!--
						<tr><td align="center">Nom: <?=$resultat['lname']?></td></tr>
						<tr><td align="center">Prenom: <?= $resultat['fname']?></td></tr>

						On peut ici rajouter les informations que l'on souhaite être visible au grand public
					-->
					<tr><td align="center">Pseudonyme: <?=$resultat['nname']?></td></tr>
					<tr><td align="center">Email: <?=$resultat['email']?></td></tr>
					<tr><td align="center">Date de naissance: <?=$resultat['date_naissance']?></td></tr>
					<tr><td align="center">Sexe: <?php if($resultat['sex'] == 1){ echo "Homme";}else{ echo "Femme";}?></td></tr>
					<tr><td align="center">Grade: <?php if($resultat['grade'] == 0){ echo "Utilisateur";} else if ($resultat['grade'] == 1){ echo "Auteur";} else if($resultat['grade'] == 2 ){echo "Modérateur";} else if($resultat['grade'] == 3){ echo "Administrateur";}?></td></tr>

				</table></td>

				<!--Affichage de la biographie si celle ci existe-->

				<td valign="top"><table border="3px" bgcolor=Gainsboro  style="border-style:solid; border-color:#0f0f0a; border-collapse: collapse;">

					<?php
					if(!empty($resultat["bio"])){
						echo "<tr><td align='center' style='min-width: 300px; max-width: 500px; padding-top: 10px; padding-bottom: 10px; padding-right: 10px; padding-left: 10px; word-break: break-all; white-space: pre-wrap'>".treatBr($resultat["bio"])."</tr></td>";
					}
					?>
				</table></td>

				<?php
				$reqArticle = $db->query("SELECT * FROM article WHERE id_author=".$resultat["id_user"]." ORDER BY publish_date DESC");
				$nbEmpty = $reqArticle->rowCount();//Verification qu'un article existe

				if($nbEmpty != 0){?><!--Si un article existe-->

				<td valign="top"><table border="3px" bgcolor=Gainsboro  style="border-style:solid; border-color:#0f0f0a; border-collapse: collapse;">

					<tr>
						<td>Numéro de l'article</td>
						<td>Nom de l'article</td>
						<td>Date de création</td>
					</tr>

					<?php
					$nbtotal = 1;

					while($resultatArticles = $reqArticle->fetch()){
							?>

							<tr>
								<td align="center">
									<?php
									echo $nbtotal;
									$nbtotal++
									?>
								</td>
								<td align="center">
									<?php echo "<a href='article?author=".$resultatArticles["id_author"]."&article=".$resultatArticles["id_article"]."'> ".$resultatArticles["name_article"]." </a>";?>
								</td>

								<td align="center">
									<?php echo strchr($resultatArticles["publish_date"], " ", true);?>
								</td>
							</tr>
							<?php
						}
					}
					?>
				</table></td>
				<?php
		}
		?>

	</table>
</div>
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
