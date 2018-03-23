<!DOCTYPE html>
<html lang="pl">
	<head>
		<meta charset="utf-8">
		<title><?php echo $title;?></title>
		<link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="screen">
		<link href="css/basic.css" rel="stylesheet" type="text/css" media="screen">
		<script async="" src="js/jquery.min.js?v=1.72"></script>
		<script async="" src="js/app.js"></script>
	</head>
	<body>
		<div id="box"></div>
		<div id="page">
			<div id="top_menu">
				<div class="text">
					<div class="left"><a href="<?php echo $config['page_url'];?>"><?php echo $config['logo_title'];?></a></div>
					<div class="right">
					<?php if(empty($user)) { ?>
						<span class="top pointer" onClick="app.openWindow(1);">Zaloguj</span> | <span class="top pointer" onClick="app.openWindow(2);">Rejestracja</span>
					<?php } else { ?>
						<?php echo $user['name'].' '.$user['lastname'];?> <small>(<?php echo $config['account_types'][$user['group_id']];?>)</small> | <a href="<?php echo $config['page_url'];?>?page=myaccount"><span class="top pointer">Moje Konto</a></span> | <span class="top pointer" onClick="app.logout();">Wyloguj</span>
					<?php } ?>
					</div>
				</div>
			</div>
			<div id="content">
				<div id="menu">
					<div class="panel panel-primary">
						<div class="list-group">
							<?php $active = ''; $active2 = ''; ?>
							<?php if($page == 'main') $active = 'active';?>
							<?php if($page == 'faq') $active2 = 'active';?>
							<a href="<?php echo $config['page_url'];?>" class="list-group-item <?php echo $active;?>">Strona Główna</a>
							<?php if(!empty($user)) getUserMenu($group); ?>
							<a href="<?php echo $config['page_url'];?>?page=faq" class="list-group-item <?php echo $active2;?>">Regulamin i FAQ</a>
						</div>
					</div>
				</div>
				<div id="view">