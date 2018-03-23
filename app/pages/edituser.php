<?php
	$var = $db->select_single('SELECT * FROM users WHERE id = '.(int)$_GET['id']);
	if($var) {
		echo '<h2>Edycja Użytkownika</h2><hr class="style-one"></hr>';
		if($user['group_id'] == 3) {
			if(!empty($_POST['send'])) {
				if(!empty($_POST['name']) && !empty($_POST['login']) && !empty($_POST['lastname'])) {
					$check = $db->select_single("SELECT * FROM users WHERE UPPER(login) = UPPER('".$_POST['login']."') AND id != ".$var['id']);
					if(!$check) {
						$db->query("UPDATE users SET subject_ids = '".$_POST['depart']."', name = '".ucfirst($_POST['name'])."', lastname = '".ucfirst($_POST['lastname'])."', login = '".$_POST['login']."' WHERE id = ".$var['id']);
						alert(1, 'Dane zostały zeedytowane.');
					} else {
						alert(2, 'Użytkownik z podanym loginem już istnieje!');
					}
				} else {
					alert(2, 'Wypełnij wszystkie pola!');
				}
			}
			$dep = $db->select_multi('SELECT * FROM departments ORDER BY name ASC');
?>
		<form action="" method="POST">
			<input type="hidden" name="send" value="1"/>
			<div class="row">
				<div class="col-lg-12">
					<div class="form-group">
						<label><?php echo $var['name'];?> <?php echo $var['lastname'];?></label>
					</div>
					<div class="form-group">
						<label>Imię:</label>
						<input type="text" class="form-control" name="name" value="<?php echo $var['name'];?>"/>
					</div>
					<div class="form-group">
						<label>Nazwisko:</label>
						<input type="text" class="form-control" name="lastname" value="<?php echo $var['lastname'];?>"/>
					</div>
					<div class="form-group">
						<label>Login:</label>
						<input type="text" class="form-control" name="login" value="<?php echo $var['login'];?>"/>
					</div>
				<?php if ($var['group_id'] == 2) { ?>
					<div class="form-group">
						<label>Wydział:</label>
						<select name="depart" class="form-control">
					<?php foreach ($dep as $d) {
						if ($d['id'] == $var['subject_ids']) {
							echo '<option value="'.$d['id'].'" selected>'.$d['name'].'</option>';
						} else {
							echo '<option value="'.$d['id'].'">'.$d['name'].'</option>';
						}
					}?>
						</select>
					</div>
				<?php } ?>
					<input type="submit" class="btn btn-primary btn-ls" value="Edytuj"/>
				<?php
					if ($var['group_id'] == 2) {
						echo '<a href="?page=promoters"><button type="button" class="btn btn-primary">Powrót</button></a>';
					} else {
						echo '<a href="?page=students"><button type="button" class="btn btn-primary">Powrót</button></a>';
					}
				?>
				</div>
			</div>
		</form>
<?php
		}
	}
?>