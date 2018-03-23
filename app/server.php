<?php
	session_start();
	error_reporting(E_ALL);
	ini_set('display_errors',1);

	require('config.php');
	require('db.php');
	require('functions.php');

	if(!empty($_POST['task'])) {
		$time_now = time();
		$db = new DB($config['db']['host'], $config['db']['username'], $config['db']['password'], $config['db']['database']);
		
		if(!empty($_SESSION['user'])) {
			if(empty($_SESSION['token'])) {
				session_destroy();
				$_SESSION = array();
				die();
			}
			$user = $db->select_single('SELECT * FROM users WHERE id = '.(int)$_SESSION['user']);
			if(!$user || $user['token'] != $_SESSION['token']) {
				session_destroy();
				$_SESSION = array();
				die();
			}
		}

		switch($_POST['task']) {
			case 1:
				if(!empty($_POST['name'])) {
					if($_POST['pass1'] != $_POST['pass2']) {
						echo 'Podane hasła nie są identyczne!';
					} elseif (!filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL)) {
						echo 'Podano błędny adres email!';
					} else {
						$check = $db->select_single("SELECT id FROM users WHERE (UPPER(login) = UPPER('".$_POST['login']."')) or (UPPER(mail) = UPPER('".$_POST['mail']."'));");
						if($check) {
							echo 'Podany login lub email jest już zajęty!';
						} else {
							echo '1';
							$token = generateToken();
							$db->query("INSERT INTO `users` (`login`, `password`, `name`, `lastname`, `mail`, `register_date`, `group_id`, `token`, `subject_ids`, `indeks`) VALUES ('".$_POST['login']."', '".sha1($_POST['pass1'])."', '".$_POST['name']."', '".$_POST['lastname']."', '".$_POST['mail']."', '".$time_now."', '".$_POST['type']."', '".$token."', '".$_POST['subjects']."', '".$_POST['indeks']."');");
							$id = $db->select_single("SELECT id FROM users WHERE UPPER(login) = UPPER('".$_POST['login']."') AND password = '".sha1($_POST['pass1'])."';");
							$_SESSION['user'] = $id['id'];
							$_SESSION['token'] = $token;
						}
					}
				} else {
					echo 'Uzupełnij wszystkie dane!';
				}
				break;
			case 2:
				if(!empty($_POST['login']) && !empty($_POST['password'])) {
					$check = $db->select_single("SELECT id FROM users WHERE (UPPER(login) = UPPER('".$_POST['login']."') OR UPPER(mail) = UPPER('".$_POST['login']."')) AND password = '".sha1($_POST['password'])."';");
					if($check) {
						echo 1;
						$_SESSION['user']  = $check['id'];
						$_SESSION['token'] = generateToken();
						$db->query("UPDATE users SET token = '".$_SESSION['token']."' WHERE id = ".$check['id']);
					} else {
						echo 'Podano nieprawidłowe dane!';
					}
				} else {
					echo 'Uzupełnij wszystkie dane!';
				}
				break;
			case 3:
				if ($_POST['window'] == 1) {
					echo getLoginWindow();
				} else if($_POST['window'] == 3) {
					$subjects = $db->select_multi('SELECT * FROM studies');
					echo getSubjectWindow($subjects);
				} else {
					$subjects    = $db->select_multi('SELECT * FROM studies');
					$departments = $db->select_multi('SELECT * FROM departments');
					echo getRegisterWindow($subjects, $departments);
				}
				break;
			case 4:
				session_destroy();
				$_SESSION = array();
				break;
			default:
				break;
		}
	}
?>