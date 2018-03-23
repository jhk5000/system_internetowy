<?php

$option = 0;

if (empty($_GET['option']) === false) {
	$option = (int) $_GET['option'];
}//end if

if ($option === 0) {
	echo '<h2>Prace dyplomowe</h2><hr class="style-one"></hr>';

	if ($user['group_id'] == 2) {
		$topics = $db->select_multi('SELECT t.*, u.name as p_name, u.lastname as p_lastname, u2.name as s_name, u2.lastname as s_lastname FROM theses_topics t JOIN users u ON u.id = t.promoter_id LEFT JOIN users u2 ON u2.id = t.student_id WHERE t.archivised = 1 AND t.promoter_id = '.$user['id']);
	} else {
		$topics = $db->select_multi('SELECT t.*, u.name as p_name, u.lastname as p_lastname, u2.name as s_name, u2.lastname as s_lastname FROM theses_topics t JOIN users u ON u.id = t.promoter_id LEFT JOIN users u2 ON u2.id = t.student_id WHERE t.archivised = 1');
	}//end if
	echo '<table class="table table-bordered table-hover">
    <thead>
      <tr>
        <th width="20px">ID</th>
        <th>Temat Pracy</th>
		<th>Promotor</th>
		<th>Student</th>
		<th width="110px"></th>
      </tr>
    </thead>
    <tbody>';
	if($topics) {
		foreach($topics as $t) {
			$student = '';
			if($t['student_id'] > 0) $student = $t['s_name'].' '.$t['s_lastname'];
			echo '<tr><td>'.$t['id'].'</td><td>'.$t['topic'].'</td><td>'.$t['p_name'].' '.$t['p_lastname'].'</td><td>'.$student.'</td><td>
			<a href="'.$config['page_url'].'?page=archive&option=1&id='.$t['id'].'"><input type="submit" class="btn btn-info btn-xs" value="Pokaż"></a> ';
			if ($user['group_id'] == 3) {
				echo '<a href="'.$config['page_url'].'?page=archive&option=3&id='.$t['id'].'"><input type="submit" class="btn btn-danger btn-xs" value="Usuń"></a> ';
			}
			echo '</td></tr>';
		}
	}
	echo '</tbody></table>';

	echo '<h2>Prace uczelniane</h2><hr class="style-one"></hr>';

	if ($user['group_id'] == 2) {
		$topics = $db->select_multi('SELECT t.*, s.studies_id, s.name as su_name, u.name as s_name, u.lastname as s_lastname FROM subjects_topics t JOIN subjects s ON s.id = t.subject_id LEFT JOIN users u ON u.id = t.teacher_id = '.$user['id'].' WHERE t.archivised = 1');
	} else {
		$topics = $db->select_multi('SELECT t.*, s.studies_id, s.name as su_name, u.name as s_name, u.lastname as s_lastname FROM subjects_topics t JOIN subjects s ON s.id = t.subject_id LEFT JOIN users u ON u.id = t.student_id WHERE t.archivised = 1');
	}//end if
	echo '<table class="table table-bordered table-hover">
	    <thead>
	      <tr>
	        <th width="20px">ID</th>
	        <th>Temat Pracy</th>
			<th>Przedmiot</th>
			<th>Student</th>
			<th>Kierunek</th>
			<th width="110px"></th>
	      </tr>
	    </thead>
	    <tbody>';
	if($topics) {
		foreach($topics as $t) {
			$studies = $db->select_single('SELECT * FROM studies WHERE id = '.$t['studies_id']);
			$student = '';
			if($t['student_id'] > 0) $student = $t['s_name'].' '.$t['s_lastname'];
			echo '<tr><td>'.$t['id'].'</td><td>'.$t['title'].'</td><td>'.$t['su_name'].'</td><td>'.$student.'</td><Td>'.$studies['name'].' ('.$config['studies_types'][$studies['type']].')<td>
			<a href="'.$config['page_url'].'?page=archive&option=2&id='.$t['id'].'"><input type="submit" class="btn btn-info btn-xs" value="Pokaż"></a> ';
			if ($user['group_id'] == 3) {
				echo '<a href="'.$config['page_url'].'?page=archive&option=4&id='.$t['id'].'"><input type="submit" class="btn btn-danger btn-xs" value="Usuń"></a> ';
			}
			echo '</td></tr>';
		}
	}
	echo '</tbody></table>';
} else if ($option === 3) {
	$db->query("DELETE FROM theses_topics WHERE id = ".(int) $_GET['id']);
	header('Location: ?page=archive');
} else if ($option === 4) {
	$db->query("DELETE FROM subjects_topics WHERE id = ".(int) $_GET['id']);
	header('Location: ?page=archive');
} else if ($option === 1) {
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
} else if ($option === 2) {
	echo '<h2>Tematy prac - Pokaż Pracę</h2><hr class="style-one"></hr>';
		$var = $db->select_single('SELECT * FROM subjects_topics WHERE id = '.(int)$_GET['id']);
?>
		<table class="table table-bordered table-hover">
			<tbody>
				<tr><td width="20%"><strong>Nazwa:</strong></td><td><?php echo $var['title'];?></td></tr>
				<tr><td><strong>Opis:</strong></td><td><?php echo $var['description'];?></td></tr>
				<tr><td><strong>Data dodania:</strong></td><td><?php echo $var['add_date'];?></td></tr>
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
			$edits = $db->select_multi('SELECT * FROM theses_edits WHERE these_id = '.(int)$_GET['id']);
			if($edits) {
				foreach($edits as $e) {
					echo '<tr><td>'.date('H:i, d.m.Y', $e['edit_date']).'</td><td>'.$e['text'].'</td><td><a href="'.$config['page_url'].'uploads/'.$e['these_id'].'-'.$e['edit_date'].'.'.$e['file_type'].'"><input type="submit" class="btn btn-primary btn-xs" value="Pobierz Załącznik"/></a> <a href="'.$config['page_url'].'?page=works&option=15&id='.$e['id'].'"><input type="submit" class="btn btn-info btn-xs" value="Komentarz"/></a></td></tr>';
				}
			}
?>				
			</tbody>
		</table>
<?php
}//end if
