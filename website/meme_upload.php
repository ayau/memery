<?php 
	$pageTitle = "meme upload";
	include_once "common/header.php";
?>
<div id="container">
	
	<div id="upload_container">
		<h1>Upload Your Images</h1>
		<form method="post" enctype="multipart/form-data"  action="upload.php" id='meme_upload'>
			<input type="file" name="images[]" id="images" multiple />
			<button type="submit" id="btn">Upload Files!</button>
		</form>

		<div id="response"></div>
		<ul id="image-list">

		</ul>
	</div>
	<script src="js/upload.js"></script>
	
<?php
	include_once "common/footer.php"
?>