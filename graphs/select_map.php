<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Weather Maps</title>
</head>

<body>

<?php
//version v0.9

	
	$start_day = $_GET['sd'] or $start_day=1;
	$start_mon = $_GET['sm'] or $start_mon=1;
	$start_yr = $_GET['sy'] or $start_yr=2008;
	
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
	
	
	$uts_start = @mktime(0,0,0,$start_mon,$start_day,$start_yr);
	IF ($_GET['n'] ==! 'on'){$uts_end = @mktime(0,0,0,$end_mon,$end_day,$end_yr);}else{$uts_end = time();}
	
	if ($uts_end > time()){$uts_end=time();}
	if ($uts_start < 1215684000){$uts_start=1215684000;}
	
	IF ($uts_start > $uts_end) {die ("Start date after end date");}
	IF ($uts_start == ''){die ("Error in entered start date");}
	IF ($uts_end == ''){die ("Error in entered end date");}
	



if ($_GET['size']=='iss'){
$img_h = 200;
$img_w = 180;
}else{
if ($_GET['size']=='ism'){
$img_h = 276;
$img_w = 250;
}else{
$img_h = 500;
$img_w = 453;
}}



echo "<table>";




echo "<tr>";
$uts = $uts_start;

while ($uts<=$uts_end){


	echo "<td>"; 
	echo date('D jS M y',$uts); 
	echo ", 12 noon</td>";

	$uts = mktime(0, 0, 0, date("m",$uts)  , date("d",$uts)+1, date("Y",$uts));

}
echo "</tr>";


if ($_GET['p'] == 'on'){
echo "<tr>";
$uts = $uts_start;

while ($uts<=$uts_end){


	echo "<td><img src=\"../archive_maps/pressure_"; 
	echo date('ymd',$uts); 
	echo "12.jpg\" width=\"".$img_w."\" height=\"".$img_h."\" /></td>";

	$uts = mktime(0, 0, 0, date("m",$uts)  , date("d",$uts)+1, date("Y",$uts));

}
echo "</tr>";
}


if ($_GET['t'] == 'on'){
echo "<tr>";
$uts = $uts_start;

while ($uts<=$uts_end){


	echo "<td><img src=\"../archive_maps/temperature_"; 
	echo date('ymd',$uts); 
	echo "12.jpg\" width=\"".$img_w."\" height=\"".$img_h."\" /></td>";

	$uts = mktime(0, 0, 0, date("m",$uts)  , date("d",$uts)+1, date("Y",$uts));

}
echo "</tr>";
}



if ($_GET['m'] == 'on'){
echo "<tr>";
$uts = $uts_start;

while ($uts<=$uts_end){


	echo "<td><img src=\"../archive_maps/rain_"; 
	echo date('ymd',$uts); 
	echo "12.jpg\" width=\"".$img_w."\" height=\"".$img_h."\" /></td>";

	$uts = mktime(0, 0, 0, date("m",$uts)  , date("d",$uts)+1, date("Y",$uts));

}
echo "</tr>";
}



if (($_GET['ws'] == 'on') or ($_GET['wsa'] == 'on') or ($_GET['wd'] == 'on')){
echo "<tr>";
$uts = $uts_start;

while ($uts<=$uts_end){


	echo "<td><img src=\"../archive_maps/wind_"; 
	echo date('ymd',$uts); 
	echo "12.jpg\" width=\"".$img_w."\" height=\"".$img_h."\" /></td>";

	$uts = mktime(0, 0, 0, date("m",$uts)  , date("d",$uts)+1, date("Y",$uts));

}
echo "</tr>";
}


echo "</table>";

?>
</body>
</html>