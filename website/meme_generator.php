<?php
	include_once "common/base.php"; 
	$pageTitle = "meme generator";
	include_once "common/header.php";
?>
<div id="container">
	<br /><br /><br /><br />

	<center id='successkid'><img src="meme_creator.php?meme=successkid&top_text=%20&bottom_text=%20" alt="I don't always fail to display this meme. But when I do, I display this text instead." /></center>
	<center><input id='toptext' type="text" size='50' placeholder='Top Text' value="Tried to make the generator respond to input"/></center>
	<center><input id='bottomtext' type="text" size='50' placeholder="Bottom Text" value="Success"/></center>
	<center><input id='submit' type="button" value='submit'/></center>
	
<?php
	include_once "common/footer.php"
?>

	<script type="text/javascript">
		$("#submit").live("click",function(){
			$("#successkid").css("min-height", function(){ return $(this).height()});
			$("#successkid").empty();
			$("#successkid").append("<img src='meme_creator.php?meme=successkid&top_text="+$("#toptext").val()+"&bottom_text="+$("#bottomtext").val()+"' alt=\"I don't always fail to display this meme. But when I do, I display this text instead.\" />");
		})
	
	</script>