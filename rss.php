<?php


function cleanText($intext) {

    return utf8_encode(

        htmlspecialchars(

            stripslashes($intext)));

}

 

// set the file's content type and character set
//header("Content-Type: text/xml;charset=utf-8");



echo <<<end
<?xml version="1.0" encoding="utf-8"?>
end;


define("IN_MODULE",true);
require_once("includes/config.inc.php");
require_once("includes/functions.inc.php");
db_connect();

$query = "SELECT * FROM records ORDER BY `id` DESC LIMIT 1";
$result = mysql_query($query) or die("Query failed with error: " . mysql_error());
$row = mysql_fetch_array($result) or die(mysql_error());

$uts_now = time();
$last_read = $row['uts'];
$next_read = $last_read + (60*15);

db_disconnect();
?>

<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
<channel>
<atom:link href="http://weather.hexoc.com/rss.php" rel="self" type="application/rss+xml" />

<title>SGS Weather</title>
<link>http://weather.hexoc.com</link>
<description>Up to date weather readings from SGS</description>
<lastBuildDate><?php echo date('r',$row['uts']); ?></lastBuildDate>
<language>en-us</language>

<item>
<title>View Current Data - <?php echo date('D, j M Y G:i',$row['uts']); ?></title>
<link>http://weather.hexoc.com</link>
<guid>http://weather.hexoc.com</guid>
<pubDate><?php echo date('r',$row['uts']); ?></pubDate>
<description>Goto Weather Page</description>
</item>

<item>
<title>Light - <?php
//printf("%01.1f", ($row['light']/255)*100);
echo printf("%01.1f", $row['light2']) . " Lux";
?></title>
<link>http://weather.hexoc.com/graphs/lightpclx_c.php</link>
<guid>http://weather.hexoc.com/graphs/lightpclx_c.php</guid>
<pubDate><?php echo date('r',$row['uts']); ?></pubDate>
<description>Light Levels</description>
</item>

<item>
<title>Temperature (Exp/Shl) - <?php
printf("%01.1f", ($row['temp']));
echo "&#176;" . "C / ";
printf("%01.1f", ($row['temp2'])); 
echo "&#176;" . "C";
?></title>
<link>http://weather.hexoc.com/graphs/1d/temp2.php</link>
<guid>http://weather.hexoc.com/graphs/1d/temp2.php</guid>
<pubDate><?php echo date('r',$row['uts']); ?></pubDate>
<description>Temperature (Exposed/Sheltered)</description>
</item>

<item>
<title>Pressure - <?php printf("%01.0f", ($row['pressure'])); ?>mb</title>
<link>http://weather.hexoc.com/graphs/1d/pressure.php</link>
<guid>http://weather.hexoc.com/graphs/1d/pressure.php</guid>
<pubDate><?php echo date('r',$row['uts']); ?></pubDate>
<description>Pressure</description>
</item>

<item>
<title>Wind - <?php
printf("%01.0f", ($row['wind_spd']*$windspd_rpm_mph_scale));
echo " MPH";  
if($row['wind_spd'] != 0) {
    echo " at ";
    printf("%01.0f", $row['wind_dir']); 
    echo "&#176;";
}
?></title>
<link>http://weather.hexoc.com/graphs/1d/windspd.php</link>
<guid>http://weather.hexoc.com/graphs/1d/windspd.php</guid>
<pubDate><?php echo date('r',$row['uts']); ?></pubDate>
<description>Wind</description>
</item>

<item>
<title>Humidity - <?php printf("%01.1f", ($row['humidity']/255)*100); ?>%</title>
<link>http://weather.hexoc.com/graphs/1d/humidity.php</link>
<guid>http://weather.hexoc.com/graphs/1d/humidity.php</guid>
<pubDate><?php echo date('r',$row['uts']); ?></pubDate>
<description>Humidity</description>
</item>

<item>
<title>Moisture - <?php printf("%01.1f", ($row['moisture']/255)*100); ?>%</title>
<link>http://weather.hexoc.com/graphs/1d/moisture.php</link>
<guid>http://weather.hexoc.com/graphs/1d/moisture.php</guid>
<pubDate><?php echo date('r',$row['uts']); ?></pubDate>
<description>Moisture Levels</description>
</item>

<item>
<title>System Voltage - <?php printf("%01.2f", $row['batt']); ?>V</title>
<link>http://weather.hexoc.com/graphs/1d/batt.php</link>
<guid>http://weather.hexoc.com/graphs/1d/batt.php</guid>
<pubDate><?php echo date('r',$row['uts']); ?></pubDate>
<description>System Voltage</description>
</item>

</channel>
</rss>
