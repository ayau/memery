<?php
 
session_start();
 
include_once "../inc/constants.inc.php";
include_once "../inc/class.user.inc.php";
$user = new User();

if(!empty($_POST['action'])){
	switch($_POST['action'])
    {
        case 'account_create':
            echo $user->account_create();
            break;
        case 'login':
        	if($_POST['rememberme']=='Yes'){
        		$remember = true;
        	}else{
        		$remember = false;
        	}        	
            if($user->_checkLogin($_POST['username'], $_POST['password'], $remember)){
            	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=/">';
		    }else{
		        echo '<META HTTP-EQUIV="Refresh" Content="0; URL=/login.php?status=failed">';	
		    }
            break;
		default:
            header("Location: ");
        break;
    }
}else{
	header("Location: ");
    exit;
}
?>