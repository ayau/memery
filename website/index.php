<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<link rel="stylesheet" href="/style.css" type="text/css" />

<center><img src="/images/darkcloudlogo.png" class="logo" alt="" /></center>

<center><img src="meme_creator.php?meme=simply&top_text=one does not simply&bottom_text=add text to an image" alt="I don't always fail to display this meme. But when I do, I display this text instead." /></center>

<!--<center><img src="meme_creator.php?meme=bolgar&top_text=creates memery&bottom_text=becomes meme" alt="I don't always fail to display this meme. But when I do, I display this text instead." /></center>
-->
<center><img src="meme_creator.php?meme=idontalways&top_text=i don't always make meme generators&bottom_text=but when I do, I make sure sentences longer than one line can fit" alt="I don't always fail to display this meme. But when I do, I display this text instead." /></center>

<center><img src="meme_creator.php?meme=greg&top_text=creates a new popular meme generator&bottom_text=automatically adjusts the font size of longer texts to make sure they all fit" alt="I don't always fail to display this meme. But when I do, I display this text instead." /></center>

<center id='successkid'><img src="meme_creator.php?meme=successkid&top_text=%20&bottom_text=%20" alt="I don't always fail to display this meme. But when I do, I display this text instead." /></center>
<center><input id='toptext' type="text" size='50' placeholder='Top Text' value="Tried to make the generator respond to input"/></center>
<center><input id='bottomtext' type="text" size='50' placeholder="Bottom Text" value="Success"/></center>
<center><input id='submit' type="button" value='submit'/></center>

	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript">
		$("#submit").live("click",function(){
			$("#successkid").empty();
			$("#successkid").append("<img src='meme_creator.php?meme=successkid&top_text="+$("#toptext").val()+"&bottom_text="+$("#bottomtext").val()+"' alt=\"I don't always fail to display this meme. But when I do, I display this text instead.\" />");
		})
	
	</script>