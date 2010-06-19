<?php
if(!defined("IN_MODULE")) {
define("IN_MODULE",true);
require_once("../includes/config.inc.php");
require_once("../includes/functions.inc.php");
}



db_connect();

$query = "SELECT * FROM records ORDER BY `id` DESC LIMIT 1";
$result = mysql_query($query) or die("Query failed with error: " . mysql_error());
$row = mysql_fetch_array($result) or die(mysql_error());

$uts_now = time();
$last_read = $row['uts'];
$p = $row['pressure'];
$l2 = $row['light2'];

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


$g = 1000 * calculateRegression($uts_data, $press_data);
$r = calculatePMCC($uts_data, $press_data);




$lpress = number_format($row['pressure'],0,".","");

$fast = $c_pressure_change_fast;
$slow = $c_pressure_change_slow;



echo "Gradient (x1000) = " . $g . "<br>PMCC = " . $r . "<br><br>fast threshold = " . $fast . "<br>slow threshold = " . $slow;

// now do the T4R PMCC stuff
echo "<br><br>T4R Class PMCC: ";

$t4r = new PMCC();
$t4r->DataX = $uts_data;
$t4r->DataY = $press_data;
$t4rp = $t4r->PMCC();
echo $t4rp;


// eureqa
//
echo "<br><br><b>Eureqa Algorithm Testing</b><br>";
echo "Using algorithm     ";
echo "f(lux, pressure) = 2.18912e-006*lux*pressure + 11.6748*pressure - 4.0645e-009*lux*lux - 0.00180449*lux - 0.00576235*pressure*pressure - 5898.97";
//echo "<br>";
$pred_temp = eureqa(1, $l2, $p);
$real_temp = $row['temp2'];
echo "<br>Predicted Temp: " . $pred_temp;
echo "<br>Real Temp: " . $real_temp;
echo "<br>Difference: " . number_format(abs($real_temp - $pred_temp),3);
echo "<br>Percentage Error: " . abs(number_format(100*(($pred_temp-$real_temp)/$real_temp),3))."%";

?>
