<?php
 
/**
 * Handles user log ins within the app
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
        	try{
        		$dsn = "mysql:host=".DB_HOST.";dbname=".DB_MEMERY.";port=".DB_PORT;
        		$this->_db = new PDO($dsn, DB_USER, DB_PASS);
        	}catch (PDOException $e){
            	echo '<br /><br /><center><h3>Connection failed</h3></center>';
            	exit();
            }
        }
        
        //Set current datetime
        date_default_timezone_set( 'Europe/London');
		$this->date = date("Y-m-d H:i:s", mktime());
        
        //If the session is logged in, check if session is valid
		if ($_SESSION['logged']) { 
			$this->_checkSession(); 
		}
		//Doesn't work. It logs the user in after he logs out.
		/* elseif ( isset($_COOKIE['mrywebLogin']) ) { 	//If not, try to log in with cookie
			$this->_checkRemembered($_COOKIE['mrywebLogin']); 
		}*/
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
        
		$password = hash('sha256', $_POST['password']);
		
		$cookie = hash('sha256', $datetime);
		
		//Insert user into database
        $sql = "INSERT INTO users(username, password, first_name, last_name, created_at, cookie)
        		VALUES(:email, :pass, :fName, :lName, :time, :cookie)";
          try
          {
          	$stmt = $this->_db->prepare($sql);
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->bindParam(":pass", $password, PDO::PARAM_STR);
            $stmt->bindParam(":fName", $_POST['firstname'], PDO::PARAM_STR);
            $stmt->bindParam(":lName", $_POST['lastname'], PDO::PARAM_STR);
            $stmt->bindParam(":time", $datetime, PDO::PARAM_STR);
            $stmt->bindParam(":cookie", $cookie, PDO::PARAM_STR);
            $stmt->execute();
            $stmt->closeCursor();
            
            //retrieving user_id
            $uid=$this->_db->lastInsertId();
            
            $values = array('id' => $uid, 'username' => $email, 'cookie' => $cookie);
            
            $this->_setSession($values, false);
            return 0; 
        }
        catch(PDOException $e)
		{
			return $e->getMessage();
		}
    }
    
    
    //Set the session when the user logs in 
    function _setSession($values, $remember, $init = true) { 
		$this->id = $values['id']; 
		$_SESSION['uid'] = $this->id; 
		$_SESSION['username'] = $values['username']; //htmlspecialchars($values->username); 
		$_SESSION['cookie'] = $values['cookie']; 
		$_SESSION['logged'] = true; 
		
		//If the user wants to be remembered
		if ($remember) { 
			$this->updateCookie($values['cookie'], true); 
		}		
		
		
		//If this is the first time the user is logging in 
		if ($init) { 
			$session = session_id(); 
			$ip = $_SERVER['REMOTE_ADDR'];
			
			$sql = "UPDATE users
			 SET session =:session, ip =:ip 
			 WHERE id =:uid"; 
			
			 try{
                $stmt = $this->_db->prepare($sql);
                $stmt->bindParam(":session", $session, PDO::PARAM_STR);
                $stmt->bindParam(":ip", $ip, PDO::PARAM_STR);
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
	
	function _checkLogin($username, $password, $remember) { 
		$remember = true;
		$password = hash('sha256', $password);
		$sql = "SELECT * FROM users 
			WHERE username =:username 
			AND password =:pass
			LIMIT 1"; 
		
		if($stmt = $this->_db->prepare($sql)) {
            $stmt->bindParam(":username", $username, PDO::PARAM_STR);
            $stmt->bindParam(":pass", $password, PDO::PARAM_STR);            
            $stmt->execute(); 
			if($row = $stmt->fetch()){
				$this->_setSession($row, $remember); 
				return true; 
			} else { 
				$this->failed = true; 
				$this->_logout(); 
				return false; 
			} 
 		}
	}
	
	//Sets the cookie on the client side
	function updateCookie($cookie, $save) { 
		$_SESSION['cookie'] = $cookie; 
		if ($save) { 
			$cookie = serialize(array($_SESSION['username'], $cookie)); 
			setcookie('mrywebLogin', $cookie, time() + 604800, '/'); 	//valid for a week (year = 31104000)
		} 
	}
	
	//Logs the user in if cookie is valid
	function _checkRemembered($cookie) { 
		list($username, $cookie) = @unserialize($cookie); 
		if (!$username or !$cookie) return;
			
			$sql = "SELECT * FROM users 
			WHERE username =:username 
			AND cookie =:cookie"; 
			
			if($stmt = $this->_db->prepare($sql)) {
            $stmt->bindParam(":username", $username, PDO::PARAM_STR);
            $stmt->bindParam(":cookie", $cookie, PDO::PARAM_STR);   
            $stmt->execute(); 
			if($row = $stmt->fetch()){
				$this->_setSession($row, true); 
			}
		} 
	}

	//If the user is logged in, makes sure all the session information is valid
	function _checkSession() { 
		$username = $_SESSION['username']; 
		$cookie = $_SESSION['cookie']; 
		$session = session_id(); 
		$ip = $_SERVER['REMOTE_ADDR']; 
		
		$sql = "SELECT * FROM users 
			WHERE username =:username 
			AND cookie =:cookie 
			AND session =:session 
			AND ip =:ip"; 

		if($stmt = $this->_db->prepare($sql)) {
            $stmt->bindParam(":username", $username, PDO::PARAM_STR);
            $stmt->bindParam(":cookie", $cookie, PDO::PARAM_STR);  
            $stmt->bindParam(":session", $session, PDO::PARAM_STR);
            $stmt->bindParam(":ip", $ip, PDO::PARAM_STR);          
            $stmt->execute(); 
			if($row = $stmt->fetch()){
				$this->_setSession($row, false, false); 
			} else { 
				$this->_logout(); 
			}
		} 
	}
	
	function _logout(){
		$_SESSION['logged'] = false; 
		$_SESSION['uid'] = 0; 
		$_SESSION['username'] = ''; 
		$_SESSION['cookie'] = ""; 
		$_SESSION['remember'] = false; 
	}

      
}