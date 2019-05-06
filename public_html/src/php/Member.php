<?php  
/**
*	Cette classe liste tous les utilisateurs du l'application web.
*
*	@author Lucian Petic
*	@copyright 2018 Poems of Monday
*	@version 1.2
*	
*/
class Member
{	
	private $member;
	private $page = 1;
	private $onPage = 5;
	private $total;

/**
*	__construct: permet de récuperer les utilisateurs, sur des pages.
*	
*	@param object $db
*/	
	function __construct($db){	
		
		if (isset($_GET['p']) AND !empty($_GET['p']) AND $_GET['p']>0){
			$this->page = intval($_GET['p']);
		}

		$req = $db->prepare('SELECT id_user FROM user ORDER BY id_user DESC');
		$req->execute(array());
		$this->total = $req->rowCount();
		$start = $this->page * $this->onPage - $this->onPage;
		$end = $start + $this->onPage;
		if ($start>$this->total) {
			$this->page = 1;
			$start = $this->page * $this->onPage - $this->onPage;
			$end = $start + $this->onPage;
		}

		$sql = "SELECT id_user, lname, fname, avatar FROM user ORDER BY id_user LIMIT ".$start.",".$end;
		$req1 = $db->prepare($sql);
		$req1->execute(array($start));
		$this->member = $req1;
	}

/**
*	  getMember, getTotal, getOnPage, getPage: sont des accesseurs de la classe car les attributs sont privés.
*	
*	@return object/int
*/

	public function getMember(){
		return $this->member;
	}

	public function getTotal(){
		return $this->total;
	}

	public function getOnPage(){
		return $this->onPage;
	}

	public function getPage(){
		return $this->page;
	}
}
?>