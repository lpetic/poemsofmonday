<?php
	if(!isset($_SERVER['PATH_INFO'])){
		require_once('../src/php/redirection_app.php'); 
		redirection_app(__FILE__); 
	}
	$_SESSION = array();
	unset($_SESSION);
	session_destroy();
	header("Location:".$_SERVER['SCRIPT_NAME']."/index");
?>