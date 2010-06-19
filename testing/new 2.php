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



$ImLoc = "../images/";


IF ($row['moisture'] < 20){
IF ($row['light'] > 220){
//day
$ImLoc =$ImLoc . "day sm";
}elseif ($row['light'] > 10){ 
//tw
$ImLoc =$ImLoc .  "set_rise sm";
}else{ 
//night
$ImLoc =$ImLoc .  "night sm";
} 
}else{
IF ($row['light'] > 220){
//day
$ImLoc =$ImLoc . "rainsm";
}elseif ($row['light'] > 10){ 
//tw
$ImLoc =$ImLoc . "rainsm_tw";
}else{ 
//night
$ImLoc =$ImLoc .  "rainsm_nit";
} 
}
$ImLoc = $ImLoc . ".gif";
$image1=imagecreatefromgif($ImLoc);
imagegif($image1);
end;


/*

$temp = $row['temp'];
$image2 = LoadGif("gsbk.gif");
imagefilter($image2,IMG_FILTER_COLORIZE,GetRed($temp),GetGreen($temp),GetBlue($temp),GetBlue($temp));
$txt_color = imageColorAllocate ($image2, 0, 0, 0);

if ( $temp < 10 && $temp > -10 ) {            //T_SPAR
	$ulc_start = 12;
} else {
	$ulc_start = 7;
}

imageString ($image2, 5, $ulc_start, 8, $temp, $txt_color);



//KOMPASS //PHP4.0 //GD2

$wert=-$row['wind_dir']; //0-360 degree
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


$txt_color = imageColorAllocate ($kompass, 255, 255, 255);

IF ( $_GET['s'] > 9 && $_GET['s'] < 100 ) {
	imageString ($kompass, 4, 8, 7, $_GET['s'], $txt_color);
} else if ($_GET['s'] >= 100) {
	imageString ($kompass, 2, 7, 8, $_GET['s'], $txt_color); 
} else {
	imageString ($kompass, 5, 12, 8, $_GET['s'], $txt_color);
}

//imagejpeg($kompass);
//imagegif($nadel);
*/

/*
imagegif($image1);
end;
$image = imagecreate(100,100) or die("Cannot Initialize new GD image stream");


imagecopymerge($image1,$image,0,0,0,0,50,33,100);

imagegif($image1);

*/

?>