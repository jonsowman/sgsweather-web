<?php
define("IN_MODULE",true);
require_once("includes/config.inc.php");
require_once("includes/functions.inc.php");

include ("includes/jpgraph/jpgraph.php");
include ("includes/jpgraph/jpgraph_line.php");
include ("includes/jpgraph/jpgraph_date.php");

$days_ago = $_GET['range'] or $days_ago = 1;
if ($days_ago > $c_max_graph_days){$days_ago = $c_max_graph_days;}
$uts_now = time();
$uts_onedayago = $uts_now - ($days_ago*60*60*24);

$uts_from = 1270080000 + 60*60*24*14; 
$uts_to = 1270252800 + 60*60*24*14;

db_connect();
$query = "SELECT * FROM records WHERE uts >= $uts_from AND uts <= $uts_to ORDER BY uts ASC";
$result = mysql_query($query) or die("Failed");
db_disconnect();

$arr_temp = array();
$arr_ctemp = array();
$arr_ctemp2 = array();
$arr_xticks = array();

while($row = mysql_fetch_array($result)){
    $l = $row['light2'];
    $p = $row['pressure'];
    array_push($arr_xticks, $row['uts']);
    $ctemp = doAlg($l, $p);
    $ctemp2 = doAlg2($l, $p);
    array_push($arr_ctemp, $ctemp-10);
    array_push($arr_ctemp2, $ctemp2-10);
    array_push($arr_temp, $row['temp']);
}

function doAlg($l, $p) {
    return 2.45487e-006*$l*$p + 11.7187*$p - 4.1119e-009*$l*$l - 0.00207619*$l - 0.00578456*$p*$p - 5920.69; 
}

function doAlg2($l, $p) { //24-04-10
    return 4.71319*log(93.0361 + 0.0153703*$l) + 0.192581/($l - 171.138) - 7.96062;
}


// Create the graph. These two calls are always required
$graph = new Graph(800,600,"auto");	     // SCALE
$graph->SetScale("datint",0,25);            // SET THE SCALE MANUALLY

// SET UP CUSTOM SETTINGS

$graph->xaxis->SetLabelAngle(90);
//$graph->xaxis->SetTextLabelInterval(10);
$graph->xaxis->scale-> SetTimeAlign(MINADJ_15);   // ALIGN TO A 5 MIN INTERVAL
$graph->xaxis->scale-> SetDateFormat( 'H:i');    // SET THE FORMAT FOR THE X-AXIS UTS CONVERSION

// SET UP TITLES

$graph->title->Set("Eureqa Testing\nFrom " . date("d/m/y", $uts_from) ." to ". date("d/m/y", $uts_to));
$graph->xaxis->SetTitle('Time','middle'); 
$graph->yaxis->SetTitle('Temperature / C','middle');
$graph->xaxis->SetTitleMargin('50'); 

// Create the linear plot
$lineplot=new LinePlot($arr_ctemp, $arr_xticks);       // Y-DATA, X-DATA (TIME)
$lineplot2 = new LinePlot($arr_temp, $arr_xticks);
$lineplot3 = new LinePlot($arr_ctemp2, $arr_xticks);

// PLOT OPTIONS
$lineplot->SetColor("red");                // LINE COLOUR
if ($_GET['xm'] != '0'){$lineplot->mark->SetType(MARK_X, 0.3);}
$lineplot->mark->SetColor("red");          // POINTS COLOUR
$lineplot->mark->Show();                   // SET THEM TO DISPLAY
$lineplot->SetWeight(1);                   // SET LINE THICKNESS

$lineplot2->SetColor("blue");                // LINE COLOUR
if ($_GET['xm'] != '0'){$lineplot2->mark->SetType(MARK_X, 0.3);}
$lineplot2->mark->SetColor("blue");          // POINTS COLOUR
$lineplot2->mark->Show();                   // SET THEM TO DISPLAY
$lineplot2->SetWeight(1);                   // SET LINE THICKNESS

$lineplot3->SetColor("green");                // LINE COLOUR
if ($_GET['xm'] != '0'){$lineplot3->mark->SetType(MARK_X, 0.3);}
$lineplot3->mark->SetColor("green");          // POINTS COLOUR
$lineplot3->mark->Show();                   // SET THEM TO DISPLAY
$lineplot3->SetWeight(1);                   // SET LINE THICKNESS

$lineplot->SetLegend ("Calc");
$lineplot2->SetLegend("Real");
$lineplot3->SetLegend("Calc 2");

// Add the plot to the graph
$graph->Add($lineplot);
$graph->Add($lineplot2);
$graph->Add($lineplot3);

// Display the graph
$graph->Stroke();

echo 'Alg 1: 2.45487e-006*$l*$p + 11.7187*$p - 4.1119e-009*$l*$l - 0.00207619*$l - 0.00578456*$p*$p - 5920.69<br>';
echo 'Alg 2: 4.71319*log(93.0361 + 0.0153703*$l) + 0.192581/($l - 171.138) - 7.96062';

?>

