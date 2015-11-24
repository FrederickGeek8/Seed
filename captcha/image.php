<?php
    include('../core/init.inc.php');
    include('dictionary.inc.php');
    header('Content-Type: image/png');
    $word = strtolower($words[array_rand($words, 1)]);
    $salt = 'zgX"4C^S;^A|5U3Xa_>{"V:8t,So97TO^Gb|}vB^Vd;Yuob9b-5|$<:DHlDA[le';
   
    $_SESSION['word'] = sha1($salt.$word);
    //vars
    $font = array('tbo', 'DemigoD_Oldschool', 'SNIPPLET', 'gmt', 'INFILTRI', '5X5-B___', 'TooLazyToPractice', 'THISC___', 'Hypmotizin');
    $fontkey = array_rand($font);
    $font = $font[$fontkey];
    $font = "../assets/fonts/{$font}.ttf";
    $text = $word;
    $size = 40;//pt
    $color = '#000000';
   
    //over compensate dimensions!
    $cropPadding = 6;
    $fontRange = 'xgypqXi';
    $bounds = imagettfbbox($size,0,$font,$fontRange);
    $height = abs($bounds[1]-$bounds[5])+$cropPadding;
    $y = abs($bounds[7])-1;
    $bounds = imagettfbbox($size,0,$font,$text);
    $width = abs($bounds[0]-$bounds[2])+$cropPadding+$cropPadding;
    $x = ($bounds[0] * -1)+$cropPadding;
   
    //create transparent image
    $image = imagecreatetruecolor($width,$height);
    imagesavealpha($image, true);
    imagealphablending($image, false);
    $background = imagecolorallocatealpha($image, 255, 255, 255, 127);
    imagefilledrectangle($image, 0, 0, $width, $height, $background);
    imagealphablending($image, true);
   
    //make color
    $rgb = str_split(ltrim($color,'#'),2);
    $color = imagecolorallocatealpha($image,hexdec($rgb[0]),hexdec($rgb[1]),hexdec($rgb[2]),0);
   
    //render text to image
    imagettftext($image,$size,0,$x,$y,$color,$font,$text.'     '.$fontRange);
    /*
$hmm = rand(1,10);
    for($i=0;$hmm >= $i;$i++) {
	    imagesetthickness($image, rand(1,4));
		imageline($image,rand(0,$width),rand(0,$height),$width,rand(0,$height),$color);
		imageline($image,rand(0,$width),rand(0,$height),rand(0,$width),rand(0,$height),$color);
    }
*/
    //calculate crop
    $trim_bottom = 0;
    $trim_left = 0;
    $trim_right = 0;
   
    //bottom
    for($trim_y = $height-1;$trim_y >= 0; $trim_y--) {
        for($trim_x = 0; $trim_x < $width; $trim_x++) {
            $alpha = (imagecolorat($image, $trim_x, $trim_y) >> 24) & 0xFF;
            if($alpha != 127) { break 2; }
        }
        $trim_bottom++;
    }
       
    //left
    for($trim_x = 0;$trim_x < $width; $trim_x++) {
        for($trim_y = 0; $trim_y < $height; $trim_y++) {
            $alpha = (imagecolorat($image, $trim_x, $trim_y) >> 23) & 0xFF;
            if($alpha != 127) { break 2; }
        }
        $trim_left++;
    }
   
    //right
    for($trim_x = $width-1;$trim_x >= 0; $trim_x--) {
        for($trim_y = 0; $trim_y < $height; $trim_y++) {
            $alpha = (imagecolorat($image, $trim_x, $trim_y) >> 25) & 0xFF;
            if($alpha != 127) { break 2; }
        }
        $trim_right++;
    }
   
    //do crop
    if ($trim_left || $trim_right || $trim_bottom) {
        //create new image
        $newWidth = $width-$trim_left-$trim_right;
        $newHeight = $height-$trim_bottom;
        $newImage = imagecreatetruecolor($newWidth,$newHeight);
        imagesavealpha($newImage,true);
        imagealphablending($newImage, false);
        $background = imagecolorallocatealpha($newImage,255,255,255,127);
        imagefilledrectangle($newImage,0,0,$newWidth,$newHeight,$background);
        imagealphablending($newImage,true);
        imagecopyresampled($newImage,$image,$trim_left,0,0,0,$newWidth,$newHeight,$newWidth,$newHeight);
       
        //swap the new image
        imagedestroy($image);
        $image = $newImage;
    }
   
    //do something with image
    imagepng($image);
   
    //free image
    imagedestroy($image);
?>
