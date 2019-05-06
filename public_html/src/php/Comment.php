<?php 
/**
*	Cette classe fournit les commentaires d'un article.
*
*	@author Lucian Petic
*	@copyright 2018 Poems of Monday
*	@version 1.4
*
*/
class Comment{

	private $id_article;
	private $comment;

/**
*	__construct: permet de récuperer les commentaires de l'article.
*	
*	@param object $db
*	@param int $id_article
*/
	
	function __construct($db, $id_article){	
		$this->id_article=$id_article;

		$req = $db->prepare('SELECT * FROM comment WHERE id_article=? ORDER BY comment_date ASC');
		$req -> execute(array($this->id_article));
		$this->comment=$req;
	}

/**
*	addComment: permet d'ajouter un commentaire à un article
*	Dans un premier temps il s'assure qu'il n'y a pas déjà un même commentaire, pour l'article courant.
*	Puis inserte le nouveau commentaire.
*
*	@param object $db
*/
	public function addComment($db){
		if(isset($_SESSION['auth'], $_POST['comment']) AND !empty($_SESSION['auth']) AND !empty($_POST['comment'])){
			$comment = treatString($_POST['comment']);
			$req = $db->prepare('SELECT * FROM comment WHERE id_article=? AND id_poster=? AND main_comment=?');
			$req->execute(array($this->id_article, $_SESSION['auth']['id_user'], $comment));
			$number = $req->rowCount();
			if ($number==0) {
				if (isset($_POST['send']) AND isset($_POST['comment']) AND !empty($_POST['comment'])){
					$req2 = $db->prepare('INSERT INTO comment(id_article, id_poster, main_comment, comment_date) VALUES (?,?,?, NOW())');
					$req2->execute(array($this->id_article, $_SESSION['auth']['id_user'], $comment));
				}
			}
		}
	}

/**
*	deleteComment: permet à l'auteur de l'article de supprimer un commentaire.
*	Pour des raisons de sécurité on utilise une variable de SESSION, et une variable GET
*
*	@param object $db
*/

	public function deleteComment($db, $art){
		if (isset($_GET['Xcomment'], $_SESSION['auth']['token'], $_GET['token'])){
			if (!empty($_GET['Xcomment']) AND !empty($_SESSION['auth']['token']) AND (strcmp($_SESSION['auth']['token'], treatString($_GET['token'])))==0){
				if (isset($_SESSION['auth']) AND !empty($_SESSION['auth']) AND $_SESSION['auth']['id_user'] == $art->getIdAuthor()) {
					$id_comment = treatInt($_GET['Xcomment']);
					$req = $db->prepare('DELETE FROM comment WHERE id_comment=? AND id_article=?');
					$req -> execute(array($id_comment, $this->id_article));
				}
			}
		}
	}
/**
*	addLikeToComment: permet d'ajouter un "J'aime" à un commentaire
*	En même temps, cette function à pour but d'enlever la mention "J'aime" si l'utilisateur le désire.
*
*	@param object $db
*	@param string $redirection
*/
	public static function addLikeToComment($db, $redirecton){
		if (isset($_SESSION['auth'], $_GET['token'])AND !empty($_SESSION['auth']) AND $_SESSION['auth']['token']==treatString($_GET['token'])){
			if (isset($_GET['like'], $_GET['comment']) AND !empty($_GET['like']) AND !empty($_GET['comment'])){
				if (intval($_GET['like'])==1){

					/* Dans un premier temps on vérifie que c'est la premiere fois 
					que l'utilisateur met la mention.*/
					$id_comment = intval($_GET['comment']);
					$id_liker = $_SESSION['auth']['id_user'];
					$req = $db->prepare('SELECT * FROM comment_like WHERE id_comment=? AND id_liker=?');
					$req->execute(array($id_comment, $id_liker));
					$number = $req->rowCount();

					/* Si ce n'est pas pour la premiere fois, on modifie la valuer 
					de ma mention, une mettant la valeur contaire par rapport à 
					ce qu'il y avait au debut.*/
					if ($number==1){
						$result = $req->fetch();
						$like = ($result['value_like']==1)? 0 : 1; 
						
						$req2 = $db->prepare('UPDATE comment_like SET value_like=? WHERE id_comment=? AND id_liker=?');
						$req2->execute(array($like, $id_comment, $id_liker));
					}elseif($number==0){
						/* Si c'est pour la premiere fois on crée la ligne dans la base de donées. */
						$req3 = $db->prepare('INSERT INTO comment_like(id_comment, id_liker, value_like) VALUES (?,?,?)');
						$req3->execute(array($id_comment, $id_liker, 1));
					}
					/* Pour finir on modifie ce qu'il y a dans les deux tables, 
					qui sont liées par les id des articles, et ainsi de commentaires...*/
					$req4 = $db->prepare('SELECT * FROM comment_like WHERE id_comment=? AND value_like=?');
					$req4->execute(array($id_comment, 1));
					$number4 = $req4->rowCount();

					$req5 = $db->prepare('UPDATE comment SET like_comment=? WHERE id_comment=?');
					$req5->execute(array($number4, $id_comment));
					/* La redirection est applique pour éviter de mettre des mention en actualisant la page*/
					header("Location: $redirecton");
					exit();
				}
			}
		}
	}

/**
*	addNote: permet d'ajouter une note à un article.
*	Cette action est valable une seule fois.
*
*	@param object $db
*/

