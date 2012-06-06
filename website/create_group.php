<?php
	include_once "common/base.php";
	$pageTitle = "Username";
	include_once "common/header.php";
	
?>

<div id="container">
	<br /><br />
	
<?php 	if(isset($_SESSION['uid'])&& $_SESSION['uid']!=0): ?>


<h2>Create Group</h2>
        <form id='create_group'>
       			<input id='group_name' type='text' size='30' placeholder='Name of Group' value=''/>
                <div id ='error_name' class='error_signup' hidden></div>
                <br /><br />
                
                <textarea id='group_description' type="text" row='4' placeholder='Group Description'></textarea>
                
                
                <input type="radio" name="privacy" id="public" value="0" checked />
                <label for="public">Public <p>(Everyone can upload contents to this group)</p></label>
  				<br />
  				<input type="radio" name="privacy" id="private" value="1" />
  				<label for="private">Private <p>(Only group memebers can upload contents)</p></label>
  				<br />
  				<input type="radio" name="privacy" id="secret" value="2" />
  				<label for="secret">Secret <p>(Only group memebers can view content)</p></label>
  				<br /><br />
                
	            
                <input type="button" name="create_group" id="create_group_submit" value="Create Group" />
        </form>
        
    	<br /><br />
</div>



<?php
	else:
		echo "<br /><center><h2>Please log in to view your page</h2></center>";
	endif;
	include_once "common/footer.php"
?>

<script>
	$("#create_group_submit").live("click",function(){
		
			name = $("#groupname").val();
			description = $("#group_description").val();
			
			privacy = $('#create_group input[type=radio]:checked').val();
			
			$.ajax({
		    	type: "POST",
		       	url: "db-interaction/groups.php",
		       	data: "action=create_group&name="+name+
		       	"&description="+description+
		       	"&privacy="+privacy,
		       	success: function(r){
		       		window.location = "/group.php?gid="+r;
		   		},
		     	error:function(){}  
  			});
			
		})
</script>