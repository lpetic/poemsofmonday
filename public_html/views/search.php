<?php
require_once('includes/includes.php');
require_once('src/php/functions.php');
require_once('src/php/db.php');

if (isset($_SESSION['auth']) AND !empty($_SESSION['auth'])) {
	$userInfo= $_SESSION['auth'];
	redirectionIfBanned($userInfo["id_user"]);//Redirection si l'utilisateur est banni
	endSessionIn5H();
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
		<div align="center">
			Voici les résultats que nous avons trouvés :
		</div>
		<div>
			<?php //Affichage de toutes les valeurs trouvées dans les tables user et articles ressemblant a la valeur passée en $_GET grâce a la barre de recherche

			$chercheUser = $db->prepare("SELECT * FROM user WHERE lname LIKE ? OR fname LIKE ? OR nname LIKE ?");//Récupération des nom/pseudo/prenom des utilisateurs ressemblants
			$chercheUser->execute(array('%'.$_GET["req"].'%','%'.$_GET["req"].'%','%'.$_GET["req"].'%'));

			$chercheArticle = $db->prepare("SELECT * FROM article WHERE name_article LIKE ?");//Récupération des articles dont le titre ressemble
			$chercheArticle->execute(array('%'.$_GET["req"].'%'));

			$nbRow = $chercheUser->rowCount();
			$nbRow += $chercheArticle->rowCount();
			?>

			<table style="margin-left: auto; margin-right: auto;">
				<tr>

					<!--Affichages des articles(gauche)-->

					<td valign="top">
						<table align="center" border="3px" bgcolor="#777777" style="border-style:solid; border-color:#0f0f0a; border-collapse: collapse;">

							<?php if($nbRow == 0){//Affichage d'un message spécifique si il n'existe aucun résultat
								echo "<tr><td style='padding:3px;'>Il n'y a aucun résultat pour cette recherche :(</td></tr>";
							} ?>

							<?php while($fChercheArticle = $chercheArticle->fetch()){ ?><!--Affichage des toutes les valeurs recupérées-->
							<tr>
								<td>
									<a href="article?author=<?php echo $fChercheArticle['id_author']?>&article=<?php echo $fChercheArticle['id_article']?>" style="color: black; display: block; width: 368px; margin: auto;"><!--Le bloc affichant l'article sert de lien redirigeant vers l'article en question-->
										<table align="center" border="3px" bgcolor="#404040" style="border-style:solid; border-color:#0f0f0a; border-collapse: collapse; margin-bottom: 1px; margin-top: 1px">
											<?php
											echo "<tr><td width='358'>Article : ".$fChercheArticle["name_article"]."</td></tr>";
											echo "<tr><td width='358'>Date de publication : ".$fChercheArticle["publish_date"]."</td></tr>";
											?>
										</table>
									</a>
								</td>
							</tr>
							<?php } ?>
						</table>
					</td>

					<!--Affichage des utilisateurs(droite)-->

					<td>
						<table align="center" border="3px" bgcolor="#777777" style="border-style:solid; border-color:#0f0f0a; border-collapse: collapse;">
							<?php while($fChercheUser = $chercheUser->fetch()){ ?><!--Récupération de la liste des utilisateurs ressemblants-->
							<tr>
								<td>
									<a href="profile?p=<?php echo $fChercheUser['id_user']?>" style="color: black; display: block; width: 368px; margin: auto;"><!--Même chose, le bloc affichant l'utilisateur sert de lien vers le profil en question-->
										<table align="center" border="3px" bgcolor="#404040" style="border-style:solid; border-color:#0f0f0a; border-collapse: collapse; margin-bottom: 1px; margin-top: 1px">
											<?php
											echo "<tr><td rowspan='3'><img src='../user/avatar/".$fChercheUser["avatar"]."' width='150' height='150' border='3' align='left'/></td width='200'>
											<td>Pseudo : ".$fChercheUser["nname"]."</td></tr>";
											echo "<tr><td width='200'>Prenom : ".$fChercheUser["fname"]."</td></tr>";
											echo "<tr><td width='200'>Nom : ".$fChercheUser["lname"]."</td></tr>";
											?>
										</table>
									</a>
								</td>
							</tr>
							<?php } ?>
						</table>
					</td>
				</tr>
			</table>
		</div>
	</div>
	<?php
	if (isset($_SESSION['auth']) AND !empty($_SESSION['auth'])) { ?>
	<ul type="1">
		<a href="contact">Nous contacter</a>
	</ul>
	<?php	
}
?>
</body>
</html>
