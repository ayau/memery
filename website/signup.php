<?php
	include_once "common/base.php";
	$pageTitle = "sign up";
	include_once "common/header.php";
	include_once "inc/class.user.inc.php";
	$db_memery = db_connect('memery');
	$user = new User($db_memery);
?>
<div id="container">
	<br /><br />
	
<?php if(isset($_GET['status']) && $_GET['status']=="completed"): ?>
    <br/><br/><br/><br/>
    <center><h3>Thank you for signing up. You will receive an email from us shortly.</h3></center>
    
    <?php
    else: ?>
	
	<h2>Sign up</h2>
        <form method="post"  id="registerform">
                <label class="toplabel" for="username">Email:</label>
                <input type="text" name="username" id="username" class='inputfields' placeholder='Email'/>
                <div id ='error_mail' class='error_signup' hidden></div>
                <br />
                
                <label class="toplabel" for="password">Password:</label>
                Passwords should be 6-18 characters long.
                <input type="password" id="password" class='inputfields' placeholder='Password'/>
                <div id ='error_pass' class='error_signup' hidden></div><br />
                
                <label class="toplabel" for="passwordre">Retype password:</label>
                <input  type="password" id="passwordre" class='inputfields' placeholder='Retype Password'/>
                <div id ='error_repass' class='error_signup' hidden></div><br />
                
                <label class="toplabel" for="firstname">First Name:</label>
                <input type="text" id="firstname" class='inputfields' placeholder='First Name'/>
                <div id ='error_firstname' class='error_signup' hidden></div><br />
                
                <label class="toplabel" for="lastname">Last Name</label>
                <input type="text" id="lastname" class='inputfields' placeholder='Last Name'/>
                <div id ='error_lastname' class='error_signup' hidden></div><br />
	            
                <input type="button" name="register" id="register" value="Sign up" />
        </form>
        
    	<br /><br />
</div>

<script src="js/signup.js"></script>
<?php
	endif;
	include_once "common/footer.php"
?>


