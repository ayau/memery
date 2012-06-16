<?php
 
/**
 * Handles interactions related to search results
 *
 * PHP version 5
 *
 * @author Alex Yau
 * @author Josh Bolgar
 * @author Markus Beissinger
 * @copyright 
 * @license  
 *
 */
 
 class Search
{
    
    /**
     * The database object
     *
     * @var object
     */
    private $_db;
 
    /**
     * Checks for a database object and creates one if none is found
     *
     * @param object $db
     * @return void
     */
    public function __construct($db=NULL)
    {
        if(is_object($db))
        {
            $this->_db = $db;
        }
        else
        {
        	try{
            	$dsn = "mysql:host=".DB_HOST.";dbname=".DB_MEMERY.";port=".DB_PORT;
            	$this->_db = new PDO($dsn, DB_USER, DB_PASS);
            }catch (PDOException $e){
            	echo '<br /><br /><center><h3>Connection failed</h3></center>';
            	exit();
            }
        }
    }
    
    function search_users($query){
    	
    	
    	$query1 = urldecode(str_replace('+','|',urlencode($query)));
		$query1 = str_replace("+","|",$query1);
		$query = strtolower($query);
		$query1 = strtolower($query1);
		
		$count = (strlen($query1)-substr_count($query1,"|"))/(substr_count($query1,"|")+1);//average word length
		$sql = "SELECT *, (LOWER(first_name) REGEXP '$query1') + (LOWER(last_name) REGEXP '$query1') +0.5*('$query' REGEXP LOWER(last_name))+0.5*('$query' REGEXP LOWER(first_name))+ 0.5*(1-ABS(length(concat(first_name,last_name))-'$count')/length(concat(first_name,last_name))) AS rel
  				FROM users
  				WHERE (LOWER(last_name) REGEXP '$query1' OR LOWER(first_name) REGEXP '$query1' OR '$query' REGEXP LOWER(last_name) OR '$query' REGEXP LOWER(first_name))
  				ORDER BY rel DESC, first_name ASC";//LIMIT!!!!!!!!!!!!!!!!!
  			if($stmt = $this->_db->prepare($sql))
   				{	
    			$stmt->execute();
    			$users = array();
    			while($row = $stmt->fetch()){
    				array_push($users, $row);
				}
    			$stmt->closeCursor();
    			return $users;
   			}
  				else
   				{
   				 echo "\t\t\t\t<li> Something went wrong. ", $db->errorInfo, "</li>\n";
   				} 	
    }
    
    
    
    function search_groups($query){
    	
    	
    	$query1 = urldecode(str_replace('+','|',urlencode($query)));
		$query1 = str_replace("+","|",$query1);
		$query = strtolower($query);
		$query1 = strtolower($query1);
		
		$count = (strlen($query1)-substr_count($query1,"|"))/(substr_count($query1,"|")+1);//average word length
		$sql = "SELECT *, (LOWER(groupname) REGEXP '$query1') +0.5*('$query' REGEXP LOWER(groupname))+0.5*(1-ABS(length(groupname)-'$count')/length(groupname)) AS rel
  				FROM groups
  				WHERE (LOWER(groupname) REGEXP '$query1' OR '$query' REGEXP LOWER(groupname))
  				ORDER BY rel DESC, groupname ASC";//LIMIT!!!!!!!!!!!!!!!!!
  			if($stmt = $this->_db->prepare($sql))
   				{	
    			$stmt->execute();
    			$groups = array();
    			while($row = $stmt->fetch()){
    				array_push($groups, $row);
				}
				
    			$stmt->closeCursor();
    			return $groups;
   			}
  				else
   				{
   				 echo "\t\t\t\t<li> Something went wrong. ", $db->errorInfo, "</li>\n";
   				} 	
    }
    
    
}
    