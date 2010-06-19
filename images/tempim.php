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
header('Cache-Control: no-cache');
header('Pragma: no-cache');
db_connect();

$query = "SELECT * FROM records ORDER BY `id` DESC LIMIT 1";
$result = mysql_query($query) or die("Query failed with error: " . mysql_error());
$row = mysql_fetch_array($result) or die(mysql_error());

$uts_now = time();
$last_read = $row['uts'];

db_disconnect();





$temp = number_format($row['temp'],0);
if($temp == 0 || $temp > 80) $temp = number_format($row['temp2'],0);
$img = LoadGif("gsbk.gif");
imagefilter($img,IMG_FILTER_COLORIZE,GetRed($temp),GetGreen($temp),GetBlue($temp),GetBlue($temp));
$txt_color = ImageColorAllocate ($img, 0, 0, 0);

if ( $temp < 10 && $temp > -10 ) {            //T_SPAR
	$ulc_start = 12;
} else {
	$ulc_start = 7;
}

ImageString ($img, 5, $ulc_start, 8, $temp, $txt_color);

//echo GetRed($temp).", ".GetGreen($temp).", ".GetBlue($temp);

imagegif($img);



?>
