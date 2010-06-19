<?php
if(!defined("IN_MODULE")) {
define("IN_MODULE",true);
require_once("../includes/config.inc.php");
require_once("../includes/functions.inc.php");
}

//header("Content-type: image/gif");
db_connect();

$query = "SELECT * FROM records ORDER BY `id` DESC LIMIT 1";
$result = mysql_query($query) or die("Query failed with error: " . mysql_error());
$row = mysql_fetch_array($result) or die(mysql_error());

$uts_now = time();
$last_read = $row['uts'];

db_disconnect();


//KOMPASS //PHP4.0 //GD2

$wert=-$row['wind_dir']; //0-360 degree

$wert = 180 + $wert;


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
header("Cache-Control: no-cache");
header("Pragma: no-cache");

$txt_color = ImageColorAllocate ($kompass, 255, 255, 255);

IF ( $row['wind_spd']*$windspd_rpm_mph_scale > 9 && $row['wind_spd']*$windspd_rpm_mph_scale < 100 ) {
	ImageString ($kompass, 4, 8, 7, $row['wind_spd']*$windspd_rpm_mph_scale, $txt_color);
} else if ($row['wind_spd'] >= 100) {
	ImageString ($kompass, 2, 7, 8, $row['wind_spd']*$windspd_rpm_mph_scale, $txt_color); 
} else {
	ImageString ($kompass, 5, 12, 8, $row['wind_spd']*$windspd_rpm_mph_scale, $txt_color);
}

imagejpeg($kompass);
//imagegif($nadel);
imagedestroy($nadel);
imagedestroy($kompass);
?>
