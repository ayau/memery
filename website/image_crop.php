<?php

	$src = $_GET['src'];
	$x0 = $_GET['x0'];
	$y0 = $_GET['y0'];
	$x1 = $_GET['x1'];
	$y1 = $_GET['y1'];


	$type = exif_imagetype($src);
	
	//Need to check if it's gif, jpeg or png
	
	switch ($type)
	  {
	    case IMAGETYPE_GIF:
	      $src = imagecreatefromgif( $src );
	      break;
	
	    case IMAGETYPE_JPEG:
	      $src = imagecreatefromjpeg( $src );
	      break;
	
	    case IMAGETYPE_PNG:
	      $src = imagecreatefrompng( $src );
	      break;
	  }
	$mime_type = image_type_to_mime_type($type);
	
	$width = $x1-$x0;
	$height = $y1-$y0;
	$image = imagecreatetruecolor($width,$height);
	
	imagecopy($image, $src, 0, 0, $x0, $y0, $width, $height);
	
	header('Content-type: ' . $mime_type) ;
	switch ($type)
	  {
	    case IMAGETYPE_GIF:
	      imagegif($image);
	      break;
	    case IMAGETYPE_JPEG:
	      imagejpeg($image);
	      break;
	    case IMAGETYPE_PNG:
	      imagepng($image);
	      break;
	  }

	ImageDestroy($src) ;
	ImageDestroy($image) ;
	exit ;


?>