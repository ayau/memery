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
    	$meme_id = $_GET['mid'];
		//$votes = 0;
		//$reply_to = -1;
    	
    	//GMT time
        date_default_timezone_set( 'Europe/London');
		$datetime = date("Y-m-d H:i:s", mktime());
		
		//Insert user into database
        $sql = "INSERT INTO comments(user_id, text, meme_id, created_at)
        		VALUES(:userid, :comment, :meme, :time)"; //reply_to item?
          try
          {
          	$stmt = $this->_db->prepare($sql);
            $stmt->bindParam(":userid", $user_id, PDO::PARAM_INT);
            $stmt->bindParam(":comment", $comment, PDO::PARAM_STR);
            $stmt->bindParam(":meme", $meme_id, PDO::PARAM_INT);
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
    
    
    //Inserts a new vote for a comment into database. Returns if successful.
    function vote_comment(){
		$meme_id = $_POST['mid'];
		$user_id = $_SESSION['uid'];
		$vote = $_POST['vote'];
		//check if vote exists for comment
		$sql = "SELECT * FROM memes_vote 
			WHERE meme_id =:mid AND user_id =:uid LIMIT 1"; 
		
		if($stmt = $this->_db->prepare($sql)) {
            $stmt->bindParam(":mid", $meme_id, PDO::PARAM_INT);
			$stmt->bindParam(":uid", $user_id, PDO::PARAM_INT);               
            $stmt->execute();
			//if it does exist, update, if not, insert
            if($row = $stmt->fetch()){
				$sql = "UPDATE memes_vote
					SET vote =:vote
					WHERE  meme_id=:mid AND user_id=:uid LIMIT 1";
				try{
                $stmt = $this->_db->prepare($sql);
                $stmt->bindParam(":mid", $meme_id, PDO::PARAM_INT);
                $stmt->bindParam(":vote", $vote, PDO::PARAM_INT);
                $stmt->bindParam(":uid", $user_id, PDO::PARAM_INT);
                $stmt->execute();
                $stmt->closeCursor();
                return true;
				}
				catch(PDOException $e){
                return false;
				}
			}
			else{
				$sql = "INSERT INTO memes_vote(meme_id, user_id, vote)
					VALUES(:mid, :uid, :vote)";
				try{
                $stmt = $this->_db->prepare($sql);
                $stmt->bindParam(":mid", $meme_id, PDO::PARAM_INT);
                $stmt->bindParam(":vote", $vote, PDO::PARAM_INT);
                $stmt->bindParam(":uid", $user_id, PDO::PARAM_INT);
                $stmt->execute();
                $stmt->closeCursor();
                return true;
				}
				catch(PDOException $e){
                return false;
				}
			}
 		}
		else{
			return false;
		}
		
		
		
    }
	
	//Inserts a new vote for a meme into database. Returns if successful.
    function vote_meme(){
		$meme_id = $_POST['mid'];
		$user_id = $_SESSION['uid'];
		$vote = $_POST['vote'];
		//check if vote exists for meme
		$sql = "SELECT * FROM memes_vote 
			WHERE meme_id =:mid AND user_id =:uid LIMIT 1"; 
		
		if($stmt = $this->_db->prepare($sql)) {
            $stmt->bindParam(":mid", $meme_id, PDO::PARAM_INT);
			$stmt->bindParam(":uid", $user_id, PDO::PARAM_INT);               
            $stmt->execute();
			//if it does exist, update, if not, insert
            if($row = $stmt->fetch()){
				$sql = "UPDATE memes_vote
					SET vote =:vote
					WHERE  meme_id=:mid AND user_id=:uid LIMIT 1";
				try{
                $stmt = $this->_db->prepare($sql);
                $stmt->bindParam(":mid", $meme_id, PDO::PARAM_INT);
                $stmt->bindParam(":vote", $vote, PDO::PARAM_INT);
                $stmt->bindParam(":uid", $user_id, PDO::PARAM_INT);
                $stmt->execute();
                $stmt->closeCursor();
                return true;
				}
				catch(PDOException $e){
                return false;
				}
			}
			else{
				$sql = "INSERT INTO memes_vote(meme_id, user_id, vote)
					VALUES(:mid, :uid, :vote)";
				try{
                $stmt = $this->_db->prepare($sql);
                $stmt->bindParam(":mid", $meme_id, PDO::PARAM_INT);
                $stmt->bindParam(":vote", $vote, PDO::PARAM_INT);
                $stmt->bindParam(":uid", $user_id, PDO::PARAM_INT);
                $stmt->execute();
                $stmt->closeCursor();
                return true;
				}
				catch(PDOException $e){
                return false;
				}
			}
 		}
		else{
			return false;
		}
		
    }

	function get_meme_vote_count($id){
		$sql = "SELECT vote FROM memes_vote
			WHERE meme_id =:mid"; 
		
		if($stmt = $this->_db->prepare($sql)) {
            $stmt->bindParam(":mid", $id, PDO::PARAM_INT);            
            $stmt->execute();
            $votes = array();
            while ($row = $stmt->fetch()){
				$vote = intval($row['vote']);
            	array_push($votes, $vote);	
            }
            return array_sum($votes);
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