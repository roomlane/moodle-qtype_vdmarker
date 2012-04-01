<?php

//echo 'KO';
//die;

$transparency = 75;
$radius = 60;
$fontsize = 5;
$margin = $radius / 3;
$width = 3 * $radius + 2 * $margin;
$deltax = sqrt($radius * $radius - ($radius / 2) * ($radius / 2));
$height2 = 2 * $radius + 2 * $margin;
$height3 = 2 * $radius + $deltax + 2 * $margin;

// universe
$universe = array(
	'color' => array(255, 255, 255, $transparency),
	'label' => 'U',
	'labelpos' => array(
		'x' => $margin * 0.7,
		'y' => $margin * 0.7)
	);

$circles2 = array(
    array(
        'color' => array(255, 250, 250, $transparency),
        'coords' => array(
            'x' => $margin + $radius, 
            'y' => $margin + $radius),
        'label' => 'A',
        'labelpos' => array(
            'x' => $margin + $radius / 2,
            'y' => $margin + $radius / 2)
        ),
    array(
        'color' => array(250, 255, 250, $transparency),
        'coords' => array(
            'x' => $margin + 2 * $radius, 
            'y' => $margin + $radius),
        'label' => 'B',
        'labelpos' => array(
            'x' => $margin + 2 * $radius + $radius / 2,
            'y' => $margin + $radius / 2)
        )
    );

$circles3 = $circles2;
$circles3[2] =
    array(
        'color' => array(250, 250, 255, $transparency),
        'coords' => array(
            'x' => $margin + $radius + $radius / 2, 
            'y' => $margin + $radius + $deltax),
        'label' => 'C',
        'labelpos' => array(
            'x' => $margin + $radius + $radius / 2,
            'y' => $margin + $radius + $deltax + $radius / 2)
        );

function drawcircle($image, $circle, $radius) {
    $color = imagecolorallocatealpha($image, $circle['color'][0], $circle['color'][1], $circle['color'][2], $circle['color'][3]);
    $x = $circle['coords']['x'];
    $y = $circle['coords']['y'];
    $w = 2 * $radius;
    $h = 2 * $radius;
    imagefilledellipse($image, $x, $y, $w, $h, $color);
    imagecolordeallocate($image, $color);
}

function drawcircleborder($image, $circle, $radius, $border) {
    $x = $circle['coords']['x'];
    $y = $circle['coords']['y'];
    $w = 2 * $radius;
    $h = 2 * $radius;
    imageellipse($image, $x, $y, $w, $h, $border);
}

function drawcirclelabel($image, $circle, $radius, $color) {
    global $fontsize;
    $x = $circle['labelpos']['x'];
    $y = $circle['labelpos']['y'];
    $label = $circle['label'];
    imagechar($image, $fontsize, $x, $y, $label, $color);
}

function drawuniverselabel($image, $color){
    global $fontsize, $universe;
    $x = $universe['labelpos']['x'];
    $y = $universe['labelpos']['y'];
    $label = $universe['label'];
    imagechar($image, $fontsize, $x, $y, $label, $color);
}

function replacecolor($c) {
    switch ($c) {
        case 150:
            $c = 0;
            break;
        case 88:
            $c = 127;
            break;
        case 113:
            $c = 127;
            break;
        case 156:
            $c = 127;
            break;
    } 

    return $c;
}

function labelarea($labelpos, $x, $y){
	global $fontsize;
	$dx = abs($x - $labelpos['x'] - $fontsize * 0.8);
	$dy = abs($y - $labelpos['y'] - $fontsize * 1.5);
	if (3 * $fontsize * $fontsize >= $dx * $dx + $dy * $dy) {
		return true;
	}
	return false;
}

function circlescoded($circles, $x, $y) {
    global $radius, $universe;
    $c = 0;
    $i = 0;
    foreach ($circles as $value) {
		if (labelarea($value['labelpos'], $x, $y)){
            return -1;
        }

        $dx = abs($x - $value['coords']['x']);
        $dy = abs($y - $value['coords']['y']);
        if ($radius * $radius >= $dx * $dx + $dy * $dy) {
            $c += pow(2, $i);
        }
        $i++;
    }

	if (0 == $c){
		// this is universe
		if (labelarea($universe['labelpos'], $x, $y)){
            return -1;
        }
		else {
			return 0;
		}
	}

    return $c;
}

