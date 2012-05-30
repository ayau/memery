<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<?php
//Setting default sessions to prevent error messages
function session_defaults() { 
	$_SESSION['logged'] = false; 
	$_SESSION['uid'] = 0; 
	$_SESSION['username'] = ''; 
	$_SESSION['cookie'] = 0; 
	$_SESSION['remember'] = false; 
}

if (!isset($_SESSION['uid']) ) { 
	session_defaults(); 
}

?>



<head>
	<title><?php echo $pageTitle ?></title>
	<link rel="stylesheet" href="/style.css" type="text/css" />
	<script type="text/javascript" src="js/jquery.min.js"></script>
</head>

<body>
	<div id="page-wrap">
		<div id="header">
			<center><a href="/"><img src="/images/memerylogopenn.png" class="logo" alt="" /></a></center>
			<div id='top_nav'>		<!--Should be part of the header -->
				<a href="/memgr.php"><div class='nav_button'>memgr</div></a>
				<a href="/meme_gallery.php"><div class='nav_button'>meme gallery</div></a>
				<a href="/meme_generator.php"><div class='nav_button'>meme generator</div></a>
				<a href="/stateofbase.php"><div class='nav_button'>stats</div></a>
				<a href="/custom_meme.php"><div class='nav_button'>upload</div></a>
				<a href='/help.php'><div class='nav_button'>help</div></a>
			</div>
		</div>