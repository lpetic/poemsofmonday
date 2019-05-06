<?php
	if(!isset($_SERVER['PATH_INFO'])){
		require_once('../src/php/redirection_app.php'); 
		redirection_app(__FILE__); 
	}
	require_once('src/php/Login.php');
	require_once('includes/form_login.php');
	redirectionIfAuth();

	$login = new Login($db);

	startHtml();
	printHead("Connexion",1);
	startBody();
	startDivBox(1);
    printMenu(1);
    br(5);
	$login->tryLogin($db);
	if (isset($_POST['send'])){
		defaultValuesLoginAfterPut($login);
	}
	printFormLogin($login);
	endDiv();
	printFooter(1);
	endDiv();
	endBodyAndHtml();
?>