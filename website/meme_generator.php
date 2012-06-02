<?php
	include_once "common/base.php"; 
	$pageTitle = "meme generator";
	include_once "common/header.php";
	include_once "inc/class.template.inc.php";
	$template = new Template();
?>
<div id="container">
	<br /><br /><br /><br />
	
<?php 
	if(isset($_GET['id'])):
		$t = $template -> get_template_by_id($_GET['id']);
		if($t!=null):
		echo "<div id='generator_panel'><img src='".$t['src']."' temp_id='".$t['id']."' source='".$t['src']."'alt=\"I don't always fail to display this meme. But when I do, I display this text instead.\"/></div>";
?>	
	<div id='generator_settings'>
		<textarea id='toptext' type="text" row='4' placeholder='Top Text'>Tried to make the generator respond to input</textarea>
		<textarea id='bottomtext' type="text" row='4' placeholder="Bottom Text">Success</textarea>
		<center><div id='generator_preview' class='btn' type="button" >preview</div></center>
		<input id='title' type='text' placeholder='Title'/>
		<center><div id='generator_submit' class='btn' type="button" >submit</div></center>
	</div>
	
<?php else:

	echo "<center><h2>This meme does not exist.</h2></center>";
	endif;
endif; ?>
	
<?php
	include_once "common/footer.php"
?>

	<script type="text/javascript">
		$("#generator_preview").live("click",function(){
			$("#generator_panel").css("min-height", function(){ return $(this).height()});
			src = $("#generator_panel img").attr("source");
			temp_id = $("#generator_panel img").attr("temp_id");
			$("#generator_panel").empty();
			$("#generator_panel").append("<img src='meme_creator.php?meme="+src+"&top_text="+$("#toptext").val()+"&bottom_text="+$("#bottomtext").val()+"' temp_id='"+temp_id+"' source='"+src+"' alt=\"I don't always fail to display this meme. But when I do, I display this text instead.\" />");
		})
		
		$("#generator_submit").live("click",function(){
			text_top = $("#toptext").val();
			text_bottom = $("#bottomtext").val();
			temp_id = $("#generator_panel img").attr("temp_id");
			title = $("#title").val();
			
			$.ajax({
		    	type: "POST",
		       	url: "db-interaction/memes.php",
		       	data: "action=insert_meme&title="+title+
		       	"&temp_id="+temp_id+
		       	"&text_top="+text_top+
		       	"&text_bottom="+text_bottom,
		       	success: function(){
		       		window.location = "/user.php";
		   		},
		     	error:function(){}  
  			});
		})
	
	</script>