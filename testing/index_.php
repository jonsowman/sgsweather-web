<?php
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
<style>
td { padding:2px; }

#aag { position:absolute; left:575px; top:45px; width:150px; height:150px; clear:both }

</style>
</head>

<body>
<link href="http://weather.hexoc.com/rss.php" rel="alternate" type="application/rss+xml" title="SGS Weather Updates" />



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

define("IN_MODULE",true);
require_once("includes/config.inc.php");
require_once("includes/functions.inc.php");

db_connect();

$query = "SELECT * FROM records ORDER BY `id` DESC LIMIT 1";
$result = mysql_query($query) or die("Query failed with error: " . mysql_error());
$row = mysql_fetch_array($result) or die(mysql_error());
db_disconnect();

$uts_now = time();
$last_read = $row['uts'];
$next_read = $last_read + (60*15);
while (($next_read + 60) < $uts_now) {
	$next_read = $next_read + (60*15);
}

echo "<h2><center>Latest Update</center></h2>  <br>";

echo "The last update was at:<b> " . date("G:i", $last_read) . " </b>on " . date("F j, Y", $row['uts']) . "<br><br>";

$c_date = date("d");
$c_year = date("Y");
$c_month = date("m");
$c_hr = date("H");

$uts_mln = mktime(0, 0, 0, $c_month, $c_date, $c_year);

db_connect();

$query = "SELECT * FROM `records` WHERE uts >= '" . $uts_mln . "' ORDER BY temp DESC LIMIT 1";

$result = mysql_query($query) or die ("Max temp query failed" . mysql_error());
$MT_r = mysql_result($result, null, "temp");
$MT_t = mysql_result($result, null, "uts");
db_disconnect();

?>


<table border="1" width="75%">

<tr>
<td style="background-color:#CCFFCC;">Light (%)</td>
<td><?php printf("%01.1f", ($row['light']/255)*100); ?></td>
</tr>

<tr>
<td style="background-color:#CCFFCC;">Temperature (&#176;C)</td>
<td><?php printf("%01.1f", ($row['temp'])); ?></td>
</tr>

<tr>
<td style="background-color:#CCFFCC;">Moisture (%)</td>
<td><?php printf("%01.1f", ($row['moisture']/255)*100); ?></td>
</tr>

<tr>
<td style="background-color:#FF6666;">Pressure (Millibars)</td>
<td><?php
//printf("%01.0f", $row['pressure']);
echo "Unavailable";
?></td>
</tr>

<tr>
<td style="background-color:#CCFFCC;">Wind Speed (RPM)</td>
<td><?php printf("%01.0f", $row['wind_spd']); ?></td>
</tr>

<tr>
<td style="background-color:#CCFFCC;">Wind Direction (Degrees)</td>
<td><?php printf("%01.0f", $row['wind_dir']); ?></td>
</tr>

<tr>
<td style="background-color:#FF6666;">Humidity (%)</td>
<td>Unavailable</td>
</tr>

</table>

<div id="aag">
<h3>At A Glance</h3>
<?php include("testing/minifeed.php"); ?>
</div>

<br>

<?php

if($c_hr > 7) {
	echo "The highest temperature so far today was "; 
	printf("%01.1f", $MT_r);
	echo "&#176;C at " . date("G:i", $MT_t) . ".<br>";
}

echo "<br>Next update expected at: <b>" . date("G:i", $next_read) . "</b>";
echo "<br><br>";
echo "<p style='border: medium solid red; padding:2px;'><b>Status: </b>" . $c_status . "</p>";

?>

		       
			   <div class="clear"></div>
			   
		       </div>
		       <!-- End Left Column -->
		 
		       <!-- Begin Right Column -->
		       <div id="rightcolumn">
		 
		             <?php include($c_siteroot . "/menu.php"); ?>
							
				<div class="clear"></div>
				
		       </div>
		       <!-- End Right Column -->
			   
         </div>	   
         <!-- End Faux Columns --> 
		 
   </div>
   <!-- End Wrapper -->
</body>
</html>
