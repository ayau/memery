<?php 
	$pageTitle = "custom meme";
	include_once "common/header.php";
?>
<div id="container">
			<br /><br /><br /><br />
	<div class='preview_panel'>	
		<i style='font-size:20px; display:block'>Click and drag on the image to select an area.</i>
		<img class='meme' style='float:left' src="res/meme_templates/simply.png" />
		<div class='preview_settings'>
			<div style='overflow:auto'>
				<div class='thumbnail_preview'><img src="res/meme_templates/simply.png" style="position: relative;" /></div>
				<p class='thumbnail_preview_text'>The 100 x 100 thumbnail for this meme</p>
			</div>
			<div style='margin-top:15px'>
				<input type="text" size='30' placeholder='Name of Meme' value=""/>
				<div style='margin-top:15px;'>
					<p style='display:inline'>Privacy:  </p>
					<select style='height:30px; font-size:16px'>
  						<option value="public">Public</option>
  						<option value="private">Private</option>
  						<option value="grouponly">Group only</option>
					</select>
        		</div>
        		<div style='margin-top:15px;'>
        			<input type="text" size='30' placeholder='Group tags (Optional)' value=""/>
        		</div>
			<div>
		</div>
	</div>
		<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
</div>

<?php
	include_once "common/footer.php"
?>

	<link rel="stylesheet" href="plugins/jquery.imgareaselect-0.9.8/css/imgareaselect-animated.css" type="text/css" />
	<script type="text/javascript" src="plugins/jquery.imgareaselect-0.9.8/scripts/jquery.imgareaselect.min.js"></script>
	<script type="text/javascript">
		/*$('<div><img src="res/meme_templates/simply.png" style="position: relative;" /></div>')
        .css({
            float: 'left',
            position: 'relative',
            overflow: 'hidden',
            margin: '0 25px',
            width: '100px',
            height: '100px'
        })
        .insertAfter($('.meme'));
		*/

		$('.meme').imgAreaSelect({ x1:0, y1:0, x2: 100, y2: 100, aspectRatio: '1:1', handles: "corners", onSelectChange: preview });
		
		
		function preview(img, selection) {
    		var scaleX = 100 / (selection.width || 1);
    		var scaleY = 100 / (selection.height || 1);
  
    		$('.thumbnail_preview img').css({
        		width: Math.round(scaleX * $('.meme').width()) + 'px',
        		height: Math.round(scaleY * $('.meme').height()) + 'px',
        		marginLeft: '-' + Math.round(scaleX * selection.x1) + 'px',
        		marginTop: '-' + Math.round(scaleY * selection.y1) + 'px'
    		});
}
	</script>