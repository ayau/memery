<?php
	include_once "common/base.php";
	$pageTitle = "sign up";
	include_once "inc/class.user.inc.php";
	$db_memery = db_connect('memery');
	$user = new User($db_memery);
	include_once "common/header.php";		//Header is after User(db). If there's error, page will be blank. Check other pages as well.
?>

<div id="container">
	<br /><br />
	<h2>Log in</h2>
        <form method="post"  id="loginform" action="db-interaction/users.php">
                <label class="toplabel" for="username">Email:</label>
                <input type="text" name="username" id="username" class='inputfields' placeholder='Email'/>
				<br />
                
                <label class="toplabel" for="password">Password:</label>
                <input type="password" id="password" name="password" class='inputfields' placeholder='Password'/>
	            <br />
	            
	            <div style='overflow:auto'>
	            	<label id='rememberme_label' for='rememberme'>Remember me</label>
	             	<input type="checkbox" id="rememberme" value="Yes" />
	             </div>
	             
				<input type="hidden" name="action" value="login">
                <input type="submit" name="login" id="login" value="Log in" />
        </form>
        
    	<br /><br />
</div>

<?php
	include_once "common/footer.php"
?>