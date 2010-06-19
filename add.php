<?php 

/*
SGS WEATHER STATION 2009
JON SOWMAN
ALL RIGHTS RESERVED

THIS FILE IS COMPLEX! BE CAREFUL WITH WHAT YOU'RE DOING
BEFORE YOU CHANGE ANYTHING TOO MUCH
BACKUPS ARE GENERALLY A GOOD PLAN
*/

// SETUP INCLUDES AND DEFINE VARIABLES
define("IN_MODULE",true);
require_once("includes/config.inc.php");
require_once("includes/functions.inc.php");
require_once('includes/FileScopeReplacer.php');
require_once('includes/checkandadd.php');
date_default_timezone_set  ( 'Europe/London' );

// THIS IS A TOP LEVEL TRACKER. IT WILL TRACK NO MATTER
// IF VERIFICATION FAILS, OR SYSTEM DISABLED

$fh = fopen("trackers/tracker.txt", 'w');
$ip = $_SERVER['REMOTE_ADDR'];
$str = "Last hit was " . $ip . " (" . gethostbyaddr($ip) . ") at " . date('l jS \of F Y h:i:s A') . "\n";
if( $ip == "62.18.44.156") {
    $str .= "(This is the pippin IP - this was a forwarded hit from pippin)\n";
}
if($_GET['bc'] == 1) {
	$str .= "The bitcheck WAS set. Unless you did this manually, this hit was from the weather station.\n\n:)\n";
} else {
	$str .= "The bitcheck WAS NOT SET. This was NOT the weather station.\n\n:(\n";
}
fputs($fh, $str);
fclose($fh);

// CHECK FOR SYSTEM ENABLED

if($enable != 1) die("System disabled.");

// CHECK FOR PASSWORD AND BITCHECK

$pic_pwd = $_GET['pwd'];
$bitcheck = $_GET['bc'];

if($bitcheck != 1) die("Verification Failure.");
if($pic_pwd != $set_pwd) die ("Authentication Failure.");

$uts_now = time();

///////////////////////////
// HYPERACTIVITY SETTINGS
/*
$uts_15 = $uts_now - (15*60);
db_connect();
$query = "SELECT * FROM records WHERE uts > '" . $uts_15 . "' ORDER BY uts DESC";
$result = mysql_query($query) or die ("Query failed: " . mysql_error());
db_disconnect();
$rows_t = mysql_num_rows($result);
if ($rows_t != 0) {
	die("There has already been a reading in the last 15 mins.");
}
*/
///////////////////////////


// GET ALL THE VARIABLES

$t_mins = $_GET['mins'];
$t_hours = $_GET['hours'];
$d_date = $_GET['date'];
$d_month = $_GET['month'];
$d_year = $_GET['year'];

$light = $_GET['l'];
$light2 = $_GET['l2'];
$gain = $_GET['g'];        // gain from PGA
$moisture = $_GET['m'];
$wind_dir = $_GET['wd'];
$wind_spd = $_GET['ws'];
$pressure = $_GET['p'];
$temp = $_GET['t'];
$batt = $_GET['b']; 
$humidity = $_GET['h'];
$temp2 = $_GET['t2'];       // ENABLE WHEN IN USE
$rain = $_GET['r'];

//if($temp2 >= 84) {       // broken temp sensor
//    $temp2 = $temp;
//}

// GRAB TIME VARS

$st_sec = $_GET['b26'];
$st_min = $_GET['b25'];
$st_hur = $_GET['b24'];
$st_dow = $_GET['b23'];
$st_day = $_GET['b22'];

// these are file trackers - see admin section on website

	$myFile = "trackers/p.txt";
	$fh = fopen($myFile, 'w') or die("Can't open file! You mong");
	$w_str = "FW_START\n\nLast read sent time vars\n\nSeconds: " . $st_sec . "\nMinutes: " . $st_min . "\nHours: " . $st_hur . "\nDOW: " . $st_dow . "\nDay: " . $st_day . "\n\nPressure: " . $pressure . "\n\nLight ADC Read: " . $light2 ."\n\nFW_END";
	fwrite($fh, $w_str);
	fclose($fh);

	$myFile = "trackers/lux.txt";
	$fh = fopen($myFile, 'a') or die("Can't open file! You mong");
	$w_str = "Photodiode values - ADC: " . $light2 . " - Gain: " . $gain . "\n";
	fwrite($fh, $w_str);
	fclose($fh);

// end trackers

// now comes the complex error checking stuff
// if something goes wrong, we need to know why
$errmsg; // init error variable

if (!checkVars(&$errmsg)) {
	$ipv = $_SERVER['REMOTE_ADDR'];
	$stringData = "The last database record addition rejected was from " . $ipv . " on " . date("F j, Y, G:i") . ".\n\n" . $errmsg;
	$myFile = "trackers/lr.txt";
	$fh = fopen($myFile, 'w') or die("Can't open file! You mong");
	fwrite($fh, $stringData);
	fclose($fh);
	die ("Variable checking failed.");
}

