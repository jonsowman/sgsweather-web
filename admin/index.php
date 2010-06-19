<?php

/*
SGS WEATHER STATION 2009
JON SOWMAN
ALL RIGHTS RESERVED
*/

define("IN_MODULE",true);
require_once("../includes/config.inc.php");
require_once("../includes/functions.inc.php");

// find the file size of a dir
function get_size($path)
{
  if(!is_dir($path)) return filesize($path);
  if ($handle = opendir($path)) {
    $size = 0;
    while (false !== ($file = readdir($handle))) {
      if($file!='.' && $file!='..') {
        // function filesize has been deleted
        $size += get_size($path.'/'.$file);
      }
    }
    closedir($handle);
    return $size;
  }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $c_title; ?> - Admin</title>
<link rel="stylesheet" type="text/css" href="../main.css" />
<script>
function checkClear() {
var answer = confirm("Clear the database?");
	if(answer) {
		window.location = "clear.php?pwd=weathercat2";
	} else {
		return false;
	}
}
</script>
</head>

<body>

   <!-- Begin Wrapper -->
   <div id="wrapper">
   
         <!-- Begin Header -->
         <div id="header">
		 
		       <h1 style="font-size:45px;"><center><?php echo $c_title; ?></center></h1>		 
			   
		 </div>
		 <!-- End Header -->
		 
         <!-- Begin Faux Columns -->
		 <div id="faux">
		 
		       <!-- Begin Left Column -->
		       <div id="leftcolumn">
		       
		       
<?php
db_connect();
$query = "SELECT * from `records` ORDER BY `id` DESC LIMIT 1";
$result = mysql_query($query) or die ("Query failed");
$row = mysql_fetch_array($result);
db_disconnect();
?>

<h2>SGS Weather Admin</h2><br>
<b>Last update from
<?php
echo $row['ip'] . " ("; //display last connection IP

switch($row['ip']) { // this bit resolves the IP to known places
case "62.18.44.156":
    echo "pippin";
    break;
case "93.97.184.163":
    echo "sheeva";
    break;
case "82.43.7.254":
    echo "SGS";
    break;
case "212.85.13.143":
    echo "SGS";
    break;
}
echo " - rdns entry: " . gethostbyaddr($row['ip']) . ")";
?>
</b><br>
<a href="#" onClick="checkClear()">Clear Database</a><br>

<?php
if(!$sms_on_update) {
    echo "<a href='smsnext.php'>Text on Next Update</a>";
} else {
    echo "Texting on next update...";
}
echo "<br>";

if(!$c_tweetnext) {
    echo "<a href='tweetnext.php'>Tweet on Next Update</a>";
} else {
    echo "Tweeting on next update...";
}

db_connect();
$result = mysql_query("SELECT * FROM `ext` WHERE data='FORCE_LAST' LIMIT 1");
$force_last = mysql_result($result, null, "uts");
db_disconnect();
?>


<br><br>
<a href="force_records.php">Force Records DB Update</a>&nbsp(Last at: <?php echo date("G:i, F j Y", $force_last); ?>)<br><b><u>WARNING: USES LOTS OF RAM - CHECK WITH JON BEFORE RUNNING THIS</u></b><br>
This script is horribly messy (hence RAM usage) - needs rewriting. For now, it works, but <b>BE VERY CAREFUL</b>.<br>
<br>

Update Status<br>
<form name="form1" action="updatestatus.php" method="GET">
<input type="text" size="60" name="newstatus" value="<?php echo stripslashes($c_status); ?>">
<input type="submit" value="Change" name="Submit">
</form>
<br>

LDR Correction<br>
<form name="form1" action="ldr_cf.php" method="GET">
<input type="text" size="60" name="newcorr" value="<?php echo stripslashes($ldr_cf); ?>">
<input type="submit" value="Change" name="Submit">
</form>
<br>

Photodiode Constant of Proportionality<br> <?php (printf("Current value in SF = '%e'", $luxCOP)) ?>
<form name="form1" action="luxcop.php" method="GET">
<input type="text" size="60" name="newcop" value="<?php echo stripslashes($luxCOP); ?>">
<input type="submit" value="Change" name="Submit">
</form>
<br>

<?php

// CALCULATE DAYS TILL NEXT CRON ARC

$today = mktime();
$month = date("m");
$year = date("Y");
$nextMonth = ($month + 1);
// sort out year jumps
if($nextMonth == 13) {
    $year = ($year+1);
    $nextMonth = 1;
}
$var = mktime(0,0,0,$nextMonth,1,$year);
$ncron = number_format(($var-$today)/86400);

// SQL
db_connect();

$query = "SELECT * FROM `records`";
$result = mysql_query($query) or die("The mysql query was full of FAIL: " . mysql_error());
$r_res = mysql_num_rows($result);

$query = "SELECT * FROM `records` ORDER BY `uts` DESC LIMIT 1";
$result = mysql_query($query) or die("The mysql query was full of FAIL: " . mysql_error());
if(mysql_num_rows($result) != 0){
$uts_newest = mysql_result($result,null,uts);
}

$query = "SELECT * FROM `records` ORDER BY `uts` ASC LIMIT 1";
$result = mysql_query($query) or die("The mysql query was full of FAIL: " . mysql_error());
if(mysql_num_rows($result) != 0){
$uts_oldest = mysql_result($result,null,uts);
}

db_disconnect();

echo "There are currently <b>" . $r_res . "</b> records in the database.<br>";
if(isset($uts_newest)) {
echo "The records range from <b>" . date("G:i \o\\n jS F Y",$uts_oldest) . "</b> to <b>" . date("G:i \o\\n jS F Y",$uts_newest) . "</b>.<br>";
} else {
echo "Data was not displayed due to an empty database.<br>";
}
echo "Next cron archive in <b>" . $ncron . " days.</b><br>";
echo "Archive Maps Dir Size: <b>" . round((get_size("../archive_maps")/1024/1024) ,2) . "mb</b>";

?>

<br><br>
<b>Trackers</b><br>
<a href="../trackers/tracker.txt"><b>Last Hit</b></a><br>
<a href="../trackers/lr.txt">Last Rejected Reading</a><br>
<a href="../trackers/lux.txt">Photodiode ADC and Gain</a><br>
<a href="../trackers/p.txt">Station Time Vars & Pressure</a><br>
<a href="../trackers/chkpress.php">Pressure Gradient and PMCC</a><br>
<a href="../trackers/cwop.txt">CWOP APRS String and Connectivity Test</a><br>

			   <div class="clear"></div>
			   
		       </div>
		       <!-- End Left Column -->
		 
		       <!-- Begin Right Column -->
		       <div id="rightcolumn">
		 
		             <?php include("../menu.php"); ?>
							
				<div class="clear"></div>
				
		       </div>
		       <!-- End Right Column -->
			   
         </div>	   
         <!-- End Faux Columns --> 
		 
   </div>
   <!-- End Wrapper -->
</body>
</html>
