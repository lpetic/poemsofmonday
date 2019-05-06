<?php
/**
*	Cette classe assure l'inscription d'un nouveau utilisateur.
*
*	@author Lucian Petic
*	@copyright 2018 Poems of Monday
*	@version 1.2
*/
class Singup{

	private $data = array("lname"=>null, "fname"=>null, "nname"=>null, "sex"=>null, "dateNaissance"=>null, "email"=>null, "remail"=>null, "pw"=>null, "rpw"=>null, "cond"=>null, "captcha"=>null);
	private $error = array("lengthValid"=>true, "sexValid"=>true, "validDate"=>true, "sameEmail"=>true, "emailValid"=>true, "inUseEmail"=>true, "inUseNname"=>true, "samePw"=>true, "lenghtPw"=>true, "regexPw"=>true, "validCond"=>true, "validCaptcha"=>true);

/**
*	__construct: permet d'initialiser toutes les informations qu'on souhaite recolter sur l'utilisateur, lors de l'inscription.
*	
*/
	
	function __construct(){
		if (isset($_POST['send'])) {
			$arrayData = $_POST;
			$this->data['lname']=treatString($_POST['lname']);
			$this->data['fname']=treatString($_POST['fname']);
			$this->data['nname']=treatString($_POST['nname']);
			$this->data['sex']=intval($_POST['sex']);
			$this->data['dateNaissance']=treatString($_POST['dateNaissance']);
			$this->data['email']=treatString($_POST['email']);
			$this->data['remail']=treatString($_POST['remail']);
			$this->data['pw']=treatString($_POST['pw']);
			$this->data['rpw']=treatString($_POST['rpw']);
			$this->data['captcha']=treatString($_POST['captcha']);
		}
	}

/**
*	trySingup: fait appel à la majorité des méthodes de cette classe.
*	Elle a pour but, de vérifier la fiabilité des données entrées par l'utilisateur.
*	Puis les introduit dans la base de donnée.
*	
*	@param object $db
*/


	public function trySingup($db){
		$this->lengthValid();
		$this->emailValid();
		$this->inUseNname($db);
		$this->inUseEmail($db);
		$this->sexValid();
		$this->sameEmail();
		$this->samePw();
		$this->lenghtPw();
		$this->regexPw();
		$this->validDate();
		$this-> validCond();
		$this->validCaptcha();
		if ($this->anyError()===true){
			$this->insertIntoDB($db);
		}
	}

/**
*	lengthValid: verifie la taille des chaînes de caractéres.
*	
*/

	private function lengthValid(){
		if (strlen($this->data['fname'])>2 AND strlen($this->data['lname'])>2 AND strlen($this->data['nname'])>2){
			if(strlen($this->data['fname'])<255 AND strlen($this->data['lname'])<255 AND strlen($this->data['nname'])<255){
				$this->error['lengthValid'] = false;
			}else{
				$this->error['lengthValid'] = "Votre nom/prénom/pseudo est un peu trop long.";
			}
		}else{
			$this->error['lengthValid'] = "Votre nom/prénom/pseudo est un peu trop court: trois lettres au moins!";
		}
	}

/**
*	sexValid: verifie la cohérence des valeurs.
*	
*/
	
	private function sexValid(){
		if ($this->data['sex']==0 OR $this->data['sex']==1){
			$this->error['sexValid'] = false;
		}else{
			$this->error['sexValid'] = "Le format n'a pas été accepté.";
		}
	}


/**
*	inUseNname: verifie qu'un pseudo n'est pas encore présent dans la base de données.
*	
*	@param object $db
*/
	

