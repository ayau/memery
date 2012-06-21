<?php
 
session_start();
 
include_once "../inc/constants.inc.php";
include_once "../inc/class.comment.inc.php";
$comment = new Comment();

if(!empty($_POST['action'])){
	switch($_POST['action'])
    {
        case 'create_comment':
            echo $comment->create_comment();
            break;
        case 'vote':
            echo $comment->vote_meme();
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