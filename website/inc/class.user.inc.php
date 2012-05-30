<?php
 
/**
 * Handles user interactions within the app
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
 
 class User
{
    
    private $_db;
    var $db = null; // PEAR::DB pointer 
	var $failed = false; // failed login attempt 
	var $date; // current date GMT 
	var $id = 0; // the current user's id 
 
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
        
        //$this->date = $GLOBALS['date']; 
		if ($_SESSION['logged']) { 
			$this->_checkSession(); 
		} elseif ( isset($_COOKIE['mtwebLogin']) ) { 
			$this->_checkRemembered($_COOKIE['mtwebLogin']); 
		}
    }
	
	/*If email is not in use, creates account then logs the user in.
	Returns
		0 - no error
		1 - email in use
	
	*/
    function account_create(){
    	//$v = sha1(time());   email verification code
    	$email = trim($_POST['email']);
    	
    	//check if email already exist
    	$sql = "SELECT COUNT(username) AS count
                FROM users
                WHERE username=:email";
        if($stmt = $this->_db->prepare($sql)) {
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->execute();
            $row = $stmt->fetch();
            if($row['count']!=0) {
                return 1; 		//Email in use;
            }
            
            //$this->sendVerificationEmail($u, $v, $_POST['firstname']);
            $stmt->closeCursor();
        }
        
        //GMT time
        date_default_timezone_set( 'Europe/London');
		$datetime = date("Y-m-d H:i:s", mktime());
        
		//Insert user into database
        $sql = "INSERT INTO users(username, password, first_name, last_name, created_at)
        		VALUES(:email, MD5(:pass), :fName, :lName, :time)";
          try
          {
          	$stmt = $this->_db->prepare($sql);
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->bindParam(":pass", $_POST['password'], PDO::PARAM_STR);
            $stmt->bindParam(":fName", $_POST['firstname'], PDO::PARAM_STR);
            $stmt->bindParam(":lName", $_POST['lastname'], PDO::PARAM_STR);
            $stmt->bindParam(":time", $datetime, PDO::PARAM_STR);
            $stmt->execute();
            $stmt->closeCursor();
            
            //retrieving user_id
            $uid=$this->_db->lastInsertId();
            
            $values = array('uid' => $uid, 'username' => $email);
			
            $this->_setSession($result, false);
            return 0; 
        }
        catch(PDOException $e)
		{
			return $e->getMessage();
		}
    }
    
    
    /*Set the session when the user logs in
    
    */
    function _setSession(&$values, $remember, $init = true) { 
		$this->id = $values->id; 
		$_SESSION['uid'] = $this->id; 
		$_SESSION['username'] = $values->username; //htmlspecialchars($values->username); 
		//$_SESSION['cookie'] = $values->cookie; 
		$_SESSION['logged'] = true; 
		
		/*if ($remember) { 
			$this->updateCookie($values->cookie, true); 
		} 
		if ($init) { 
			$session = $this->db->quote(session_id()); 
			$ip = $this->db->quote($_SERVER['REMOTE_ADDR']);

			$sql = "UPDATE member SET session = $session, ip = $ip WHERE " . 
					"id = $this->id"; 
			$this->db->query($sql); 
		} */
}

    
    
}