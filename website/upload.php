<?php
include_once "common/base.php"; 
include_once "inc/class.template.inc.php";
$template = new Template();


foreach ($_FILES["images"]["error"] as $key => $error) {
	if ($error == UPLOAD_ERR_OK) {
		$name = $_FILES["images"]["name"][$key];
		$src = "uploads/" . $_FILES['images']['name'][$key];
		
		move_uploaded_file( $_FILES["images"]["tmp_name"][$key], $src);
		
		$template -> create_template($name, $src, $_SESSION['uid']);
	}
}

echo "<h2>Successfully Uploaded Images</h2>";

?>


