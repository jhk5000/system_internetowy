<?php
	if($user['group_id'] == 2 && $user['accepted'] == 0) {
		echo '<h2>Tematy prac</h2><hr class="style-one"></hr>';
		alert(2, 'Poczekaj na akceptację Twojego konta!');
		exit();
	}
	if($option == 1) {
		$studies = $db->select_multi('SELECT s.*, d.name as d_name FROM studies s JOIN departments d ON d.id = s.deparment_id WHERE s.id IN (SELECT studies_id FROM subjects WHERE id IN (SELECT subject_id FROM studies_groups_teachers WHERE teacher_id = '.$user['id'].'))');
		if (empty($studies) === true) {
			alert(2, 'Nie zostałeś przypisany do żadnego przedmiotu!');
			exit;
		}//end if
		echo '<h2>Tematy prac - Dodaj Pracę</h2><hr class="style-one"></hr>';
		if(!empty($_POST['send'])) {
			if(!empty($_POST['name']) && !empty($_POST['desc'])) {
				alert(1, 'Nowy temat pracy został dodany!');
				$db->query("INSERT INTO `theses_topics` (`topic`, `promoter_id`, `student_id`, `add_date`, `take_date`, `description`, `studies_id`) VALUES ('".$_POST['name']."', '".$user['id']."', '0', '".$time_now."', '0', '".$_POST['desc']."', '".$_POST['studies']."');");
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
						<label>Temat Pracy</label>
						<input name="name" value="" class="form-control">
					</div>
					<div class="form-group">
						<label>Opis Pracy</label>
						<textarea class="form-control" name="desc" rows="5" style="resize:none;"></textarea>
					</div>
					<div class="form-group">
						<label>Kierunek:</label>
						<select name="studies" class="form-control">
					<?php
						foreach ($studies as $s) {
							echo '<option value="'.$s['id'].'">'.$s['name'].'</option>';
						}
					?>
						</select>
					</div>
					<input type="submit" class="btn btn-primary btn-ls" value="Dodaj"/>
				</div>
			</div>
		</form>
<?php
	} else if ($option == 3) {
		echo '<h2>Tematy prac - Edytuj Temat</h2><hr class="style-one"></hr>';
		if(!empty($_POST['send'])) {
			if(!empty($_POST['name']) && !empty($_POST['desc'])) {
				alert(1, 'Edycja tematu zakończona!');
				$db->query("UPDATE theses_topics SET studies_id = '".$_POST['studies']."', topic = '".$_POST['name']."', description = '".$_POST['desc']."' WHERE id = ".(int)$_GET['id']);
			} else {
				alert(2, 'Uzupełnij wszystkie pola!');
			}
		}

		$var = $db->select_single('SELECT * FROM theses_topics WHERE id = '.(int)$_GET['id']);
?>
		<form action="" method="POST">
			<input type="hidden" name="send" value="1"/>
			<div class="row">
				<div class="col-lg-12">
					<div class="form-group">
						<label>Temat Pracy</label>
						<input name="name" value="<?php echo $var['topic'];?>" class="form-control">
					</div>
					<div class="form-group">
						<label>Opis Pracy</label>
						<textarea class="form-control" name="desc" rows="5" style="resize:none;"><?php echo $var['description'];?></textarea>
					</div>
					<div class="form-group">
						<label>Kierunek:</label>
						<select name="studies" class="form-control">
					<?php
						if ($user['group_id'] == 2) {
							$studies = $db->select_multi('SELECT * FROM studies WHERE id IN (SELECT DISTINCT studies_id FROM subjects WHERE id IN (SELECT subject_id FROM studies_groups_teachers WHERE teacher_id = '.$user['id'].'))');
						} else {
							$studies = $db->select_multi('SELECT * FROM studies');
						}//end if

						foreach ($studies as $s) {
							if ($s['id'] == $var['studies_id']) {
								echo '<option value="'.$s['id'].'" selected>'.$s['name'].' ('.$config['studies_types'][$s['type']].')</option>';
							} else {
								echo '<option value="'.$s['id'].'">'.$s['name'].' ('.$config['studies_types'][$s['type']].')</option>';
							}
						}
					?>
						</select>
					</div>
					<input type="submit" class="btn btn-primary btn-ls" value="Edytuj"/>
				</div>
			</div>
		</form>
<?php
	} elseif($option == 4) {
		$db->query('DELETE FROM theses_topics WHERE id = '.(int)$_GET['id']);
		header('Location: '.$config['page_url'].'?page=topics');
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
	} elseif($option == 2) {
		echo '<h2>Tematy prac - Pokaż Pracę</h2><hr class="style-one"></hr>';
		$var = $db->select_single('SELECT t.*, s.name as s_name, s.lastname as s_lastname, p.name as p_name, p.lastname as p_lastname FROM theses_topics t LEFT JOIN users s ON s.id = t.student_id LEFT JOIN users p ON p.id = t.promoter_id WHERE t.id = '.(int)$_GET['id']);
?>
		<table class="table table-bordered table-hover">
			<tbody>
				<tr><td><strong>Nazwa:</strong></td><td><?php echo $var['topic'];?></td></tr>
				<tr><td><strong>Opis:</strong></td><td><?php echo $var['description'];?></td></tr>
				<tr><td><strong>Promotor:</strong></td><td><?php echo $var['p_name'].' '.$var['p_lastname'];?></td></tr>
				<tr><td><strong>Data dodania:</strong></td><td><?php echo date('H:i, d.m.Y', $var['add_date']);?></td></tr>
				<tr><td><strong>Student:</strong></td><td><?php echo $var['s_name'].' '.$var['s_lastname'];?></td></tr>
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
			$edits = $db->select_multi('SELECT * FROM theses_edits WHERE these_id = '.(int)$_GET['id']);
			if($edits) {
				foreach($edits as $e) {
					echo '<tr><td>'.date('H:i, d.m.Y', $e['edit_date']).'</td><td>'.$e['text'].'</td><td><a href="'.$config['page_url'].'uploads/'.$e['these_id'].'-'.$e['edit_date'].'.'.$e['file_type'].'"><input type="submit" class="btn btn-primary btn-xs" value="Pobierz Załącznik"/></a> <a href="'.$config['page_url'].'?page=topics&option=15&id='.$e['id'].'"><input type="submit" class="btn btn-info btn-xs" value="Komentarz"/></a></td></tr>';
				}
			}
?>				
			</tbody>
		</table>
<?php
	} elseif($option == 400) {
		$db->query('UPDATE theses_topics SET archivised = 1 WHERE id = '.(int) $_GET['id']);
		header('Location: ?page=topics');
	} elseif($option == 400) {
		$db->query('UPDATE subjects_topics SET archivised = 1 WHERE id = '.(int) $_GET['id']);
		header('Location: ?page=topics');
	} else {
		echo '<h2>Tematy prac</h2><hr class="style-one"></hr>';
		if($user['group_id'] == 2) {
			$topics = $db->select_multi('SELECT t.*, u.name as p_name, u.lastname as p_lastname, u2.name as s_name, u2.lastname as s_lastname FROM theses_topics t JOIN users u ON u.id = t.promoter_id LEFT JOIN users u2 ON u2.id = t.student_id WHERE t.archivised = 0 AND t.promoter_id = '.$user['id']);
		} else {
			$topics = $db->select_multi('SELECT t.*, u.name as p_name, u.lastname as p_lastname, u2.name as s_name, u2.lastname as s_lastname FROM theses_topics t JOIN users u ON u.id = t.promoter_id LEFT JOIN users u2 ON u2.id = t.student_id');
		}
		echo '<h2>Prace dyplomowe</h2>';
		echo '<table class="table table-bordered table-hover">
    <thead>
      <tr>
        <th width="20px">ID</th>
        <th>Temat Pracy</th>
		<th>Promotor</th>
		<th>Student</th>
		<th width="227px"></th>
      </tr>
    </thead>
    <tbody>';
		if($topics) {
			foreach($topics as $t) {
				$student = '';
				if($t['student_id'] > 0) $student = $t['s_name'].' '.$t['s_lastname'];
				echo '<tr><td>'.$t['id'].'</td><td>'.$t['topic'].'</td><td>'.$t['p_name'].' '.$t['p_lastname'].'</td><td>'.$student.'</td><td>
				<a href="'.$config['page_url'].'?page=topics&option=2&id='.$t['id'].'"><input type="submit" class="btn btn-info btn-xs" value="Pokaż"></a> 
				<a href="'.$config['page_url'].'?page=topics&option=3&id='.$t['id'].'"><input type="submit" class="btn btn-primary btn-xs" value="Edytuj"></a> 
				<a href="'.$config['page_url'].'?page=topics&option=4&id='.$t['id'].'"><input type="submit" class="btn btn-danger btn-xs" value="Usuń"></a> <a href="'.$config['page_url'].'?page=topics&option=400&id='.$t['id'].'"><input type="submit" class="btn btn-primary btn-xs" value="Archiwizuj"></a></td></tr>';
			}
		}
		echo '</tbody></table>';
		if ($user['group_id'] == 2) {
			echo '<a href="'.$config['page_url'].'?page=topics&option=1"><input type="submit" class="btn btn-primary btn-ls" value="Dodaj Temat"/></a>';
		}
		if ($user['group_id'] > 2) {
			echo '<h2>Projekty uczelniane</h2>';

		    	$topics = $db->select_multi('SELECT t.*, s.name as su_name, u.name as s_name, u.lastname as s_lastname FROM subjects_topics t JOIN subjects s ON s.id = t.subject_id LEFT JOIN users u ON u.id = t.student_id WHERE t.archivised = 0');
				echo '<table class="table table-bordered table-hover">
    <thead>
      <tr>
        <th width="20px">ID</th>
        <th>Temat Pracy</th>
		<th>Przedmiot</th>
		<th>Student</th>
		<th width="237px"></th>
      </tr>
    </thead>
    <tbody>';
		if($topics) {
			foreach($topics as $t) {
				$student = '';
				if($t['student_id'] > 0) $student = $t['s_name'].' '.$t['s_lastname'];
				echo '<tr><td>'.$t['id'].'</td><td>'.$t['title'].'</td><td>'.$t['su_name'].'</td><td>'.$student.'</td><td>
				<a href="'.$config['page_url'].'?page=works&option=2&id='.$t['id'].'"><input type="submit" class="btn btn-info btn-xs" value="Pokaż"></a> 
				<a href="'.$config['page_url'].'?page=works&option=3&id='.$t['id'].'"><input type="submit" class="btn btn-primary btn-xs" value="Edytuj"></a> 
				<a href="'.$config['page_url'].'?page=works&option=4&id='.$t['id'].'"><input type="submit" class="btn btn-danger btn-xs" value="Usuń"></a> <a href="'.$config['page_url'].'?page=topics&option=401&id='.$t['id'].'"><input type="submit" class="btn btn-primary btn-xs" value="Archiwizuj"></a></td></tr>';
			}
		}
		echo '</tbody></table>';
		}
	}
