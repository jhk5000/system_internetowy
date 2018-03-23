<h2>Przypomnij hasło</h2><hr class="style-one"></hr>
<?php
	if(!empty($_POST['send'])) {
		if(!empty($_POST['name'])) {
			$check = $db->select_single("SELECT * FROM users WHERE UPPER(login) = UPPER('".$_POST['name']."') OR UPPER(mail) = UPPER('".$_POST['name']."');");
			if($check) {
				alert(1, 'Link resetujący hasło został wysłany na adres email!');
			} else {
				alert(2, 'Brak konta o podanych danych!');
			}
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
				<label>Podaj email lub login</label>
				<input name="name" value="" class="form-control">
			</div>
			<input type="submit" class="btn btn-primary btn-ls" value="Przypomnij Hasło"/>
		</div>
	</div>
</form>