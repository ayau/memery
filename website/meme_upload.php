<?php
	include_once "common/base.php"; 
	$pageTitle = "meme upload";
	include_once "common/header.php";
	
	if (!$_SESSION['logged']):
?>
	<div id="container">
		<br /><br /><br />
		<center><h2>Sorry, you have to log in to upload</h2></center>
	</div>
<?php		
	
	else:
?>
		 
<div id="container">
	
	<div id="upload_container" class='rounded'>
		<center>
		<h1>Upload Your Images</h1>
		<form method="post" enctype="multipart/form-data"  action="upload.php" id='meme_upload'>
			<input type="file" name="images[]" id="images" multiple />
			<button type="submit" id="btn">Upload Files!</button>
		</form>
		</center>
		<div id="response"></div>
		<ul id="preview_list">

		</ul>
		<center><div id='upload_save' class='btn' hidden>Save</div></center>
	</div>

	<link rel="stylesheet" href="plugins/jquery.imgareaselect-0.9.8/css/imgareaselect-animated.css" type="text/css" />
	<script type="text/javascript" src="plugins/jquery.imgareaselect-0.9.8/scripts/jquery.imgareaselect.min.js"></script>
	<script src="js/upload.js"></script>
	<script type="text/javascript">
	
		function preview(img, selection) {
    		var scaleX = 100 / (selection.width || 1);
    		var scaleY = 100 / (selection.height || 1);
  
    		$(img).parent().find(".thumbnail_preview img").css({
        		width: Math.round(scaleX * img.width) + 'px',
        		height: Math.round(scaleY * img.height) + 'px',
        		marginLeft: '-' + Math.round(scaleX * selection.x1) + 'px',
        		marginTop: '-' + Math.round(scaleY * selection.y1) + 'px'
    		});
    	}
    </script>	
<?php
	endif;
	include_once "common/footer.php"
?>