<?php
	if(!isset($_SERVER['PATH_INFO'])){
		require_once('../src/php/redirection_app.php');
		redirection_app(__FILE__); 
	}
	require_once('src/php/Member.php');
	require_once('includes/print_member.php');

	$member = new Member($db);

	startHtml();
	printHead("Membres",1);
	startBody();
	startDivBox(1);
	printMenu(1);
	br(7);
	printMain($member);
	endDiv();
	endDiv();
	endBodyAndHtml();
?>