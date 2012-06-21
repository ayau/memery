<?php
 
session_start();
 
include_once "../inc/constants.inc.php";
include_once "../inc/class.comments.inc.php";
$comment = new Comment();

if(!empty($_POST['action'])){
	switch($_POST['action'])
    {
        case 'create_comment':
            echo $group->create_comment();
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