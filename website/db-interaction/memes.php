<?php
 
session_start();
 
include_once "../inc/constants.inc.php";
include_once "../inc/class.meme.inc.php";
$meme = new Meme();

if(!empty($_POST['action'])){
	switch($_POST['action'])
    {
        case 'insert_meme':
            echo $meme->insert_meme();
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