<?php

	function printHead($title="Poems of Monday",$previousFolder=0){
		$previous = "";
		if($previousFolder==1){
			$previous = "../";
		}?>
<head>
	<title><?=$title?></title>
	<meta lang="fr-FR">
	<meta charset="utf-8">
	<meta name="description" content="Free Web Poems">
	<meta name="keywords" content="Poems, Text, Image, Network">
	<meta name="author" content="Lucian Petic, Joris I...">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" type="image/jpg" href="<?=$previous?>media/img/icon/icon.png">
	<link rel="stylesheet" type="text/css" href="<?=$previous?>src/css/main_style.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link href="https://fonts.googleapis.com/css?family=Bad+Script|Lobster+Two|Lora|Raleway" rel="stylesheet">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
		<?php
	}

	function printMenu($previousFolder=0){
		$previous = "";
		if($previousFolder==1){
			$previous = "../";
		}?>
<nav>
	<header class = "container" >
		<div class = "logo">
			<a href="<?=$previous?>app.php/index">
				<img src="<?=$previous?>media/img/icon/logo.png" width="300" id="img_art">
			</a>
		</div>
		<div class = "right">
			<ul class = "menu" id="up">
				<li class="title">
					<a href="<?=$previous?>">Menu</a> 
					<ul class = "sousmenu"><?php
					if (isset($_SESSION['auth']) AND !empty($_SESSION['auth'])) { ?>
						<li><a href = "<?=$previous?>app.php/upload">Ecrire un poeme</a></li>
					<?php
					} ?>
						<li><a href = "<?=$previous?>app.php/article">Articles</a></li>
						<li><a href = "<?=$previous?>app.php/member">Membres</a></li>
						<li><a href = "<?=$previous?>app.php/contact">Contact</a></li>
					</ul>
				</li>
				<?php if (isset($_SESSION['auth']) AND !empty($_SESSION['auth'])){ ?>
				<li class="title">
					<a href="<?=$previous?>app.php/profile" id="singup"><button>Mon Compte</button></a>
				</li>
				<li class="title">
					<a href="<?=$previous?>app.php/logout" id="singup"><button>Déconnexion</button></a>
				</li>
				<?php }else{ ?>
				<li class="title">
					<a href="<?=$previous?>app.php/singup" id="singup"><button>S'inscrire</button></a>
				</li>
				<li class="title">
					<a href="<?=$previous?>app.php/login" id="login"><button>Connexion</button></a>
				</li>
				<?php } ?>
				<form method="GET" action="search">
					<li class="title">
				    	<input id="searchbar" type="text" placeholder="Rechercher..." name="req">
				    </li>
				    <li class="title">
				   		<input type="submit">
				   	</li>
				</form>
			</ul>
		</div>
	</header>
</nav>
		<?php
	}

	function printFooter($previousFolder=0){
		$previous = "";
		if($previousFolder==1){
			$previous = "../";
		}

		?>
<footer>
	<div id="network">
		<ul>
			<li><a href ="http://www.poeziideluni.xyz" class ="pom"><img title = "Poems of Monday" src="<?=$previous?>media/img/icon/icon.png" width="60px" height="60px"></a></li>
			<li><a href ="https://www.facebook.com/poeziideluni/" class ="fb"><img title = "Facebook" src="<?=$previous?>media/img/icon/fbColorButton.png" width="60px" height="60px"></a></li>
			<li><a href ="#" class ="insta"><img title = "Instagram" src="<?=$previous?>media/img/icon/instaColorButton.png" width="60px" height="60px"></a></li>
			<li><a href ="#" class ="yt"><img  title="YouTube" src="<?=$previous?>media/img/icon/ytColorButton.png" width="60px" height="60px"></a></li>
			<li><a href ="#" class ="in"><img  title="LinkedIn" src="<?=$previous?>media/img/icon/inColorButton.png" width= "60px" height="60px"></a></li>
		</ul>
	</div>
	<h4>COPYRIGHT © "Poems of Monday"</h4>
</footer>
		<?php
	}
	

	function printLogo($previousFolder=0){ 
		$previous="";
		if($previousFolder==0){
			$previous="";
		}else{
			$previous="../";
		}
		?>
<img src="<?=$previous?>../media/img/icon/icon.png" width=125px;>
	<?php
	}

	function printAvatarIfIsset($avatar){
		$img = '../user/avatar/'.$avatar;
		return $img;
	}

	function printMessageFlash(){
		if(isset($_SESSION['flash'])){?>
<span class="error"><?php echo $_SESSION['flash']?></span>
		<?php unset($_SESSION ['flash']);
		}
	}
	function startHtml(){?>	
<!DOCTYPE html>
<html>
	<?php
	}

/**	
*	startBody: affiche le code html
*/

	function startBody(){?>
		<body>
	<?php
	}

/**	
*	endBody: affiche le code html
*/

	function endBodyAndHtml(){?>
		</body>
	</html>
	<?php
	}

	function startDivBox($id){?>
	<div id="<?php echo 'box'.$id?>">
		<div class="banner"></div>
		<div class="pagecenter">
	<?php
	}

	function br($x){
		$br = "<br>";
		$br_final = "";
		for ($i=0; $i < $x ; $i++) { 
			$br_final .= $br;
		}
		echo $br_final;
	}

	function endDiv(){?>
		</div>
	<?php
	}


?>