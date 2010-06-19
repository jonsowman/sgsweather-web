<?php
define("IN_MODULE",true);

require_once ("../includes/config.inc.php");
require_once ("../includes/functions.inc.php");

include ("../includes/jpgraph/jpgraph.php");
include ("../includes/jpgraph/jpgraph_line.php");
include ("../includes/jpgraph/jpgraph_date.php");

$daysrange = $_GET['range'] or $daysrange = 1;
$daysrange=$daysrange*1;

$aa = $_GET['aa'] or $aa = 1;
$xmarks = $_GET['xm'] or $xm = 0;

if (!is_integer($daysrange) || !is_numeric($daysrange) || $daysrange < 1 or $daysrange > 30) {
	$daysrange = 1;
}

// WORK OUT THE LOWEST UTS IN THE ONE DAY RANGE
$uts_now = time();
$uts_lb = $uts_now - (24 * 60 * 60 * $daysrange);

// SQL CONNECT AND QUERIES

db_connect();
$query = "SELECT * FROM records WHERE uts >= " . $uts_lb . " ORDER BY id ASC LIMIT 1";
$e_check = mysql_query($query) or die ("query failed");
$uts_e = mysql_result($e_check,null,"uts");

$query = "SELECT * FROM records WHERE uts >= " . $uts_lb . " ORDER BY id ASC";
$result = mysql_query($query) or die ("query failed");
db_disconnect();

// LOAD DATA INTO ARRAYS FROM RESULT RESOURCES

$arr_data = array();      // primary src
$lux_data = array();      // RETURN THIS
$arr_xticks = array();

while($row = mysql_fetch_array($result)){
	array_push($arr_data, ($row['light']/255)*100);
	array_push($lux_data, $row['light2']);
	array_push($arr_xticks, $row['uts']);
}

// Create the graph. These two calls are always required
$graph = new Graph(800,600,"auto");	     // SCALE
$graph->SetScale("datint",0,100);            // SET THE SCALE MANUALLY
//$graph->SetY2Scale("log",0,10000);
$graph->SetY2Scale("log");
$graph->SetMargin(50,50,50,125);

// SET UP CUSTOM SETTINGS

$graph->xaxis->SetLabelAngle(90);
//$graph->xaxis->SetTextLabelInterval(10);
$graph->xaxis->scale-> SetTimeAlign(MINADJ_15);   // ALIGN TO A 15 MIN INTERVAL
$graph->xaxis->scale-> SetDateFormat( 'H:i');    // SET THE FORMAT FOR THE X-AXIS UTS CONVERSION

// SET UP TITLES

$graph->title->Set("Light Levels - " . date("G:i, F j", $uts_e) . " to " . date("G:i, F j", $uts_now));
$graph->xaxis->SetTitle('Time','middle'); 
$graph->yaxis->SetTitle('Light Level / %','middle');
$graph->y2axis->SetTitle('Light Level / lx','middle');
$graph->xaxis->SetTitleMargin('75'); 
$graph->xaxis->scale-> SetDateFormat('G:i - d/m');
if ($aa == 1) { 
	//$graph->img->SetAntiAliasing();
}

$graph->yaxis->SetColor("darkgreen");
$graph->y2axis->SetColor("red");

// Create the linear plot
$lineplot=new LinePlot($arr_data, $arr_xticks);       // Y-DATA, X-DATA (TIME)
$lineplot2=new LinePlot($lux_data, $arr_xticks);

// PLOT OPTIONS
$lineplot->SetColor("darkgreen");                // LINE COLOUR
$lineplot->SetWeight(1);                   // SET LINE THICKNESS
if($xmarks == 1) {
	$lineplot->mark->SetType(MARK_X, 0.3);     // POINTS STYLE
	$lineplot->mark->SetColor("darkgreen");          // POINTS COLOUR
	$lineplot->mark->Show();                   // SET THEM TO DISPLAY
}

$lineplot2->SetColor("red");                // LINE COLOUR
$lineplot2->SetWeight(1);                   // SET LINE THICKNESS
if($xmarks == 1) {
	$lineplot2->mark->SetType(MARK_X, 0.3);     // POINTS STYLE
	$lineplot2->mark->SetColor("red");          // POINTS COLOUR
	$lineplot2->mark->Show();                   // SET THEM TO DISPLAY
}


$lineplot->SetLegend ("Light / %");
$lineplot2->SetLegend("Light / lx");

// Add the plot to the graph
$graph->Add($lineplot);
$graph->AddY2($lineplot2);

//sort the gridlines
$graph->ygrid->Show (false,true);
$graph->y2grid->Show (true,true);

// Display the graph
$graph->Stroke();


?>
