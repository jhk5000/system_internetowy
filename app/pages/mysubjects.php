<h3>Moje Przedmioty</h3><hr class="style-one"></hr>
<?php
$subjects = $db->select_multi('SELECT * FROM subjects s WHERE s.studies_id = '.$user['subject_ids']);

echo '<table class="table table-bordered table-hover">
		<thead>
		  <tr>
			<th>Nazwa Przedmiotu</th>
			<th>Prowadzący</th>
		  </tr>
		</thead>
		<tbody>';
if (empty($subjects) === false) {
	foreach ($subjects as $s) {
		$t = $db->select_single('SELECT * FROM users WHERE id = (SELECT teacher_id FROM studies_groups_teachers WHERE subject_id = '.$s['id'].' AND group_id IN (SELECT group_id FROM studies_groups_students WHERE student_id = '.$user['id'].'))');
		echo '<tr><td>'.$s['name'].'</td><td>'.$t['name'].' '.$t['lastname'].'</td></tr>';
	}//end foreach
} else {
	echo '<tr><td colspan="6">Poczekaj na przydział do grupy.</td></tr>';
}
echo '</tbody></table>';
