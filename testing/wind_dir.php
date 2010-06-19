<?php
if(!defined("IN_MODULE")) {
define("IN_MODULE",true);
require_once("../includes/config.inc.php");
require_once("../includes/functions.inc.php");
}

function LoadGif ($imgname)
{
    $im = @imagecreatefromgif ($imgname); /* Attempt to open */
    if (!$im) { /* See if it failed */
        $im = imagecreatetruecolor (150, 30); /* Create a blank image */
        $bgc = imagecolorallocate ($im, 255, 255, 255);
        $tc = imagecolorallocate ($im, 0, 0, 0);
        imagefilledrectangle ($im, 0, 0, 150, 30, $bgc);
        /* Output an errmsg */
        imagestring ($im, 1, 5, 5, "Error loading $imgname", $tc);
    }
    return $im;
}

function GetRed ($tr){
	IF ($tr > 25){
		return 255;
	}elseif ($tr < 10){
		return 0;
	}else{
	 	return ($tr-10)/15*255;
	}
}

function GetGreen ($tg){
	IF ($tg > 22){
		return 0;
	}elseif ($tg < 0){
		return 0;
	}elseif ($tg < 15){
	 	return ($tg-0)/12*225;
	}else{
		return (22-$tg)/10*225;
	}
}

function GetBlue ($tb){
	IF ($tb > 15){
		return 0;
	}elseif ($tb < 0){
		return 255;
	}else{
	 	return (15-$tb)/15*255;
	}
}

header("Content-type: image/gif");
db_connect();

$query = "SELECT * FROM records ORDER BY `id` DESC LIMIT 1";
$result = mysql_query($query) or die("Query failed with error: " . mysql_error());
$row = mysql_fetch_array($result) or die(mysql_error());

$uts_now = time();
$last_read = $row['uts'];

db_disconnect();


//KOMPASS //PHP4.0 //GD2

$wert=-$row['wind_dir']; //0-360 degree
$null=0; //Offset
$bgcolor=0xF2F2E6;
$grad=$wert+$null;
$kompass=imagecreatefromjpeg("../images/windmid.jpg");
$nadel=imagecreatefromjpeg("../images/wind_dir.jpg");

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

IF ( $row['wind_spd'] > 9 && $row['wind_spd'] < 100 ) {
	ImageString ($kompass, 4, 8, 7, $row['wind_spd'], $txt_color);
} else if ($row['wind_spd'] >= 100) {
	ImageString ($kompass, 2, 7, 8, $row['wind_spd'], $txt_color); 
} else {
	ImageString ($kompass, 5, 12, 8, $row['wind_spd'], $txt_color);
}

imagejpeg($kompass);
//imagegif($nadel);
imagedestroy($nadel);
imagedestroy($kompass);
?>