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

db_disconnect();





IF ($row['moisture'] < $c_rain_threshold){
IF ($row['light2'] > 10000){
//day
    $ImLoc =$ImLoc . "day sm";
}elseif ($row['light2'] > 2000) {
    // cloudy day
    $ImLoc = $ImLoc . "cldsm";
}elseif ($row['light2'] > 300){ 
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

?>
