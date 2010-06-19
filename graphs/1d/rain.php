<?php
define("IN_MODULE",true);
require_once ("../../includes/config.inc.php");
require_once ("../../includes/functions.inc.php");

include ("../../includes/jpgraph/jpgraph.php");
include ("../../includes/jpgraph/jpgraph_line.php");
include ("../../includes/jpgraph/jpgraph_date.php");

// WORK OUT THE LOWEST UTS IN THE ONE DAY RANGE
$days_ago = $_GET['range'] or $days_ago = 1;
if ($days_ago > $c_max_graph_days){$days_ago = $c_max_graph_days;}
$uts_now = time();
$uts_onedayago = $uts_now - ($days_ago*60*60*24);


db_connect();
$query = "SELECT * FROM records WHERE uts >= " . $uts_onedayago . " ORDER BY id ASC";
$result = mysql_query($query) or die ("query failed");
db_disconnect();

$arr_data = array();
$arr_xticks = array();

while($row = mysql_fetch_array($result)){
	array_push($arr_data, $row['rain']);
	array_push($arr_xticks, $row['uts']);
}

// Create the graph. These two calls are always required
$graph = new Graph(800,600,"auto");	     // SCALE
$graph->SetScale("datint",0,20);            // SET THE SCALE MANUALLY

// SET UP CUSTOM SETTINGS

$graph->xaxis->SetLabelAngle(90);
//$graph->xaxis->SetTextLabelInterval(10);
$graph->xaxis->scale-> SetTimeAlign(MINADJ_15);   // ALIGN TO A 5 MIN INTERVAL
$graph->xaxis->scale-> SetDateFormat( 'H:i');    // SET THE FORMAT FOR THE X-AXIS UTS CONVERSION

// SET UP TITLES

$graph->title->Set("Rainfall - " . date("G:i, F j", $uts_onedayago) . " to " . date("G:i, F j", $uts_now));
$graph->xaxis->SetTitle('Time','middle'); 
$graph->yaxis->SetTitle('Rain / mm','middle');
$graph->xaxis->SetTitleMargin('50'); 

// Create the linear plot
$lineplot=new LinePlot($arr_data, $arr_xticks);       // Y-DATA, X-DATA (TIME)

// PLOT OPTIONS
$lineplot->SetColor("red");                // LINE COLOUR
if ($_GET['xm'] != '0'){$lineplot->mark->SetType(MARK_X, 0.3);}
$lineplot->mark->SetColor("red");          // POINTS COLOUR
$lineplot->mark->Show();                   // SET THEM TO DISPLAY
$lineplot->SetWeight(1);                   // SET LINE THICKNESS

// Add the plot to the graph
$graph->Add($lineplot);

// Display the graph
$graph->Stroke();


?>
