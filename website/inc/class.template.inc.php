<?php
 
/**
 * Handles interactions related to meme templates
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
 
 class Template
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
    
    //Creates and store template to database after user uploads. Returns the template id.
    function create_template($name, $src, $created_by){
    	//GMT time
        date_default_timezone_set( 'Europe/London');
		$datetime = date("Y-m-d H:i:s", mktime());
		
		//Insert user into database
        $sql = "INSERT INTO templates(name, src, created_by, created_at)
        		VALUES(:name, :src, :uid, :time)";
          try
          {
          	$stmt = $this->_db->prepare($sql);
            $stmt->bindParam(":name", $name, PDO::PARAM_STR);
            $stmt->bindParam(":src", $src, PDO::PARAM_STR);
            $stmt->bindParam(":uid", $created_by, PDO::PARAM_INT);
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
    
    
    //Retrieves a list of template uploaded by a user
    function get_templates_by_UID($uid){
		
        $sql = "SELECT * FROM templates 
			WHERE created_by =:uid ORDER BY id DESC"; 
		
		if($stmt = $this->_db->prepare($sql)) {
            $stmt->bindParam(":uid", $uid, PDO::PARAM_INT);            
            $stmt->execute();
            $templates = array(); 
			while($row = $stmt->fetch()){
				array_push($templates, $row);				
			}
			return $templates;
 		}
    }
    
    //Retrieves the next 20 template uploaded by a user
    function get_templates_for_preview($uid, $position=0){
		
        $sql = "SELECT * FROM templates 
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
    
     //Retrieves a list of template based on the template id
    function get_template_by_id($id){
		
        $sql = "SELECT * FROM templates 
			WHERE id =:id LIMIT 1"; 
		
		if($stmt = $this->_db->prepare($sql)) {
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);            
            $stmt->execute();
            $row = $stmt->fetch();
	        return $row;
 		}
    }
    
    function update_template_info(){
    	$file_name = $_POST['file_name'];
    	$name = $_POST['name'];
    	$x0 = $_POST['x0'];
    	$y0 = $_POST['y0'];
    	$x1 = $_POST['x1'];
    	$y1 = $_POST['y1'];   	
    	
    	$sql = "UPDATE templates
			 SET name =:name, crop_x0 =:x0, crop_y0 =:y0, crop_x1 =:x1, crop_y1 =:y1 
			 WHERE  src=:fname AND created_by=:uid LIMIT 1"; 
			
			 try{
                $stmt = $this->_db->prepare($sql);
                $stmt->bindParam(":name", $name, PDO::PARAM_STR);
                $stmt->bindParam(":x0", $x0, PDO::PARAM_INT);
                $stmt->bindParam(":y0", $y0, PDO::PARAM_INT);
                $stmt->bindParam(":x1", $x1, PDO::PARAM_INT);
                $stmt->bindParam(":y1", $y1, PDO::PARAM_INT);
                $stmt->bindParam(":fname", $file_name, PDO::PARAM_STR);
                $stmt->bindParam(":uid", $_SESSION['uid'], PDO::PARAM_INT);
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