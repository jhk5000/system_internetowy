<?php
	if($option == 1) {
		echo '<h2>Wydziały - Dodaj</h2><hr class="style-one"></hr>';
		if(!empty($_POST['send'])) {
			if(!empty($_POST['name'])) {
				alert(1, 'Wydział został dodany!');
				$db->query("INSERT INTO `departments` (`name`) VALUES ('".$_POST['name']."');");
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
						<label>Nazwa wydziału</label>
						<input name="name" value="" class="form-control">
					</div>
					<input type="submit" class="btn btn-primary btn-ls" value="Dodaj"/>
				</div>
			</div>
		</form>
<?php
	} elseif($option == 2) {
		$var = $db->select_single('SELECT * FROM departments WHERE id = '.(int)$_GET['id']);
		echo '<h2>Kierunki - '.$var['name'].'</h2><hr class="style-one"></hr>';
		$studies = $db->select_multi('SELECT * FROM studies WHERE deparment_id = '.(int)$_GET['id']);
		echo '<table class="table table-bordered table-hover"><thead><tr><th>Nazwa Kierunku</th><th>Typ</th><th width="160px"></th></tr></thead><tbody>';
		if($studies) {
			foreach($studies as $s) {
				echo '<tr><td>'.$s['name'].'</td>
					<td>'.$config['studies_types'][$s['type']].'</td>
				<td>
				<a href="'.$config['page_url'].'?page=departments&option=10&id='.$s['id'].'"><input type="submit" class="btn btn-info btn-xs" value="Pokaż"></a>
				<a href="'.$config['page_url'].'?page=departments&option=11&id='.$s['id'].'"><input type="submit" class="btn btn-primary btn-xs" value="Edytuj"></a> 
				<a href="'.$config['page_url'].'?page=departments&option=12&id='.$s['id'].'"><input type="submit" class="btn btn-danger btn-xs" value="Usuń"></a></td>';
			}
		}
		echo '</tbody></table>';
		echo '<a href="'.$config['page_url'].'?page=departments&option=8&id='.(int)$_GET['id'].'"><input type="submit" class="btn btn-primary" value="Dodaj Kierunek"/></a>';
	} elseif($option == 11) {
		if(!empty($_POST['send'])) {
			echo '<br/>';
			if(!empty($_POST['name'])) {
				alert(1, 'Kierunek został zmieniony!');
				$db->query("UPDATE studies SET name = '".$_POST['name']."' WHERE id = ".(int) $_GET['id']);
			} else {
				alert(2, 'Uzupełnij wszystkie pola!');
			}
		}
		$var = $db->select_single('SELECT * FROM studies WHERE id = '.(int)$_GET['id']);
		echo '<h2>Edytuj kierunek - '.$var['name'].'</h2><hr class="style-one"></hr>';
?>

<form action="" method="POST">
	<input type="hidden" name="send" value="1"/>
	<div class="row">
		<div class="col-lg-12">
			<div class="form-group">
				<label>Nazwa kierunku</label>
				<input name="name" value="<?php echo $var['name'];?>" class="form-control">
			</div>
			<div class="form-group">
				<label>Typ kierunku</label>
				<select name="type" class="form-control">
				<?php
					foreach ($config['studies_types'] as $key => $val) {
						if ($var['type'] == $key) {
							echo '<option value="'.$key.'" selected>'.$val.'</option>';
						} else {
							echo '<option value="'.$key.'">'.$val.'</option>';
						}
					}
				?>
				</select>
			</div>
			<input type="submit" class="btn btn-primary btn-ls" value="Zapisz"/>
		</div>
	</div>
</form>
<?php
	} elseif($option == 8) {
		if(!empty($_POST['send'])) {
			echo '<br/>';
			if(!empty($_POST['name'])) {
				alert(1, 'Kierunek został dodany!');
				$db->query("INSERT INTO `studies` (`name`, `deparment_id`, `type`) VALUES ('".$_POST['name']."', '".(int)$_GET['id']."', '".(int) $_POST['type']."');");
			} else {
				alert(2, 'Uzupełnij wszystkie pola!');
			}
		}
		$var = $db->select_single('SELECT * FROM departments WHERE id = '.(int)$_GET['id']);
		echo '<h2>Dodaj kierunek - '.$var['name'].'</h2><hr class="style-one"></hr>';
?>
		<form action="" method="POST">
			<input type="hidden" name="send" value="1"/>
			<div class="row">
				<div class="col-lg-12">
					<div class="form-group">
						<label>Nazwa kierunku</label>
						<input name="name" value="" class="form-control">
					</div>
					<div class="form-group">
						<label>Typ kierunku</label>
						<select name="type" class="form-control">
						<?php
							foreach ($config['studies_types'] as $key => $val) {
								echo '<option value="'.$key.'">'.$val.'</option>';
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
		if(!empty($_POST['send'])) {
			echo '<br/>';
			if(!empty($_POST['name'])) {
				alert(1, 'Nazwa wydziału została zmieniona!');
				$db->query("UPDATE departments SET name = '".$_POST['name']."' WHERE id = ".(int)$_GET['id']);
			} else {
				alert(2, 'Uzupełnij wszystkie pola!');
			}
		}
		$var = $db->select_single('SELECT * FROM departments WHERE id = '.(int)$_GET['id']);
		echo '<h2>Wydziały - '.$var['name'].'</h2><hr class="style-one"></hr>';
?>
		<form action="" method="POST">
			<input type="hidden" name="send" value="1"/>
			<div class="row">
				<div class="col-lg-12">
					<div class="form-group">
						<label>Nazwa wydziału</label>
						<input name="name" value="<?php echo $var['name'];?>" class="form-control">
					</div>
					<input type="submit" class="btn btn-primary btn-ls" value="Edytuj"/>
				</div>
			</div>
		</form>
<?php
	} elseif($option == 4) {
		echo '<h2>Wydziały - Usuń Wydział</h2><hr class="style-one"></hr>';
		$db->query('DELETE FROM subjects WHERE department_id = '.(int)$_GET['id']);
		$db->query('DELETE FROM departments WHERE id = '.(int)$_GET['id']);
		alert(1, 'Wydział został usunięty!');
	} elseif($option == 12) {
		$db->query('DELETE FROM studies WHERE id = '.(int) $_GET['id']);
		alert(1, 'Kierunek został usunięty.');
	} elseif($option == 6) {
		echo '<h2>Wydziały - Usuń Przedmiot</h2><hr class="style-one"></hr>';
		$db->query('DELETE FROM subjects WHERE id = '.(int)$_GET['id']);
		alert(1, 'Przedmiot został usunięty!');
	} elseif($option == 7) {
		echo '<h2>Wydziały - Edytuj Przedmiot</h2><hr class="style-one"></hr>';
		if(!empty($_POST['send'])) {
			if(!empty($_POST['name'])) {
				alert(1, 'Dane przedmiotu zostały zmienione!');
				$db->query("UPDATE subjects SET name = '".$_POST['name']."' WHERE id = ".(int)$_GET['id']);
			} else {
				alert(2, 'Uzupełnij wszystkie pola!');
			}
		}
		$var = $db->select_single('SELECT * FROM subjects WHERE id = '.(int)$_GET['id']);
?>
		<form action="" method="POST">
			<input type="hidden" name="send" value="1"/>
			<div class="row">
				<div class="col-lg-12">
					<div class="form-group">
						<label>Nazwa przedmiotu</label>
						<input name="name" value="<?php echo $var['name'];?>" class="form-control">
					</div>
					<input type="submit" class="btn btn-primary btn-ls" value="Edytuj"/>
				</div>
			</div>
		</form>
<?php
	} elseif($option == 5 && !empty($_GET['id'])) {
		echo '<h2>Kierunku - Nowy Przedmiot</h2><hr class="style-one"></hr>';
		if(!empty($_POST['send'])) {
			if(!empty($_POST['name'])) {
				alert(1, 'Przedmiot został dodany!');
				$db->query("INSERT INTO `subjects` (`name`, `studies_id`) VALUES ('".$_POST['name']."', '".(int)$_GET['id']."');");
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
						<label>Nazwa przedmiotu</label>
						<input name="name" value="" class="form-control">
					</div>
					<input type="submit" class="btn btn-primary btn-ls" value="Dodaj"/>
				</div>
			</div>
		</form>
<?php
	} elseif($option == 10) {
		$var = $db->select_single('SELECT * FROM studies WHERE id = '.(int)$_GET['id']);
		echo '<h2>Kierunek - '.$var['name'].'</h2><hr class="style-one"></hr>';
		$subjects = $db->select_multi('SELECT * FROM subjects WHERE studies_id = '.(int)$_GET['id']);
		echo '<table class="table table-bordered table-hover"><thead><tr><th>Nazwa Przedmiotu</th><th width="110px"></th></tr></thead><tbody>';
		if($subjects) {
			foreach($subjects as $s) {
				echo '<tr><td>'.$s['name'].'</td><td>
				<a href="'.$config['page_url'].'?page=departments&option=7&id='.$s['id'].'"><input type="submit" class="btn btn-primary btn-xs" value="Edytuj"></a> 
				<a href="'.$config['page_url'].'?page=departments&option=6&id='.$s['id'].'"><input type="submit" class="btn btn-danger btn-xs" value="Usuń"></a></td>';
			}
		}
		echo '</tbody></table>';
		echo '<a href="'.$config['page_url'].'?page=departments&option=5&id='.(int)$_GET['id'].'"><input type="submit" class="btn btn-primary" value="Dodaj Przedmiot"/></a>';
	} else {
		echo '<h2>Wydziały</h2><hr class="style-one"></hr>';
		$departments = $db->select_multi('SELECT * FROM departments');
		echo '<table class="table table-bordered table-hover">
    <thead>
      <tr>
        <th>Nazwa Wydziału</th>
		<th width="157px"></th>
      </tr>
    </thead>
    <tbody>';
		if($departments) {
			foreach($departments as $d) {
				echo '<tr><td>'.$d['name'].'</td><td>
				<a href="'.$config['page_url'].'?page=departments&option=2&id='.$d['id'].'"><input type="submit" class="btn btn-info btn-xs" value="Pokaż"></a> 
				<a href="'.$config['page_url'].'?page=departments&option=3&id='.$d['id'].'"><input type="submit" class="btn btn-primary btn-xs" value="Edytuj"></a> 
				<a href="'.$config['page_url'].'?page=departments&option=4&id='.$d['id'].'"><input type="submit" class="btn btn-danger btn-xs" value="Usuń"></a></td>';
			}
		}
		echo '</tbody></table>';
		echo '<a href="'.$config['page_url'].'?page=departments&option=1"><input type="submit" class="btn btn-primary" value="Dodaj Wydział"/></a>';
	}
?>