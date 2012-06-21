<?php
 
/**
 * Handles interactions related to comments
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
 
 class Comment
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
    
    //Creates and store a newly created comment. Returns user id for the comment.
    function create_comment(){
    	
    	$user_id = $_SESSION['uid'];
    	$comment = $_POST['comment'];
    	$meme_id = $_SESSION['meme_id'];
		$votes = 0;
		$reply_to = -1;
    	
    	//GMT time
        date_default_timezone_set( 'Europe/London');
		$datetime = date("Y-m-d H:i:s", mktime());
		
		//Insert user into database
        $sql = "INSERT INTO comments(user, comment, meme, reply_to, votes, created_at)
        		VALUES(:userid, :comment, :meme, :reply_to, :votes, :time)";
          try
          {
          	$stmt = $this->_db->prepare($sql);
            $stmt->bindParam(":userid", $user_id, PDO::PARAM_INT);
            $stmt->bindParam(":comment", $comment, PDO::PARAM_STR);
            $stmt->bindParam(":meme", $meme_id, PDO::PARAM_INT);
            $stmt->bindParam(":reply_to", $reply_to, PDO::PARAM_INT);
			$stmt->bindParam(":votes", $votes, PDO::PARAM_INT);
            $stmt->bindParam(":time", $datetime, PDO::PARAM_STR);
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
    
    
    //Inserts a new vote for a comment into database and a new relation between user and comment in vote_relations. Returns if successful.
    function vote_comment($id, $vote){
		//retrieve votes for comment
		$sql = "SELECT * FROM comments 
			WHERE comment_id =:id LIMIT 1"; 
		
		if($stmt = $this->_db->prepare($sql)) {
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);            
            $stmt->execute();
	        $row = $stmt->fetch();
 		}
		//new vote total
		$votes = $row['votes'] + $vote;
		//update the table with the new vote total
		$sql = "UPDATE comments
			 SET votes =:votes
			 WHERE comment_id=:id LIMIT 1"; 
			
			 try{
                $stmt = $this->_db->prepare($sql);
                $stmt->bindParam(":votes", $votes, PDO::PARAM_INT);
                $stmt->bindParam(":id", $id, PDO::PARAM_INT);
                $stmt->execute();
                $stmt->closeCursor();
                
                return true;
            }
            catch(PDOException $e)
            {
                return false;
            }
		
		// Add the user and comment to the vote_relations table so can't vote again
    	//GMT time
        date_default_timezone_set( 'Europe/London');
		$datetime = date("Y-m-d H:i:s", mktime());
		
		$user_id = $_SESSION['uid'];
		//Insert user into database
        $sql = "INSERT INTO vote_relations(item_id, user_id, created_at)
        		VALUES(:cid, :uid, :time)";
          try
          {
          	$stmt = $this->_db->prepare($sql);
            $stmt->bindParam(":cid", $id, PDO::PARAM_INT);
            $stmt->bindParam(":uid", $user_id, PDO::PARAM_INT);
            $stmt->bindParam(":time", $datetime, PDO::PARAM_STR);
            $stmt->execute();
            $stmt->closeCursor();
            return true;
        }
        catch(PDOException $e)
		{
			return false;
		}
    }
    
    
      //Retrieves a comment by its id
    function get_comment_by_id($id){
		
        $sql = "SELECT * FROM comments 
			WHERE id =:id LIMIT 1"; 
		
		if($stmt = $this->_db->prepare($sql)) {
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);            
            $stmt->execute();
            $row = $stmt->fetch();
	        return $row;
 		}
    }
    
    //retrieve user information by uid
    function get_user($id){
    	 $sql = "SELECT * FROM users
			WHERE id =:id LIMIT 1"; 
		
		if($stmt = $this->_db->prepare($sql)) {
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);            
            $stmt->execute();
			$row = $stmt->fetch();
			return $row;
 		}
    }
    
    //returns true if the user has voted for the comment
    function get_voted($id){
    	$uid = $_SESSION['uid'];
    	
    	 $sql = "SELECT * FROM vote_relations
			WHERE item_id =:id AND user_id=:uid LIMIT 1"; 
		
		if($stmt = $this->_db->prepare($sql)) {
            $stmt->bindParam(":id", $id, PDO::PARAM_INT); 
            $stmt->bindParam(":uid", $uid, PDO::PARAM_INT);            
            $stmt->execute();
			if($row = $stmt->fetch())
				return true;
			else
				return false;
 		}
    }
    
    //Retrieve all comments by user
    function get_comments($id){
    	 $sql = "SELECT comment_id FROM comments
			WHERE user_id =:uid"; 
		
		if($stmt = $this->_db->prepare($sql)) {
            $stmt->bindParam(":uid", $id, PDO::PARAM_INT);            
            $stmt->execute();
            $comments = array();
            while ($row = $stmt->fetch()){
            	$comment = $this->get_comment_by_id($row['comment_id']);
            	array_push($comments, $comment);	
            }
            return $comments;
 		}
    }
	
	//Retrieve all votes on comments by user
    function get_votes($id){
    	 $sql = "SELECT item_id FROM vote_relations
			WHERE user_id =:uid"; 
		
		if($stmt = $this->_db->prepare($sql)) {
            $stmt->bindParam(":uid", $id, PDO::PARAM_INT);            
            $stmt->execute();
            $comments = array();
            while ($row = $stmt->fetch()){
            	$comment = $this->get_comment_by_id($row['item_id']);
            	array_push($comments, $comment);			
            }	
            return $comments;
 		}
    }
   
    
    
}