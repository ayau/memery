<?php
	include_once "common/base.php";
	$pageTitle = "Group name";
	include_once "common/header.php";
	include_once "inc/class.group.inc.php";
	$group = new Group();
	
	if(isset($_GET['gid'])):
		$g = $group->get_group_by_id($_GET['gid']);	
?>

<div id="container">
	<br /><br /><br />
	
<?php 	if(isset($_SESSION['uid'])&& $_SESSION['uid']!=0): 
	
	$privacy = $g['privacy'];
	if($privacy==0){
		$privacy_text = "public";
		$following = $group->get_following($g['id']);
	}else if($privacy==1){
		$privacy_text = "private";
		$following = $group->get_following($g['id']);
	}else{
		$privacy_text = "secret";
		$following = $group->get_following($g['id']);
	}

	if($following || $privacy!=2):
	
	echo "<h2 style='display:inline; margin-right:20px;'>".$g['groupname']."</h2>";
	
	echo "<p class='hint'>(".$privacy_text.")</p>";
	
	if($g['created_by']==$_SESSION['uid']){
		echo "<div class='mid btn' type='button'>Owner</div></a>";
	}else if(!$following){
		if($privacy==0)
			echo "<div class='mid btn' type='button'>Follow group</div></a>";
		else
			echo "<div class='mid btn' type='button'>Request to join group</div></a>";
	}else{
		echo "<div class='mid btn' type='button'>Following</div></a>";
	}
	
	echo "<p>".$g['description']."</p>";
	
	if($g['privacy']!=0){
		$users = $group->get_users_in_group($g['id']);
	
		//If group is public, don't show members
		echo "<h2>Members</h2>";
		for($i=0; $i<count($users); $i++){
			$u = $users[$i];	
			echo $u['first_name']." ".$u['last_name'];
			if($u['id']==$g['created_by']){
				echo " <p class='hint'>(Owner)</p>";
			}
		}
	}
?>
	<h2>Trending</h2>
	<div class='user_panel rounded'>
	<div class='leftarrow'></div>
	<div class='visible_panel'>
		<div id='memes_slider' class='slider'>	
		
		</div>
	</div>	
	<div class='rightarrow'></div>
	</div>
	
	<h2>Recent</h2>
	<div class='user_panel rounded'>
	<div class='leftarrow'></div>
	<div class='visible_panel'>
		<div id='memes_slider' class='slider'>	
		
		</div>
	</div>	
	<div class='rightarrow'></div>
	</div>
	
	<h2>What's new</h2>
	<p>News feed and stuff</p>










<?php
	else:
		echo "<br /><center><h2>You do not have permission to view this group</h2></center>";
	endif;
	else:
		echo "<br /><center><h2>Please log in to view your page</h2></center>";
	endif;
	else:
		echo "<br /><center><h2>No group specified.</h2></center>";
	endif;
	include_once "common/footer.php"
?>