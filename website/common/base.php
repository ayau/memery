<?php
    // Set the error reporting level
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
 
    // Start a PHP session
    session_start();
 
    // Include site constants
    include_once "inc/constants.inc.php";
 	
    
    function db_connect($database){
    	if($database == 'meme_cloud'){
    		$db_name = DB_MEME_CLOUD;
    	}else if ($database == 'memery'){
    		$db_name = DB_MEMERY;
    	}
    
    	// Create a database object
    	try {
        	$dsn = "mysql:host=".DB_HOST.";dbname=".$db_name.";port=".DB_PORT;
        	$db = new PDO($dsn, DB_USER, DB_PASS);
        	return $db;
    	} catch (PDOException $e) {
        	echo 'Connection failed: ' . $e->getMessage();
        	exit;
    	}
    }
?>