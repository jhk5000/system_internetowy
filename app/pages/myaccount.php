<?php
	if($option == 1) {
		echo '<h2>Zmiana Hasła</h2><hr class="style-one"></hr>';
		if(!empty($_POST['send'])) {
			if(!empty($_POST['old']) && !empty($_POST['pass1']) && !empty($_POST['pass2'])) {
				if($_POST['pass1'] != $_POST['pass2']) {
					alert(2, 'Podane hasła nie są identyczne!');
				} elseif(sha1($_POST['old']) != $user['password']) {
					alert(2, 'Stare hasło nie jest prawidłowe!');
				} else {
					$db->query("UPDATE users SET password = '".sha1($_POST['pass1'])."' WHERE id = ".$user['id']);
					alert(1, 'Hasło zostało zmienione!');
				}
			} else {
				alert(2, 'Wypełnij wszystkie pola!');
			}
		}
?>
		<form action="" method="POST">
			<input type=hidden name="send" value="1">
			<div class="row">
				<div class="col-lg-12">
					<div class="form-group">
						<label>Stare Hasło:</label>
						<input name="old" value="" class="form-control">
					</div>
					<div class="form-group">
						<label>Nowe Hasło:</label>
						<input name="pass1" value="" class="form-control">
					</div>
					<div class="form-group">
						<label>Powtórz Nowe Hasło:</label>
						<input name="pass2" value="" class="form-control">
					</div>
					<input type="submit" class="btn btn-primary btn-ls" value="Zmień Hasło"/>
				</div>
			</div>
		</form>
<?php
	} else {
		echo '<h2>Moje Konto</h2><hr class="style-one"></hr>';
?>
		<table class="table table-bordered table-hover">
			<tbody>
				<tr><td><strong>Imie:</strong></td><td><?php echo $user['name'];?></td></tr>
				<tr><td><strong>Nazwisko:</strong></td><td><?php echo $user['lastname'];?></td></tr>
				<tr><td><strong>Data rejestracji:</strong></td><td><?php echo date('H:i, d.m.Y', $user['register_date']);?></td></tr>
				<tr><td><strong>Typ konta:</strong></td><td><?php echo $config['account_types'][$user['group_id']];?></td></tr>
			<?php if($user['group_id'] == 1) {
				$studies = $db->select_single('SELECT name, type FROM studies WhERE id = '.$user['subject_ids']);
				echo '<tr><td><strong>Kierunek:</strong></td><td>'.$studies['name'].'</td></tr>';
				$promoter = $db->select_single('SELECT * FROM users WHERE id = '.$user['promoter_id']);
				$p = '';
				if($promoter)
					$p = $promoter['name'].' '.$promoter['lastname'];
				$topic = $db->select_single('SELECT * FROM theses_topics WHERE student_id = '.$user['id']);
				$t = '';
				if($topic)
					$t = $topic['topic'];
			?>
				<tr><td><strong>Promotor:</strong></td><td><?php echo $p;?></td></tr>
				<tr><td><strong>Tytuł Pracy:</strong></td><td><?php echo $t;?></td></tr>
				<tr><td><strong>Stopień studiów:</strong></td><td><?php echo $config['studies_types'][$studies['type']];?></td></tr>
				<tr><td><strong>Indeks:</strong></td><td><?php echo $user['indeks'];?></td></tr>
			<?php } ?>
			</tbody>
		</table>
		<a href="<?php $config['page_url'];?>?page=myaccount&option=1"><input type="submit" class="btn btn-primary btn-ls" value="Zmień Hasło"/></a>
<?php
	}
?>