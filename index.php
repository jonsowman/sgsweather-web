<?php

/*
SGS WEATHER STATION SERVER SIDE CODING
JON SOWMAN
2009
ALL RIGHTS RESERVED
*/

define("IN_MODULE",true); // why did you call it this? weirdo.
require_once("includes/config.inc.php");
require_once("includes/functions.inc.php");


db_connect();
$query = "SELECT * FROM records ORDER BY `uts` DESC LIMIT 1";
$result = mysql_query($query) or die("Query failed with error: " . mysql_error());
$row = mysql_fetch_array($result) or die(mysql_error());
db_disconnect();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script type="text/javascript" src="includes/jquery.js"></script>
<script type="text/javascript" src="includes/wx.js"></script>
<script>
var load_uts = 0; // the uts of the displayed data
var auto_int;     // handle for the interval
var auto_status = 1; // the soft auto update status (defined by focus/blur)
var auto_status_force = 1; // forced auto update status (defined by user)
var last_check_uts = getUTS(); // we can get away with local UTS here
var secs_since_last = 0;

$(document).ready(function() {
    loadFn();
});

$(window).blur(function() {
    if(auto_status != 0 && auto_status_force != 0) {
        clearInterval(auto_int);
        $("#autoopt").html("Auto Update Off - Focus on this window to turn on");
        auto_status = 0;
    }
});

$(window).focus(function() {
    if(auto_status != 1 && auto_status_force != 0) {
        current_uts = getUTS();
        // do an update now if we know its old
        if((current_uts - last_check_uts)/60 > 1) {
            updateTimes();
        }
        auto_int = setInterval("updateTimes()",30000);
        $("#autoopt").html("Auto Update On - <a href='#' onClick='autoOff()'>Turn Auto Update Off</a>");
        auto_status = 1;
    }
});

</script>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <meta name="description" content="Sutton Grammar School Weather Station">
    <meta name="keywords" content="sutton, grammar, school, weather, station, sgs, hexoc">
    <meta name="author" content="Jon Sowman and Matt Brejza">
    <meta name="email" content="webmaster@hexoc.com">
    <meta name="Distribution" content="Global">
    <meta name="Rating" content="General">
    <meta name="Revisit-after" content="1 Day">
	<meta name="verify-v1" content="mkvqdcsIgDVgX1TIRuUyb2XG0qAo3jbZdnrISlX/SMk=" />
<title><?php echo $c_title; ?></title>
<link rel="stylesheet" type="text/css" href="main.css" />
<style>
td { padding:2px; }

#aag { position:absolute; left:515px; top:0px; width:165px; height:60px; clear:both; border:2px solid black; padding:2px }

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

$uts_now = time();
$last_read = $row['uts'];
	if ($last_read == 0 ) { 
	$edbmsg = "<h2>The database is empty :(</h2><br><br>This would indicate the station has been offline for two months or more. Hopefully it'll be back soon.<br><br><b>Last status: " . stripslashes($c_status) . "</b><br><br>If you are an admin, please click <a href='/admin'>here</a> for adminy things.";
} else {
	$isdata = true;
}
$next_read = $last_read + (60*15);
while (($next_read + 60) < $uts_now) {
	$next_read = $next_read + (60*15);
}

$uts_ago = $uts_now - $last_read;
$ago = round(($uts_ago/60),0);


$c_date = date("d");   // midnight last night
$c_year = date("Y");
$c_month = date("m");
$c_hr = date("H");

$uts_mln = mktime(0, 0, 0, $c_month, $c_date, $c_year); // midnight last night

// let's only try and display stuff if stuff exists to display

