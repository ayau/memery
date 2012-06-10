<?php
    include_once "../inc/constants.inc.php";
	include_once "../inc/class.search.inc.php";
	$search = new Search();
	
	$users = $search -> search_users($_GET['query']);
	
	$suggestions = array();
	$data = array();
	
	for($i = 0; $i<count($users); $i ++){
		$u = $users[$i];
		array_push($suggestions, $u['first_name']." ".$u['last_name']);
		$d = array($u['id'], 'user');
		array_push($data, $d);
	}
	
	$groups = $search -> search_groups($_GET['query']);
	
	for($i = 0; $i<count($groups); $i ++){
		$g = $groups[$i];
		array_push($suggestions, $g['groupname']);
		$d = array($g['id'], 'group');
		array_push($data, $d);
	}
	
	//$suggestions = array("first result","second result");
	$result = array('query'=>$_GET['query'],'suggestions'=>$suggestions,'data'=>$data);
	echo json_encode($result);

?>