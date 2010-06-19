<?php

if(!defined("IN_MODULE")) {
//die("Direct Call Disabled.");
define("IN_MODULE",true);
require_once("includes/config.inc.php");
require_once("includes/functions.inc.php");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $c_title; ?></title>
<!-- <link rel="stylesheet" type="text/css" href="main.css" /> -->
<style>
td { padding:2px; }
.style4 {font-size: 36px}
</style>
</head>

<body>

		       
<?php
/*
define("IN_MODULE",true);
require_once("includes/config.inc.php");
require_once("includes/functions.inc.php");
*/
db_connect();

$query = "SELECT * FROM records ORDER BY `id` DESC LIMIT 1";
$result = mysql_query($query) or die("Query failed with error: " . mysql_error());
$row = mysql_fetch_array($result) or die(mysql_error());

$uts_now = time();
$last_read = $row['uts'];

db_disconnect();


?>

<!--
<img src="images/
<?php
IF ($row['moisture'] < $c_rain_threshold){
IF ($row['light2'] > 10000){
//day
echo "day sm";
}elseif ($row['light2'] > 2000) {
    // cloudy day
    echo "cldsm";
}elseif ($row['light2'] > 300){ 
//tw
echo "set_rise sm";
}else{ 
//night
echo "night sm";
} 
}else{
IF ($row['light2'] > 10000){
//day
echo "rainsm";
}elseif ($row['light2'] > 1000){ 
//tw
echo "rainsm_tw";
}else{ 
//night
echo "rainsm_nit";
} 
} ?>
.gif" width="50" height="33" />
-->

<!-- set random UTS value so on ajax update, the src changes
----- which defeats browser caching -->
<img id = "aaglight" src = "images/lightim.php?uts=1" />
<img id = "aagtemp" src = "images/tempim.php?uts=1" width="31" height="31" /> 
<!--
<img id = "aagwind" src = "images/windir.php?d=<?php printf("%01.0f", ($row['wind_dir'])); ?>&s=<?php printf("%01.0f", ($row['wind_spd'] * $windspd_rpm_mph_scale)); ?>" width="32" height="32" />
-->
<img id = "aagwind" src = "images/windim.php?uts=1" />
<img id = "aagpressure" src = "images/pr_img.php?uts=1" width="40" height="40" />


</body>
</html>
