<?php
/**
*	Ce fichier contient tout les fonctions de l'ensemble de l'application web.
*	
*	@author Lucian Petic
*	@copyright 2018 Poems of Monday
*	@version 1.2
*/

/**
*
*	encrytData: permet de crypter les messages des utilisateurs.
*	Elle utilise la fonction prédéfinie en php openssl_encrypt.
*
*	@param string $data
*	@return string 
*/
	
	function encryptData($data){
		$method = 'aes128';
		$pw = "R3xXFILzEE9kAvJvm4zz0e2uC8d799b16de0f6f7bf7960cee7771e898C3M8H4zCqX74uzrGOA0qVXOnxn";
		$iv = "1453798568492560";
		$data = trim(htmlspecialchars($data));
		$data = openssl_encrypt($data, $method, $pw, OPENSSL_RAW_DATA, $iv);
		return base64_encode($data);
	}

/**	
*	decrytData: permet de decrypter les messages des utilisateurs.
*	Elle utilise la fonction prédéfinie en php openssl_decrypt.
*	
*	@param string $data
*	@return string 
*/

	function decryptData($data){
		$data = base64_decode($data);
		$method = 'aes128';
		$pw = "R3xXFILzEE9kAvJvm4zz0e2uC8d799b16de0f6f7bf7960cee7771e898C3M8H4zCqX74uzrGOA0qVXOnxn";
		$iv = "1453798568492560";
		$data = openssl_decrypt($data, $method, $pw, OPENSSL_RAW_DATA, $iv);
		$data = htmlspecialchars_decode($data);
		return $data;
	}

/**	
*	printString: d'affiche un text sur une page HTML.
*	
*	@param string $str
*	@return string 
*/

	function printString($str){
		return html_entity_decode($str);
	}

/**	
*	treatString: traite le texte de façon sécurisée.
*	
*	@param string $str
*	@return string 
*/

	function treatString($str){
		if (is_string($str)){
			return trim(htmlentities(strip_tags($str)));
		}
	}

/**	
*	treatInt: traite un entier de façon sécurisée.
*	
*	@param integer $int
*	@return integer
*/

	function treatInt($int){
		if (is_numeric($int)==true AND isset($int) AND !empty($int)) {
			return intval($int);
		}
	}

/**	
*	simulationKey: donne un entier aléatoire.
*	
*	@return integer
*/

	function simulationKey(){
		$key = mt_rand(1,9);
		for ($i=0; $i <8; $i++) { 		
			$key.= mt_rand(0,9);
		}
		$key = intval($key);
		return $key;
	}

/**	
*	silutationToken: donne une chaîne de caractéres aléatoire.
*	
*	@return string
*/

	function silutationToken(){
		$var = time();
		$var*= mt_rand(111, 366);
		$var = sha1($var);
		return $var;
	}

/**	
*	creatEndSession: initialise une variable de SESSION.
*
*/
	function creatEndSession(){
		$_SESSION['endSessionIn5H']=time()+60*60*5;
	}

/**	
*	endSessionIn5H: déconnecte l'utilisateur, 5 heures après sa dernière connexion.
*
*/

	function endSessionIn5H(){
		if ($_SESSION['endSessionIn5H']<time()) {
			header("Location: logout");
			exit();
		}
	}

/**	
*	redirectionIfAuth: impeche à l'utilisateur d'acceder à certaines pages s'il est déjà connecté (pages: connexion, inscription...).
*
*/

	function redirectionIfAuth(){
		if (isset($_SESSION['auth']) AND !empty($_SESSION['auth'])){
			header("Location: profile");
			exit();
		}
	}

/**	
*	redirectionIfNotAuth: impeche à l'utilisateur d'acceder à certaines pages s'il n'est pas connecté (pages: edition de poem, ajout de poem...).
*
*/

	function redirectionIfBanned(){
		$db = new PDO("mysql:host=localhost; dbname=poemsofmonday; charset=UTF8", "root", "");
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$checkIfBan = $db->query("SELECT confirm FROM user WHERE id_user=".$_SESSION['auth']['id_user']);
		$fetchCheckIfBan = $checkIfBan->fetch();
		$_SESSION['auth']["confirm"] = $fetchCheckIfBan["confirm"];

		if($_SESSION['auth']["confirm"] == -1){
			header("Location: banni");
			exit();
		}
	}

	function redirectionIfNotAuth(){
		if (!isset($_SESSION['auth']) AND empty($_SESSION['auth'])) {
			header("Location: index");
			exit();
		}
	}

/**	
*	getDateFrFormat: donné la date en français.
*
*	@param string $date
*	@return string
*/


	function getDateFrFormat($date){
		$date= treatString($date);
		$daysWeek=array("Mon"=>"lundi", "Tue"=>"mardi", "Wed"=>"mercredi", "Thu"=>"jeudi", "Fri"=>"vendredi", "Sat"=>"samedi", "Sun"=>"dimanche");
		$dateFinal = $daysWeek[date('D', strtotime($date))].", ";
		$dateFinal .= date('d', strtotime($date))."/";
		$dateFinal .= date('m', strtotime($date))."/";
		$dateFinal .= date('Y', strtotime($date));

		return $dateFinal;
	}

