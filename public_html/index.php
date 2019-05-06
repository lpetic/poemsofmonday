<?php
if(!isset($_SERVER['PATH_INFO'])){
	require_once('src/php/redirection_app.php'); 
	redirection_app(__FILE__, false); 
}
?>

<!DOCTYPE html>
<html>
<?php 
printHead('Accueil', 1);
?>
<body>
	<div id="box1">
		<div class="banner"></div>
		<div class="pagecenter">
        	<?php printMenu(1)?>
		<div class="quot">
			<h1 class ="cite">
				Ceci est un réseau de poèmes,<br>
				Ici on fonde tout ce que tu aimes.<br>
				Peu importe ton âge,<br>
				Tu trouveras ton Ange.<br>
				<br>
			</h1>
		</div>
	</div>
    </div>
	<div id="box2">
    		<div class="bande">
    		<div class="pagecenter">
        	<article class="artone">
			
		</article>
		</div>
		</div>
			<?php printFooter(1)?>
	</div>
</body>
</html>