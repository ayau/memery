<?php
	include_once "common/base.php";
	$pageTitle = "Username";
	include_once "common/header.php";
	include_once "inc/class.template.inc.php";
	$template = new Template();
	include_once "inc/class.meme.inc.php";
	$meme = new Meme();
	
?>

<div id="container">
	<br /><br />
	<h1>Groups</h1>
	<div class='user_panel rounded'>

		<div class='user_panel_item rounded' style="background-image: url(res/meme_templates/tiny/futurama-fry-t.png)">
			<div class='darken_hover rounded'>
				<img class='user_panel_img' style='opacity:0' src='res/meme_templates/tiny/futurama-fry-t.png'/>
			</div>		
		</div>
		<div class='user_panel_item rounded'><div class='darken_hover rounded'></div></div>
		<div class='user_panel_item rounded'><div class='darken_hover rounded'></div></div>
		<div class='user_panel_item rounded'><div class='darken_hover rounded'></div></div>
		<div class='user_panel_item rounded'><div class='darken_hover rounded'></div></div>
		<div class='user_panel_item rounded'><div class='darken_hover rounded'></div></div>
		<div class='user_panel_item rounded'><div class='darken_hover rounded'></div></div>
		<div class='user_panel_item rounded'><div class='darken_hover rounded'></div></div>
	</div>
	
	<h1>Created memes</h1>
	<div class='user_panel rounded'>
	<div class='leftarrow'></div>
	<div class='visible_panel'>
		<div id='memes_slider' class='slider'>	
<?php $memes = $meme -> get_memes_for_preview($_SESSION['uid']);
	$max = ceil(count($memes)/4)*4;
	for($i=0; $i<$max; $i++){
		if($i< count($memes)){
			$m = $memes[$i];
			$t = $template -> get_template_by_id($m['template_id']);
			$crop = 'image_crop.php?src='.$t['src'].'&x0='.$t['crop_x0'].'&y0='.$t['crop_y0'].'&x1='.$t['crop_x1'].'&y1='.$t['crop_y1'];
			
			$texts = $m['text_top']."\n".$m['text_bot'];
			
			echo "<a href='/meme.php?id=".$m['id']."'>";
			echo "<div class='user_panel_item rounded' style='background-image: url(".$crop.")'>";
			echo "<div class='darken_hover rounded'>";
				echo "<img class='user_panel_img' title=\"".$texts."\" style='opacity:0' src='".$t['src']."'/>";
			echo "</div>		
		</div></a>";
		}else{
			echo "<div class='user_panel_item rounded'><div class='darken_hover rounded'></div></div>";
		}
	}
?>	
	</div>
	</div>	
	<div class='rightarrow'></div>
	</div>
	
	<h1>Uploads</h1>
	<div class='user_panel rounded'>
	<div class='leftarrow'></div>
	<div class='visible_panel'>
		<div id='uploads_slider' class='slider'>	
<?php $templates = $template -> get_templates_for_preview($_SESSION['uid']);
	$max = ceil(count($templates)/4)*4;
	for($i=0; $i<$max; $i++){
		if($i< count($templates)){
			$t = $templates[$i];
			$crop = 'image_crop.php?src='.$t['src'].'&x0='.$t['crop_x0'].'&y0='.$t['crop_y0'].'&x1='.$t['crop_x1'].'&y1='.$t['crop_y1'];
			
			echo "<a href='/meme_generator.php?id=".$t['id']."'>";
			echo "<div class='user_panel_item rounded' style='background-image: url(".$crop.")'>";
			echo "<div class='darken_hover rounded'>";
				echo "<img class='user_panel_img' title=\"".$t['name']."\" style='opacity:0' src='".$t['src']."'/>";
			echo "</div>		
		</div></a>";
		}else{
			echo "<div class='user_panel_item rounded'><div class='darken_hover rounded'></div></div>";
		}
	}
?>	
	</div>
	</div>
	<div class='rightarrow'></div>
	</div>
	<br /><br /><br /><br />

</div>

<?php
	include_once "common/footer.php"
?>

<script>
	var uploads = <?php echo count($templates) ?>;
	var uploads_pos = 0;
	
	var memes = <?php echo count($memes) ?>;
	var memes_pos = 0;
	
	$(".rightarrow").live("click",function(){
		slider = $(this).parent().find(".slider");
		
		if(slider.attr('id') == 'uploads_slider'){
			if(uploads_pos<=uploads-4){
				uploads_pos +=4;
				scroll_right(slider);
			}
		}else if(slider.attr('id') == 'memes_slider'){
			if(memes_pos<=memes-4){
				memes_pos +=4;
				scroll_right(slider);
			}
		}		
	})
	
	$(".leftarrow").live("click",function(){
		slider = $(this).parent().find(".slider");
		
		if(slider.attr('id') == 'uploads_slider'){
			if(uploads_pos>=uploads-4){
				uploads_pos -=4;
				scroll_left(slider);
			}
		}else if(slider.attr('id') == 'memes_slider'){
			if(memes_pos>=memes-4){
				memes_pos -=4;
				scroll_left(slider);
			}
		}
	})
	
	function scroll_right(slider){
		slider.animate({
			left: '-=780'}, 500, function(){
				
		})
	}
	
	function scroll_left(slider){
		slider.animate({
			left: '+=780'}, 500, function(){
				
		})
	}	
</script>