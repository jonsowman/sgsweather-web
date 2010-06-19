<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"" .  $_GET['fn'] . ".csv\"");
//Version 0.9.5.3
define("IN_MODULE",true);

require_once ("../includes/config.inc.php");
require_once ("../includes/functions.inc.php");

	$start_hour = $_GET['sh'] or $start_hour=1;
	$start_day = $_GET['sd'] or $start_day=1;
	$start_mon = $_GET['sm'] or $start_mon=1;
	$start_yr = $_GET['sy'] or $start_yr=2008;
	$end_hour = $_GET['eh'] or $end_hour=24;
	$end_day = $_GET['ed'] or $end_day=31;
	$end_mon = $_GET['em'] or $end_mon=date('m');
	$end_yr = $_GET['ey'] or $end_yr=date('Y');
		
	$start_yr = $start_yr+10-10;
	$end_yr = $end_yr+10-10;
	
	IF ($start_yr < 10){$start_yr = "0" . ($start_yr);}
	IF ($end_yr < 10){$end_yr = "0" . ($end_yr);}	
	IF ($start_yr < 100){$start_yr = "20" . ($start_yr);}
	IF ($end_yr < 100){$end_yr = "20" . ($end_yr);}
		
	IF ($start_yr < 2008){$start_yr = 2008; $start_mon = 1;}
	IF ($end_yr > date('Y')){$end_yr = date('Y'); $end_mon = date('m'); $end_day = date('d'); $end_hour = date('G');}
	
	
	$uts_start = @mktime($start_hour,0,0,$start_mon,$start_day,$start_yr);
	IF ($_GET['n'] ==! 'on'){$uts_end = @mktime($end_hour,0,0,$end_mon,$end_day,$end_yr);}else{$uts_end = time();}
	
	
	IF ($uts_start > $uts_end) {die ("Start date after end date");}
	IF ($uts_start == ''){die ("Error in entered start date");}
	IF ($uts_end == ''){die ("Error in entered end date");}
	
	

echo "Date, Time, Moisture, Light, Wind Speed, Wind Direction /°, Temperature /°C, Pressure /mBar, Humidity /%, Batt Voltage /V,Temperature2 /°C,Rain /mm,Light2 /Lux\n\n";


$Ptruts_monyr = mktime(0,0,0,date('m',$uts_start),0,date('Y',$uts_start)); //only used to compare against final date

$Ptrmon = date('m',$uts_start);			//main pointers
$Ptryr = date('y',$uts_start);


while ($Ptruts_monyr < $uts_end){

IF (file_exists("../archive/WeatherData_" .  date('M', mktime(0,0,0,$Ptrmon)). $Ptryr . ".csv")){
	
	$File = fopen("../archive/WeatherData_" .date('M', mktime(0,0,0,$Ptrmon)). $Ptryr . ".csv", "r");

	while (!(feof($File))){
		$CSVArray =  fgetcsv($File);
		IF (($CSVArray[0] >= $uts_start) && ($CSVArray[0] <= $uts_end))
		{
			echo date('d-m-Y',$CSVArray[0]) . "," . date('G:i',$CSVArray[0]) . "," .$CSVArray[1] . "," .$CSVArray[2] . "," .$CSVArray[3] . "," .$CSVArray[4] . "," .$CSVArray[5] . "," .$CSVArray[6]. "," . $CSVArray[7] . "," .$CSVArray[8] . "," .$CSVArray[9] . "," .$CSVArray[10] . "," .$CSVArray[11] .  "\n";
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


//Get database data now

//sanitize($uts_start);
//sanitize($uts_end);


db_connect();
$query = "SELECT * FROM records WHERE uts >= " . $uts_start . " AND uts <= " . $uts_end . " ORDER BY id ASC";
$result = mysql_query($query) or die ("query failed");
db_disconnect();

	while($c_row = mysql_fetch_array($result)){            // GRAB EACH LINE AND EXPORT TO CSV
		echo date('d-m-Y',$c_row['uts']) . ","  . date('G:i',$c_row['uts']) . ","  . $c_row['moisture'] . "," . $c_row['light'] . "," . $c_row['wind_spd'] . "," . $c_row['wind_dir'] . "," . $c_row['temp'] . "," . $c_row['pressure']. "," . $c_row['humidity']. "," . $c_row['batt'] . "," . $c_row['temp2']. "," . $c_row['rain'] . "," . $c_row['light2'] . "\n";
				
	}	

?>