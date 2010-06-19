<?php

// CRON JOB FILE

// WEATHER STATION 2008 - JON SOWMAN
// THIS FILE SHOULD NOT BE CALLED DIRECTLY

// MAINTENANCE FILE
// -----------------------------
// REMOVES DATA OLDER THAN ONE MONTH FROM THE DATABASE
// AND ARCHIVES IT TO A CSV FILE

define("IN_MODULE",true);
require_once("/var/www/weather/includes/config.inc.php");         // CRON SPECIFIC INCLUDES
require_once("/var/www/weather/includes/functions.inc.php");
require("/var/www/weather/includes/Mail/mime.php");
require("/var/www/weather/includes/Mail.php");

$this_month = date("m");
$this_year = date("Y");

if($this_month == 1) {                       // ADJUST FOR GOING OVER YEAR BOUNDARY
	$last_month = 12;
	$last_year = $this_year - 1;
	$arc_month = 11;
	$arc_year = $this_year - 1;
} else if ($this_month == 2) {
	$last_month = $this_month - 1;
	$last_year = $this_year;
	$arc_month = 12;
	$arc_year = $this_year - 1;
} else {
	$last_month = $this_month - 1;
	$last_year = $this_year;
	$arc_month = $this_month - 2;
	$arc_year = $this_year;
}

$uts_lowerbound = mktime(0, 0, 0, $last_month, 1, $last_year);   // FIND UTS OF THE BEGINNING OF LAST MONTH
$uts_archivemonth = mktime(0, 0, 0, $arc_month, 1, $arc_year);

$csv_name = "WeatherData_" . date("My", $uts_archivemonth);    //eg.   WeatherData_Apr08.csv

$query = "SELECT * FROM `records` WHERE uts < '" . $uts_lowerbound . "' ORDER BY `id` ASC";
db_connect();
$result = mysql_query($query) or die ("The archiving query failed: " . mysql_error());
$numrows = mysql_num_rows($result);
db_disconnect();

if( $numrows <= 0 ) {
	$did_something = false;
	$email_msg = "The maintenance script ran successfully, but there were no records to archive.";
} else {
	$did_something = true;
	$tFile = "/var/www/weather/archive/" . $csv_name . ".csv";
	$fHandle = fopen($tFile, "w") or die("File creation failed.");
	//fwrite($fHandle, "File Write Start\n\nUTS,Moisture,Light,Wind Speed,Wind Direction,Temperature,Pressure,Humidity,System Voltage,Temperature2,Rain\n\n");           // removed
	$i = 0;
	while($c_row = mysql_fetch_array($result)){            // GRAB EACH LINE AND EXPORT TO CSV
		$w_string = $c_row['uts'] . "," . $c_row['moisture'] . "," . $c_row['light'] . "," . $c_row['wind_spd'] . "," . $c_row['wind_dir'] . "," . $c_row['temp'] . "," . $c_row['pressure'] . "," . $c_row['humidity'] . "," . $c_row['batt'] . "," . $c_row['temp2'] . "," . $c_row['rain'] . "," . $c_row['light2'] . "," . $c_row['ip'] . "\n";
		fwrite($fHandle, $w_string);
		$i++;
	}
	$email_msg = "The maintenance script ran successfully; " . $i . " records were archived.";
	//fwrite($fHandle, "\n\nFile Write End"); // removed
	fclose($fHandle);
}
//  HOURS, MINUTES,Time,Date, MOISTURE, LIGHT, WIND SPD,ACTUAL TEMP /ºC,Wind Dir /º,Compass Direction,,

// NOW DELETE THE OLD DATA FROM THE DATABASE, IF APPLICABLE
if($did_something) {
	$query = "DELETE FROM `records` WHERE uts < '" . $uts_lowerbound . "'";
	db_connect();
	if(mysql_query($query)) {
		$email_msg .= " These records were deleted from the database successfully.";
	} else {
		$email_msg .= " However, these records could not be deleted from the database.";
	}
	db_disconnect();
}

//LETS LET THE ADMINS KNOW WHATS GOING DOWN IN TINSELTOWN
//TEXT VERSION
if($did_something) {
	$text = "Maintentance Report from SGS Weather\n\nThe CRON daemon said: " .$email_msg . "\n\nThe archived data can be found here:\nhttp://www.hexoc.com/weather/archive/" . $csv_name . ".csv\n\nThis is an automated email. Please do not reply.";
} else {
	$text = "Maintentance Report from SGS Weather\n\nThe CRON daemon said: " .$email_msg . "\n\nThis is an automated email. Please do not reply";
}
//HTML VERSION
if($did_something) {
	$html = "<b>Maintentance Report from SGS Weather</b><br><br>The CRON daemon said: " . $email_msg . "
	<br><br>
	The archived data can be found here:
	<br>
	<a href='http://www.hexoc.com/weather/archive/" . $csv_name . ".csv'>http://www.hexoc.com/weather/archive/" . $csv_name . ".csv</a>
	<br><br>
	This is an automated email. Please do not reply.<br><br>";
} else {
	$html = "<b>Maintentance Report from SGS Weather</b><br><br>The CRON daemon said: " . $email_msg . "
	<br><br>
	This is an automated email. Please do not reply.<br><br>";
}

$recipients = "jon.sowman@gmail.com, matthew@brejza.plus.com";
$hdrs = array(
              'From'    => 'SGS Weather <weather@hexoc.com>',
              'To'      => 'Weather Admins',
              'Subject' => 'Maintenance Report - ' . $csv_name,
              'Reply-To' => 'jon.sowman@gmail.com'
              );
$mime = new Mail_mime();
$mime->setTXTBody($text);
$mime->setHTMLBody($html);
$body = $mime->get();
$hdrs = $mime->headers($hdrs);
$mail =& Mail::factory('mail');
$res = $mail->send($recipients, $hdrs, $body);
if (PEAR::isError($res)) { print($res->getMessage());}

?>
