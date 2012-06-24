<?php 
	include_once "common/base.php";
	$pageTitle = "memery";
	include_once "common/header.php";
	include_once "inc/class.meme.inc.php";
	$meme = new Meme();
    include_once "inc/class.comment.inc.php";
	$comment = new Comment();
	include_once "inc/class.template.inc.php";
	$template = new Template();
	include_once "inc/class.group.inc.php";
	$group = new Group();
	include_once "inc/class.userinfo.inc.php";
	$userinfo = new Userinfo();
?>
<div id="container">
			<br /><br /><br />
<?php

	//Get user id
	if(isset($_GET['uid']))
		$uid = $_GET['uid'];
	else
		$uid = $_SESSION['uid'];


	//Get mode. u = upload, p = public	
	if(isset($_GET['mode']))
		$mode = $_GET['mode'];
	else
		$mode = 'p';

	//Getting splash meme	
	if(isset($_GET['mid'])){
		$m = $meme -> get_meme_by_id($_GET['mid']);	
		$mid = $_GET['mid'];
	}else{
		switch($mode){
			case 'u':
				$m = $meme-> get_memes_created($uid, 1);
				$m = $m[0];
				$mid = $m['id'];
				break;
			case 'p':
				$m = $meme -> get_memes_popular(1);
				$m = $m[0];
				$mid = $m['id'];
				break;
		}
	}

	//checking if meme exists
	if($m!=null):
		$t = $template -> get_template_by_id($m['template_id']);


	switch($mode){
		case 'u':
			if($uid==$_SESSION['uid'])
				echo "<h2>Memes by you</h2>";
			else{
				$name = $userinfo->get_name($uid);
				echo "<h2>Memes by ".$name."</h2>";				
			}
			break;
		case 'p':

			break;
	}
?>

	<h3>
	<div id='memgr_container'>
		<?php echo "<h2 style='margin:20px; width:450px; float:left'>".$m['title']."</h2>";?>
		
		<div id='navigation'>
			<div id='right_arrow'><img src='images/right_arrow.png'/></div>
			<div id='left_arrow'><img src='images/left_arrow.png'/></div>
		</div>
	<div class='splash panel'>
		
<?php
 	echo "<img src='meme_creator.php?meme=".$t['src']."&top_text=".$m['text_top']."&bottom_text=".$m['text_bot']."' meme_id='".$m['id']."' alt=\"I don't always fail to display this meme. But when I do, I display this text instead.\" />";

	$groups = $meme->get_group_tags($mid);
	for($i = 0; $i < count($groups); $i++){
		$g = $group -> get_group_by_id($groups[$i]);
		if(strlen(trim($g['groupname']))>0)
			echo "<div type='group' class='tags no_hover'>".$g['groupname']."</div>";
	}

	$keywords = $meme->get_keyword_tags($mid);
	for($i = 0; $i < count($keywords); $i++){
		if(strlen(trim($keywords[$i])) > 0)
		echo "<div type='keyword' class='tags no_hover'>#".$keywords[$i]."</div>";
	}

?>	
	</div>

<!-- Displaying preview panels-->
<?php

		switch($mode){
			case 'u':
			 	$previews = $meme->get_memes_created($uid, 9, $mid);
				break;
			case 'p':
				$popularity = $meme -> get_popularity($mid);
				$previews = $meme->get_memes_popular(9, $popularity);
				break;
		}	


	 for($i = 0; $i < 9; $i ++){
		if($i<count($previews)){
			$m = $previews[$i];
 			$t = $template -> get_template_by_id($m['template_id']);
 			$crop = 'image_crop.php?src='.$t['src'].'&x0='.$t['crop_x0'].'&y0='.$t['crop_y0'].'&x1='.$t['crop_x1'].'&y1='.$t['crop_y1'];
			if(strlen(trim($m['title']))>0)
				$texts = $m['title'];
			else
				$texts = $m['text_top']."\n".$m['text_bot'];

			if($mode === 'u')
				echo "<a href='/index.php?mode=".$mode."&uid=".$uid."&mid=".$m['id']."'>";
			else
				echo "<a href='/index.php?mode=".$mode."&mid=".$m['id']."'>";
			echo "<div class='small panel rounded' style='background-image: url(".$crop.")'>";
			echo "<div class='darken_hover rounded'>";
			echo "<img title=\"".$texts."\" style='opacity:0' src='".$t['src']."'/>";
			echo "</div></div></a>";

 		}else{
 			echo "<div class='small panel rounded'><div class='darken_hover rounded'></div></div>";
 		}
	}


?>
	
	<div id='rating_cloud'>		
		<table>
			<tr><td>Total Views</td><td><?php echo $meme->get_views($mid)?></td></tr>
			<tr><td>Rank</td><td>325</td></tr>
			<tr><td>Viral Meter</td><td>98%</td></tr>
			<tr><td>Date Created</td><td>05/04/12</td></tr>
			<tr><td>Creator</td><td>jgar4321</td></tr>
		</table>
	</div>
	<div id='rate_share'>
        <div id='meme_vote_count'>
            <?php 
                $meme_votes = $comment -> get_meme_vote_count($mid);
                echo "votes: ",$meme_votes;
            ?>
        </div>
		<img id='meme_upvote' src='images/upvote.png' width='30px'/>
		<img id='meme_downvote' style='position:relative; top:5px' src='images/downvote.png' width='30px'/>
		<img style='margin-left:30px;' src='images/share_fb.png' width='40px'/>
		<img src='images/share_tw.png' width='40px'/>
	</div>

	</div>	<!--End of Top display panel-->
	
	<div id='comments_container'>
		<h2>Comments and whatnot</h2>
        <form method='POST' onsubmit='submit_comment()'>
            <input id='comment_text' type='text' placeholder='Write a comment...'/>
        </form>
	</div>
	
		
	<!--<center style='position:absolute; left:500px'><a href="/"><img src="images/lightcloud.png" class="logo" alt="" /></a></center>-->
	<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
</div>

<?php
		else:
			echo "<center><h2>This meme does not exist.</h2></center>";
		endif;

	include_once "common/footer.php"
?>

<script type="text/javascript">


	$("#left_arrow").live("click",function(){
		alert('left');
	})
	
	$("#right_arrow").live("click",function(){
		alert('right');
	})
	
	
	//Increment views after 4 seconds
	setTimeout(function(){
		mid = <?php echo $mid ?>;
		$.ajax({
		    	type: "POST",
		       	url: "db-interaction/memes.php",
		       	data: "action=inc_views&mid="+mid,
		       	success: function(){
		   		},
		     	error:function(){}  
  			});}, 4000);

    $("#meme_upvote").live("click",function(){
    vote_meme(1);
    })

    $("#meme_downvote").live("click",function(){
    vote_meme(-1);
    })
    
    function submit_comment(){
    alert("submitted!");
    }

    function vote_meme(v){
    meme_id = <?php echo $mid ?>;
        $.ajax({
        type: "POST",
        url: "db-interaction/comments.php",
        data: "action=vote_meme&mid="+meme_id+
        "&vote="+v,
        success:function(){
        },
        error:function(){
            alert('sad panda :( there was an error in the vote. please refresh and try again.');
        }
        });
    }


</script>