<?php
	if(!isset($_SERVER['PATH_INFO'])){
		require_once('../src/php/redirection_app.php'); 
		redirection_app(__FILE__); 
	}
	require 'src/php/Confirm.php';

	$verif = new Confirm();
	$_SESSION['flash'] = $verif->verification($db);
	header("Location:".$_SERVER['SCRIPT_NAME']."/index");
?>