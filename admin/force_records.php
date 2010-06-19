<?php
// THIS FILE IS A MESS
// I KNOW
// I'M SORRY
//
// :(

//
define("IN_MODULE",true);

require_once ("../includes/config.inc.php");
require_once ("../includes/functions.inc.php");

$DEBUG = 1;

$uts_now = time();

$Lgt_data = array();
$Temp_data = array();
$Temp2_data = array();
$Moi_data = array();
$WndS_data = array();
	$WndS_MA_data = array();
	$WndS_MA_temp = array();
$WndD_data = array();
$Prss_data = array();
$Hum_data = array();
$Batt_data = array();
$Rain_data = array();

$arr_xticks = array();

/////////////////
// getArrEXTUTS($array, $time_array, $maxmin, $nonzero)
//
// $array - the array to run the query on
// $time - the array containing the time ticks in uts format
// $maxmin - 0 = search for minimum, 1 = search for maximum  - DEFAULTS TO MAX
// $nonzero - should the value be nonzero? true/false  - DEFAULTS TO FALSE
// 
// returns results in an array, pick up as follows:
// list ($myval, $myval_uts) = getArrEXTUTS($temp, $time, 0, true)
/*  FUNCTIONS DECLARED IN GOBAL FUNCTIONS
function getArrEXTUTS($iarr, &$time_arr, $maxmin=1, $nonzero=false) {
	$min_v = 999999; // a very positive number
	$max_v = -9999999; // a very negative number
	$ptr_min_v = 0;
	$ptr_max_v = 0;
	$iarr_l = count($iarr);
	
	if ( $maxmin == 0 ) {							 // looking for min
		for($i=0; $i<$iarr_l; $i++) {
			$ptr_v = floatval($iarr[$i]);
			if ($ptr_v < $min_v) {
				if($nonzero && $ptr_v == 0) {
					// skip this
				} else {
					$min_v = $ptr_v;
					$ptr_min_v = $time_arr[$i];
				}
			}
		}
		return array ($min_v, $ptr_min_v);
	} else if ( $maxmin == 1 ) {        			// looking for max
		for($i=0; $i<$iarr_l; $i++) {
			$ptr_v = $iarr[$i];
			if ($ptr_v > $max_v) {
				$max_v = $ptr_v;
				$ptr_max_v = $time_arr[$i];
			}
		}
		return array ($max_v, $ptr_max_v);
	} else {
		die("maxmin value in getArrEXTUTS was not recognised.");
	}
}

/////////////////////////

function extExists($data, $maxmin) {
	$query = "SELECT * FROM `ext` WHERE data='" . $data . "' AND maxmin='" . $maxmin . "' LIMIT 1";
	db_connect();
	$result = mysql_num_rows(mysql_query($query));
	db_disconnect();
	if ($result <= 0) {
		return false;
	} else {
		return true;
	}
}

function extInsert($data, $maxmin, $value, $uts) {
	if (extExists($data, $maxmin)) {
		$query = "UPDATE `ext` SET value='" . $value . "' WHERE data='" . $data . "' AND maxmin='" . $maxmin . "'";
	} else {
		$query = "INSERT INTO `ext` VALUES (null, '" . $uts . "', '" . $data . "', '" . $maxmin . "', '" . $value . "')";
	}
	db_connect();
	if(mysql_query($query)) {
		db_disconnect();
		return true;
	} else {
		die(mysql_error());
		db_disconnect();
		return false;
	}
}
*/
////////////////
$uts_start = 1199145600; // 1st jan 2008
$uts_end = $uts_now;

$Ptruts_monyr = mktime(0,0,0,date('m',$uts_start),0,date('Y',$uts_start)); //only used to compare against final date

$Ptrmon = date('m',$uts_start);			//main pointers
$Ptryr = date('y',$uts_start);


WHILE ($Ptruts_monyr < $uts_end){
IF (file_exists("../archive/WeatherData_" .  date('M', mktime(0,0,0,$Ptrmon)). $Ptryr . ".csv")){	
	$File = fopen("../archive/WeatherData_" .date('M', mktime(0,0,0,$Ptrmon)). $Ptryr . ".csv", "r");
	while (!(feof($File))){
		$CSVArray =  fgetcsv($File);
		IF (($CSVArray[0] >= $uts_start) && ($CSVArray[0] <= $uts_end))
		{
			array_push($Lgt_data, ($CSVArray[2]/255)*100);// IF ($CSVArray[2] > $Max_Lgt){$Max_Lgt = $CSVArray[2];}			
			array_push($Moi_data, ($CSVArray[1]/255)*100); IF ($CSVArray[1] > $Max_Moi){$Max_Moi = $CSVArray[1];}			
			array_push($Temp_data, $CSVArray[5]);// IF ($CSVArray[5] > $Max_Tmp){$Max_Tmp = $CSVArray[5];}		
			array_push($Temp2_data, $CSVArray[9]);		
			array_push($WndD_data, $CSVArray[4]);// IF ($CSVArray[4] > $Max_WdD){$Max_WdD = $CSVArray[4];}			
			array_push($Prss_data, $CSVArray[6]);// IF ($CSVArray[6] > $Max_Prs){$Max_Prs = $CSVArray[6];}	
			array_push($WndS_data, $CSVArray[3]); IF ($CSVArray[3] > $Max_WdS){$Max_WdS = $CSVArray[3];}
			array_push($Hum_data, $CSVArray[7]);
			array_push($Batt_data, $CSVArray[8]);
			array_push($Rain_data, $CSVArray[10]);
					
			array_push($arr_xticks, $CSVArray[0]);			
		}
	}
	fclose($File);
}
$Ptrmon++;
IF ($Ptrmon == 13){
	$Ptrmon = 1;
	$Ptryr++;
	IF ($Ptryr < 10){$Ptryr = '0' . $Ptryr;}
}
$Ptruts_monyr = mktime(0,0,0,$Ptrmon,0,("20".$Ptryr));
}

