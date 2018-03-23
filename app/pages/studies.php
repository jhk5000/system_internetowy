<h3>Moje Kierunki</h3><hr class="style-one"></hr>
<?php

if (empty($_GET{'option'}) === true) {
	$studies = $db->select_multi('SELECT s.*, d.name as d_name FROM studies s JOIN departments d ON d.id = s.deparment_id WHERE s.id IN (SELECT studies_id FROM subjects WHERE id IN (SELECT subject_id FROM studies_groups_teachers WHERE teacher_id = '.$user['id'].'))');
	echo '<table class="table table-bordered table-hover">
		<thead><tr>
			<th>Nazwa Kierunku</th>
			<th>Wydział</th>
			<th>Stopień</th>
			<th width="57px"></th>
		  </tr></thead><tbody>';
	if ($studies) {
		foreach ($studies as $s) {
			echo '<tr><td>'.$s['name'].'</td><td>'.$s['d_name'].'</td><td>'.$config['studies_types'][$s['type']].'</td>
			<td>
				<a href="'.$config['page_url'].'?page=studies&option=2&id='.$s['id'].'"><input type="submit" class="btn btn-info btn-xs" value="Pokaż"/></a>
			</td>
			</tr>';
		}
	}
	echo '</tbody></table>';
	if ($user['group_id'] > 2) {
		echo '<a href="'.$config['page_url'].'?page=studies&option=1"><input type="submit" class="btn btn-primary" value="Dodaj Kierunek"/></a>';
	}
} else if($_GET['option'] == 2) {
	$var = $db->select_single('SELECT * FROM studies WHERE id = '.$_GET['id']);
	echo '<h2>'.$var['name'].'</h2>';
	echo '<table class="table table-bordered table-hover">
		<thead><tr>
			<th>Nazwa Przedmiotu</th>
		  </tr></thead><tbody>';
	$subjects = $db->select_multi('SELECT * FROM subjects WHERE id IN (SELECT subject_id FROM studies_groups_teachers WHERE teacher_id = '.$user['id'].')');
	if ($subjects) {
		foreach ($subjects as $s)
			echo '<tr><td>'.$s['name'].'<td></tr>';
	} else {
		echo '<tr><td colspan="6">Brak przedmiotów</td></tr>';
	}
	echo '</tbody></table>';
}//end if
