<?php
/**
*	Cette classe permet de récupérer le poem depuis la base de donnée.
*	Mais aussi les commentaires du poem.
*
*	@author Lucian Petic
*	@copyright 2018 Poems of Monday
*	@version 1.4
*/
class Article{

	private $id_author;
	private $id_article;
	private $article;
	private $basic = array('page'=>1, 'onPage'=>5, 'orderBy'=>1, 'total'=>0, 'orderBy'=>0);

/**
*	__construct: permet de récuperer l'article.
*	On prevoit deux cas: 1) on  connait precisement l'article à donner
*						 2) on liste tous les articles
*	
*	@param object $db
*/
	function __construct($db){

		if(isset($_GET['author'], $_GET['article']) AND !empty($_GET['author']) AND !empty($_GET['article'])){

			$this->id_author=treatInt($_GET['author']);
			$this->id_article=treatInt($_GET['article']);

			$req = $db->prepare("SELECT id_author, id_article, name_article, main_article, publish_date, img_article, note_art, id_user, fname, lname, avatar FROM article, user WHERE id_author=? AND id_article=? AND id_user=? AND publish_date < NOW() "); 
			$req ->execute(array($this->id_author, $this->id_article, $this->id_author));
			$this->article = $req->fetch();

			if (empty($this->article)){
				header('Location: error');
				exit();
			}
		}else{
			if (isset($_GET['p']) AND !empty($_GET['p']) AND $_GET['p']>0){
				$this->basic['page']=$_GET['p'];
			}
			$req = $db->prepare("SELECT id_article FROM article WHERE publish_date < NOW()");
			$req->execute();
			$this->basic['total']=$req->rowCount();

			$start = $this->basic['page'] * $this->basic['onPage'] - $this->basic['onPage'];
			$end = $start + $this->basic['onPage'];

			if ($start>$this->basic['total']){
				$this->basic['page'] = 1;
				$start = $this->basic['page'] * $this->basic['onPage'] - $this->basic['onPage'];
				$end = $start + $this->basic['onPage'];
			}
			$orderBy = $this->orderBy();
			//$sql = "SELECT id_author, id_article, name_article, main_article, publish_date, img_article, note_art, id_user, fname, lname, avatar FROM article, user WHERE publish_date < NOW() ORDER BY ".$orderBy ." DESC LIMIT ".$start.",".$end;
			$sql = "SELECT * FROM article LEFT JOIN user ON 'article.id_author'='user.id_user' WHERE publish_date < NOW() ORDER BY ".$orderBy ." DESC LIMIT ".$start.",".$end;
			$req1 = $db->prepare($sql);
			$req1 ->execute();
			$this->article = $req1;
		}
	}

/**
*	getProposal: fait une proposition d'articles, en function des parametres à exclure.
*	C'est pour ne pas répéter deux fois une même proposition.
*
*	@param object $db
*	@param bool $who
*	@param int $id_art1
*	@param int $id_art2
*	@return array
*/
	public function getProposal($db, $who=true, $id_art1=0, $id_art2=0){

		($who===true)? $sql= 'id_author = ?' : $sql= 'id_author != ?';
		$req = $db->prepare('SELECT * FROM article WHERE '. $sql . ' AND id_article != ? AND id_article != ? AND id_article != ? AND publish_date<NOW() ORDER BY RAND() LIMIT 1');
		$req -> execute(array($this->id_author, $this->id_article, $id_art1, $id_art2));
		$result = $req->fetch();

		if (isset($result) AND !empty($result)){
			return $result;
		}
	}

/**
*	alreadyNoted: donne la possibilité de noter un article.
*
*	@param object $db
*	@param bool $who
*	@param int $id_art1
*	@param int $id_art2
*	@return array
*/

	public function alreadyNoted($db){
		$id_article = $this->getIdArticle();
		$req = $db ->prepare('SELECT * FROM article_note WHERE id_article=? AND id_rater=?');
		$req->execute(array($id_article, $_SESSION['auth']['id_user']));
		$number = $req->rowCount();
		return ($number==0)? false : true;
	}

/**
*	orderBy: détérmine l'ordre dont les resultats sont affichés.
*
*	@return str
*/
	private function orderBy(){
		if (isset($_GET['o']) AND !empty($_GET['o']) AND $_GET['o']>0) {
			if ($_GET['o']==1) {
				$this->basic['orderBy']=intval($_GET['o']);
				return 'id_author';
			}elseif($_GET['o']==2){
				$this->basic['orderBy']=intval($_GET['o']);
				return 'theme';
			}elseif($_GET['o']==3){
				$this->basic['orderBy']=intval($_GET['o']);
				return 'name_article';
			}else{
				$this->basic['orderBy']=intval($_GET['o']);
				return 'publish_date';
			}
		}else{
			$this->basic['orderBy']=0;
			return 'publish_date';
		}
	}

/**
*	getIdAuthor, getIdArticle, getArticle ... : sont des accesseurs de la classe car les attributs sont privés.
*	
*	@return array
*/

	public function getIdAuthor(){
		return $this->id_author;
	}

	public function getIdArticle(){
		return $this->id_article;
	}

	public function getArticle(){
		return $this->article;
	}

	public function getTotal(){
		return $this->basic['total'];
	}

	public function getOnPage(){
		return $this->basic['onPage'];
	}

	public function getPage(){
		return $this->basic['page'];
	}

	public function getOrderBy(){
		return $this->basic['orderBy'];
	}
}
?>