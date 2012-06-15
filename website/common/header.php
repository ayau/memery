<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<?php
//Setting default sessions to prevent error messages
function session_defaults() { 
	$_SESSION['logged'] = false; 
	$_SESSION['uid'] = 0; 
	$_SESSION['username'] = ''; 
	$_SESSION['cookie'] = ""; 
	$_SESSION['remember'] = false; 
}

if (!isset($_SESSION['uid']) ) { 
	session_defaults(); 
}
?>



<head>
	<title><?php echo $pageTitle ?></title>
	<link rel="stylesheet" href="/style.css" type="text/css" />
	<script type="text/javascript" src="js/jquery.min.js"></script>
</head>

<body>
	<div id="page-wrap">
		<div id="header">
			<center><a href="/"><img src="/images/memerylogopenn.png" class="logo" alt="" /></a></center>
	
		<div id='mini_account'>			
<?php if($_SESSION['uid']==0): ?>
			<a href="/signup.php"><div class='header_button btn'>Sign up</div></a>
			<a href="/login.php"><div class='header_button btn'>Log in</div></a>			
<?php else: ?>
			<p>Logged in as: <a href="/user.php?uid=<?php echo $_SESSION['uid']?>"><?php echo $_SESSION['username'];?></a></p>
			<a href="/logout.php"><div class='header_button btn'>Log out</div></a>
			<br /><br />
<?php endif;?>
		</div>
		
			
			<div id='top_nav'>		<!--Should be part of the header -->
				<div style='position:relative;'>
					<input type="text" name="search" id="search" class='inputfields' placeholder='Search'/>
					<img src='images/search.png' class='search'/>
				</div>
				<a href="/meme_generator.php"><div class='nav_button'>meme generator</div></a>
				<a href="/stateofbase.php"><div class='nav_button'>stats</div></a>
				<a href="/meme_upload.php"><div class='nav_button'>upload</div></a>
				<a href='/help.php'><div class='nav_button'>help</div></a>
			</div>
		</div>
		
<script>
	//if enter is pressed
    $('#search').keypress(function(e){
    	if (e.which == 13)
    		searchenter();
	});
	
	//or search button is pressed
	$(".search").live("click",function(){
		searchenter();
	});
	
    	function searchenter(){
    		if($("#search").val().replace(/\s/g,"").length>0){	//check if search is empty
    			item = $("#search").val().replace(/^(\s+)|(\s+)$/g,"");	//removing space from beginning and end
    			item = item.replace(/(\s+)/g," ");	//replacing consecutive spaces with one space
    			window.location = "search.php?search="+encodeURIComponent(item)
  		 }
    	};
</script>