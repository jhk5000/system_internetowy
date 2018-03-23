<?php

$config = array();

$config['title'] = 'System intenetowy do zarządzania projektami studenckimi';

$config['db']['host']     = 'localhost';
$config['db']['username'] = 'root';
$config['db']['password'] = '';
$config['db']['database'] = 'baza';

define('TIME_NOW', time());

$config['page_url']      = 'http://localhost/system_internetowy/';
$config['logo_title']    = 'Zarządzca Projektów Studenckich';
$config['account_types'] = array(1 => 'Student', 2 => 'Dydaktyk', 3 => 'Administrator');
$config['studies_types'] = array(1 => 'Licencjat', 2 => 'Inżynier', 3 => 'Magister', 4 => 'Doktor');

$config['pages_groups'] = array(
	'topics'       => array(2,3),
	'main'         => array(0,1,2,3),
	'mytopic'      => array(1),
	'myaccount'    => array(1,2,3),
	'lostpassword' => array(0),
	'departments'  => array(3),
	'students'     => array(1,2,3),
	'promoters'    => array(3),
	'faq'          => array(0,1,2,3),
	'edituser'     => array(3),
	'messages'     => array(1,2,3),
	'myworks'      => array(1),
	'works'        => array(2,3),
	'mysubjects'   => array(1),
	'studies'      => array(2),
	'groups'       => array(3),
	'archive'      => array(2,3),
);

$config['password']['max_lenght'] = 30;
$config['password']['min_lenght'] = 6;

$config['login']['min_lenght'] = 5;
$config['login']['max_lenght'] = 20;
