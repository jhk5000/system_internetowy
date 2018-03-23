<?php
	echo '<h2>Wiadomości</h2><hr class="style-one"></hr>';
	if($option == 3) {
		$msg = $db->select_single('SELECT m.* FROM messages m WHERE m.id = '.(int)$_GET['id']);
		if($msg && ($msg['from_user'] == $user['id'] || $msg['to_user'] == $user['id'])) {
			if($msg['from_user'] == $user['id']) {
				$author = $db->select_single('SELECT * FROM users WHERE id = '.$msg['to_user']);
			} else {
				$author = $db->select_single('SELECT * FROM users WHERE id = '.$msg['from_user']);
			}
?>
			<table class="table table-bordered table-hover">
			<tbody>
				<tr><td width="120px"><strong>Tytuł:</strong></td><td><?php echo $msg['title'];?></td></tr>
				<tr><td><strong>Data wysłania:</strong></td><td><?php echo date('H:i, d.m.Y', $msg['send_date']);?></td></tr>
			<?php if($msg['from_user'] == $user['id']) { ?>
				<tr><td><strong>Odbiorca:</strong></td><td><?php echo $author['name'];?> <?php echo $author['lastname'];?></td></tr>
			<?php } else { ?>
				<tr><td><strong>Nadawca:</strong></td><td><?php echo $author['name'];?> <?php echo $author['lastname'];?></td></tr>
			<?php } ?>
				<tr><td><strong>Treść:</strong></td><td><?php echo $msg['message'];?></td></tr>
			</tbody>
		</table>
		<?php if($msg['to_user'] == $user['id']) {
				echo '<a href="'.$config['page_url'].'?page=messages&option=2&id='.(int)$_GET['id'].'"><input type="submit" class="btn btn-primary" value="Odpowiedź"/></a>';
			}
		} else {
			alert(2, 'Nie masz praw, by przeczytać tą wiadomość!');
		}
	} elseif($option == 2) {
		if(!empty($_POST['send'])) {
			if(!empty($_POST['recipe']) && !empty($_POST['title']) && !empty($_POST['message'])) {
				$name = explode(' ', $_POST['recipe']);
				$recipe = $db->select_single("SELECT id FROM users WHERE UPPER(name) = UPPER('".$name[0]."') AND UPPER(lastname) = UPPER('".$name[1]."')");
				if($recipe) {
					$db->query("INSERT INTO `messages` (`from_user`, `to_user`, `title`, `message`, `send_date`, `read_date`) VALUES ('".$user['id']."', '".$recipe['id']."', '".$_POST['title']."', '".$_POST['message']."', '".$time_now."', '0');");
					alert(1, 'Wiadomość została wysłana.');
				} else {
					alert(2, 'Taki użytkownik nie istnieje!');
				}
			} else {
				alert(2, 'Proszę wypełnić wszystkie pola!');
			}
		}
		$recipe = '';
		$title = '';
		if(!empty($_GET['id'])) {
			$msg = $db->select_single('SELECT m.*, u.name, u.lastname FROM messages m JOIN users u ON u.id = m.from_user WHERE m.id = '.(int)$_GET['id']);
			$recipe = $msg['name'].' '.$msg['lastname'];
			$title = '[RE] '.$msg['title'];
		}
?>
		<form action="" method="POST">
			<input type="hidden" name="send" value="1"/>
			<div class="row">
				<div class="col-lg-12">
					<div class="form-group">
						<label>Odbiorca (imię nazwisko):</label>
						<input type="text" class="form-control" name="recipe" value="<?php echo $recipe;?>"/>
					</div>
					<div class="form-group">
						<label>Tytuł Wiadomości:</label>
						<input type="text" class="form-control" name="title" value="<?php echo $title;?>"/>
					</div>
					<div class="form-group">
						<label>Wiadomość:</label>
						<textarea class="form-control" name="message" rows="7" style="resize:none;"></textarea>
					</div>
					<input type="submit" class="btn btn-primary btn-ls" value="Wyślij"/>
				</div>
			</div>
		</form>
<?php
	} elseif($option == 1) {
		echo '<a href="'.$config['page_url'].'?page=messages" style="display: inline-block;"><input type="submit" class="btn btn-primary" value="Wiadomości Odebrane"/></a> ';
		echo '<a href="'.$config['page_url'].'?page=messages&option=1" syle="display: inline-block;"><input type="submit" class="btn btn-primary" value="Wiadomości Wysłane"/></a><br/><br/>';
		$messages = $db->select_multi('SELECT m.*, u.name, u.lastname FROM messages m JOIN users u ON u.id = m.to_user WHERE m.from_user = '.$user['id']);
		echo '<table class="table table-bordered table-hover">
			<tr>
				<th width="150px">Tytuł</th>
				<th>Odbiorca</th>
				<th>Data Wysłania</th>
				<th width="57px"></th>
			  </tr>
			<tbody>';
		if($messages) {
			foreach($messages as $m) {
				echo '<tr><td>'.$m['title'].'</td><td>'.$m['name'].' '.$m['lastname'].'</td><td>'.date('H:i, d.m.Y', $m['send_date']).'</td><td><a href="'.$config['page_url'].'?page=messages&option=3&id='.$m['id'].'"><input type="submit" class="btn btn-primary btn-xs" value="Pokaż"></a></td></tr>';
			}
		}
			echo '</tbody>
		</table>';
		echo '<a href="'.$config['page_url'].'?page=messages&option=2"><input type="submit" class="btn btn-primary" value="Nowa Wiadomość"/></a>';
	} else {
		echo '<a href="'.$config['page_url'].'?page=messages&option=1" style="display: inline-block;"><input type="submit" class="btn btn-primary" value="Wiadomości Wysłane"/></a> ';
		echo '<a href="'.$config['page_url'].'?page=messages" style="display: inline-block;"><input type="submit" class="btn btn-primary" value="Wiadomości Odebrane"/></a><br/><br/>';
		$messages = $db->select_multi('SELECT m.*, u.name, u.lastname FROM messages m JOIN users u ON u.id = m.from_user WHERE m.to_user = '.$user['id']);
		echo '<table class="table table-bordered table-hover">
			<tr>
				<th width="150px">Tytuł</th>
				<th>Nadawca</th>
				<th>Data Wysłania</th>
				<th width="57px"></th>
			  </tr>
			<tbody>';
		if($messages) {
			foreach($messages as $m) {
				echo '<tr><td>'.$m['title'].'</td><td>'.$m['name'].' '.$m['lastname'].'</td><td>'.date('H:i, d.m.Y', $m['send_date']).'</td><td><a href="'.$config['page_url'].'?page=messages&option=3&id='.$m['id'].'"><input type="submit" class="btn btn-primary btn-xs" value="Pokaż"></a></td></tr>';
			}
		}
			echo '</tbody>
		</table>';
		echo '<a href="'.$config['page_url'].'?page=messages&option=2"><input type="submit" class="btn btn-primary" value="Nowa Wiadomość"/></a>';
	}
?>