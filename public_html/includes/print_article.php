<?php 
	
	function printBasic($art){
		startHtml();
		printHead("Poems", 1);
		startBody();
		startDivBox(1);
		printMenu(1);
		br(7);
		printBasicMain($art);
		endDiv();
		endDiv();
		endBodyAndHtml();
	}

	function printBasicMain($art){?>
	<div style="margin-left: auto; margin-right: auto; ">
		<div id="art">
			<table border="1" style="background-color: lightgrey;">
				<?php
				while ($result = $art->getArticle()->fetch()) {?>
					<tr>
						<td rowspan="2">
							<?php printImageIfIsset($result['img_article'], $result['id_article']) ?>
						</td>
						<td>
							<a href="<?='article?author='.$result['id_author'].'&article='. $result['id_article']?>"><?=$result['name_article']?></a>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo treatArticle(substr($result['main_article'], 0, 150)."...") ?>
						</td>
					</tr>
				<?php
				}
				?>
				<tr>
					<td colspan="2" style="text-align: center;"><?php printPaginacion($art)?></td>
				</tr>
			</table>
		</div>
	</div>
	<?php
	}

	function printPaginacion($art){?>
		<div id="pagination_article">
		<?php
		for ($i=1; $i<ceil($art->getTotal()/$art->getOnPage())+1; $i++) {
			if ($art->getPage()!=$i) {?>
				<a href="article?p=<?= $i."&o=".$art->getOrderBy()?>"><?= $i ?></a>
			<?php
			}else{?>
				<a><?= $i ?></a>
		<?php
			}
		} ?>
		<div>
		<?php
	}

	function printWithAuthorAndArticle($db, $art, $comment){
		startHtml();
		printHead($art->getArticle()['name_article']. " - " .  $art->getArticle()['fname']." ". $art->getArticle()['lname'], 1);
		startBody();
		printMenu(1); 
		printPageWithAuthorAndArticle($db, $art, $comment);
		endBodyAndHtml();
	}
	
	function printPageWithAuthorAndArticle($db, $art, $comment){
		$result = $art->getArticle();
		$id_author = $result["id_author"];
		$full_name = printString($result['fname']. " ".$result['lname']);
		$publish_date = getDateFrFormat($result['publish_date']);

		printStartPage($full_name, $id_author)?>
		<h5 id="publish_date"><?php echo "Publié le: ". $publish_date?></h5>
		<h3 id="note_final"><?=($result['note_art']!=null)?treatInt($result['note_art']).'/20':''?></h3>
       	<?php printImageIfIsset($result['img_article'], $result['id_article'],"article");?>
		<h2><?= printString($result["name_article"])?></h2>		
		<?php echo treatArticle($result["main_article"]);
		printAddNote($db, $art);
		printComment($db, $art, $comment);
		if(numberOfArticle($db, $art)>1){printThreeProposal($db, $art, "Du même auteur: ");}
		printThreeProposal($db, $art, "Ce contenu pourrait vous intéresser: ", false); 
		printEndPage();
	}

	function printStartPage($full_name, $id_author){?>
		<div class="banner"></div>
		<div id="box2"><br><br><br><br><br><br>
			<div class="pagecenter">
	        	<div class="artone"><br>
				<h3 id="name_author">Auteur: 
					<a href="<?php echo 'profile?p='. $id_author?>"><?=$full_name?>
					</a>
				</h3>
	<?php
	}

	function printEndPage(){?>
		</div>
		</div><br><br><br><br>
	<?php
	}

	function printProposal($result){
		if(isset($result) AND !empty($result)):?>
			<div class="imgmore">
				<a href="<?php echo "article?author=" . $result['id_author'] . "&article=".$result["id_article"];?>" title="<?=treatString(substr($result['main_article'], 0, 200)).'...'?>"> 
					<?php printImageIfIsset($result['img_article'], $result['id_article'], "proposal")?>
					<h4 class="titlemore"><?php echo $result["name_article"];?></h4>
				</a>
			</div><?php
		endif;
	}


	
	function printThreeProposal($db, $result, $title, $who=true){
		$numberOfArt = numberOfArticle($db, $result);
		if($numberOfArt>1 OR $who==false): ?>
		<div class="linemore">
			<h3><b><?= $title ?></b></h3><?php
			for ($i=0; $i < 3 ; $i++){ 
				if ($i==0){
					$first=$result->getProposal($db, $who);
					printProposal($first);
				}
				if ($i==1){
					$second=$result->getProposal($db, $who, $first['id_article']);
					printProposal($second);
				}
				if ($i==2){
					$third=$result->getProposal($db, $who, $first['id_article'], $second['id_article']);
					printProposal($third);
				}
			}
		endif;
		?>
		</div>
		<br>
		<?php 
	}


	function numberOfArticle($db, $result){
		$req = $db ->prepare('SELECT id_article FROM article WHERE id_author=?');
		$req->execute(array($result->getIdAuthor()));
		$numberOfArt = $req->rowCount();
		return $numberOfArt;
	}

	function printImageIfIsset($img, $id_article, $where="proposal"){
		$width=0;
		if($where==="article"){
			$width=500;
		}elseif($where==="proposal"){
			$width=180;
		}
		if(intval($img)==1 AND $where==="article"): ?>
			<img src="<?= "../user/picture/".$id_article.'.jpg';?>" width="<?=$width?>">
			<?php
		elseif(intval($img)==1 AND $where==="proposal"): ?>
			<img src="<?= "../user/picture/".$id_article.'.jpg';?>" width="<?=$width?>">
			<?php
		elseif(intval($img)==0 AND $where==="proposal"): ?>
			<img src="../user/picture/0.jpg" width="<?=$width?>">
			<?php 
		endif;
	}

	function printComment($db, $art, $comment){
		$id_author = $art->getIdAuthor();
		$id_article = $art->getIdArticle();
		htmlStartTable();
		while($result = $comment->getComment()->fetch()){
			$result2 = $comment->getInfoPoster($db, $result['id_poster']);
			$img = printAvatarIfIsset($result2['avatar']);
			$id_comment = $result['id_comment'];
			$nbLikes = $comment->getNumberOfLikes($db, $id_comment);
			$alreadyLiked = $comment->alreadyLiked($db, $id_comment);
			htmlcontentTable($id_author, $id_article, $id_comment, $result['main_comment'], $result2['fname']." ".$result2['lname'], $img, $nbLikes, $alreadyLiked);
		}	
		htmlFormComment();
		htmlEndTable();
	}

	function htmlStartTable(){?>
		<hr>
		<div class="comment">
			<br>
			<h3><b>Commentaires:</b></h3>
			<table class="tab_comment" border="0">
	<?php
	}

	function htmlEndTable(){?>
			</table>
			<br>
		</div><br><hr><br>
	<?php
	}

	function htmlcontentTable($id_author, $id_article, $id_comment, $comment, $full_name, $img, $nbLikes, $alreadyLiked){?>
		<tr class="comment_tr">
			<td>
				<img src="<?= $img ?>" class="comment_img">
				<p class="comment_name"><?=$full_name?></p>
			</td>
			<td>
				<p class="comment_main"><?=treatComment($comment)?></p>
				<span><?php echo $nbLikes; htmlToAddLike($id_author, $id_article, $id_comment, $alreadyLiked)?></span>
			</td>
			<?php htmlDeleteComment($id_author, $id_article, $id_comment)?>
		</tr><?php
	}

	function htmlToAddLike($id_author, $id_article, $id_comment, $alreadyLiked){
		if (isset($_SESSION['auth']) AND !empty($_SESSION['auth'])):?>
			<a href="<?= 'article?author='.$id_author.'&article='.$id_article.'&comment='.$id_comment.'&like=1'.'&token='.$_SESSION['auth']['token']?>" title="J'aime">
				<button <?=$alreadyLiked?>>J'aime</button>
			</a>
	<?php
		endif;
	}

	function htmlDeleteComment($id_author, $id_article, $id_comment){
		if(isset($_SESSION['auth']) AND !empty($_SESSION['auth']) AND $_SESSION['auth']['id_user'] == $id_author):?>
			<td>
				<a href="<?= 'article?author='.$id_author.'&article='.$id_article.'&Xcomment='.$id_comment.'&token='.$_SESSION['auth']['token']?>" title="Supprimer le commentaire">
					<button>X</button>
				</a>
			</td>
		<?php 
		endif;
	}

	function htmlFormComment(){
		if(isset($_SESSION['auth']) AND !empty($_SESSION['auth'])){
			htmlCommentToPost();
		}else{ 
			htmlCommentInterdiction();
		}
	}

	function htmlCommentToPost(){
		$img_user = printAvatarIfIsset($_SESSION['auth']['avatar']);
		?>
		<tr>
			<td>
				<img src="<?= $img_user?>" class="comment_img">
				<p class="comment_name"><?=$_SESSION['auth']['fname'] ." ". $_SESSION['auth']['lname']?></p>
			</td>
			<td>
				<form method="POST" action="">
					<textarea name ="comment" placeholder=" Ecrivez votre commentaire..."></textarea><br>
					<input type="submit" name="send">
				</form>
			</td>
		</tr>
	<?php
	}

	function htmlCommentInterdiction(){?>
		<tr>
			<td colspan="2">
				<p id="comment_interdiction">Connectez vous pour laisser un commentaire. <a href="login" target="_blank">Se connecter ici.</a><br>
				Pas encore de compte? <a href="singup" target="_blank">S'inscrire ici.</a>
				</p>
			</td>	
		</tr>
	<?php
	}

	function printAddNote($db, $art){
		if(isset($_SESSION['auth']) AND !empty($_SESSION['auth'])){
			if(!$art->alreadyNoted($db)){
				htmlAddNote();
			}
		}
	}

	function htmlAddNote(){?>
		<hr><br>
		<form method="POST">
			<label for="note">Notez ce poem:</label>
			<input id="note" type="number" name="note" min="0" max="20" value="10">
			<input type="submit" name="send_note" value="Noter">
		</form>
		<br>
	<?php
	}
?>