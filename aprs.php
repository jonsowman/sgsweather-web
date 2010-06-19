<?php
// THIS WAS A FILE USED IN CWOP/CURL TESTING
// IT IS FUNCTIONAL IF THE UPDATE FUNCTIONS ARE ENABLED
// UNDER NORMAL OPERATIONAL CONDITIONS THIS FILE
// IS NEVER USED
// JON SOWMAN 2009
define("IN_MODULE",true);
include("includes/config.inc.php");
include("includes/functions.inc.php");


db_connect();
$query = "SELECT * FROM records ORDER BY `id` DESC LIMIT 1";
$result = mysql_query($query) or die("Query failed with error: " . mysql_error());
$row = mysql_fetch_array($result) or die(mysql_error());
db_disconnect();

$light = $row['light']; //l
$wind_dir = $row['wind_dir'];  //c
$wind_spd = $row['wind_spd'];  //g
$temp = $row['temp']; //t in deg/F
$rain = $row['rain'];  //# for raw
$pressure = $row['pressure'];  //b

//adjust seqs

$light = round(($light/255)*100);
$pressure = round($pressure * 10);
$temp = round(($temp*1.8)+32);
$wind_spd =  wind_spd *$windspd_rpm_mph_scale;

$temp = leading_zeros($temp, '3');
$wind_spd = leading_zeros($wind_spd, '3');
$wind_dir = leading_zeros($wind_dir, '3');
$pressure = leading_zeros($pressure, '5');

$str1 = "_" . $wind_dir . "/" . $wind_spd . "l" . $light . "t" . $temp . "b" . $pressure . "XPIC";
$str2 = "~SGS1230WX3240" . $str1 . "0111111001111110~";
echo $str2 . "<br><br><br>";

$str3 = "_" . $wind_dir . "/" . $wind_spd . "g002" . "t" . $temp . "b" . $pressure . "pic";
$str4 = "DW1367>APRS,TCPXX*,qAX,CWOP-2:!5121.97N/00011.20W" . $str3 . "";
echo $str4 . "<br><br>";

/*
$fp = fsockopen("cwop.aprs.net", 14580, $errno, $errstr, 10);
//$fp = stream_socket_client("tcp://cwop.aprs.net:14580", $errno, $errstr);
if (!$fp) {
    echo "ERROR: $errno - $errstr<br />\n";
} else {
    fwrite($fp, "user DW1367 pass -1\r\n");
	fwrite($fp, $str4);
	fwrite($fp, "\r\n");
    fclose($fp);
	echo "wrote";
}
*/


echo "WARNING: CWOP Connect DISABLED in this file. Updates to CWOP are now completed automatically.";


?>
