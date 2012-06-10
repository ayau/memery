<?php
 
/**
 * Handles interactions realted to user information
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
 
 class Userinfo
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
            $dsn = "mysql:host=".DB_HOST.";dbname=".DB_MEMERY.";port=".DB_PORT;
            $this->_db = new PDO($dsn, DB_USER, DB_PASS);
        }
    }
    
    function get_name($id){
    	 $sql = "SELECT first_name, last_name FROM users
			WHERE id =:uid LIMIT 1"; 
		
		if($stmt = $this->_db->prepare($sql)) {
            $stmt->bindParam(":uid", $id, PDO::PARAM_INT);            
            $stmt->execute();
            if ($row = $stmt->fetch())
            	return $row['first_name']." ".$row['last_name'];
            else
            	return "";
 		}
    }
    
}