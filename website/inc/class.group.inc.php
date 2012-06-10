<?php
 
/**
 * Handles interactions related to groups
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
 
 class Group
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
    
    //Creates and store a newly created group
    function create_group(){
    	
    	$name = $_POST['name'];
    	$description = $_POST['description'];
    	$created_by = $_SESSION['uid'];
    	$privacy = $_POST['privacy'];
    	
    	//GMT time
        date_default_timezone_set( 'Europe/London');
		$datetime = date("Y-m-d H:i:s", mktime());
		
		//Insert user into database
        $sql = "INSERT INTO groups(groupname, description, privacy, created_by, created_at)
        		VALUES(:name, :desc, :privacy, :uid, :time)";
          try
          {
          	$stmt = $this->_db->prepare($sql);
            $stmt->bindParam(":name", $name, PDO::PARAM_STR);
            $stmt->bindParam(":desc", $description, PDO::PARAM_STR);
            $stmt->bindParam(":privacy", $privacy, PDO::PARAM_INT);
            $stmt->bindParam(":uid", $created_by, PDO::PARAM_INT);
            $stmt->bindParam(":time", $datetime, PDO::PARAM_STR);
            $stmt->execute();
            $stmt->closeCursor();
            
            //retrieving user_id
            $id=$this->_db->lastInsertId();
            
            $this->follow_group($id, $created_by);
            
            return $id;
        }
        catch(PDOException $e)
		{
			return $e->getMessage();
		}
    }
    
    
    //Inserts user and group relationship into database
    function follow_group($group_id, $user_id){
    	
    	//GMT time
        date_default_timezone_set( 'Europe/London');
		$datetime = date("Y-m-d H:i:s", mktime());
		
		//Insert user into database
        $sql = "INSERT INTO group_relations(group_id, user_id, created_at)
        		VALUES(:gid, :uid, :time)";
          try
          {
          	$stmt = $this->_db->prepare($sql);
            $stmt->bindParam(":gid", $group_id, PDO::PARAM_INT);
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
    
    
    //Retrieves the next 20 template uploaded by a user
    function get_groups_for_preview($uid, $position=0){
		
        $sql = "SELECT * FROM group_relations 
			WHERE user_id =:uid ORDER BY created_at DESC
			LIMIT :pos, 40"; 
		
		if($stmt = $this->_db->prepare($sql)) {
            $stmt->bindParam(":uid", $uid, PDO::PARAM_INT);
            $stmt->bindParam(":pos", $position, PDO::PARAM_INT);           
            $stmt->execute();
            $groups = array(); 
			while($row = $stmt->fetch()){
				$group = $this->get_group_by_id($row['group_id']);
				array_push($groups, $group);				
			}
			return $groups;
 		}
    }
    
      //Retrieves a group by its id
    function get_group_by_id($id){
		
        $sql = "SELECT * FROM groups 
			WHERE id =:id LIMIT 1"; 
		
		if($stmt = $this->_db->prepare($sql)) {
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);            
            $stmt->execute();
            $row = $stmt->fetch();
	        return $row;
 		}
    }
    
    
      //Retrieves all users in a group
    function get_users_in_group($id){
		
        $sql = "SELECT user_id FROM group_relations 
			WHERE group_id =:id"; 
		
		if($stmt = $this->_db->prepare($sql)) {
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);            
            $stmt->execute();
            $users = array(); 
			while($row = $stmt->fetch()){
				$user = $this->get_user($row['user_id']);
				array_push($users, $user);				
			}
			return $users;
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
    
    //returns true if the user is in a group
    function get_following($id){
    	$uid = $_SESSION['uid'];
    	
    	 $sql = "SELECT * FROM group_relations
			WHERE group_id =:id AND user_id=:uid LIMIT 1"; 
		
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
    
    //Retrieve all groups
    function get_groups($id){
    	 $sql = "SELECT group_id FROM group_relations
			WHERE user_id =:uid"; 
		
		if($stmt = $this->_db->prepare($sql)) {
            $stmt->bindParam(":uid", $id, PDO::PARAM_INT);            
            $stmt->execute();
            $groups = array();
            while ($row = $stmt->fetch()){
            	$group = $this->get_group_by_id($row['group_id']);
            	array_push($groups, $group);	
            }
            return $groups;
 		}
    }
   
    
    
}