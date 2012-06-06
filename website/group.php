<?php
	include_once "common/base.php";
	$pageTitle = "Group name";
	include_once "common/header.php";
	include_once "inc/class.group.inc.php";
	$group = new Group();
	
	if(isset($_GET['id'])):
		$g = $group->get_group_by_id($_GET['id']);	
?>

<div id="container">
	<br /><br />
	
<?php 	if(isset($_SESSION['uid'])&& $_SESSION['uid']!=0): 

	echo "<h2>".$g['groupname']."</h2>";
	echo "<p>".$g['description']."</p>";
	
	$users = $group->get_users_in_group($g['id']);
	
	//If group is public, don't show members
	echo "<h2>Members</h2>";
	for($i=0; $i<count($users); $i++){
		$u = $users[$i];	
		echo $u['first_name']." ".$u['last_name'];
	}
?>












<?php
	else:
		echo "<br /><center><h2>Please log in to view your page</h2></center>";
	endif;
	else:
		echo "<br /><center><h2>No group specified.</h2></center>";
	endif;
	include_once "common/footer.php"
?>