if($use_server_time) {
	$time_f = time();
	$one_hour = 60 *60;                   
	if($daylight_saving) {               // apply DST correction if set in config
		$time_f = $time_f + $one_hour;
	}
	$time_f = $time_f - $c_startup_delay_secs;
	$t_mins = date("i"); // for twitter
        $t_hours = date("H"); // for twitter
} else {       // otherwise constuct unix timestamp from the supplied vars
	if(checkTime()) {
		$time_f = mktime($t_hours, $t_mins, 0, $d_month, $d_date, $d_year);
	} else {
		die("The supplied time variables were not valid.");
	}
}

// ok, if we got to here everything is cool variable-wise
// let's get the data into the db

db_connect();


$query = "INSERT INTO records VALUES (
null,
'".sanitize($time_f)."',
'".sanitize($light)."',
'".sanitize($moisture)."',
'".sanitize($wind_dir)."',
'".sanitize($wind_spd)."',
'".sanitize($pressure)."',
'".sanitize($temp)."',
'".sanitize($humidity)."',
'".sanitize($batt)."',
'".sanitize($temp2)."',
'".sanitize($rain)."',
'".sanitize($light2)."',
'".$ip."')";

mysql_query($query) or die("Could not run query.<br>" . mysql_error());
db_disconnect();

// this header is for the XPORT serin stuff
// ... when we get it working
// ... if ever
header("Zone: D");   // send new header

// check to see if need to send text

if($sms_on_update) {
	$msgstr = "The station successfully completed an update at " . date("G:i") . ".";
	$newsms = new SMS("447729366996",$msgstr,false);
	$newsms->send();
	
	$params = array();
	$params['dir'] = 'includes';
	$params['include_nested'] = 0;
	$params['search_what'] = '$sms_on_update = true';
	$params['replace_to'] = '$sms_on_update = false';
	$params['file_name_match'] = '/^config.inc.php/';  // <-- this mean beginning from 'test'
	$replacer = new FileScopeReplacer( $params );
	$replacer->doWork();
	
}

// stupid twit : call with both $light values so it can choose which to use
callTwitter($light, $light2, $temp2, $pressure, $t_hours, $t_mins);

//update to CWOP if enabled in config
if($c_usecwop) {
	$light_cwop = round(($light/255)*100);
	$pressure_cwop = round($pressure * 10);
	$temp_cwop = round(($temp*1.8)+32);
	$wind_spd_cwop =  round($wind_spd * $windspd_rpm_mph_scale);
	$wind_dir_cwop = round($wind_dir);

	$temp_cwop = leading_zeros($temp_cwop, '3');
	$light_cwop = leading_zeros($light_cwop, '3');
	$wind_spd_cwop = leading_zeros($wind_spd_cwop, '3');
	$wind_dir_cwop = leading_zeros($wind_dir_cwop, '3');
	$pressure_cwop = leading_zeros($pressure_cwop, '5');

	$str3 = "_" . $wind_dir_cwop . "/" . $wind_spd_cwop . "g..." . "t" . $temp_cwop . "b" . $pressure_cwop . "pic";
	$str4 = "DW1367>APRS,TCPXX*,qAX,CWOP-2:!5122.00N/00011.26W" . $str3 . "";

	$fp = fsockopen("cwop.aprs.net", 14580, $errno, $errstr, 10);
	if (!$fp) {
	    echo "ERROR: $errno - $errstr<br />\n";
	    $stringData = "Conn failed\n\n";
	} else {
	    fwrite($fp, "user DW1367 pass -1\r\n");
		fwrite($fp, $str4);
		fwrite($fp, "\r\n");
	    fclose($fp);
		$stringData = "Conn success\n\n";
	}

	$stringData .= "APRS string follows\n" . $str4 . "\n\n";

	$myFile = "trackers/cwop.txt";
	$fh = fopen($myFile, 'w') or die("Can't open file! You mong");
	fwrite($fh, $stringData);
	fclose($fh);

}

$uPOST['varn0']='temp';
$uPOST['varn1']='light';
$uPOST['varn2']='pressure';
$uPOST['varn3']='wind_spd';
$uPOST['varn4']='batt';
//$uPOST['varn5']='';

$uPOST['varv0']=$temp;
$uPOST['varv1']=$light;
$uPOST['varv2']=$pressure;
$uPOST['varv3']=$wind_spd;
$uPOST['varv4']=$batt;

$uPOST['uts']=$uts_now;

checkandadd($uPOST);

// nobody will ever see this, but it's useful for manual testing
echo "<b>Success!</b> Record added to database.<br><br>";

?>
