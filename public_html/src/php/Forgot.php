<?php 
/**
*	Cette classe permet de récupérer le mot de passe d'un utilisateur.
*
*	@author Lucian Petic
*	@copyright 2018 Poems of Monday
*	@version 1.4
*/
class Forgot
{
	private $data = array("login"=>null, "id_user"=>0);
	private $error = array("emailValid"=>true, "notExist"=>true, "regex"=>true, "same"=>true, "input"=>true);
	
/**
*	__construct: permet de récuperer les entrées de l'utilisateur.
*	
*	@param object $db
*/
	function __construct($db){	
		if (isset($_POST['login'], $_POST['send']) AND !empty($_POST['login'])) {
			$this->data['login'] = treatString($_POST['login']);
		}
	}

/**
*	sendMailToUser: devrait envoyer une mail à l'utilisateur, mais dans notre cas, 
*	il va créer un fichier qui comporte le lien pour la récupération du mot de passe.
*
*	@param object $db
*/

	public function sendMailToUser($db){
		if (isset($_POST['send']) AND emailValid($this->data['login'])==true){
				$this->existUser($db);
				if(!$this->error['notExist']){
					$codeForMail = silutationToken();
					$code = md5($codeForMail);
					$req = $db->prepare("INSERT INTO recovery(id_user, code, date_recovery) VALUES (?,?, NOW())");
					$req->execute(array($this->data['id_user'], $code));
					$this->error['notExist']=false;
					$this->error['emailValid']=false;

					$file = "recovery_password_user".$this->data['id_user'].".txt";
					file_put_contents($file, "app.php?recov="$codeForMail);
				}else{
					$this->error['notExist']="Adresse email inconnue.";
				}
			}else{
				$this->error['emailValid']=emailValid($this->data['login']);
			}
	}

/**
*	existUser: vérifie l'existance d'un utilisateur à partir de son adresse email.
*
*	@param object $db
*/
	private function existUser($db){
		$req = $db->prepare("SELECT id_user FROM user WHERE email=?");
		$req->execute(array($this->data['login']));
		$exist = $req->rowCount();
		if ($exist==1){
			$id_user = $req->fetch();
			$this->data['id_user'] = $id_user['id_user'];
			$this->error['notExist'] = false;
		}
	}

/**
*	verificationCode: vérifie le code du mail (fichier texte) pour poursuivre la récupération.
*
*	@param object $db
*/
	public function verificationCode($db){
		if (isset($_GET['recov']) AND !empty($_GET['recov'])){
			$code = md5(treatString($_GET['recov']));
			$req = $db->prepare('SELECT * FROM recovery WHERE code=? ORDER BY id_recovery DESC');
			$req->execute(array($code));
			$result = $req->fetch();
			$exist = $req->rowCount();
			if($exist!=1){
				header('Location: error');
				exit();
			}else{
				$this->data['id_user'] = $result['id_user'];
				return true;
			}
		}
	}

/**
*	setNewPassword: permet de saisir le nouveau mot de passe.
*
*	@param object $db
*/
	public function setNewPassword($db){
		if (isset($_POST['send_2'], $_POST['pw'], $_POST['rpw']) AND !empty($_POST['pw']) AND !empty($_POST['rpw'])) {
			$pw = treatString($_POST['pw']);
			$rpw = treatString($_POST['rpw']);
			if (strcmp($pw, $rpw)==0) {
				if (preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])#', $pw) AND strlen($pw)>5){
					$pw = password_hash($pw, PASSWORD_BCRYPT);
					$req = $db->prepare("UPDATE user SET pw=? WHERE id_user=?");
					$req->execute(array($pw, $this->data['id_user']));
					header('Location: login');
				}else{
					$this->error['regex'] = "Le mot de passe doit contenir au moins 6 caractères dont une lettre majuscule, minusucle et un chiffre.";
				}
			}else{
				$this->error['same'] = "Les deux champs ne sont pas identiques.";
			}
		}else{
			$this->error['input'] = "Veillez saissir le nouveau mot de passe.";
		}
	}

/**
*	 getError: sont des accesseurs de la classe car les attributs sont privés.
*	
*	@return array
*/

	public function getError(){
		return $this->error;
	}
}

?>