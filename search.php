<?php
define("IN_MODULE",true);
require_once("includes/config.inc.php");
require_once("includes/functions.inc.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $c_title; ?> - Search</title>
<link rel="stylesheet" type="text/css" href="main.css" />
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

<h2>Search</h2><br>
		       
<?php

db_connect();
$query = "SELECT * from `records`";
if(mysql_num_rows(mysql_query($query)) == 0){
	db_disconnect();
	$isdata = false;
} else {
	db_disconnect();
	$isdata = true;
}

$s_active = false;

if (isset($_POST['Submit']) && isset($_POST['year']) && isset($_POST['mins']) && isset($_POST['hours']) && isset($_POST['date']) && isset($_POST['month']) && is_numeric($_POST['month']) && is_numeric($_POST['date']) && is_numeric($_POST['year']) && is_numeric($_POST['mins']) && is_numeric($_POST['hours'])) {       // search called
	$s_active = true;
	$hours = $_POST['hours'];
	$mins = $_POST['mins'];
	$date = $_POST['date'];
	$month = $_POST['month'];
	$year = $_POST['year'];
} else {
	echo "Please enter your search below.<br><br>";
}

// if the database is empty, warn the user that this aint gonna work
if(!$isdata) {
	echo "<b>Sorry, the database is empty and search queries will fail.</b><br><br>";
}

?>

<form name="form1" action="search.php" method="post">
<input type="text" size=2 name="hours" <?php if($s_active) echo "value='" . $hours ."'"; ?>>
:
<input type="text" size=2 name="mins" <?php if($s_active) echo "value='" . $mins ."'"; ?>>
 Time (hours:mins)<br>
<input type="text" size=2 name="date" <?php if($s_active) echo "value='" . $date ."'"; ?>>
/
<input type="text" size=2 name="month" <?php if($s_active) echo "value='" . $month ."'"; ?>>
/
<input type="text" size=5 name="year" <?php if($s_active) echo "value='" . $year ."'"; ?>> Date (dd/mm/yyyy)<br><br>
<input type="submit" name="Submit" value="Submit">
</form>

<?php
if($s_active) {

	$searched_uts = mktime($hours, $mins, 0, $month, $date, $year);
	$searched_uts_bound_l = $searched_uts - (7.5 * 60);
	$searched_uts_bound_u = $searched_uts + (7.5 * 60);
	db_connect();
	$query = "SELECT * FROM `records` WHERE uts > " . $searched_uts_bound_l . " AND uts < " . $searched_uts_bound_u . " ORDER BY id ASC";
	$result = mysql_query($query) or die ("Query failed: " . mysql_error());
	db_disconnect();
	$rows = mysql_num_rows($result);

	if($rows <= 0) {
		echo "<br><b>No results were found in the specified time range.</b>";
	} else {

		while($row = mysql_fetch_array($result)){
			echo "<br><h3>Time: " . date("G:i", $row['uts']) . "</h3><br>";
			echo "Light: ";
			printf("%01.1f", ($row['light']/255)*100);
			echo "% and ";
			if($row['light2'] >= 100) { $T_FORMAT="%1.0f"; } else { $T_FORMAT="%1.2f"; }
			printf($T_FORMAT, $row['light2']);
			echo "lx<br>";
			
			echo "Exposed Temperature: ";
			printf("%01.2f", $row['temp']);
			echo "&#176;C<br>";

			echo "Sheltered Temperature: ";
			printf("%01.2f", $row['temp2']);
			echo "&#176;C<br>";
			
			echo "Moisture: ";
			printf("%01.1f", ($row['moisture']/255)*100);
			echo "%<br>";
			
			echo "Pressure: ";
			printf("%01.1f", $row['pressure']);
			echo "mb<br>";
			
			echo "Wind Speed: ";
			printf("%01.0f", $row['wind_spd']);
			echo " RPM<br>";
			
			echo "Wind Direction: ";
			printf("%01.0f", $row['wind_dir']);
			echo " Degrees<br>";

			echo "Humidity: ";
			printf("%01.0f", ($row['humidity']/255)*100);
			echo "%<br>";

			echo "System Voltage: ";
			printf("%01.2f", $row['batt']);
			echo "V<br>";

			echo "<i><font size=1>UTS ";
			printf("%01.0f", $row['uts']);
			echo "</font></i><br>";
			
			echo "<br>";
		}

	}
}



?>
<i><br>Please Note: Data is only available for the last month. For those of you who know how this thing works, this form will only search for data currently in the database and not that which is archived. CSV archive search implementation is on our todo list.</i>
		       
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