	public function addNote($db){
		if (isset($_POST['note'], $_POST['send_note']) AND !empty($_POST['note'])){
			$note = treatInt($_POST['note']);
			$req = $db ->prepare('SELECT * FROM article_note WHERE id_article=? AND id_rater=?');
			$req->execute(array($this->id_article, $_SESSION['auth']['id_user']));
			$number = $req->rowCount();
			if ($number===0) {
				if ($note>=0 AND $note<=20){
					$req2 = $db->prepare('INSERT INTO article_note(id_article, id_rater, value_note) VALUES(?,?,?)');
					$req2 ->execute(array($this->id_article, $_SESSION['auth']['id_user'], $note));

					$req3 = $db->prepare('SELECT * FROM article_note WHERE id_article=?');
					$req3 -> execute(array($this->id_article));

					$i=1;
					/* Ici on actualise la note de l'article.*/
					while ($result3 = $req3->fetch()){
						$note+=$result3['value_note'];
						$i++;
					}
					$note=$note/$i;
					$note=round($note, 2);
					$req4=$db->prepare('UPDATE article SET note_art=? WHERE id_article=?');
					$req4->execute(array($note, $this->id_article));
				}
			}
		}
	}

/**
*	getInfoPoster: récupére depuis la base de donné des information sur l'utilisateur.
*	Cette action est valable une seule fois.
*
*	@param object $db
*	@param int $id_poster
*	@return array
*/

	public static function getInfoPoster($db, $id_poster){
		$req = $db->prepare('SELECT id_user, lname, fname, nname, sex, avatar FROM user WHERE id_user=?');
		$req->execute(array($id_poster));
		$result = $req->fetch();
		return $result;
	}

/**
*	getNumberOfLikes: donne le nombre de mentions "J'aime" d'un commentaire.
*
*	@param object $db
*	@param int $id_comment
*	@return int
*/

	public static function getNumberOfLikes($db, $id_comment){
		$req = $db ->prepare('SELECT like_comment FROM comment WHERE id_comment=?');
		$req->execute(array($id_comment));
		$number = $req->fetch();
		return $number['like_comment'];
	}
/**
*	alreadyLiked: il s'agit de donner un peut de style CSS, en function du fait s'il y a une mention, ou non.
*
*	@param object $db
*	@param int $id_comment
*	@return str
*/

	public static function alreadyLiked($db, $id_comment){
		if (isset($_SESSION['auth']) AND ! empty($_SESSION['auth'])){
			$req = $db ->prepare('SELECT value_like FROM comment_like WHERE id_comment=? AND id_liker=?');
			$req->execute(array($id_comment, $_SESSION['auth']['id_user']));
			$result = $req->fetch();
			if ($result['value_like']==1){
				return 'class="liked"';
			}else{
				return 'class="not_liked"';
			}
		}
	}

/**
*	getComment: sont des accesseurs de la classe car les attributs sont privés.
*	
*	@return array
*/

	public function getComment(){
		return $this->comment;
	}
}
?>