if($isdata) {

    echo "<h2>Latest Update</h2>  <br><br><font size=3><span id='timelast'>"; // put in statics
        if($last_read > $uts_mln) {
            echo "<font size=3>The most recent update was <b>today</b> at<b> ";
            echo date("G:i", $last_read) . "</font></b>";
	} else {
            echo "<font size=3>The most recent update was at:<b> ";
            echo date("G:i", $last_read) . " </b>on " . date("F j, Y", (int)$last_read) . "</font>"; // there used to be a bug here
	}
    echo "</span></font><span id='ago'>";
        
	if ($ago <= 60) {
		if($ago == 1) {
			echo " (<b>" . $ago . " minute</b> ago)";
		} else if($ago == 0) {
			echo " (a moment ago)";
		} else {
			echo " (<b>" . $ago . " minutes</b> ago)";
		}
        }
         
        echo "</span>&nbsp<a href='#' onClick='forceRefresh();'><img id='refreshimg' src='images/refresh.gif' border=0></a>";
	echo "<font size=1><span id='sincelastwrapper'><br>Last checked for updates <b><span id='sincelast'>a moment</span></b> ago.</span></font><br><br>";

	db_connect();

	$query = "SELECT * FROM `records` WHERE uts >= '" . $uts_mln . "' ORDER BY temp2 DESC LIMIT 1";

	$result = mysql_query($query);// or die ("Max temp query failed" . mysql_error());
	$r_rows = mysql_num_rows($result);
	if ($r_rows > 0) {
		$MT_r = mysql_result($result, null, "temp2");
		$MT_t = mysql_result($result, null, "uts");
	}
	db_disconnect();

	db_connect();

	$query = "SELECT * FROM `records` WHERE uts >= '" . $uts_mln . "' ORDER BY temp2 ASC LIMIT 1";

	$result = mysql_query($query);// or die ("Min temp query failed" . mysql_error());
	$r_rows = mysql_num_rows($result);
	if ($r_rows > 0) {
		$MT_m = mysql_result($result, null, "temp2");
		$MT_n = mysql_result($result, null, "uts");
	}
	db_disconnect();

	?>

        <p id="infobar" style='display:none; background-color:#AEEEEE; padding:2px;'><b>The information below is <span id="infostatus">up to date</span>.</b></p>

	<table id="infotable" border="1" width="85%">

	<tr>
	<td width="60%" style="background-color:#CCFFCC;">Light Level (<a href="includes/lux.php" target="_blank">Lux</a>)/(%)</td>
	<td id="ajaxlight" style="background-color:#CCFFCC;"><?php
	if($row['light2'] >= 100) { $T_FORMAT="%1.0f"; } else { $T_FORMAT="%1.2f"; }
	printf($T_FORMAT, $row['light2']);
	printf(" / %01.1f", ($row['light']/255)*100);
	?></td></tr>

	<tr>
	<td style="background-color:#CCFFCC;">Temperature (&#176;C)</td>
        <td id="ajaxtemp" style="background-color:#<?php if($row['temp']>80 || $row['temp=']==0) echo "FF3D3D"; else echo "CCFFCC"; ?>;"><?php
        printf("%01.1f", ($row['temp']));
        $pred_temp = eureqa($c_eureqa_alg, $row['light2'], $row['pressure']);
        echo " <small>(Predicted: " . $pred_temp;
        //echo " - Error: " . calcPercentageError($pred_temp, $row['temp2'])."%";
        echo ")</small>";
        ?></td>
	</tr>

	<tr>
        <td style="background-color:#CCFFCC;">Sheltered Temperature (&#176;C)</td> 
        <td id="ajaxtemp2" style="background-color:#<?php if($row['temp2']>80) echo "FF3D3D"; else echo "CCFFCC"; ?>;"><?php printf("%01.1f", ($row['temp2'])); ?></td>
	</tr>

	<tr>
	<td style="background-color:#CCFFCC;">Pressure (Millibars)</td>
	<td id="ajaxpressure" style="background-color:#CCFFCC;"><?php
	printf("%01.1f", $row['pressure']);
	?></td>
	</tr>

	<!--<tr>
	<td style="background-color:#CCFFCC;">Rainfall (Tips)</td>
	<td id="ajaxrain" style="background-color:#FF3D3D;"><?php printf("%01.0f", ($row['rain'])); ?></td>
	</tr>-->

	<tr><!-- $windspd_rpm_mph_scale *  -->
	<td style="background-color:#CCFFCC;">Wind Speed (MPH)</td>
	<td id="ajaxwind_spd" style="background-color:#CCFFCC;"><?php printf("%01.0f", ($row['wind_spd'])*$windspd_rpm_mph_scale); ?></td>
	</tr>

	<tr>
	<td style="background-color:#CCFFCC;">Wind Direction (Degrees)</td>
	<td id="ajaxwind_dir" style="background-color:#FF3D3D;"><?php printf("%01.0f", $row['wind_dir']); ?></td>
	</tr>

	<tr>
	<td style="background-color:#CCFFCC;">Moisture (%)</td>
	<td id="ajaxmoisture" style="background-color:#FF3D3D;"><?php printf("%01.1f", ($row['moisture']/255)*100); ?></td>
	</tr>

	<tr>
	<td style="background-color:#CCFFCC;">Humidity (%)</td>
	<td id="ajaxhumidity" style="background-color:#FF3D3D;"><?php printf("%01.1f", ($row['humidity']/255)*100); ?></td>
	</tr>

	<tr>
	<td style="background-color:#CCFFCC;">System Voltage (V)</td>
	<td id="ajaxbatt" style="background-color:#CCFFCC;"><?php printf("%01.2f", $row['batt']); ?></td>
	</tr>

        <tr>
	<td style="background-color:#CCFFCC;">Server (<a href="#" onClick="javascript:window.open('includes/servers.php','serversh','width=600,height=400,scrollbars=1')">What's this?</a>)</td>
        <td id="ajaxserver" style="background-color:#CCFFCC;"><?php
        echo resolveIP($row['ip']);
        ?></td>
	</tr>

	</table>
        <font size=1><span id="autoopt">Auto Update On - <a href="#" onClick="autoOff()">Turn Auto Update Off</a></span></font><br>

	<div id="aag">
	<h3><b><center>At A Glance<center></b></h3>
	<?php include("minifeed.php"); ?>
	</div>

	<br>

	<?php
	echo "<b>Today's temperatures since midnight:</b><br><span id='temps'>";
	if ($r_rows > 0) {
		if($c_hr > 7) {
			echo "High: <b>"; 
			printf("%01.1f", $MT_r);
			echo "</b>";
			echo "&#176;C at <b>" . date("G:i", $MT_t) . "</b>, ";

			echo "Low: <b>"; 
			printf("%01.1f", $MT_m);
			echo "</b>";
                        echo "&#176;C at <b>" . date("G:i", $MT_n) . "</b>.";

                } else {
                    echo "Will be displayed after 7am";
                }
	} else {
            echo "Will be displayed after 7am";
        }


	$mt_next = number_format(($next_read - $uts_now)/60,0);

        echo "</span><br><br><font size=3><span id='timenext'>";            
        echo "Next update expected at <b>" . date("G:i", $next_read);
        echo "</font></b><span id='next'>";
        
	if($mt_next == 1) {
		echo " (in <b>" . $mt_next . " minute</b>).";
	} else if($mt_next == 0) {
		echo " (any second now).";
	} else {
		echo " (in <b>" . $mt_next . " minutes</b>).";
	}
        
	echo "</span><br><br>";
	echo "<p style='border: medium solid red; padding:2px;'><b>Status: </b>" . stripslashes($c_status) . "</p>";

} else {

// if we got to here, the db is empty. sad smiley.

	echo $edbmsg;

}

?>

		       
			   <div class="clear"></div>
			   
		       </div>
		       <!-- End Left Column -->
		 
		       <!-- Begin Right Column -->
		       <div id="rightcolumn">
		
		            <?php /*include($c_siteroot . "/menu.php"); */?>
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
