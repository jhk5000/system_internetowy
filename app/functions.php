<?php
	function alert($type, $text) {
		if($type == 1) {
			echo '<div class="alert alert-success"><strong>Sukces:</strong> '.$text.'</div>';
		} else {
			echo '<div class="alert alert-warning"><strong>Błąd:</strong> '.$text.'</div>';
		}
	}

	function getLoginWindow() {
		global $config;
		return '<div id="loginBox" class="modalWindow">
				<div id="blackX" onClick="app.closeModal();"></div>
				<h2>Logowanie</h2><hr class="style-one"></hr>
				<b>Login:</b> <input onkeypress="app.check_key(event);" type="text" class="form-control" id="login_name" value=""/>
				<b>Hasło:</b> <input onkeypress="app.check_key(event);" type="password" class="form-control" id="login_password" value=""/></br>
				<center><input class="btn btn-primary btn-ls" type="submit" onClick="app.login();" value="Zaloguj"/> 
				<a href="'.$config['page_url'].'?page=lostpassword"><input class="btn btn-info btn-ls" type="submit" value="Przypominj Hasło"/></a></center>
				</div>';
	}
	
	function getSubjectWindow($subjects) {
		$output = '<div id="subjectBox" class="modalWindow">
				<div id="blackX" onClick="app.closeModal();"></div>
				<h2>Wybierz Kierunki</h2><hr class="style-one"></hr>
				<div class="row">
				<div class="col-lg-6">';
				if($subjects)
					foreach($subjects as $s)
						$output .= '<input type="checkbox" onChange="app.onSubjectChange('.$s['id'].');" id="subject_'.$s['id'].'" value="0"> '.$s['name'].'<br/>';
				$output .= '</div></div><br/>
				<center><input class="btn btn-primary btn-lg" type="submit" onClick="app.closeModal();" value="Zamknij"/></center>
				</div>';
		return $output;
	}
	
	function getRegisterWindow($subjects, $departments) {
		global $config;
		$output = '<div id="registerBox" class="modalWindow">
				<div id="blackX" onClick="app.closeModal();"></div>
				<h2>Rejestracja</h2><hr class="style-one"></hr>
				<div class="row">
				<div class="col-lg-6">
					<b>Login:</b> <input onkeypress="app.check_key(event);" type="text" class="form-control" id="register_login" value=""/>
					<b>Hasło:</b> <input onkeypress="app.check_key(event);" type="password" class="form-control" id="register_pass1" value=""/>
					<b>Powtórz hasło:</b> <input onkeypress="app.check_key(event);" type="password" class="form-control" id="register_pass2" value=""/>
					<b>Email:</b> <input onkeypress="app.check_key(event);" type="text" class="form-control" id="register_mail" value=""/>
				</div>
				<div class="col-lg-6">
				<b>Imię:</b> <input onkeypress="app.check_key(event);" type="text" class="form-control" id="register_name" value=""/>
				<b>Nazwisko:</b> <input onkeypress="app.check_key(event);" type="text" class="form-control" id="register_lastname" value=""/>
				<b>Typ konta:</b> <select id="user_type" onChange="app.userTypeChange();" class="form-control">
				<option value="1">Student</option>
				<option value="2">Dydaktyk (wymaga potwierdzenia)</option>
				</select>
				<div id="student_subject" style="display:block;">
				<b>Kierunek:</b> <select id="subject" class="form-control">';
				if($subjects) {
					foreach($subjects as $s) {
						$output .= '<option value="'.$s['id'].'">'.$s['name'].' ('.$config['studies_types'][$s['type']].')</option>';
					}
				}
				$output .= '</select>
				<b>Numer indeksu:</b> <input onkeypress="app.check_key(event);" type="text" class="form-control" id="indeks" value=""/>
				</div>
				<div id="promoter_subject" style="display:none;">
				<b>Wydział:</b> <select id="p_subject" class="form-control">';
				if($departments) {
					foreach($departments as $s) {
						$output .= '<option value="'.$s['id'].'">'.$s['name'].'</option>';
					}
				}
				$output .= '</select></div>
				</div></div><br/>
				<center><input class="btn btn-primary btn-lg" type="submit" onClick="app.register();" value="Zarejestruj"/></center>
				</div>';
		return $output;
	}
	
	function checkPageAccess($page, $group, $access, $url) {
		if($access[$page])
			foreach($access[$page] as $a)
				if($group == $a)
					return;
		header('Location: '.$url);
	}
	
	function generateToken() {
		$newcode = NULL;
		$acceptedChars = '123456789zxcvbnmasdfghjklqwertyuiop';
		for($i=0; $i < 50; $i++) {
			$cnum[$i] = $acceptedChars{mt_rand(0, 33)};
			$newcode .= $cnum[$i];
		}
		return $newcode;
	}
	
	function getUserMenu($group) {
		global $config;
		global $page;
		if($group == 1) {
			if($page == 'mysubjects')
				echo '<a href="'.$config['page_url'].'?page=mysubjects" class="list-group-item active">Moje Przedmioty</a>';
			else
				echo '<a href="'.$config['page_url'].'?page=mysubjects" class="list-group-item">Moje Przedmioty</a>';
			if($page == 'mytopic')
				echo '<a href="'.$config['page_url'].'?page=mytopic" class="list-group-item active">Praca Dyplomowa</a>';
			else
				echo '<a href="'.$config['page_url'].'?page=mytopic" class="list-group-item">Praca Dyplomowa</a>';
			if($page == 'myworks')
				echo '<a href="'.$config['page_url'].'?page=myworks" class="list-group-item active">Moje prace uczelniane</a>';
			else
				echo '<a href="'.$config['page_url'].'?page=myworks" class="list-group-item">Moje prace uczelniane</a>';
			if($page == 'students')
				echo '<a href="'.$config['page_url'].'?page=students" class="list-group-item active">Moja Grupa</a>';
			else
				echo '<a href="'.$config['page_url'].'?page=students" class="list-group-item">Moja Grupa</a>';
		} elseif($group == 2) {
			if($page == 'students')
				echo '<a href="'.$config['page_url'].'?page=students" class="list-group-item active">Studenci</a>';
			else
				echo '<a href="'.$config['page_url'].'?page=students" class="list-group-item">Studenci</a>';
			if($page == 'archive')
				echo '<a href="'.$config['page_url'].'?page=archive" class="list-group-item active">Archiwum</a>';
			else
				echo '<a href="'.$config['page_url'].'?page=archive" class="list-group-item">Archiwum</a>';
			if($page == 'works')
				echo '<a href="'.$config['page_url'].'?page=works" class="list-group-item active">Moje Tematy</a>';
			else
				echo '<a href="'.$config['page_url'].'?page=works" class="list-group-item">Moje Tematy</a>';
			if($page == 'topics')
				echo '<a href="'.$config['page_url'].'?page=topics" class="list-group-item active">Prace Dyplomowe</a>';
			else
				echo '<a href="'.$config['page_url'].'?page=topics" class="list-group-item">Prace Dyplomowe</a>';
			if($page == 'studies')
				echo '<a href="'.$config['page_url'].'?page=studies" class="list-group-item active">Moje kierunki</a>';
			else
				echo '<a href="'.$config['page_url'].'?page=studies" class="list-group-item">Moje kierunki</a>';
		} else {
			if($page == 'topics')
				echo '<a href="'.$config['page_url'].'?page=topics" class="list-group-item active">Tematy</a>';
			else
				echo '<a href="'.$config['page_url'].'?page=topics" class="list-group-item">Tematy</a>';
			if($page == 'archive')
				echo '<a href="'.$config['page_url'].'?page=archive" class="list-group-item active">Archiwum</a>';
			else
				echo '<a href="'.$config['page_url'].'?page=archive" class="list-group-item">Archiwum</a>';
			if($page == 'promoters')
				echo '<a href="'.$config['page_url'].'?page=promoters" class="list-group-item active">Dydaktycy</a>';
			else
				echo '<a href="'.$config['page_url'].'?page=promoters" class="list-group-item">Dydaktycy</a>';
			if($page == 'students')
				echo '<a href="'.$config['page_url'].'?page=students" class="list-group-item active">Studenci</a>';
			else
				echo '<a href="'.$config['page_url'].'?page=students" class="list-group-item">Studenci</a>';
			if($page == 'groups')
				echo '<a href="'.$config['page_url'].'?page=groups" class="list-group-item active">Grupy</a>';
			else
				echo '<a href="'.$config['page_url'].'?page=groups" class="list-group-item">Grupy</a>';
			if($page == 'departments')
				echo '<a href="'.$config['page_url'].'?page=departments" class="list-group-item active">Wydziały</a>';
			else
				echo '<a href="'.$config['page_url'].'?page=departments" class="list-group-item">Wydziały</a>';
		}
		if($page == 'messages')
			echo '<a href="'.$config['page_url'].'?page=messages" class="list-group-item active">Wiadomości</a>';
		else
			echo '<a href="'.$config['page_url'].'?page=messages" class="list-group-item">Wiadomości</a>';
	}


function getName(array $array) {
	$string = '';
	if (empty($array['name']) === false && $array['name'] !== null) {
		$string .= $array['name'];
	}//end if

	if (empty($array['lastname']) === false && $array['lastname'] !== null) {
		$string .= ' '.$array['lastname'];
	}//end if

	return trim($string);
}