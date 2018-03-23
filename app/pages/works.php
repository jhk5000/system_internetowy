<?php
	if($user['group_id'] == 2 && $user['accepted'] == 0) {
		echo '<h2>Tematy prac</h2><hr class="style-one"></hr>';
		alert(2, 'Poczekaj na akceptację Twojego konta!');
		exit();
	}
	if($option == 1) {
		echo '<h2>Tematy prac - Dodaj Pracę</h2><hr class="style-one"></hr>';

		$subjects = $db->select_multi('SELECT s.*, p.name as p_name, p.type FROM subjects s JOIN studies p ON p.id = s.studies_id WHERE s.id IN (SELECT DISTINCT subject_id FROM studies_groups_teachers WHERE teacher_id = '.$user['id'].')');

		if (empty($subjects) === true) {
			alert(2, 'Nie zostałeś przypisany do żadnego przedmiotu!');
			exit;
		}//end if

		if(!empty($_POST['send'])) {
			if(!empty($_POST['name']) && !empty($_POST['desc'])) {
				alert(1, 'Nowy temat zadania został dodany!');
				$db->query("INSERT INTO `subjects_topics` (`title`, `description`, `add_date`, `user_id`, `student_id`, `subject_id`, `teacher_id`) 
						VALUES ('".$_POST['name']."', '".$_POST['desc']."', '".$time_now."', '".$user['id']."', '0', '".$_POST['subject']."', '".$user['id']."');");
			} else {
				alert(2, 'Uzupełnij wszystkie pola!');
			}
		}
?>
		<form action="" method="POST">
			<input type="hidden" name="send" value="1"/>
			<div class="row">
				<div class="col-lg-12">
					<div class="form-group">
						<label>Temat Zadania</label>
						<input name="name" value="" class="form-control">
					</div>
					<div class="form-group">
						<label>Opis Zadania</label>
						<textarea class="form-control" name="desc" rows="5" style="resize:none;"></textarea>
					</div>
					<div class="form-group">
						<label>Przedmiot</label>
						<select class="form-control" name="subject">
					<?php
						foreach($subjects as $s) {
							echo '<option value="'.$s['id'].'">'.$s['name'].' ('.$s['p_name'].' - '.$config['studies_types'][$s['type']].')</option>';
						}
					?>
						</select>
					</div>
					<input type="submit" class="btn btn-primary btn-ls" value="Dodaj"/>
				</div>
			</div>
		</form>
<?php
	} elseif($option == 3) {
		echo '<h2>Tematy prac - Edytuj Temat</h2><hr class="style-one"></hr>';
		if(!empty($_POST['send'])) {
			if(!empty($_POST['name']) && !empty($_POST['desc'])) {
				alert(1, 'Edycja tematu zakończona!');
				$db->query("UPDATE subjects_topics SET title = '".$_POST['name']."', description = '".$_POST['desc']."' WHERE id = ".(int)$_GET['id']);
			} else {
				alert(2, 'Uzupełnij wszystkie pola!');
			}
		}
		$var = $db->select_single('SELECT * FROM subjects_topics WHERE id = '.(int)$_GET['id']);
?>
		<form action="" method="POST">
			<input type="hidden" name="send" value="1"/>
			<div class="row">
				<div class="col-lg-12">
					<div class="form-group">
						<label>Temat Pracy</label>
						<input name="name" value="<?php echo $var['title'];?>" class="form-control">
					</div>
					<div class="form-group">
						<label>Opis Pracy</label>
						<textarea class="form-control" name="desc" rows="5" style="resize:none;"><?php echo $var['description'];?></textarea>
					</div>
					<input type="submit" class="btn btn-primary btn-ls" value="Edytuj"/>
				</div>
			</div>
		</form>
<?php
	} elseif($option == 4) {
		$db->query('DELETE FROM subjects_topics WHERE id = '.(int)$_GET['id']);
		header('Location: '.$config['page_url'].'?page=works');
	} elseif($option == 15) {
		echo '<h2>Tematy prac - Komentarz</h2><hr class="style-one"></hr>';
		$var = $db->select_single('SELECT * FROM theses_edits WHERE id = '.(int)$_GET['id']);
		if(!empty($_POST['send'])) {
			$db->query("UPDATE theses_edits SET comment = '".$_POST['desc']."' WHERE id = ".$var['id']);
			$var['comment'] = $_POST['desc'];
			alert(1, 'Komentarz został zmieniony.');
		}
?>
		<form action="" method="POST">
			<input type="hidden" name="send" value="1"/>
			<div class="row">
				<div class="col-lg-12">
					<div class="form-group">
						<label>Komentarz do pracy</label>
						<textarea class="form-control" name="desc" rows="5" style="resize:none;"><?php echo $var['comment'];?></textarea>
					</div>
					<input type="submit" class="btn btn-primary btn-ls" value="Edytuj"/>
				</div>
			</div>
		</form>
<?php
	} elseif($option == 400) {
		$db->query('UPDATE subjects_topics SET archivised = 1 WHERE id = '.(int) $_GET['id']);
		header('Location: ?page=works');
	} elseif($option == 2) {
		echo '<h2>Tematy prac - Pokaż Pracę</h2><hr class="style-one"></hr>';
		$var = $db->select_single('SELECT * FROM subjects_topics WHERE id = '.(int)$_GET['id']);
?>
		<table class="table table-bordered table-hover">
			<tbody>
				<tr><td width="20%"><strong>Nazwa:</strong></td><td><?php echo $var['title'];?></td></tr>
				<tr><td><strong>Opis:</strong></td><td><?php echo $var['description'];?></td></tr>
				<tr><td><strong>Data dodania:</strong></td><td><?php echo date('H:i, d.m.Y', $var['add_date']);?></td></tr>
				<tr><td><strong>Student:</strong></td><td><?php echo $var['student_id'];?></td></tr>
			</tbody>
		</table>
		<h3>Lista zmian</h3><hr class="style-one"></hr>
		<table class="table table-bordered table-hover">
			<tr>
				<th width="150px">Data zmiany</th>
				<th>Opis</th>
				<th width="199px"></th>
			  </tr>
			<tbody>
<?php
		$changes = $db->select_multi('SELECT * FROM subject_topic_edits WHERE topic_id = '.$var['id'].' ORDER BY id DESC');
		if ($changes) {
			foreach($changes as $e) {
				echo '<tr><td>'.date('H:i, d.m.Y', $e['add_date']).'</td><td>'.$e['comment'].'</td><td><a href="'.$config['page_url'].'uploads/'.$e['topic_id'].'-'.$e['add_date'].'.'.$e['file_type'].'"><input type="submit" class="btn btn-primary btn-xs" value="Pobierz Załącznik"/></a><a href="'.$config['page_url'].'?page=topics&option=15&id='.$e['id'].'"> <input type="submit" class="btn btn-info btn-xs" value="Komentarz"/></a></td></tr>';
			}
		} else {
			echo '<tr><td>Brak wyników</td></tr>';
		}
?>				
			</tbody>
		</table>
<?php
	} else {
		echo '<h2>Tematy prac</h2><hr class="style-one"></hr>';
		$topics = $db->select_multi('SELECT t.*, s.studies_id, s.name as su_name, u.name as s_name, u.lastname as s_lastname FROM subjects_topics t JOIN subjects s ON s.id = t.subject_id LEFT JOIN users u ON u.id = t.teacher_id = '.$user['id'].' WHERE t.archivised = 0');
		echo '<table class="table table-bordered table-hover">
    <thead>
      <tr>
        <th width="20px">ID</th>
        <th>Temat Pracy</th>
		<th>Przedmiot</th>
		<th>Student</th>
		<th>Kierunek</th>
		<th width="237px"></th>
      </tr>
    </thead>
    <tbody>';
		if($topics) {
			foreach($topics as $t) {
				$studies = $db->select_single('SELECT * FROM studies WHERE id = '.$t['studies_id']);
				$student = '';
				if($t['student_id'] > 0) $student = $t['s_name'].' '.$t['s_lastname'];
				echo '<tr><td>'.$t['id'].'</td><td>'.$t['title'].'</td><td>'.$t['su_name'].'</td><td>'.$student.'</td><Td>'.$studies['name'].' ('.$config['studies_types'][$studies['type']].')<td>
				<a href="'.$config['page_url'].'?page=works&option=2&id='.$t['id'].'"><input type="submit" class="btn btn-info btn-xs" value="Pokaż"></a> 
				<a href="'.$config['page_url'].'?page=works&option=3&id='.$t['id'].'"><input type="submit" class="btn btn-primary btn-xs" value="Edytuj"></a> 
				<a href="'.$config['page_url'].'?page=works&option=4&id='.$t['id'].'"><input type="submit" class="btn btn-danger btn-xs" value="Usuń"></a> 
				<a href="'.$config['page_url'].'?page=works&option=400&id='.$t['id'].'"><input type="submit" class="btn btn-primary btn-xs" value="Archiwizuj"></td></tr>';
			}
		}
		echo '</tbody></table>';
		echo '<a href="'.$config['page_url'].'?page=works&option=1"><input type="submit" class="btn btn-primary btn-ls" value="Dodaj Temat"/></a>';
	}
?>