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

$uts_l_b = $uts_now - (60*60*5);
$uts_l_t = $uts_now - (60*60*4);

$query = "SELECT * FROM records WHERE `uts` > " . $uts_l_b . " AND `uts` < " . $uts_l_t . " ORDER BY uts ASC LIMIT 1";
$result = mysql_query($query) or die("Query 2 failed with error: " . mysql_error());
$row2 = mysql_fetch_array($result) or die(mysql_error());

db_disconnect();

$lpress = number_format($row['pressure'],0);
$lpress2 = number_format($row2['pressure'],0);

echo $lpress . "  " . $lpress2;

if($lpress >= $lpress2) {
	$img_str = "coldfront1.png";
	$image1=imagecreatefrompng($img_str);
	$txt_color = ImageColorAllocate ($image1, 255, 0, 0);
} else {
	$img_str = "coldfront2.png";
	$image1=imagecreatefrompng($img_str);
	$txt_color = ImageColorAllocate ($image1, 0, 0, 255);
}

ImageString ($image1,3,1,37,$lpress,$txt_color);

//imagegif($image1);

?>