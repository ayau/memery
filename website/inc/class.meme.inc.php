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
            try{
            	$dsn = "mysql:host=".DB_HOST.";dbname=".DB_MEMERY.";port=".DB_PORT;
            	$this->_db = new PDO($dsn, DB_USER, DB_PASS);
            }catch (PDOException $e){
            	echo '<br /><br /><center><h3>Connection failed</h3></center>';
            	exit();
            }
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
    //Used on meme gen
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
   
    //insert keyword, meme_id pairs into database.
    //Used on meme gen
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
   
   //Given a meme_id, returns all group ids
   //Used on index.
   function get_group_tags($id){
   		$sql = "SELECT group_id FROM group_tags 
			WHERE meme_id =:id"; 
		
		if($stmt = $this->_db->prepare($sql)) {
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);            
            $stmt->execute();
            $groups = array();
            while($row = $stmt->fetch()){
            	array_push($groups, $row['group_id']);
            }
	        return $groups;
 		}
   }
   
      //Given a meme_id, returns all keywords
      //Used on index.
   function get_keyword_tags($id){
   		$sql = "SELECT keyword FROM keyword_tags 
			WHERE meme_id =:id"; 
		
		if($stmt = $this->_db->prepare($sql)) {
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);            
            $stmt->execute();
            $keywords = array();
            while($row = $stmt->fetch()){
            	array_push($keywords, $row['keyword']);
            }
	        return $keywords;
 		}
   }
   
   //Retrieves x memes created. Order by date, desc. Gets all meme_id less than $mid
   //Used on index and user.php
    function get_memes_created($uid, $limit, $mid=null){
		
    	if ($mid==null)
    		$cutoff = "";
    	else
    		$cutoff = "AND id<".$mid;
    	
        $sql = "SELECT * FROM memes 
			WHERE created_by =:uid ".$cutoff." ORDER BY id DESC
			LIMIT :lim"; 
		
		if($stmt = $this->_db->prepare($sql)) {
            $stmt->bindParam(":uid", $uid, PDO::PARAM_INT);
            $stmt->bindParam(":lim", $limit, PDO::PARAM_INT);         
            $stmt->execute();
            $memes = array(); 
			while($row = $stmt->fetch()){
				array_push($memes, $row);				
			}
			return $memes;
 		}
    }
    
   //Retrieves x popular memes. Order by popularity, desc. Gets all meme_id less than $mid
   //Used on index and user.php
    function get_memes_popular($limit, $pop=null){
		
    	if ($pop==null)
    		$cutoff = "";
    	else
    		$cutoff = "WHERE views<".$pop;		//personalized?
    	
        $sql = "SELECT *, views AS pop FROM memes 
			".$cutoff." ORDER BY pop DESC
			LIMIT :lim"; 
		
		if($stmt = $this->_db->prepare($sql)) {
            $stmt->bindParam(":lim", $limit, PDO::PARAM_INT);         
            $stmt->execute();
            $memes = array(); 
			while($row = $stmt->fetch()){
				array_push($memes, $row);				
			}
			return $memes;
 		}
    }
    
    function get_popularity($mid){
    	 $sql = "SELECT views AS pop FROM memes
    	 WHERE id =:mid LIMIT 1"; 
		
		if($stmt = $this->_db->prepare($sql)) {
            $stmt->bindParam(":mid", $mid, PDO::PARAM_INT);         
            $stmt->execute();
			$row = $stmt->fetch();
			return $row['pop'];
 		}
    }
    
    function get_views($mid){
    	$sql = "SELECT views FROM memes 
    		WHERE id =:mid
			LIMIT 1"; 
		
		if($stmt = $this->_db->prepare($sql)) {
            $stmt->bindParam(":mid", $mid, PDO::PARAM_INT);         
            $stmt->execute();
			$row = $stmt->fetch();
			return $row['views'];
 		}
    }
    
    //Increment views by one
    function inc_views(){
    	$mid = $_POST['mid'];
    	$sql = "UPDATE memes
			 SET views = views+1 
			 WHERE id =:mid
			 LIMIT 1"; 
			
			 try{
                $stmt = $this->_db->prepare($sql);
                $stmt->bindParam(":mid", $mid, PDO::PARAM_INT);
                $stmt->execute();
                $stmt->closeCursor();
                
                return true;
            }
            catch(PDOException $e)
            {
                return false;
            }
    }
    
}