<?php
 
session_start();
 
include_once "../inc/constants.inc.php";
include_once "../inc/class.group.inc.php";
$group = new Group();

if(!empty($_POST['action'])){
	switch($_POST['action'])
    {
        case 'create_group':
            echo $group->create_group();
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