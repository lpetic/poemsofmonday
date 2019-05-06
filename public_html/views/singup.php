<?php
	if(!isset($_SERVER['PATH_INFO'])){
		require_once('../src/php/redirection_app.php'); 
		redirection_app(__FILE__); 
	}
	require_once('src/php/Singup.php');
	require_once('src/php/Login.php');
	require_once('includes/form_singup.php');

	$singup = new Singup();

	startHtml();
	printHead("S'inscrire",1);
	startBody();	
	startDivBox(1);
	printMenu(1);
	br(5);
	if (isset($_POST['send'])) {
		$singup->trySingup($db);
	}
	printFormSingup($singup);
	endDiv();
	printFooter(1);
	endDiv();
	endBodyAndHtml();
?>