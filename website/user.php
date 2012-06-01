<?php
	include_once "common/base.php";
	$pageTitle = "Username";
	include_once "common/header.php";
	include_once "inc/class.template.inc.php";
	$template = new Template();
	
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
		<div class='user_panel_item rounded'><div class='darken_hover rounded'></div></div>
		<div class='user_panel_item rounded'><div class='darken_hover rounded'></div></div>
		<div class='user_panel_item rounded'><div class='darken_hover rounded'></div></div>
		<div class='user_panel_item rounded'><div class='darken_hover rounded'></div></div>
	</div>
	
	
	<h1>Uploads</h1>
	<div class='user_panel rounded'>
<?php $templates = $template -> get_template_by_UID($_SESSION['uid']);
	for($i=0; $i<4; $i++){
		if($i< count($templates)){
			$t = $templates[$i];
			$crop = 'image_crop.php?src='.$t['src'].'&x0='.$t['crop_x0'].'&y0='.$t['crop_y0'].'&x1='.$t['crop_x1'].'&y1='.$t['crop_y1'];
			
			echo "<div class='user_panel_item rounded' style='background-image: url(".$crop.")'>";
			echo "<div class='darken_hover rounded'>";
				echo "<img class='user_panel_img' title=\"".$t['name']."\" style='opacity:0' src='".$t['src']."'/>";
			echo "</div>		
		</div>";
		}else{
			echo "<div class='user_panel_item rounded'><div class='darken_hover rounded'></div></div>";
		}
	}
?>
	</div>
	<br /><br /><br /><br />

</div>

<?php
	include_once "common/footer.php"
?>