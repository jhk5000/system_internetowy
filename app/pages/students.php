<?php
	echo '<h2>Studenci</h2><hr class="style-one"></hr>';
	if($user['group_id'] == 1) {
		$group = $db->select_single('SELECT * FROM studies_groups_students WHERE student_id = '.$user['id']);
		$students = $db->select_multi("SELECT u.*, t.topic FROM users u LEFT JOIN theses_topics t ON t.student_id = u.id WHERE u.id IN (SELECT student_id FROM studies_groups_students WHERE group_id = ".$group['group_id'].")");
		echo '<table class="table table-bordered table-hover"><thead><tr>
					<th>Imie</th>
					<th>Nazwisko</th>
					<th>Promotor</th>
					<th>Temat Pracy</th>
				  </tr></thead><tbody>';
		if($students) {
			foreach($students as $s) {
				if ($s['promoter_id'] > 0) {
					$var = $db->select_single('SELECT * FROM users WHERE id = '.(int) $s['promoter_id']);
					$promoter = $var['name'].' '.$var['lastname'];
				} else {
					$promoter = 'B/D';
				}//end if

				echo '<tr><td>'.$s['name'].'</td><td>'.$s['lastname'].'</td><td>'.$promoter.'</td><td>'.$s['topic'].'</td></tr>';
			}
		}
		echo '</tbody></table>';
	} else {
		if (empty($_GET['option'])) {
			if($user['group_id'] == 2 && $user['accepted'] == 0) {
				alert(2, 'Poczekaj na akceptację Twojego konta!');
				exit();
			}//end if

			if ($user['group_id'] == 3) {
				$students = $db->select_multi('SELECT u.* FROM users u LEFT JOIN theses_topics t ON t.student_id = u.id WHERE u.group_id = 1 AND u.group_id = 1');
			} else {
				$students = $db->select_multi('SELECT u.* FROM users u LEFT JOIN theses_topics t ON t.student_id = u.id WHERE u.group_id = 1 AND u.id IN (SELECT student_id FROM studies_groups_students WHERE group_id IN (SELECT group_id FROM studies_groups_teachers WHERE teacher_id = '.$user['id'].'))');
			}//end if

			echo '<table class="table table-bordered table-hover">
					<thead>
					  <tr>
						<th>Imie</th>
						<th>Nazwisko</th>
						<th>Promotor</th>
						<th>Numer Indeksu</th>
						<th>Kierunek</th>
						<th>Temat Pracy</th>
						<th width="110px"></th>
					  </tr>
					</thead>
					<tbody>';
			if (empty($students) === false) {
				foreach ($students as $s) {
					$promoter = $db->select_single('SELECT * FROM users WHERE id = '.$s['promoter_id']);
					if (empty($promoter) === true) {
						$promoter = 'B/D';
					} else {
						$promoter = $promoter['name'].' '.$promoter['lastname'];
					}//end if

					$these = $db->select_single('SELECT * FROM theses_topics WHERE student_id = '.$s['id']);

					if (empty($these) === true) {
						$these = '';
					} else {
						$these = $these['topic'];
					}//end if

					$studies = $db->select_single('SELECT * FROM studies WHERE id = '.$s['subject_ids']);

					echo '<tr><td>'.$s['name'].'</td><td>'.$s['lastname'].'</td><td>'.$promoter.'</td><td>'.$s['indeks'].'</td><td>'.$studies['name'].' ('.$config['studies_types'][$studies['type']].')</td><td>'.$these.'</td><td><a href="'.$config['page_url'].'?page=students&option=1&id='.$s['id'].'"><input type="submit" class="btn btn-info btn-xs" value="Edytuj"/></a> <a href="'.$config['page_url'].'?page=students&option=5&id='.$s['id'].'"><input type="submit" class="btn btn-danger btn-xs" value="Usuń"/></a></td></tr>';
				}//end foreach
			} else {
				echo '<tr><td colspan="7">Lista jest pusta</td></tr>';
			}//end if

			echo '</tbody></table>';
} else if ($_GET['option'] == 5) {
	$db->query('DELETE FROM users WHERE id = '.(int) $_GET['id']);
	header('Location: ?page=students');
} else {
	if (empty($_POST) === false) {
		$db->query("UPDATE users SET indeks = '".$_POST['indeks']."', mail = '".$_POST['mail']."', name = '".$_POST['name']."', lastname= '".$_POST['lastname']."' WHERE id = ".(int) $_GET['id']);
		alert(1, 'Dane zostały zmienione!');
	}
	$student = $db->select_single('SELECT * FROM users WHERE id = '.(int) $_GET['id']);
?>
<form action="" method="POST" enctype="multipart/form-data">
	<input type="hidden" name="send" value="1"/>
	<div class="row">
		<div class="col-lg-12">
			<div class="form-group">
				<label>Imię:</label>
				<input class="form-control" name="name" value="<?php echo $student['name'];?>"/>
			</div>
			<div class="form-group">
				<label>Nazwisko:</label>
				<input class="form-control" name="lastname" value="<?php echo $student['lastname'];?>"/>
			</div>
			<div class="form-group">
				<label>Mail:</label>
				<input class="form-control" name="mail" value="<?php echo $student['mail'];?>"/>
			</div>
			<div class="form-group">
				<label>Indeks:</label>
				<input class="form-control" name="indeks" value="<?php echo $student['indeks'];?>"/>
			</div>
			<div class="form-group">
				<label>Status:</label>
				<select name="status" class="form-control">
					<option value="1">Zaakceptowany</option>
					<option value="0">Oczekujący</option>
				</select>
			</div>
			<input type="submit" class="btn btn-primary btn-ls" value="Edytuj"/>
			<a href="?page=students"><button type="button" class="btn btn-primary">Powrót</button></a>
		</div>
	</div>
</form>
<?php
	}
}
