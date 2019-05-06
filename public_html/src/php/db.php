<?php 
	try{
		$db = new PDO("mysql:host=localhost; dbname=poemsofmonday; charset=UTF8", "root", "");

		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch (Exception $e){
		die('Erreur : ' . $e->getMessage());
	}
?>