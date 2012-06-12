<?php
 
/**
 * Handles interactions related to memes
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
 
 class Meme
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
    
    //Creates and store meme to database after user generates it. Returns the meme id.
    function insert_meme(){
    	//GMT time
        date_default_timezone_set( 'Europe/London');
		$datetime = date("Y-m-d H:i:s", mktime());
		
		$title = $_POST['title'];
		$temp_id = $_POST['temp_id'];
		$text_top = $_POST['text_top'];
		$text_bottom = $_POST['text_bottom'];
		$created_by = $_SESSION['uid'];
		$privacy = $_POST['privacy'];
		
		//Insert user into database
        $sql = "INSERT INTO memes(title, template_id, created_by, created_at, text_top, text_bot, privacy)
        		VALUES(:title, :temp_id, :uid, :time, :ttop, :tbot, :privacy)";
          try
          {
          	$stmt = $this->_db->prepare($sql);
            $stmt->bindParam(":title", $title, PDO::PARAM_STR);
            $stmt->bindParam(":temp_id", $temp_id, PDO::PARAM_INT);
            $stmt->bindParam(":uid", $created_by, PDO::PARAM_INT);
            $stmt->bindParam(":time", $datetime, PDO::PARAM_STR);
            $stmt->bindParam(":ttop", $text_top, PDO::PARAM_STR);
            $stmt->bindParam(":tbot", $text_bottom, PDO::PARAM_STR);
            $stmt->bindParam(":privacy", $privacy, PDO::PARAM_INT);
            $stmt->execute();
            $stmt->closeCursor();
            
            //retrieving user_id
            $id=$this->_db->lastInsertId();
            
            return $id;
        }
        catch(PDOException $e)
		{
			return $e->getMessage();
		}
    }
    
    
    //Retrieves a list of memes created by a user
    function get_memes_by_UID($uid){
		
        $sql = "SELECT * FROM memes
			WHERE created_by =:uid ORDER BY id DESC"; 
		
		if($stmt = $this->_db->prepare($sql)) {
            $stmt->bindParam(":uid", $uid, PDO::PARAM_INT);            
            $stmt->execute();
            $memes = array(); 
			while($row = $stmt->fetch()){
				array_push($memes, $row);				
			}
			return $memes;
 		}
    }
    
    //Retrieves the next 20 memes created by a user
    function get_memes_for_preview($uid, $position=0){
		
        $sql = "SELECT * FROM memes 
			WHERE created_by =:uid ORDER BY id DESC
			LIMIT :pos, 20"; 
		
		if($stmt = $this->_db->prepare($sql)) {
            $stmt->bindParam(":uid", $uid, PDO::PARAM_INT);
            $stmt->bindParam(":pos", $position, PDO::PARAM_INT);           
            $stmt->execute();
            $templates = array(); 
			while($row = $stmt->fetch()){
				array_push($templates, $row);				
			}
			return $templates;
 		}
    }
    
     //Retrieves a list of memes based on the meme id
    function get_meme_by_id($id){
		
        $sql = "SELECT * FROM memes 
			WHERE id =:id LIMIT 1"; 
		
		if($stmt = $this->_db->prepare($sql)) {
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);            
            $stmt->execute();
            $row = $stmt->fetch();
	        return $row;
 		}
    }
    
    //insert group_id, meme_id pairs into database
   function insert_group_tags(){
   		$meme_id = $_POST['meme_id'];
   		$groups = $_POST['groups'];
   		
   		$groups = explode(",", $groups[0]);
   		
   		for($i = 0; $i<count($groups); $i++){
   			$group_id =  $groups[$i];
   			
   			//Insert each pair into the database
	        $sql = "INSERT INTO group_tags(meme_id, group_id)
	        		VALUES(:meme_id, :group_id)";
	          try
	          {
	          	$stmt = $this->_db->prepare($sql);
	            $stmt->bindParam(":meme_id", $meme_id, PDO::PARAM_INT);
	            $stmt->bindParam(":group_id", $group_id, PDO::PARAM_INT);
	            $stmt->execute();
	            $stmt->closeCursor();
	            
	        }
	        catch(PDOException $e)
			{
				return $e->getMessage();
			}
   		}
   }
   
    //insert keyword, meme_id pairs into database
   function insert_keyword_tags(){
   		$meme_id = $_POST['meme_id'];
   		$keywords = $_POST['keywords'];
   		
   		$keywords = explode(",", $keywords[0]);
   		
   		for($i = 0; $i<count($keywords); $i++){
   			$keyword =  $keywords[$i];
   			
   			//Insert each pair into the database
	        $sql = "INSERT INTO keyword_tags(meme_id, keyword)
	        		VALUES(:meme_id, :keyword)";
	          try
	          {
	          	$stmt = $this->_db->prepare($sql);
	            $stmt->bindParam(":meme_id", $meme_id, PDO::PARAM_INT);
	            $stmt->bindParam(":keyword", $keyword, PDO::PARAM_STR);
	            $stmt->execute();
	            $stmt->closeCursor();
	            
	        }
	        catch(PDOException $e)
			{
				return $e->getMessage();
			}
   		}
   }
    
    
}