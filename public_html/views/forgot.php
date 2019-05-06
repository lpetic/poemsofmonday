<?php
	if(!isset($_SERVER['PATH_INFO'])){
		require_once('../src/php/redirection_app.php'); 
		redirection_app(__FILE__); 
	}
	require_once('src/php/Forgot.php');
	require_once('includes/form_forgot.php');
	redirectionIfAuth();

	$forgot = new Forgot($db);

	startHtml();
	printHead("Récuperation mot de passe",1);
	startBody();
	startDivBox(1);
	printMenu(1);
	br(7);
	printMain($db, $forgot);
	endDiv();
	printFooter(1);
	endDiv();
	endBodyAndHtml();
?>