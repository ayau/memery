<?php
	include_once "common/base.php";
	$pageTitle = "Group name";
	include_once "common/header.php";
	include_once "inc/class.search.inc.php";
	include_once "inc/class.userinfo.inc.php";
	include_once "inc/class.group.inc.php";
	$search = new Search();
	$userinfo = new Userinfo();
    $group = new Group();
	
?>

<div id="container">
	<br /><br /><br />

<?php
if(isset($_GET['search'])):
	$item = urldecode($_GET['search']);
	echo "<h2>Search results for: <u>".$item."</u></h2>";
	echo "<br />";
	
	echo "<Table id='search_table'>";
	$users = $search -> search_users($_GET['search']);
	$groups =  $search -> search_groups($_GET['search']);
	
	for ($i = 0; $i<count($users); $i++){
		$u = $users[$i]; 
		echo "<tr>";
    	echo "<td style='max-width:300px; padding-right:50px'><a href='user.php?uid=".$u['id']."'>".$u['first_name']." ".$u['last_name']."</a></td>";
    	$percent = $u['rel']/3.25 * 100;
    	echo "<td> name match: ".ceil($percent)."%</td>";
    	echo "<tr />";
	}
	
	for ($i = 0; $i<count($groups); $i++){
		$g = $groups[$i]; 
		echo "<tr>";
    		
    	$following = true;
    	if($g['privacy']==2){
    		$following = $group -> get_following($g['id']);
    	}
    	if($following){
    			
    		$name = $userinfo -> get_name($g['created_by']); 				
    				
    		echo "<td style='max-width:500px; padding-right:50px'><a href='group.php?id=".$g['id']."'>".$g['groupname']." <p class='hint'>(Owned by ".$name.")</p></a></td>";
    		$percent = $g['rel']/2 * 100;
    		echo "<td> name match: ".ceil($percent)."%</td>";
    		echo "<tr />";
		}
	}
	
	
	echo "<tr><td style='padding-top:30px'>end of search</td></tr>";
	echo "</Table>";
endif;
?>


<div>
<?php	
	include_once "common/footer.php"
?>