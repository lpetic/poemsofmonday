<?php
	
	function printMain($member){
		?>
		<div id="member";>
			<table border="1" style="background-color: lightgrey">
				<?php
				while ($result = $member->getMember()->fetch()) {
					$id_user = $result['id_user'];
					$full_name = $result['fname']." ". $result['lname'];
					$img = printAvatarIfIsset($result['avatar']);
					?>
					<tr>
						<td>
							<img src="<?=$img?>" class="member_img" width=90>
						</td>
						<td>
							<a href="<?='../app.php/profile?p='.$id_user?>"><?= $full_name ?></a>
						</td>
					</tr>
				<?php
				} ?>
					<tr>
						<td colspan="2" style="text-align: center;"><?php printPaginacion($member)?></td>
					</tr>
				
			</table>
		</div>
	<?php
	}

	function printPaginacion($member){?>
		<div id="pagination_member">
		<?php
		for ($i=1; $i<ceil($member->getTotal()/$member->getOnPage())+1; $i++) {
			if ($member->getPage()!=$i) {?>
				<a href="../app.php/member?p=<?= $i ?>"><?= $i ?></a>
			<?php
			}else{?>
				<a><?= $i ?></a>
		<?php
			}
		} ?>
		<div>
		<?php
	}
?>