	private function inUseNname($db){
		$reqNname = $db->prepare('SELECT nname FROM user WHERE nname=?');
		$reqNname->execute(array($this->data['nname']));
		$nbNname = $reqNname->rowCount();
		if ($nbNname==0) {
			$this->error['inUseNname'] = false;
		}else{
			$this->error['inUseNname'] = "Ce pseudo est déjà utilisé.";
		}
	}

/**
*	inUseEmail: verifie qu'une adresse email n'est pas encore présent dans la base de données.
*	
*	@param object $db
*/
	private function inUseEmail($db){
		$reqEmail = $db->prepare('SELECT email FROM user WHERE email=?');
		$reqEmail->execute(array($this->data['email']));
		$nbEmail = $reqEmail->rowCount();
		if ($nbEmail==0) {
			$this->error['inUseEmail'] = false;
		}else{
			$this->error['inUseEmail'] = "L'adresse mail est déjà utilisée.";
		}
	}
	
/**
*	emailValid: verifie qu'une adresse email est valide.
*	
*/
	private function emailValid(){
		if (filter_var($this->data['email'], FILTER_VALIDATE_EMAIL)==true) {
			$this->error['emailValid']=false;
		}else{
			$this->error['emailValid']= "Adresse mail invalide.";
					
		}
	}

/**
*	sameEmail: verifie deux entrées d'adresse email, sont les mêmes.
*	
*/
	private function sameEmail(){
		if (strcmp($this->data['email'], $this->data['remail'])==0) {
			$this->error['sameEmail'] = false;
		}else{
			$this->error['sameEmail'] = "L'adresse mail n'est pas identique dans les deux champs.";
		}
	}

/**
*	samePw: verifie deux entrées de mot de passe, sont les mêmes.
*	
*/
	private function samePw(){
		if (strcmp($this->data['pw'], $this->data['rpw'])==0) {
			$this->error['samePw'] = false;
		}else{
			$this->error['samePw'] =  "Le mot de passe n'est pas identique dans les deux champs.";
		}
	}

/**
*	lenghtPw: vérification de la taille d'un mot de passe.
*	
*/
	private function lenghtPw(){
		if (strlen($this->data['pw'])>5){
			$this->error['lenghtPw'] =  false;
		}else{
			$this->error['lenghtPw'] = "Mot de passe trop court. Il faut au moins 6 caracteres";
		}
	}

/**
*	regexPw: vérification de la presence d'un chiffre, d'une lettre minuscule, ainsi que d'une lettre majuscule.
*	
*/
	private function regexPw(){
		if (preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])#', $this->data['pw'])) {
			$this->error['regexPw'] =  false;
		}else{
			$this->error['regexPw'] =  "Votre mot de passe doit contenir au moins un chiffre, une lettre minuscule, ainsi qu'une majuscule.";
		}
	}

/**
*	validDate: verifié qu'une date n'est pas supérieure à la date d'aujourd'hui.
*	
*/
	private function validDate(){
		$d=date('Y-m-d');
		if ( $d > $this->data['dateNaissance']) {
			$this->error['validDate'] = false;
		}else{
			$this->error['validDate'] = "Date invalide!";
		}
	}

/**
*	validCaptcha: verifié le code du captcha entrée par l'utilisateur, est correct.
*	
*/
	private function validCaptcha(){
		if ($this->data['captcha']==$_SESSION['captcha']) {
			$this->error['validCaptcha'] = false;
		}else{
			$this->error['validCaptcha'] = "Code incorect :(";
		}
	}

/**
*	validCond: verifié que la checkbox des conditions d'utilisation a été coché.
*	
*/
	private function validCond(){
		if (isset($_POST['cond']) AND !empty($_POST['cond'])) {
			$this->error['validCond'] = false;
		}else{
			$this->error['validCond'] = "Vous devez accepter les conditons d'utilisation.";
		}
	}

/**
*	insertIntoDB: introduit les informations récoltées dans la base de données.
*	
*	@param object $db
*/
	private function insertIntoDB($db){
		$tmp_pw = $this->data['pw'];
		$this->data['pw'] = password_hash($this->data['pw'], PASSWORD_BCRYPT);

		$req = $db->prepare('INSERT INTO user(fname, lname, nname, date_naissance, sex, email, pw, date_inscription, confirm_key, avatar) VALUES (?,?,?,?,?,?,?,?,?,?)');
		$key = simulationKey();
		$req->execute(array($this->data['fname'], $this->data['lname'], $this->data['nname'], $this->data['dateNaissance'], $this->data['sex'], $this->data['email'], $this->data['pw'], date('Y-m-d'), $key, 'default'.$this->data['sex'].'.jpg'));
		$id_user=$db->lastInsertId();

		sendMail($this->data['email'], $this->data['fname'], $key, $id_user);
		$this->createSession($db, $id_user);
	}

/**
*	createSession: Crée une session pour l'utilisateur, directement lors de l'inscription.
*
*	@param object $db
*	@param int $id-user
*/

	private static function createSession($db, $id_user){
		$req = $db->prepare('SELECT * FROM user WHERE id_user=?');
		$req->execute(array($id_user));
		$resultat = $req->fetch();
		$_SESSION['auth']=$resultat;
		$_SESSION['auth']['token']=silutationToken();
		creatEndSession();
		header('Location: profile');
		exit();
	}


/**
*	anyError: verifié que toutes les valeurs du tableau sont faux.
*	
*	@return bool
*/
	private function anyError(){
		$r=0;
		foreach ($this->error as $key => $value) {
			if ($this->error[$key]==false){
				$r++;
			}
		}
		return ($r===count($this->error)) ? true : false;
	}

/**
*	getError: est un accesseur de la classe.
*	
*	@return array
*/
	
	public function getError(){
		return $this->error;
	}
}
?>