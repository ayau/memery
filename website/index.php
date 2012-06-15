<?php 
	include_once "common/base.php";
	$pageTitle = "memery";
	include_once "common/header.php";
	include_once "inc/class.meme.inc.php";
	$meme = new Meme();
	include_once "inc/class.template.inc.php";
	$template = new Template();
?>
<div id="container">
			<br /><br /><br />
<?php
	if(isset($_GET['mid'])):
	$m = $meme -> get_meme_by_id($_GET['mid']);
		if($m!=null):
			$t = $template -> get_template_by_id($m['template_id']);
?>
	<div id='memgr_container'>
		<?php echo "<h2 style='margin-left:15px'>".$m['title']."<p class='hint' style='margin-left:20px; display:inline; font-size:20px;'><a href='group.php?gid=".'1'."'>(trolling in the deep)</p></a></h2>";?>
	
	<div class='splash panel'>
		
		<?php echo "<img src='meme_creator.php?meme=".$t['src']."&top_text=".$m['text_top']."&bottom_text=".$m['text_bot']."' meme_id='".$m['id']."' alt=\"I don't always fail to display this meme. But when I do, I display this text instead.\" />";?>
		
	</div>
	
	<div class='small panel'><img src="res/meme_templates/tiny/scumbag-steve-t.png" /></div>
	<div class='small panel'><img src="res/meme_templates/tiny/good-guy-greg-t.png" /></div>
	<div class='small panel'><img src="res/meme_templates/tiny/the-most-interesting-man-in-the-world-t.png" /></div>
	<div class='small panel'><img src="res/meme_templates/tiny/socially-awkward-penguin-t.png" /></div>
	<div class='small panel'><img src="res/meme_templates/tiny/scumbag-steve-t.png" /></div>
	<div class='small panel'><img src="res/meme_templates/tiny/futurama-fry-t.png" /></div>
	<div class='small panel'><img src="res/meme_templates/tiny/good-guy-greg-t.png" /></div>
	<div class='small panel'><img src="res/meme_templates/tiny/the-most-interesting-man-in-the-world-t.png" /></div>
	<div class='small panel'><img src="res/meme_templates/tiny/socially-awkward-penguin-t.png" /></div>
	
	<div id='rating_cloud'>		
		<table>
			<tr><td>Total Views</td><td>1392</td></tr>
			<tr><td>Rank</td><td>325</td></tr>
			<tr><td>Viral Meter</td><td>98%</td></tr>
			<tr><td>Date Created</td><td>05/04/12</td></tr>
			<tr><td>Creator</td><td>jgar4321</td></tr>
		</table>
	</div>
	<div id='rate_share'>
		<img src='images/upvote.png' width='30px'/>
		<img style='position:relative; top:5px' src='images/downvote.png' width='30px'/>
		<img style='margin-left:30px;' src='images/share_fb.png' width='40px'/>
		<img src='images/share_tw.png' width='40px'/>
	</div>
	</div>
	
	<div id='comments_container'>
		<h2>Comments and whatnot</h2>
		<p>mbei333: This meme sucks ass!!</p>
		<p>alex_1337: ^ what he says</p>
	</div>
	
		
	<!--<center style='position:absolute; left:500px'><a href="/"><img src="images/lightcloud.png" class="logo" alt="" /></a></center>-->
	<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
</div>

<?php
		else:
			echo "<center><h2>This meme does not exist.</h2></center>";
		endif;
	endif;
	include_once "common/footer.php"
?>