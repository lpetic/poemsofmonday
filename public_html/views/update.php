<?php
	if(!isset($_SERVER['PATH_INFO'])){
		require_once('../src/php/redirection_app.php'); 
		redirection_app(__FILE__); 
	}
	require_once('src/php/Article.php');
	require_once('src/php/Upload.php');
	require_once('src/php/Update.php');
	require_once('includes/form_editarticle.php');
	redirectionIfNotAuth();
	
	$update = new Update($db);

	startHtml();
	printHead("Editer un poem",1);
	startBody();
	startDivBox(1);
	printMenu(1);
	br(4);
	printFormEdit($update);
	$update->mainFunction($db);
	endDiv();
	endDiv();
	endBodyAndHtml();
?>