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
		default:
            header("Location: ");
        break;
    }
}else{
	header("Location: ");
    exit;
}
?>