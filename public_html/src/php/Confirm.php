<?php
/**
*	Cette classe est destinée à la confirmation de l'utilisateur.
*	Lors de l'inscription, l'utilisateur doit récevoir lien pour la confirmation du compte.
*
*	@author Lucian Petic
*	@copyright 2018 Poems of Monday
*	@version 1.1
*
*/
class Confirm{

	private $id;
	private $key;

/**
*	__construct: permet de récuperer la clé et l'id pour confirmer un compte.
*	
*/
	function __construct(){
		if (isset($_GET['id']) AND isset($_GET['key']) AND !empty($_GET['id']) AND !empty($_GET['key']) AND $_GET['key']>100000000 AND $_GET['key']<999999999 AND $_GET['id']>0){
			$this->id=treatInt($_GET['id']);
			$this->key=treatInt($_GET['key']);
		}
	}

/**
*	verification: verifie que les information de l'objet Confirm sont bien présentes dans la base de données
*	
*	@param object $db
*	@return str
*/
	public function verification($db)
	{
		if (isset($this->id, $this->key) AND !empty($this->id) AND !empty($this->key))
		{
			$req = $db->prepare('SELECT id_user, confirm_key, confirm FROM user WHERE id_user=? AND confirm_key=?');
			$req ->execute(array($this->id,$this->key));
			$result = $req->fetch();
			$exist = $req->rowCount();
			if ($exist==1){
				if ($result['confirm']==1){
					return "Votre compte a déjà été confirmé.";
				}elseif($result['confirm']==0){
					$req2 = $db->prepare('UPDATE user SET confirm=1 WHERE id_user=?');
					$req2->execute(array($this->id));
					return "Votre compte a été confirmé avec succès.";
				}else{
					return "Votre compte n'a pas pu être confirmé, malheuresement.";
				}
			}else{
				return "Votre compte n'a pas pu être confirmé, malheuresement.";
			}
		}else{
			return "Votre compte n'a pas pu être confirmé, malheuresement.";
		}
	} 
}

?>