<?php
define("IN_MODULE",true);
require_once("includes/config.inc.php");
require_once("includes/functions.inc.php");


$action = $_GET['action'];

switch($action) {

case "test":
    echo "OK";
    break;

case "getLastUTS":
    $query = "SELECT * FROM records ORDER BY uts DESC LIMIT 1";
    $result = mysql_query($query) or die("Query failed in ajax.php"); // this is naughty
    $row = mysql_fetch_array($result);
    $last_uts = $row['uts'];
    echo $last_uts;
break;

case "updateTimes":

    $uts_now = time();
    $c_date = date("d");   // midnight last night
    $c_year = date("Y");
    $c_month = date("m");
    $c_hr = date("H");
    db_connect();
    $query = "SELECT * FROM records ORDER BY `id` DESC LIMIT 1";
    $result = mysql_query($query) or die ("Query failed with error" . mysql_error());
    $row = mysql_fetch_array($result);
    db_disconnect();

    $last_read = $row['uts'];
    $uts_mln = mktime(0, 0, 0, $c_month, $c_date, $c_year); // midnight last night

    $timeString;

    if($last_read == 0) { echo "0"; die(); } else {

        $next_read = $last_read + (60*15);
        while(($next_read + 60) < $uts_now) {
            $next_read = $next_read + (60*15);
        }

        $uts_ago = $uts_now - $last_read;
        $ago = round(($uts_ago/60),0);
    
        $timeString = $last_read . "|";
        if($last_read > $uts_mln) {
             $timeString .= "The most recent update was <b>today</b> at<b> " . date("G:i", $last_read) . "</b>";
	} else {
		$timeString .= "The most recent update was at:<b> " . date("G:i", $last_read) . " </b>on " . date("F j, Y", (int)$row['uts']);
	}

        $timeString .= "|Next update expected at <b>" . date("G:i", $next_read) . "</font></b>";


        if ($ago <= 60) {
		if($ago == 1) {
			$timeString .= "| (<b>" . $ago . " minute</b> ago)";
		} else if($ago == 0) {
			$timeString .= "| (a moment ago)";
		} else {
			$timeString .= "| (<b>" . $ago . " minutes</b> ago)";
		}
	} else {
            echo "0";
            die();
        }

        $mt_next = number_format(($next_read - $uts_now)/60,0);

        if($mt_next == 1) {
            $timeString .= "| (in <b>" . $mt_next . " minute</b>)";
        } else if($mt_next == 0) {
            $timeString .= "| (any second now)";
        } else {
            $timeString .= "| (in <b>" . $mt_next . " minutes</b>)";
        }
    
        echo $timeString;

    }

    break;


case getDataString:

    $uts_now = time();
    $c_date = date("d");   // midnight last night
    $c_year = date("Y");
    $c_month = date("m");
    $c_hr = date("H");

    $uts_mln = mktime(0, 0, 0, $c_month, $c_date, $c_year); // midnight last night

    db_connect();
    $query = "SELECT * FROM records ORDER BY `id` DESC LIMIT 1";
    $result = mysql_query($query) or die ("Query failed with error" . mysql_error());
    $row = mysql_fetch_array($result);

    $query = "SELECT * FROM `records` WHERE uts >= '" . $uts_mln . "' ORDER BY temp2 DESC LIMIT 1";

    $result = mysql_query($query);// or die ("Max temp query failed" . mysql_error());
    $r_rows = mysql_num_rows($result);
    if ($r_rows > 0) {
            $MT_r = mysql_result($result, null, "temp2"); // use temp2 until temp works
            $MT_t = mysql_result($result, null, "uts");
    }


    $query = "SELECT * FROM `records` WHERE uts >= '" . $uts_mln . "' ORDER BY temp2 ASC LIMIT 1";

    $result = mysql_query($query);// or die ("Min temp query failed" . mysql_error());
    $r_rows = mysql_num_rows($result);
    if ($r_rows > 0) {
            $MT_m = mysql_result($result, null, "temp2");
            $MT_n = mysql_result($result, null, "uts");
    }

    $c_hr = date("H");
    $tempString;

    if ($r_rows > 0) {
            if($c_hr > 7) {
                    $tempString .= "High: <b>" . sprintf("%01.1f", $MT_r) . "</b>&#176;C at <b>" . date("G:i", $MT_t) . "</b>, ";

                    $tempString .= "Low: <b>" . sprintf("%01.1f", $MT_m) . "</b>&#176;C at <b>" . date("G:i", $MT_n) . "</b>.";

            } else {
                $tempString .= "Will be displayed after 7am";
            }
    } else {
        $tempString .= "Will be displayed after 7am";
    }



    db_disconnect();
 
    // we need to construct a |-delimited string
    $dataString = $row['uts'];

    if($row['light2'] >= 100) { $T_FORMAT="%1.0f"; } else { $T_FORMAT="%1.2f"; }
	$dataString .= "|" . sprintf($T_FORMAT, $row['light2']);
	$dataString .= sprintf(" / %01.1f", ($row['light']/255)*100);

    $dataString .= "|" . sprintf("%01.1f", ($row['temp']));
    $dataString .= "|" . sprintf("%01.1f", ($row['temp2']));
    $dataString .= "|" . sprintf("%01.1f", ($row['pressure']));
    $dataString .= "|" . sprintf("%01.0f", ($row['rain'])); 
    $dataString .= "|" . sprintf("%01.0f", ($row['wind_spd'])*$windspd_rpm_mph_scale);
    $dataString .= "|" . sprintf("%01.0f", ($row['wind_dir']));
    $dataString .= "|" . sprintf("%01.1f", ($row['moisture']/255)*100); 
    $dataString .= "|" . sprintf("%01.1f", ($row['humidity']/255)*100); 
    $dataString .= "|" . sprintf("%01.2f", $row['batt']);
    $dataString .= "|" . resolveIP($row['ip']);

    echo $dataString . "|" . $tempString;
    
    break;

} // close switch


?>
