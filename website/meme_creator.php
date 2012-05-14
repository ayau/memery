<?php

// clean up the input
if(empty($_GET['top_text']) || empty($_GET['bottom_text']) || empty($_GET['meme']))
    fatal_error('Error: No text specified.') ;

$top = html_entity_decode($_GET['top_text']);
$top = strtoupper($top);

$bottom = html_entity_decode($_GET['bottom_text']);
$bottom = strtoupper($bottom);

$meme = html_entity_decode($_GET['meme']);
$meme = strtoupper($meme);

/* check if meme id is valid
if(empty($text))
    fatal_error('Error: Text not properly formatted.') ;
*/

// customizable variables
$font_file      = 'res/font/impact.ttf';
$font_size      = 30 ; // font size in pts
$font_color     = '#ffffff' ;
$image_file     = 'res/meme_templates/'.$meme.'.png';


// trust me for now...in PNG out PNG
$mime_type          = 'image/png' ;
$extension          = '.png' ;
$s_end_buffer_size  = 4096 ;


// check for GD support
if(!function_exists('ImageCreate'))
    fatal_error('Error: Server does not support PHP image generation') ;

// check font availability;
if(!is_readable($font_file)) {
    fatal_error('Error: The server is missing the specified font.') ;
}

// create and measure the text

//top
$font_rgb = hex_to_rgb($font_color) ;
$box_top = @ImageTTFBBox($font_size,0,$font_file,$top) ;

$top_width = abs($box_top[2]-$box_top[0]);
$top_height = abs($box_top[5]-$box_top[3]);

//Bottom
$font_rgb = hex_to_rgb($font_color) ;
$box_bottom = @ImageTTFBBox($font_size,0,$font_file,$bottom) ;

$bottom_width = abs($box_bottom[2]-$box_bottom[0]);
$bottom_height = abs($box_bottom[5]-$box_bottom[3]);

//Image itself
$image =  imagecreatefrompng($image_file);

if(!$image || !$box_top || !$box_bottom)
{
    fatal_error('Error: The server could not create this image.') ;
}

// x and y for the bottom right of the text
// so it expands like right aligned text
list($w, $h) = getimagesize($image_file);

$x_top     = ($w - $top_width)/2 + $top_width;
$y_top     = $h*0.2;

$x_bottom     = ($w - $bottom_width)/2 + $bottom_width;
$y_bottom     = $h*0.9;



// allocate colors and measure final text position
$font_color = ImageColorAllocate($image,$font_rgb['red'],$font_rgb['green'],$font_rgb['blue']) ;
$outline_color = ImageColorAllocate($image,0,0,0) ;

$image_width = imagesx($image);

$put_top_x = $image_width - $top_width - ($image_width - $x_top);
$put_top_y = $y_top;

$px = 3;

// Write the text
for($c1 = ($put_top_x-abs($px)); $c1 <= ($put_top_x+abs($px)); $c1++)
        for($c2 = ($put_top_y-abs($px)); $c2 <= ($put_top_y+abs($px)); $c2++)
            imagettftext($image, $font_size, 0, $c1, $c2, $outline_color, $font_file, $top);
            
imagettftext($image, $font_size, 0, $put_top_x,  $put_top_y, $font_color, $font_file, $top);

$put_bottom_x = $image_width - $bottom_width - ($image_width - $x_bottom);
$put_bottom_y = $y_bottom;

// Write the text
for($c1 = ($put_bottom_x-abs($px)); $c1 <= ($put_bottom_x+abs($px)); $c1++)
        for($c2 = ($put_bottom_y-abs($px)); $c2 <= ($put_bottom_y+abs($px)); $c2++)
            imagettftext($image, $font_size, 0, $c1, $c2, $outline_color, $font_file, $bottom);
            
imagettftext($image, $font_size, 0, $put_bottom_x,  $put_bottom_y, $font_color, $font_file, $bottom);

header('Content-type: ' . $mime_type) ;
ImagePNG($image) ;

ImageDestroy($image) ;
exit ;


/*
    attempt to create an image containing the error message given.
    if this works, the image is sent to the browser. if not, an error
    is logged, and passed back to the browser as a 500 code instead.
*/
function fatal_error($message)
{
    // send an image
    if(function_exists('ImageCreate'))
    {
        $width = ImageFontWidth(5) * strlen($message) + 10 ;
        $height = ImageFontHeight(5) + 10 ;
        if($image = ImageCreate($width,$height))
        {
            $background = ImageColorAllocate($image,255,255,255) ;
            $text_color = ImageColorAllocate($image,0,0,0) ;
            ImageString($image,5,5,5,$message,$text_color) ;
            header('Content-type: image/png') ;
            ImagePNG($image) ;
            ImageDestroy($image) ;
            exit ;
        }
    }

    // send 500 code
    header("HTTP/1.0 500 Internal Server Error") ;
    print($message) ;
    exit ;
}


/*
    decode an HTML hex-code into an array of R,G, and B values.
    accepts these formats: (case insensitive) #ffffff, ffffff, #fff, fff
*/
function hex_to_rgb($hex) {
    // remove '#'
    if(substr($hex,0,1) == '#')
        $hex = substr($hex,1) ;

    // expand short form ('fff') color to long form ('ffffff')
    if(strlen($hex) == 3) {
        $hex = substr($hex,0,1) . substr($hex,0,1) .
               substr($hex,1,1) . substr($hex,1,1) .
               substr($hex,2,1) . substr($hex,2,1) ;
    }

    if(strlen($hex) != 6)
        fatal_error('Error: Invalid color "'.$hex.'"') ;

    // convert from hexidecimal number systems
    $rgb['red'] = hexdec(substr($hex,0,2)) ;
    $rgb['green'] = hexdec(substr($hex,2,2)) ;
    $rgb['blue'] = hexdec(substr($hex,4,2)) ;

    return $rgb ;
}


?>