/**	
*	emailValid: verifie la validité d'une adresse mail.
*
*	@param string $email
*	@return bool / string
*/

	function emailValid($email){
		if (filter_var($email, FILTER_VALIDATE_EMAIL)==true) {
			return true;
		}else{
			return "Adresse mail invalide.";	
		}
	}

/**	
*	uploadImg: permet d'ajouter une image sur l'application web.
*
*	@param string $postImg
*	@param integer $id
*	@param integer $maxSize
*/

	function uploadImg($postImg, $id, $maxSize){
		//  $_FILES[$postImg]['size'][0] . "est la taille";
		if(intval($_FILES[$postImg]['size'][0]) < $maxSize){
			foreach ($_FILES[$postImg]['error'] as $key => $error) {
	  			if ($error == UPLOAD_ERR_OK) {
	        		$tmp_name = $_FILES[$postImg]['tmp_name'][$key];
	        		$id=strval($id);
	        		$name = $id.".jpg";
	        		move_uploaded_file($tmp_name, "user/picture/$name");
	        		resizeImg("user/picture/$name", "user/picture/$name", "500", "250", "80");
	        	}
        	}
       	}else{
       		echo "ERROR TAILLE MAX EST: ".$maxSize ."<br>";
       		exit();
       	}
	}

/**	
*	resizeImg: permet de rediméntioner une image.
*
*	@param string $source
*	@param string $path
*	@param integer $width
*	@param integer $height
*	@param integer $quality
*/

	function resizeImg($source, $path, $width, $height, $quality){

		$imageSize = getimagesize($source);
		$imageRessource = imagecreatefromjpeg($source);
		$imageFinal = imagecreatetruecolor($width, $height);
		$final = imagecopyresampled($imageFinal, $imageRessource, 0, 0, 0, 0, $width, $height, $imageSize[0], $imageSize[1]);
		imagejpeg($imageFinal, $path, $quality);
	}

/**	
*	issetAndNotEmptyArray: verifié que chaque clé d'un tableau, 
*	n'est pas vide et a été initialisée.
*
*	@param array $array
*	@return bool
*/

	function issetAndNotEmptyArray($array){
		$r=0;
		foreach ($array as $key => $value) {
			if ((isset($array[$key]) AND !empty($array[$key]))==true){
				$r++;
			}
		}
		return ($r===count($array)) ? true : false;
	}

/**	
*	sendMail: envoie un mail à l'utilisateur, lors de l'inscription.
*
*	@param string $to
*	@param string $name
*	@param integer $key
*	@param integer $id
*/


	function sendMail($to, $name, $key, $id){
		$key = urlencode($key);
		$id = urlencode($id);
		$message = 'Bonjour '. $name .', je vous invite à confimer votre compte à l\'adresse suivante: "http://localhost/newP/v1.2.1/confirmation?id='.$id.'&k='.$key.'" Je vous souhaite une agrèable journée Poems of Monday';

		//mail($to,"Confirmation de compte - Poems of Monday", $message);
	}

/**	
*	treatArticle: prepare un article à être affiché.
*
*	@param string $text
*	@return string
*/

	function treatBr($text){
		$search = array("\r\n", "\n", "\r"); 
		$text = str_replace($search, "<br>", $text);
		return $text;
	}

	function treatArticle($text){
		return '<p class="poem">'.treatBr(printString($text)).'</p><br>';
	}

/**	
*	treatComment: prepare un commentaire à être affiché.
*
*	@param string $text
*	@return string
*/

	function treatComment($text){
		$text = printString($text);
		$search = array(":D",":(",":*",":p",":P","-_-","<3",":O",":o", ";)","3:)","^_^",":3",":'(","O:)","(y)","(n)","8)");
		$replace = array('😃','😟','😘','😛','😜','😑','♥','😲','😧','😉','😈','😊','😺','😓','😇','👍','👎','😎');
		$text = str_replace($search, $replace, $text);
		$text = treatBr($text);
		return $text;
	}

/**
*	pseudoFromId: renvoie le pseudo correspondant à l'id passé en parametre présent dans la base de donnée.
*
*	@param integer $id
*/	

	function pseudoFromId($id){
		$db = new PDO("mysql:host=localhost; dbname=poemsofmonday; charset=UTF8", "root", "");
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$pseudoSender = $db->query("SELECT nname FROM user WHERE id_user=".$id);
		$pseudoFetch = $pseudoSender->fetch();
		$pseudoSender = $pseudoFetch['nname'];
		return $pseudoSender;
	}
?>