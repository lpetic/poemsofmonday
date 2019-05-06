<?php 
	session_start();
	require_once('includes/includes.php');
	require_once('src/php/functions.php');
	require_once('src/php/db.php');

	switch($_SERVER['PATH_INFO']) {

	case("/index") :
		require_once('index.php');
		break;
	case("/login") :
		require_once('views/login.php');
		break;
	case("/logout") :
		require_once('views/logout.php');
		break;
	case("/forgot") :
		require_once('views/forgot.php');
		break;
	case("/singup") :
		require_once('views/singup.php');
		break;
	case("/message") :
		require_once('views/message.php');
		break;
	case("/member") :
		require_once('views/member.php');
		break;
	case("/article") :
		require_once('views/article.php');
		break;
	case("/upload") :
		require_once('views/upload.php');
		break;
	case("/update") :
		require_once('views/update.php');
		break;
	case("/confirmation") :
		require_once('views/confirmation.php');
		break;
	case("/profile") :
		require_once('views/profile.php');
		break;
	case("/error") :
		require_once('views/error.php');
		break;
	case("/confirmation") :
		require_once('views/confirmation.php');
		break;
	case("/administration") :
		require_once('views/administration.php');
		break;
	case("/editionprofil") :
		require_once('views/editionprofil.php');
		break;
	case("/search") :
		require_once('views/search.php');
		break;
	case("/contact") :
		require_once('views/contact.php');
		break;
	case("/banni") :
		require_once('views/banni.php');
		break;					
	case("/") :
	case("") :
		header("Location:".$_SERVER['SCRIPT_NAME']."/index");
		break;
	default :
		require_once('views/error.php');
	}

?>