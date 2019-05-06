<?php 
/**
*	Cette classe fournit les messages entre deux utilisateurs.
*
*	@author Lucian Petic
*	@copyright 2018 Poems of Monday
*	@version 1.4
*
*/
class Message
{
	private $user1;
	private $user2;
	private $messages;

/**
*	__construct: permet de récuperer les donées sur les deux utilisateurs.
*	
*	@param object $db
*/
	function __construct($db)
	{	
		if (isset($_GET['chat']) AND !empty($_GET['chat'])) {
			$id_user1 = treatInt($_SESSION['auth']['id_user']);
			$id_user2 = treatInt($_GET['chat']);
			$req = $db->prepare('SELECT id_user, fname, lname FROM user WHERE id_user=?');
			$req ->execute(array($id_user1));
			$this->user1 = $req->fetch();
			$req2 = $db->prepare('SELECT id_user, fname, lname FROM user WHERE id_user=?');
			$req2 ->execute(array($id_user2));
			$this->user2= $req2->fetch();

			if (( $req -> rowCount()==1 AND $req2 -> rowCount()==1 AND isset($this->user1, $this->user2) AND !empty($this->user1) AND !empty($this->user2))==false) {
				header("Location: error");
				exit();
			}

		}else{
			header("Location: error");
			exit();
		}
	}

/**
*	getProposal: récupére tout les messages des deux utilisateurs.
*
*	@param object $db
*
*/
	public function allMessages($db){
		$req = $db->prepare('SELECT * FROM message WHERE id_sender=? AND id_receiver=?  OR  id_sender=? AND id_receiver=? ORDER BY id_message ASC');
		$req->execute(array($this->user1['id_user'], $this->user2['id_user'], $this->user2['id_user'], $this->user1['id_user']));
		$this->messages = $req;
	}

/**
*	addMessage: ajoute un message à la conversation.
*
*	@param object $db
*
*/
	public function addMessage($db){
		if (isset($_POST['message'],$_POST['send']) AND !empty($_POST['message'])){
			$id_user1 = $this->user1['id_user'];
			$id_user2 = $this->user2['id_user'];
			$message = encryptData($_POST['message']);
			$req = $db->prepare('SELECT * FROM message WHERE id_sender=? AND id_receiver=?  ORDER BY id_message DESC');
			$req->execute(array($id_user1, $id_user2));
			$result = $req ->fetch();
			var_dump($result['message']);
			var_dump($message);
			if (strcmp($result['message'], $message)!=0){
				$req2 = $db->prepare('INSERT INTO message(id_sender, id_receiver, message, message_date) VALUES (?,?,?, NOW())');
				$req2->execute(array($id_user1, $id_user2, $message));
			}
		}
	}

/**
*	getAuthorOfMessage: donne le nom du receveur et du celui qui reçoit le message
*
*	@param string $message
*
*/
	public function getAuthorOfMessage($message){
		if ($message['id_sender']==$_SESSION['auth']['id_user']) {
			return $this->user1['fname'];
		}else{
			return $this->user2['fname'];
		}
	}

/**
*	getContacts: donne les contacts de l'utilisateur courant.
*
*	@param string $message
*
*/
	public static function getContacts($db){
		$req = $db->prepare('SELECT id_following FROM follow WHERE id_followed = ?');
		$req->execute(array($_SESSION['auth']['id_user']));
		return $req;
	}

/**
*	getNameOfContact: donne des données sur les contacts de l'utilisateur courant.
*
*	@param string $message
*
*/
	public static function getNameOfContact($db, $id_user){
		$req = $db->prepare('SELECT fname, lname FROM user WHERE id_user=?');
		$req->execute(array($id_user));
		$result = $req->fetch();
		$fullName=$result['fname']." ".$result['lname'];
		return $fullName;
	}

/**
*	getUser1, getUser2, getMessages : sont des accesseurs de la classe car les attributs sont privés.
*	
*	@return array
*/

	public function getUser1(){
		return $this->user1;
	}

	public function getUser2(){
		return $this->user2;
	}

	public function getMessages(){
		return $this->messages;
	}
}

?>