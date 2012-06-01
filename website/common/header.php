<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<?php
//Setting default sessions to prevent error messages
function session_defaults() { 
	$_SESSION['logged'] = false; 
	$_SESSION['uid'] = 0; 
	$_SESSION['username'] = ''; 
	$_SESSION['cookie'] = ""; 
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
	
		<div id='mini_account'>			
<?php if($_SESSION['uid']==0): ?>
			<a href="/signup.php"><div class='header_button btn'>Sign up</div></a>
			<a href="/login.php"><div class='header_button btn'>Log in</div></a>			
<?php else: ?>
			<p>Logged in as: <a href="/user.php?uid=<?php echo $_SESSION['uid']?>"><?php echo $_SESSION['username'];?></a></p>
			<a href="/logout.php"><div class='header_button btn'>Log out</div></a>
<?php endif;?>
		</div>
		
			
			<div id='top_nav'>		<!--Should be part of the header -->
				<a href="/memgr.php"><div class='nav_button'>memgr</div></a>
				<a href="/meme_gallery.php"><div class='nav_button'>meme gallery</div></a>
				<a href="/meme_generator.php"><div class='nav_button'>meme generator</div></a>
				<a href="/stateofbase.php"><div class='nav_button'>stats</div></a>
				<a href="/meme_upload.php"><div class='nav_button'>upload</div></a>
				<a href='/help.php'><div class='nav_button'>help</div></a>
			</div>
		</div>