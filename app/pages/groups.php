<?php

if (empty($_GET['option']) === true) {
	$dep = 0;
	$var = $db->select_multi('SELECT * FROM studies');
	if (empty($_POST['depar']) === false) {
		$dep = (int) $_POST['depar'];
		$var = $db->select_multi('SELECT * FROM studies WHERE deparment_id = '.$dep);
	}//end if

	$filter = $db->select_multi('SELECT * FROM departments');
?>
<h2>Grupy</h2><hr class="style-one"></hr>
<form action="" method="POST">
	<div style="display: inline-block; width: 100px">Wydzial:</div>
	<div style="display: inline-block; width: 300px">
	<select name="depar" class="form-control">
		<option value="0">Wybierz...</option>
<?php
foreach ($filter as $v) {
	if ($dep == $v['id']) {
		echo '<option value="'.$v['id'].'" selected>'.$v['name'].'</option>';
	} else {
		echo '<option value="'.$v['id'].'">'.$v['name'].'</option>';
	}//end if
}//end foreach
?>
	</select>
	</div>
	<div style="display: inline-block: width: 50px; float: right;">
	<input type="submit" value="Filtruj" class="btn btn-primary"/>
	</div>
	</br></br>
</form>
<table class="table table-bordered table-hover">
    <thead>
      <tr>
        <th>Nazwa Kierunku</th>
        <th>Liczba grup</th>
		<th width="60px"></th>
      </tr>
    </thead>
    <tbody>
<?php
	if (empty($var) === false) {
		foreach ($var as $val) {
			$count = $db->select_single('SELECT COUNT(*) as c FROM studies_groups WHERE studies_id = '.$val['id']);
			echo '<tr><td>'.$val['name'].'</td><td>'.$count['c'].'</td><td><a href="?page=groups&option=show&id='.$val['id'].'"><input type="submit" class="btn btn-primary btn-xs" value="Pokaż"></a></td></tr>';
		}
	}
?>
    </tbody>
</table>
<?php
} else if ($_GET['option'] === 'teachers') {
	$group = $db->select_single('SELECT * FROM studies_groups WHERE id = '.(int) $_GET['id']);

	if (empty($_POST) === false) {
		$db->query('DELETE FROM studies_groups_teachers WHERE group_id = '.(int) $_GET['id']);
		foreach ($_POST as $key => $val) {
			if ($val != 0) {
				$subject = explode('_', $key)[1];
				$db->query("INSERT INTO `studies_groups_teachers` (`teacher_id`, `group_id`, `subject_id`) VALUES ('".$val."', '".(int) $_GET['id']."', '".$subject."');");
			}//end if
		}//end foreach
		alert(1, 'Dane zostały zapisane!');
	}//end if
	echo '<h2>Grupa: '.$group['name'].'</h2><hr class="style-one"></hr>';
	$subjects = $db->select_multi('SELECT * FROM subjects WHERE studies_id = '.(int) $_GET['studies']);
	$studies  = $db->select_single('SELECT * FROM studies WHERE id = '.(int) $_GET['studies']);
	$teachers = $db->select_multi('SELECT * FROM users WHERE group_id = 2 AND subject_ids = '.$studies['deparment_id']);
	echo '<form action="" method="POST">';
	echo '<table class="table table-bordered table-hover">
    <thead>
      <tr>
        <th>Nazwa przedmiotu</th>
        <th>Wykładowca</th>
		<th width="235px"></th>
      </tr>
    </thead>
    <tbody>';
	foreach ($subjects as $s) {
		$teacher = $db->select_single('SELECT * FROM studies_groups_teachers t JOIN users u ON u.id = t.teacher_id WHERE t.group_id = '.$group['id'].' AND t.subject_id = '.$s['id']);
		echo '<tr><td>'.$s['name'].'</td><td>';
		echo '<select class="form-control" name="subject_'.$s['id'].'"><option value="0">-</option>';
		foreach ($teachers as $v) {
			if ($v['id'] == $teacher['id']) {
				echo '<option value="'.$v['id'].'" selected>'.$v['name'].' '.$v['lastname'].'</option>';
			} else {
				echo '<option value="'.$v['id'].'">'.$v['name'].' '.$v['lastname'].'</option>';
			}
		}
		echo '</select>';
		echo '</td></tr>';
	}//end foreach
	echo '</tbody></table>';
	echo '<input type="submit" class="btn btn-primary" value="Zapisz"/></form>';
?>

<?php
} else if ($_GET['option'] === 'show') {
	echo '<h2>Grupy</h2><hr class="style-one"></hr>';
	$var = $db->select_multi('SELECT * FROM studies_groups WHERE studies_id = '.(int) $_GET['id']);
?>
<table class="table table-bordered table-hover">
    <thead>
      <tr>
        <th>Nazwa Grupy</th>
        <th>Liczba studentów</th>
		<th width="265px"></th>
      </tr>
    </thead>
    <tbody>
<?php
	if (empty($var) === false) {
		foreach ($var as $val) {
			$students = $db->select_single('SELECT COUNT(*) as c FROM studies_groups_students WHERE group_id = '.$val['id']);
			echo '<tr><td>'.$val['name'].'</td><td>'.$students['c'].'</td><td><a href="?page=groups&option=details&id='.$val['id'].'"><input type="submit" class="btn btn-primary btn-xs" value="Studenci"></a> 
			<a href="?page=groups&option=teachers&id='.$val['id'].'&studies='.$_GET['id'].'"><input type="submit" class="btn btn-warning btn-xs" value="Wykładowcy"></a> 
			<a href="?page=groups&option=edit&id='.$val['id'].'"><input type="submit" class="btn btn-success btn-xs" value="Edytuj"></a> 
			<a href="?page=groups&option=delete&id='.$val['id'].'&show='.$_GET['id'].'"><input type="submit" class="btn btn-danger btn-xs" value="Usuń"></a>
			</td></tr>';
		}
	} else {
		echo '<tr><td colspan="6">Brak grup</td></tr>';
	}//end if
?>
    </tbody>
</table>
<?php
	echo '<a href="?page=groups&option=add&id='.$_GET['id'].'"><input type="submit" class="btn btn-warning" value="Dodaj"/></a>';
} else if ($_GET['option'] === 'delete') {
	$db->query('DELETE FROM studies_groups WHERE id = '.(int) $_GET['id']);
	$db->query('DELETE FROM studies_groups_students WHERE group_id = '.(int) $_GET['id']);
	$db->query('DELETE FROM studies_groups_teachers WHERE group_id = '.(int) $_GET['id']);
	header('Location: ?page=groups&show='.$_GET['show']);
} else if ($_GET['option'] === 'edit' || $_GET['option'] === 'add') {
	if ($_GET['option'] === 'edit') {
		echo '<h2>Edycja grupy</h2><hr class="style-one"></hr>';
		$val = $db->select_single('SELECT * FROM studies_groups WHERE id = '.(int) $_GET['id']);
	} else {
		echo '<h2>Dodaj grupę</h2><hr class="style-one"></hr>';
		$val = array('name' => '', 'teacher_id' => 0);
	}//end if

	if (empty($_POST['send']) === false) {
		if (empty($_POST['name']) === false) {
			if ($_GET['option'] === 'edit') {
				$db->query("UPDATE studies_groups SET name = '".$_POST['name']."', teacher_id = '".$_POST['teacher']."' WHERE id = ".(int) $_GET['id']);
				$val['name']       = $_POST['name'];
				$val['teacher_id'] = (int) $_POST['teacher'];
			} else {
				$db->query("INSERT INTO `studies_groups` (`studies_id`, `name`, `add_date`) VALUES ('".$_GET['id']."', '".$_POST['name']."', '".TIME_NOW."');");
			}//end if
			alert(1, 'Dane zostały zapisane!');
		} else {
			alert(2, 'Podaj nazwę grupy!');
		}//end if
	}//end if
?>
<form action="" method="POST">
	<input type="hidden" name="send" value="1"/>
	<div class="row">
		<div class="col-lg-12">
			<div class="form-group">
				<label>Nazwa grupy:</label>
				<input type="text" class="form-control" name="name" value="<?php echo $val['name'];?>"/>
			</div>
			<input type="submit" class="btn btn-primary btn-ls" value="Wyślij"/>
		</div>
	</div>
</form>
<?php
} else if ($_GET['option'] === 'details') {
	if (empty($_POST['send']) === false) {
		$db->query("INSERT INTO `studies_groups_students` (`student_id`, `group_id`) VALUES ('".$_POST['student']."', '".$_GET['id']."');");
	}//end if

	if (empty($_GET['remove']) === false) {
		$db->query('DELETE FROM studies_groups_students WHERE student_id = '.$_GET['remove'].' AND group_id = '.$_GET['id']);
		header('Location: ?page=groups&option=details&id='.$_GET['id']);
	}//end if

	$group = $db->select_single('SELECT * FROM studies_groups WHERE id = '.(int) $_GET['id']);
	echo '<h2>Grupa: '.$group['name'].'</h2><hr class="style-one"></hr>';

	$students = $db->select_multi('SELECT * FROM studies_groups_students s JOIN users u ON u.id = s.student_id WHERE s.group_id = '.$group['id']);
?>
<table class="table table-bordered table-hover">
    <thead>
      <tr>
        <th>Imię</th>
        <th>Nazwisko</th>
		<th width="60px"></th>
      </tr>
    </thead>
    <tbody>
<?php
if (empty($students) === false) {
	foreach ($students as $s) {
		echo '<tr><td>'.$s['name'].'</td><td>'.$s['lastname'].'</td><td>
		<a href="?page=groups&option=details&id='.$_GET['id'].'&remove='.$s['student_id'].'"><input type="submit" class="btn btn-danger btn-xs" value="Usuń"/></a></td></tr>';
	}
} else {
	echo '<tr><td colspan="6">Brak listy</td></tr>';
}//end if

$students = $db->select_multi('SELECT * FROM users WHERE group_id = 1 AND subject_ids = '.$group['studies_id'].' AND id NOT IN (SELECT s.student_id FROM studies_groups_students s JOIN users u ON u.id = s.student_id WHERE s.group_id = '.$group['id'].')');
if (empty($students) === false) {
?>
    </tbody>
</table>

<form action="" method="POST">
	<input type="hidden" name="send" value="1"/>
	<div class="row">
		<div class="col-lg-12">
			<div class="form-group">
				<label>Student:</label>
				<select name="student" class="form-control">
			<?php
				foreach ($students as $t) {
					echo '<option value="'.$t['id'].'">'.$t['name'].' '.$t['lastname'].'</option>';
				}//end foreach
			?>
				</select>
			</div>
			<input type="submit" class="btn btn-primary btn-ls" value="Dodaj"/>
		</div>
	</div>
</form>
<?php
}//end if
}//end if
