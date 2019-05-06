<?php
	if(!isset($_SERVER['PATH_INFO'])){
		require_once('../src/php/redirection_app.php'); 
		redirection_app(__FILE__); 
	}
	require_once('src/php/Article.php');
	require_once('src/php/Comment.php');
	require_once('includes/print_article.php');

	$art = new Article($db);
	$id_art = $art->getIdArticle();
	$id_auth = $art->getIdAuthor();
	
	if(isset($id_auth, $id_art)){
		$commentTreat = new Comment($db, $id_art);
		$commentTreat->addNote($db);
		$commentTreat->addComment($db);
		$commentTreat->addLikeToComment($db, 'article?author='.$id_auth.'&article='.$id_art);
		$commentTreat->deleteComment($db, $art);
		$comment = new Comment($db, $id_art);
		printWithAuthorAndArticle($db, $art, $comment);
	}else{
		printBasic($art);
	}
	?>
