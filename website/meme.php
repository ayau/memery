<?php
	include_once "common/base.php"; 
	$pageTitle = "meme generator";
	include_once "common/header.php";
	include_once "inc/class.meme.inc.php";
	$meme = new Meme();
	include_once "inc/class.template.inc.php";
	$template = new Template();
?>
<div id="container">
	<br /><br /><br /><br />
	
<?php 
	if(isset($_GET['id'])):
		$m = $meme -> get_meme_by_id($_GET['id']);
		if($m!=null):
		$t = $template -> get_template_by_id($m['template_id']);
		echo "<center><img src='meme_creator.php?meme=".$t['src']."&top_text=".$m['text_top']."&bottom_text=".$m['text_bot']."' meme_id='".$m['id']."' alt=\"I don't always fail to display this meme. But when I do, I display this text instead.\" /></center>";
	else:
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