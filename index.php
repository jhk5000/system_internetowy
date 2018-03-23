<?php

session_start();
error_reporting(E_ALL);
ini_set('display_errors',1);

require('app/config.php');
require('app/db.php');
require('app/functions.php');

$time_now = time();
$group = 0;
$db = new DB($config['db']['host'], $config['db']['username'], $config['db']['password'], $config['db']['database']);

if(!empty($_SESSION['user'])) {
	if(empty($_SESSION['token'])) {
		session_destroy();
		$_SESSION = array();
		header('Location: '.$config['page_url']);
		die;
	}
	$user = $db->select_single('SELECT * FROM users WHERE id = '.(int)$_SESSION['user']);
	if(!$user || $user['token'] != $_SESSION['token']) {
		session_destroy();
		$_SESSION = array();
		header('Location: '.$config['page_url']);
		die;
	}
	$group = $user['group_id'];
}

$title = $config['title'];
$option = 0;
if(!empty($_GET['option'])) $option = $_GET['option'];
$page = 'main';
if(!empty($_GET['page'])) $page = $_GET['page'];
checkPageAccess($page, $group, $config['pages_groups'], $config['page_url']);
require('layout/header.php');
require('app/pages/'.$page.'.php');
require('layout/footer.php');
