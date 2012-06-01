<?php
 
session_start();
 
include_once "../inc/constants.inc.php";
include_once "../inc/class.template.inc.php";
$template = new Template();

if(!empty($_POST['action'])){
	switch($_POST['action'])
    {
        case 'update_template_info':
            echo $template->update_template_info();
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