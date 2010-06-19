<?php

define("IN_MODULE",true);
include("../includes/config.inc.php");

//KOMPASS //PHP4.0 //GD2

// import
$ws = $_GET['s'];


$wert=-$_GET['d']; //0-360 degree
$wert = 180 + $wert;
$null=0; //Offset
$bgcolor=0xF2F2E6;
$grad=$wert+$null;
$kompass=imagecreatefromjpeg("windmid.jpg");
$nadel=imagecreatefromjpeg("wind_dir.jpg");

$mov_nadel= imagerotate($nadel, $grad, $bgcolor);

$xKom=imagesx($kompass);
$yKom=imagesy($kompass);

$x2Kom=$xKom/2;
$y2Kom=$yKom/2;

$xNad=imagesy($mov_nadel);
$yNad=imagesy($mov_nadel);

$y2Nad=$yNad/2+$x2Kom-$yNad; //92/2=46 + 100-92
$x2Nad=$xNad/2+$y2Kom-$xNad;

imagecopy ( $kompass, $mov_nadel, $x2Nad, $y2Nad, 0, 0, $xNad, $xNad );

header("Content-type: image/jpg");

$txt_color = ImageColorAllocate ($kompass, 255, 255, 255);

IF ( $ws > 9 && $ws < 100 ) {
	ImageString ($kompass, 4, 8, 7, $ws, $txt_color);
} else if ($ws >= 100) {
	ImageString ($kompass, 2, 7, 8, $ws, $txt_color); 
} else {
	ImageString ($kompass, 5, 12, 8, $ws, $txt_color);
}

imagejpeg($kompass);
//imagegif($nadel);
imagedestroy($nadel);
imagedestroy($kompass);






?>