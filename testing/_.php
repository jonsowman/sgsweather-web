<?php
//KOMPASS //PHP4.0 //GD2

$wert=-$_GET['d']; //0-360 degree
$null=0; //Offset
$bgcolor=0xFFFFFF;
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
IF ($_GET['s'] > 9){ImageString ($kompass, 5, 8, 8, $_GET['s'], $txt_color);}else{ImageString ($kompass, 5, 12, 8, $_GET['s'], $txt_color);}
imagejpeg($kompass);
//imagegif($nadel);
imagedestroy($nadel);
imagedestroy($kompass);






?>