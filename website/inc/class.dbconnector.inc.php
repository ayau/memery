<?php
 
/**
 * Handles simple database calls within the app
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

 class db_connector
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
            $dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";port=".DB_PORT;
            $this->_db = new PDO($dsn, DB_USER, DB_PASS);
        }
    }
    
	public function getMemesCrawled(){
		//get total number of memes crawled
		$sql = "SELECT
					COUNT(*) as count
				FROM crawl_data";
		if($stmt = $this->_db->prepare($sql))
		{
			$stmt->execute();
			$row = $stmt->fetch();
			return $row['count'];
			}
		else
		{
			echo "Something went wrong when connecting to database.";
		}
	}
	
	public function getMemesProcessed(){
		//get total number of memes crawled
		$sql = "SELECT
					COUNT(*) as count
				FROM crawl_data
				WHERE processed = 1";
		if($stmt = $this->_db->prepare($sql))
		{
			$stmt->execute();
			$row = $stmt->fetch();
			return $row['count'];
			}
		else
		{
			echo "Something went wrong when connecting to database.";
		}
	}        
    
} 