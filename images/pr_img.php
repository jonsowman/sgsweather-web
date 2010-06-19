<?php
if(!defined("IN_MODULE")) {
define("IN_MODULE",true);
require_once("../includes/config.inc.php");
require_once("../includes/functions.inc.php");
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

$uts_l_b = $uts_now - (60*60*4);
$uts_l_t = $uts_now;

$query = "SELECT * FROM records WHERE `uts` > " . $uts_l_b . " AND `uts` < " . $uts_l_t . " ORDER BY uts ASC";
$result = mysql_query($query) or die("Query 2 failed with error: " . mysql_error());
if (mysql_num_rows($result) == 0){die();}
$row2 = mysql_fetch_array($result) or die(mysql_error());

db_disconnect();


$press_data = array();
$uts_data = array();


while($row2 = mysql_fetch_array($result)){
	array_push($press_data, $row2['pressure']);	
	array_push($uts_data, $row2['uts']);
}

//if (count($press_data) > 0){

$g = 1000 * calculateRegression($uts_data, $press_data);
$r = calculatePMCC($uts_data, $press_data);




$lpress = number_format($row['pressure'],0,".","");

$fast = $c_pressure_change_fast;
$slow = $c_pressure_change_slow;


if($g>=$fast){
$img_str = "pressure_imgs/pressure_rise_2.gif";
$image1=imagecreatefromgif($img_str);
$txt_color = ImageColorAllocate ($image1, 255, 0, 0);
}else{ 
	if($g>=$slow){
	$img_str = "pressure_imgs/pressure_rise_1.gif";
	$image1=imagecreatefromgif($img_str);
	$txt_color = ImageColorAllocate ($image1, 255, 0, 0);
	}else{
		if($g>=-$slow){
		$img_str = "pressure_imgs/pressure_same.gif";
		$image1=imagecreatefromgif($img_str);
		$txt_color = ImageColorAllocate ($image1, 0, 0, 0);
		}else{
			if($g>-$fast){
			$img_str = "pressure_imgs/pressure_fall_1.gif";
			$image1=imagecreatefromgif($img_str);
			$txt_color = ImageColorAllocate ($image1, 0, 0, 255);
			}else{
			$img_str = "pressure_imgs/pressure_fall_2.gif";
			$image1=imagecreatefromgif($img_str);
			$txt_color = ImageColorAllocate ($image1, 0, 0, 255);
			}
		}
	}
}



/*
if($g>0) {
	$img_str = "coldfront1.png";
	$image1=imagecreatefrompng($img_str);
	$txt_color = ImageColorAllocate ($image1, 255, 0, 0);
} else {
	$img_str = "coldfront2.png";
	$image1=imagecreatefrompng($img_str);
	$txt_color = ImageColorAllocate ($image1, 0, 0, 255);
}*/
//echo $lpress;
ImageString ($image1,3,0,28,$lpress,$txt_color);

imagegif($image1);

?>
