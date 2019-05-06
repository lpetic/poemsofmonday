<?php
	if(!isset($_SERVER['PATH_INFO'])){
		require_once('../src/php/redirection_app.php'); 
		redirection_app(__FILE__); 
	}
	require_once('src/php/redirection_app.php');
	require_once('src/php/Message.php');
	require_once('includes/form_message.php');

	$message = new Message($db);
	$message->addMessage($db);

	printMessage($db, $message);
	printFormMessage($db, $message);

?>