//now check the db

db_connect();
sanitize($uts_start);
sanitize($uts_end);
$query = "SELECT * FROM records WHERE uts >= " . $uts_start . " AND uts <= " . $uts_end . " ORDER BY id ASC";
$result = mysql_query($query) or die ("query failed");
db_disconnect();

$t=0;
while($row = mysql_fetch_array($result)){
	array_push($Lgt_data, ($row['light']/255)*100);//	 IF ($row['light'] > $Max_Lgt){$Max_Lgt = $row['light'];}
	array_push($Moi_data, ($row['moisture']/255)*100);	 IF ($row['moisture'] > $Max_Moi){$Max_Moi = $row['moisture'];}
	array_push($Temp_data, $row['temp']);// IF ($row['temp'] > $Max_Tmp){$Max_Tmp = $row['temp'];}
	array_push($WndD_data, $row['wind_dir']);// IF ($row['wind_dir'] > $Max_WdD){$Max_WdD = $row['wind_dir'];}
	array_push($Prss_data, $row['pressure']);// IF ($row['pressure'] > $Max_Prs){$Max_Prs = $row['pressure'];}	
	array_push($WndS_data, $row['wind_spd']); IF ($row['wind_spd'] > $Max_WdS){$Max_WdS = $row['wind_spd'];}
	array_push($Hum_data, $row['humidity']);
	array_push($Batt_data, $row['batt']);
	array_push($Temp2_data, $row['temp2']);
	array_push($Rain_data, $row['rain']);
	
	array_push($arr_xticks, $row['uts']);
}

// get maxs
list($max_temp, $max_temp_uts) = getArrEXTUTS($Temp_data, $arr_xticks, 1);
list($max_prss, $max_prss_uts) = getArrEXTUTS($Prss_data, $arr_xticks, 1);
list($max_wnds, $max_wnds_uts) = getArrEXTUTS($WndS_data, $arr_xticks, 1);
list($max_batt, $max_batt_uts) = getArrEXTUTS($Batt_data, $arr_xticks, 1);

// get mins
list($min_temp, $min_temp_uts) = getArrEXTUTS($Temp_data, $arr_xticks, 0, true);
list($min_prss, $min_prss_uts) = getArrEXTUTS($Prss_data, $arr_xticks, 0, true);

//extInsert(data, maxmin, value, uts)
extInsert("temp", 1, $max_temp, $max_temp_uts);
extInsert("temp", 0, $min_temp, $min_temp_uts);
extInsert("pressure", 1, $max_prss, $max_prss_uts);
extInsert("pressure", 0, $min_prss, $min_prss_uts);
extInsert("wind_spd", 1, $max_wnds, $max_wnds_uts);
extInsert("batt", 1, $max_batt, $max_batt_uts);

db_connect();
$query = "UPDATE `ext` SET uts='".$uts_now."' WHERE data='FORCE_LAST'";
mysql_query($query) or die("FORCE_LAST query failed: ".mysql_error());
db_disconnect();

// and debug

function dPrint($data, $value, $uts, $maxmin, $units) {
	echo ($maxmin == 1) ? "max" : "min";
	echo " ".$data." was: " . $value . $units . " at " . date("G:i, F j Y", $uts) . ".<br>";
}

if($DEBUG) {
	dPrint("temp", $max_temp, $max_temp_uts, 1, "C");
	dPrint("temp", $min_temp, $min_temp_uts, 0, "C");
	dPrint("prss", $max_prss, $max_prss_uts, 1, "mb");
	dPrint("prss", $min_prss, $min_prss_uts, 0, "mb");
	dPrint("WndS", $max_wnds, $max_wnds_uts, 1, " rpm");
	dPrint("batt", $max_batt, $max_batt_uts, 1, "V");
}

echo "<br><br><b>Records database 'ext' updated.<br>Successfully traversed through all archives and database records.<br>Timestamp FORCE_LAST added to database.</b>";

?>
