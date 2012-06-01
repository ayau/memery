<?php
 
    session_start();
     
    $_SESSION['logged'] = false; 
	$_SESSION['uid'] = 0; 
	$_SESSION['username'] = ''; 
	$_SESSION['cookie'] = ""; 
	$_SESSION['remember'] = false; 
 
?>
 
<meta http-equiv="refresh" content="0;URL=login.php">