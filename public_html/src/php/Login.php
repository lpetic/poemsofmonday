<?php
/**
*	Cette classe assure la connexion de l'utilisateur.
*
*	@author Lucian Petic
*	@copyright 2018 Poems of Monday
*	@version 1.4
*
*/
class Login{	

	private $data=array("clogin"=>null, "cpw"=>null, "captcha"=>null);
	private $error=array("clogin"=>null, "cpw"=>null, "captcha"=>null);
	private $tentations=0;
	private $con;

/**
*	__construct: permet de récuperer l'adresse email et le mot de passe entrées par l'utilisateur.
*	
*	@param object $db
*/

	public function __construct($db){
		if (isset($_POST['clogin']) AND isset($_POST['cpw']) AND !empty($_POST['clogin']) AND !empty($_POST['cpw'])) {
			$this->data['clogin']=treatString($_POST['clogin']);
			$this->data['cpw']=treatString($_POST['cpw']);
			if (isset($_POST['captcha']) AND !empty($_POST['clogin']) AND $this->numberOfTriesOverflow()==true){
				$this->data['captcha']=treatString($_POST['captcha']);
			}
		}else{
			$this->error['clogin']="Entrez vous indentifiants.";
		}
	}

/**
*	tryLogin: fait appel à la majorité des méthodes de cette classe.
*	Elle a pour but, de vérifier les données entrées par l'utilisateur.
*	
*	@param object $db
*/
	public function tryLogin($db){
		if (!isset($_SESSION['tentations'])) {
			$_SESSION['tentations']=0;
		}
		$this->validCaptcha();
		if (($this->error["clogin"] AND $this->error["captcha"])==false AND isset($_POST['send'])) {
		$this->connectDB($db);
		}
	}

/**
*	numberOfTriesOverflow: vérifié le nombre des tentatives qu'un utilisateur a fait.
*	Lorsque il en fait plus de 5, un code captcha sera necessaire pour la suite de la connexion;
*	
*	@return bool 
*/

	public function numberOfTriesOverflow(){
		if (isset($_SESSION['tentations']) AND !empty($_SESSION['tentations'])) {
			$this->tentations=$_SESSION['tentations'];
			if ($this->tentations<5) {
				return false;
			}else{
				return true;
			}
		}
	}

/**
*	connectDB: vérifié les données saisies par l'utilisateur. 
*	
*	@param object $db
*/
	public function connectDB($db){
		$req= $db->prepare('SELECT * FROM user WHERE email = ?');
		$req->execute(array($this->data['clogin']));
		$result = $req -> fetch();
		if (!$this->validCaptcha() && password_verify($this->data['cpw'], $result['pw'])==true){
			$this->error['clogin']=false;
			$this->error['cpw']=false;
			$this->con = $result;
			$this->redirectionToProfile();
		}else{
			$_SESSION['tentations']+=1;
			$this->error['cpw']="Mauvais email ou mot de passe.";
		} 
	}

/**
*	validCaptcha: vérifie le captcha après l'entrée d'un mot de passe incorrect plus de 5 fois.
*	
*/
	
	public function validCaptcha(){
		if (isset($this->data['captcha']) AND $this->numberOfTriesOverflow()===true) {
			if ($this->data['captcha']==$_SESSION['captcha']) {
				$this->error['captcha']=false;
			}else{
				$this->error['captcha']="Code incorect :(";
			}
		}elseif($this->numberOfTriesOverflow()===false){
			$this->error['captcha']=false;
		}
	}

/**
*	emailValid: vérifie le si un email est valide.
*	
*/

	private function emailValid(){
		if (filter_var($this->data['clogin'], FILTER_VALIDATE_EMAIL)==true){
			return true;
		}else{
			$this->error['clogin']="Adresse mail invalide.";
		}
	}

/**
*	redirectionToProfile: fait une redirection sur le profile de l'utilisateur.
*	
*/

	private function redirectionToProfile(){
		if (isset($this->con) AND !empty(($this->con))){
			$this->createCookieLogin();
			creatEndSession();
			$_SESSION['auth']=$this->con;
			$_SESSION['auth']['token']=silutationToken();
			$_SESSION['tentations']=0;
			header('Location: profile');
		}
	}

/**
*	createCookieLogin: crée un cookie qui contient l'adresse email de connexion si l'utilisateur en a fait la demande, lors de la connexion.
*	
*/

	private function createCookieLogin(){
		if (isset($_POST['remember'])){
			setcookie("login", $this->data['clogin'], time()+60*60*24*365);
		}
	}

/**
*	getData, getError, getCon: sont des accesseurs de la classe car les attributs sont privés.
*	
*	@return array
*/

	public function getData(){
		return $this->data;
	}

	public function getError(){
		return $this->error;
	}

	public function getCon(){
		return $this->con;
	}
}

?>