<?php
// SGS WEATHER 2009
// RECURSE DB AND CSV ARCHIVES TO REFRESH THE
// RECORDS DATABASE
// JON SOWMAN
// ALL RIGHTS RESERVED
//
define("IN_MODULE",true);
require_once("includes/config.inc.php");
require_once("includes/functions.inc.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $c_title; ?></title>
<link rel="stylesheet" type="text/css" href="main.css" />
</head>

<body>

   <!-- Begin Wrapper -->
   <div id="wrapper">
   
         <!-- Begin Header -->
         <div id="header">
		 
		       <h1><center>SGS Weather Station</center></h1>		 
			   
		 </div>
		 <!-- End Header -->
		 
         <!-- Begin Faux Columns -->
		 <div id="faux">
		 
		       <!-- Begin Left Column -->
		       <div id="leftcolumn">
		       
<?php

db_connect();
$result = mysql_query("SELECT * FROM `ext` WHERE data='FORCE_LAST' LIMIT 1");
$force_last = mysql_result($result, null, "uts");
db_disconnect();

function getExt($data, $maxmin) {
db_connect();	
$query = "SELECT * FROM `ext` WHERE data='".$data."' AND maxmin='".$maxmin."' LIMIT 1";
$result = mysql_query($query) or die("query failed: " . mysql_error());
db_disconnect();
if(mysql_num_rows($result) > 0) {
	$value = mysql_result($result, null, "value");
	$uts = mysql_result($result, null, "uts");
} else {
	die("Searched data did not exist!");
}
return array ($value, $uts);
}

list ($max_temp, $max_temp_uts) = getExt("temp", "1");
list ($min_temp, $min_temp_uts) = getExt("temp", "0");

list ($max_prss, $max_prss_uts) = getExt("pressure", "1");
list ($min_prss, $min_prss_uts) = getExt("pressure", "0");


?>
			   
<b><h2>Station Records</h2></b><br>

<h3>The last forced update of records was at: <?php echo date("G:i, F j Y", $force_last) ?></h3><br>
<i><font size="2">The records system should update automatically. Should it ever need refreshing, this can be done by forcing an update. Only administrators can do this. If you think a forced update is required, send us a note using the contact form.</font></i><br>

<br>
<b>Temperature</b><br>
The highest temperature recorded was: <b><?php echo $max_temp . "C at " . date("G:i, F j Y", $max_temp_uts); ?></b><br>
The lowest temperature recorded was: <b><?php echo $min_temp . "C at " . date("G:i, F j Y", $min_temp_uts); ?></b><br><br>

<b>Pressure</b><br>
The highest pressure recorded was: <b><?php echo $max_prss . "mb at " . date("G:i, F j Y", $max_prss_uts); ?></b><br>
The lowest pressure recorded was: <b><?php echo $min_prss . "mb at " . date("G:i, F j Y", $min_prss_uts); ?></b><br><br>


		       
			   <div class="clear"></div>
			   
		       </div>
		       <!-- End Left Column -->
		 
		       <!-- Begin Right Column -->
		       <div id="rightcolumn">
		 
		             <?php include("menu.php"); ?>
							
				<div class="clear"></div>
				
		       </div>
		       <!-- End Right Column -->
			   
         </div>	   
         <!-- End Faux Columns --> 
		 
   </div>
   <!-- End Wrapper -->
</body>
</html>