function createimages($filenameprefix, $circles, $width, $height, $display) {
    global $transparency, $radius;
    
    $image = imagecreatetruecolor($width, $height);
    imagealphablending($image, true);

    $back = imagecolorallocate($image, 255, 255, 255);
    imagefilledrectangle($image, 0, 0, $width - 1, $height - 1, $back);
    imagecolordeallocate($image, $back);
    
    $border = imagecolorallocate($image, 200, 200, 200);
    imagerectangle($image, 0, 0, $width - 1, $height - 1, $border);
    imagecolordeallocate($image, $border);

    $border = imagecolorallocate($image, 50, 50, 50);
	foreach ($circles as $value) {
        drawcircle($image, $value, $radius);
    }
    imagecolordeallocate($image, $border);
 
    //! create json with the circle coordinates and radiuses
    
    // colorize
    for ($y = 0; $y < $height - 1; $y++) {
        for ($x = 0; $x < $width - 1; $x++) {
            $rgb = imagecolorat($image, $x, $y);
            $r = replacecolor(($rgb >> 16) & 0xFF);
            $g = replacecolor(($rgb >> 8) & 0xFF);
            $b = replacecolor($rgb & 0xFF);
            
            $color = imagecolorallocate($image, $r, $g, $b);
            imagesetpixel($image, $x, $y, $color);
            imagecolordeallocate($image, $color);
        }
    }
    
    $images = array(); 
    // init overlays
    for ($i = 0; $i < pow(2, count($circles)); $i++) {
        $images[$i] = imagecreatetruecolor($width, $height);
        imagealphablending($images[$i], true);
        $bg = imagecolorallocate($images[$i], 255, 255, 255);
        imagecolortransparent($images[$i], $bg);
        imagefilledrectangle($images[$i], 0, 0, $width - 1, $height - 1, $bg);
        imagecolordeallocate($images[$i], $bg);
    }
    
    // create the overlays
    for ($y = 0; $y < $height - 1; $y++) {
        for ($x = 0; $x < $width - 1; $x++) {
            $rgb = imagecolorat($image, $x, $y);
            $r = ($rgb >> 16) & 0xFF;
            $g = ($rgb >> 8) & 0xFF;
            $b = $rgb & 0xFF;
            
            $m = ($x + $y) % 11;
            if ((0 == $m) || (1 == $m)) {
                if ($r + $g + $b > 200){
                    $r = 0;
                    $g = 0;
                    $b = 0;                    
                } else {
                    $r = 255;
                    $g = 255;
                    $b = 255;
                }
                $ccoded = circlescoded($circles, $x, $y);
                if ($ccoded !== -1) {
                    $color = imagecolorallocate($images[$ccoded], $r, $g, $b);
                    imagesetpixel($images[$ccoded], $x, $y, $color);
                    imagecolordeallocate($images[$ccoded], $color);
                }
            }
        }
    }
    
    // write out overlays
    for ($i = 0; $i < pow(2, count($circles)); $i++) {
        imagepng($images[$i], $filenameprefix.$i.'.png', 9);
        imagedestroy($images[$i]);
    }

	$images = array(); 
    // init overlays
    for ($i = 0; $i < pow(2, count($circles)); $i++) {
        $images[$i] = imagecreatetruecolor($width, $height);
        imagealphablending($images[$i], true);
        $bg = imagecolorallocate($images[$i], 255, 255, 255);
        imagecolortransparent($images[$i], $bg);
        imagefilledrectangle($images[$i], 0, 0, $width - 1, $height - 1, $bg);
        imagecolordeallocate($images[$i], $bg);
    }
    
    // create the overlays
    for ($y = 0; $y < $height - 1; $y++) {
        for ($x = 0; $x < $width - 1; $x++) {
            $rgb = imagecolorat($image, $x, $y);
            $r = ($rgb >> 16) & 0xFF;
            $g = ($rgb >> 8) & 0xFF;
            $b = $rgb & 0xFF;
            
            $m = ($x + $y) % 11;
            if ((5 == $m) || (6 == $m)) {
                if ($r + $g + $b > 200){
                    $r = 255;
                    $g = 0;
                    $b = 0;                    
                } else {
                    $r = 255;
                    $g = 255;
                    $b = 255;
                }
                $ccoded = circlescoded($circles, $x, $y);
                if ($ccoded !== -1) {
                    $color = imagecolorallocate($images[$ccoded], $r, $g, $b);
                    imagesetpixel($images[$ccoded], $x, $y, $color);
                    imagecolordeallocate($images[$ccoded], $color);
                }
            }
        }
    }
    
    // write out overlays
    for ($i = 0; $i < pow(2, count($circles)); $i++) {
        imagepng($images[$i], $filenameprefix.$i.'i.png', 9);
        imagedestroy($images[$i]);
    }
    
    foreach ($circles as $value) {
        drawcircleborder($image, $value, $radius, $border);
    }

    imagefilter($image, IMG_FILTER_GAUSSIAN_BLUR);

    drawuniverselabel($image, $border);
	foreach ($circles as $value) {
        drawcirclelabel($image, $value, $radius, $border);
    }

    
    if ($display === true) {
      header('Content-Type: image/png');
      imagepng($image);
    }
    
    imagepng($image, $filenameprefix.'.png', 9);
    
    imagedestroy($image);
}

createimages('2c', $circles2, $width, $height2, false);
createimages('3c', $circles3, $width, $height3, true);

/*
header('Content-Type: image/png');

$image2 = imageCreateTrueColor($width, $height);
imagealphablending($image2, true);
drawCircle($image2, $circles[0], 80);
imagecopy($image2, $image, 0, 0, 0, 0, $width, $height);

//imagefilter($image2, IMG_FILTER_PIXELATE, 2, true);
imagepng($image2);

imagedestroy($image2);

imagedestroy($image);
 */
?>
