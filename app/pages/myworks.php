<?php
	echo '<h2>Moje Prace</h2><hr class="style-one"></hr>';
	if($option == 0) {
		$topics = $db->select_multi('SELECT t.*, s.name as su_name FROM subjects_topics t JOIN subjects s ON s.id = t.subject_id WHERE t.student_id = '.$user['id']);
		echo '<table class="table table-bordered table-hover">
		<thead><tr>
			<th>Temat Pracy</th>
			<th>Opis</th>
			<th>Przedmiot</th>
			<th>Status</th>
			<th>Prowadzący</th>
			<th width="57px"></th>
		  </tr></thead><tbody>';
		if($topics) {
			foreach($topics as $t) {
				$status = 'Aktywna';
				if ($t['rate'] > 0) {
					$status = 'Oceniona';
				}
				$teacher = $db->select_single('SELECT * FROM users WHERE id = '.$t['teacher_id']);
				echo '<tr><td>'.$t['title'].'</td><td>'.$t['description'].'</td><td>'.$t['su_name'].'</td><td>'.$status.'</td><td>'.$teacher['name'].' '.$teacher['lastname'].'</td><td>
				<a href="'.$config['page_url'].'?page=myworks&option=2&id='.$t['id'].'"><input type="submit" class="btn btn-info btn-xs" value="Pokaż"></a></td></tr>';
			}
		}
		echo '</tbody></table>';
		echo '<a href="'.$config['page_url'].'?page=myworks&option=9"><input type="submit" class="btn btn-primary" value="Wybierz Pracę"></a> ';
		if ($user['group_id'] > 1) {
			echo '<a href="'.$config['page_url'].'?page=myworks&option=3"><input type="submit" class="btn btn-primary" value="Dodaj Pracę"></a> ';
		}//end if
		echo '<a href="'.$config['page_url'].'?page=myworks&option=8"><input type="submit" class="btn btn-primary" value="Dodaj Propozycję"></a> ';
	} elseif($option == 1) {
		$db->query('UPDATE subjects_topics SET student_id = '.$user['id'].' WHERE id = '.(int)$_GET['id']);
		alert(1, 'Zadanie zostało dodane do listy!');
		header('refresh: 1; ?page=myworks');
	} elseif($option == 2) {
		$var = $db->select_single('SELECT t.*, s.name as s_name FROM subjects_topics t JOIN subjects s ON s.id = t.subject_id WHERE t.id = '.(int)$_GET['id']);
		$rate = 'Brak';
		$status = 'Aktywna';
		if ($var['rate'] > 0) {
			$rate = $var['rate'];
			$status = 'Oceniona';
		}
?>
		<table class="table table-bordered table-hover">
			<tbody>
				<tr><td width=20%><strong>Nazwa:</strong></td><td><?php echo $var['title'];?></td></tr>
				<tr><td><strong>Opis:</strong></td><td><?php echo $var['description'];?></td></tr>
				<tr><td><strong>Przedmiot:</strong></td><td><?php echo $var['s_name'];?></td></tr>
				<tr><td><strong>Status:</strong></td><td><?php echo $status;?></td></tr>
				<tr><td><strong>Ocena:</strong></td><td><?php echo $rate;?></td></tr>
			</tbody>
		</table>
		<h3>Lista zmian</h3><hr class="style-one"></hr>
		<table class="table table-bordered table-hover">
			<tr>
				<th width="150px">Data zmiany</th>
				<th>Opis</th>
				<th width="107px"></th>
			  </tr>
			<tbody>
<?php
		$changes = $db->select_multi('SELECT * FROM subject_topic_edits WHERE topic_id = '.$var['id'].' ORDER BY id DESC');
		if ($changes) {
			foreach($changes as $e) {
				echo '<tr><td>'.date('H:i, d.m.Y', $e['add_date']).'</td><td>'.$e['comment'].'</td><td><a href="'.$config['page_url'].'uploads/'.$e['topic_id'].'-'.$e['add_date'].'.'.$e['file_type'].'"><input type="submit" class="btn btn-primary btn-xs" value="Pobierz Załącznik"/></a></td></tr>';
			}
		} else {
			echo '<tr><td>Brak wyników</td></tr>';
		}
?>				
			</tbody>
		</table>
		<a href="<?php echo $config['page_url'];?>?page=myworks&option=4&id=<?php echo $_GET['id'];?>"><input type="submit" class="btn btn-primary btn-ls" value="Dodaj Wersję"/></a>
		<h3>Komentarze</h3><hr class="style-one"></hr>
<?php
		$comments = $db->select_multi('SELECT c.*, u.name, u.lastname FROM comments c JOIN users u ON u.id = c.uiser_id WHERE c.type = 1 AND c.outer_id = '.$_GET['id']);
		if ($comments) {
			foreach ($comments as $c) {
				echo '<strong>'.$c['name'].' '.$c['lastname'].'</strong>: '.$c['text'].'</br>';
			}
		} else {
			echo 'Brak komentarzy.';
		}
	} else if($option == 4) {
		$var = $db->select_single('SELECT id, title FROM subjects_topics WHERE id = '.$_GET['id']);
		if(!empty($_POST['send'])) {
			$file_type = explode('.', $_FILES['uploadFile']['name']);
			$l = sizeof($file_type);
			if($l > 1) {
				$file_type = $file_type[$l - 1];
				$target_dir =  'uploads/'.$var['id'].'-'.$time_now.'.'.$file_type;
				if(move_uploaded_file($_FILES['uploadFile']['tmp_name'], $target_dir)) {
					$db->query("INSERT INTO `subject_topic_edits` (`topic_id`, `add_date`, `file_type`, `comment`) 
						VALUES ('".$var['id']."', '".$time_now."', '".$file_type."', '".$_POST['desc']."');");
					alert(1, 'Wersja pracy została dodana.');
				}
			}
		}
?>
<form action="" method="POST" enctype="multipart/form-data">
	<input type="hidden" name="send" value="1"/>
	<div class="row">
		<div class="col-lg-12">
			<div class="form-group">
				<label><?php echo $var['title'];?></label>
			</div>
			<div class="form-group">
				<label>Opis Wersji:</label>
				<textarea class="form-control" name="desc" rows="7" style="resize:none;"></textarea>
			</div>
			<div class="form-group">
				<label class="btn btn-warning" for="my-file-selector">
					<input id="my-file-selector" name="uploadFile" type="file" style="display:none;" onchange="$('#upload-file-info').html($(this).val());">
					Dodaj Załącznik...
				</label>
				<span class='label label-info' id="upload-file-info"></span>
			</div>
			<input type="submit" class="btn btn-primary btn-ls" value="Dodaj Wersję"/>
		</div>
	</div>
</form>
<?php
	} else if($option == 3) {
		if (empty($_POST['send']) === false) {
			if (empty($_POST['topic']) === false) {
				alert(1, 'Praca została dodana.');
				$db->query("INSERT INTO `subjects_topics` (`title`, `description`, `add_date`, `user_id`, `student_id`, `subject_id`) 
					VALUES ('".$_POST['topic']."', '".$_POST['desc']."', '".$time_now."', '".$user['id']."', '".$user['id']."', '".$_POST['subject']."');");
			} else {
				alert(2, 'Musisz podać temat pracy.');
			}
		}
?>
<form action="" method="POST">
	<input type=hidden name="send" value="1">
	<div class="row">
		<div class="col-lg-12">
			<div class="form-group">
				<label>Przedmiot:</label>
				<select class="form-control" name="subject">
				<?php
					$subjects = $db->select_multi('SELECT * FROM subjects WHERE studies_id = '.$user['subject_ids']);
					foreach ($subjects as $s)
						echo '<option value="'.$s['id'].'">'.$s['name'].'</option>';
				?>
				</select>
			</div>
			<div class="form-group">
				<label>Temat Pracy:</label>
				<input name="topic" value="" class="form-control">
			</div>
			<div class="form-group">
				<label>Opis Pracy:</label>
				<textarea name="desc" class="form-control"></textarea>
			</div>
			<input type="submit" class="btn btn-primary btn-ls" value="Dodaj Pracę"/>
		</div>
	</div>
</form>
<?php
	} else if($option == 9) {
		$topics = $db->select_multi('SELECT t.*, s.name as subject_name FROM subjects_topics t JOIN subjects s ON s.id = t.subject_id WHERE t.student_id = 0 AND t.subject_id IN (SELECT subject_id FROM studies_groups WHERE id IN (SELECT group_id FROM studies_groups_students WHERE student_id = '.$user['id'].')) AND t.teacher_id IN (SELECT teacher_id FROM studies_groups_teachers WHERE group_id IN (SELECT group_id FROM studies_groups_students WHERE student_id = '.$user['id'].'));');
		echo '<table class="table table-bordered table-hover">
		<thead><tr>
			<th>Temat Pracy</th>
			<th>Opis</th>
			<th>Przedmiot</th>
			<th>Prowadzący</th>
			<th width="57px"></th>
		  </tr></thead><tbody>';
		if($topics) {
			foreach($topics as $t) {
				$teacher = $db->select_single('SELECT * FROM users WHERE id = '.$t['teacher_id']);
				echo '<tr><td>'.$t['title'].'</td><td>'.$t['description'].'</td><td>'.$t['subject_name'].'</td><td>'.$teacher['name'].' '.$teacher['lastname'].'</td><td>
				<a href="'.$config['page_url'].'?page=myworks&option=1&id='.$t['id'].'"><input type="submit" class="btn btn-info btn-xs" value="Wybierz"></a></td></tr>';
			}
		} else {
			echo '<tr><td colspan="5">Brak tematów do wyboru</td></tr>';
		}
		echo '</tbody></table>';
	} else if($option == 8) {
		if (empty($_POST['send']) === false) {
			if (empty($_POST['topic']) === false) {
				alert(1, 'Praca została dodana.');
				$db->query("INSERT INTO `subjects_topics` (`title`, `description`, `add_date`, `user_id`, `student_id`, `subject_id`) 
					VALUES ('".$_POST['topic']."', '".$_POST['desc']."', '".$time_now."', '".$user['id']."', '0', '".$_POST['subject']."');");
			} else {
				alert(2, 'Musisz podać temat pracy.');
			}
		}
?>
<form action="" method="POST">
	<input type=hidden name="send" value="1">
	<div class="row">
		<div class="col-lg-12">
			<div class="form-group">
				<label>Przedmiot:</label>
				<select class="form-control" name="subject">
				<?php
					$subjects = $db->select_multi('SELECT * FROM subjects WHERE studies_id = '.$user['subject_ids']);
					foreach ($subjects as $s)
						echo '<option value="'.$s['id'].'">'.$s['name'].'</option>';
				?>
				</select>
			</div>
			<div class="form-group">
				<label>Temat Pracy:</label>
				<input name="topic" value="" class="form-control">
			</div>
			<div class="form-group">
				<label>Opis Pracy:</label>
				<textarea name="desc" class="form-control"></textarea>
			</div>
			<input type="submit" class="btn btn-primary btn-ls" value="Dodaj Propozycję"/>
		</div>
	</div>
</form>
<?php
	}
?>