<?php

function printFormMessage($db, $message){
	$req=$message->getContacts($db);
	?>
	<form method="POST">
		<select><?php
			while($result = $req->fetch()){
				$id_following = $result['id_following'];
				$fullName=$message->getNameOfContact($db, $id_following);
			?>
				<option value="message.php?chat=1"><?=$fullName?></option>
			<?php
			}
			?>
		</select><br>
		<textarea type="text" name="message"></textarea><br>
		<input type="submit" name="send">
	</form>
<?php
}

function printMessage($db, $message){
	$message->allMessages($db);
	while ($result = $message->getMessages()->fetch()) {
		$a=$message->getAuthorOfMessage($result);
		echo $a.": ";
		echo decryptData($result['message']).'<br>';
	}
}

?>