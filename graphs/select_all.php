<?php

//Version 0.9.14.1
define("IN_MODULE",true);

require_once ("../includes/config.inc.php");
require_once ("../includes/functions.inc.php");

include ("../includes/jpgraph/jpgraph.php");
include ("../includes/jpgraph/jpgraph_line.php");
include ("../includes/jpgraph/jpgraph_date.php");


//$start = microtime(true);





	$start_hour = $_GET['sh'] or $start_hour=1;
	$start_day = $_GET['sd'] or $start_day=1;
	$start_mon = $_GET['sm'] or $start_mon=1;
	$start_yr = $_GET['sy'] or $start_yr=2008;
	$end_hour = $_GET['eh'] or $end_hour=24;
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
	
	
	$uts_start = @mktime($start_hour,0,0,$start_mon,$start_day,$start_yr);
	IF ($_GET['n'] ==! 'on'){$uts_end = @mktime($end_hour,0,0,$end_mon,$end_day,$end_yr);}else{$uts_end = time();}
	

	
	IF ($uts_start > $uts_end) {die ("Start date after end date");}
	IF ($uts_start == ''){die ("Error in entered start date");}
	IF ($uts_end == ''){die ("Error in entered end date");}
	

$LineLight = $_GET['l'];
$LineTempe = $_GET['t'];
$LineTempe2 = $_GET['t2'];
$LineWindS = $_GET['ws'];
$LineWindS_AM = $_GET['wsa'];
$LineWindD = $_GET['wd'];
$LineMoist = $_GET['m'];
$LinePress = $_GET['p'];
$LineHumid = $_GET['h'];
$LineBattV = $_GET['bv'];
$LineRain = $_GET['r'];
$Count = 0;

//Settings


//Enable marks
IF ($_GET['ex'] =='on'){
$EnableMarkL = 'on';
$EnableMarkT = 'on';
$EnableMarkT2 = 'on';
$EnableMarkM = 'on';
$EnableMarkWS = 'on';
$EnableMarkWS_AM = 'on';
$EnableMarkWD = 'on';
$EnableMarkP = 'on';
$EnableMarkH = 'on';
$EnableMarkBV = 'on';
$EnableMarkR = 'on';
}
$MarkSize = $_GET['xs'];		//"x's" (marks) size

$LineWeight = $_GET['lw'];		//Line weight

$GraphSizeX = $_GET['x'];		//graph size
$GraphSizeY = $_GET['y'];

$GraphTitle = $_GET['title'];	//graph title


IF ($lineLight == 'on'){$Count = $count+1;}
IF ($LineTempe == 'on'){$Count = $count+1;}
IF ($LineTempe2 == 'on'){$Count = $count+1;}
IF ($LineWindS == 'on'){$Count = $count+1;}
IF ($LineWindS_AM == 'on'){$Count = $count+1;}
IF ($LineWindD == 'on'){$Count = $count+1;}
IF ($LineMoist == 'on'){$Count = $count+1;}
IF ($LinePress == 'on'){$Count = $count+1;}
IF ($LineHumid == 'on'){$Count = $count+1;}
IF ($LineBattV == 'on'){$Count = $count+1;}
IF ($LineRain == 'on'){$Count = $count+1;}

IF ($Count = 0) {die ("No selected vairables");}
/*
$Max_Lgt = 0;
$Max_Tmp = 0;
$Min_Tmp = 0;
$Max_WdS = 0;
$Max_WdSMA = 0;
$Max_WdD = 0;
$Max_Moi = 0;
$Max_Prs = 0;
$Max_Hum = 0;
$Max_Bat = 0;
*/


//$Scale_Lgt = $_GET['ls'] or $Scale_Lgt = 1;
//$Scale_Tmp = $_GET['ts'] or $Scale_Tmp = 1;
$Scale_Moi = $_GET['ms'] or $Scale_Moi = 1;
//$Scale_Wdd = $_GET['wds'] or $Scale_Wdd = 0.5;
$Scale_Wds = $_GET['wss'] or $Scale_Wds = 0.5;
$Scale_WdsMA = $_GET['wsas'] or $Scale_WdsMA = 0.5;
//$Scale_Prs = $_GET['ps'] or $Scale_WdsMA = 0.5;




$Lgt_data = array();
$Temp_data = array();
$Temp2_data = array();
$Moi_data = array();
$WndS_data = array();
	$WndS_MA_data = array();
	$WndS_MA_temp = array();
$WndD_data = array();
$Prss_data = array();
$Hum_data = array();
$Batt_data = array();
$Rain_data = array();

$arr_xticks = array();

$WndS_MA_Ptr = 0;
$WndS_MA_N = $_GET['wsan'] or 20;


$Ptruts_monyr = mktime(0,0,0,date('m',$uts_start),0,date('Y',$uts_start)); //only used to compare against final date

$Ptrmon = date('m',$uts_start);			//main pointers
$Ptryr = date('y',$uts_start);


WHILE ($Ptruts_monyr < $uts_end){
IF (file_exists("../archive/WeatherData_" .  date('M', mktime(0,0,0,$Ptrmon)). $Ptryr . ".csv")){	
	$File = fopen("../archive/WeatherData_" .date('M', mktime(0,0,0,$Ptrmon)). $Ptryr . ".csv", "r");
	while (!(feof($File))){
		$CSVArray =  fgetcsv($File);
		IF (($CSVArray[0] >= $uts_start) && ($CSVArray[0] <= $uts_end))
		{
			array_push($Lgt_data, ($CSVArray[2]/255)*100);// IF ($CSVArray[2] > $Max_Lgt){$Max_Lgt = $CSVArray[2];}			
			array_push($Moi_data, ($CSVArray[1]/255)*100); IF ($CSVArray[1] > $Max_Moi){$Max_Moi = $CSVArray[1];}			
			array_push($Temp_data, $CSVArray[5]);// IF ($CSVArray[5] > $Max_Tmp){$Max_Tmp = $CSVArray[5];}		
			array_push($Temp2_data, $CSVArray[9]);		
			array_push($WndD_data, $CSVArray[4]);// IF ($CSVArray[4] > $Max_WdD){$Max_WdD = $CSVArray[4];}			
			array_push($Prss_data, $CSVArray[6]);// IF ($CSVArray[6] > $Max_Prs){$Max_Prs = $CSVArray[6];}	
			array_push($WndS_data, $CSVArray[3]); IF ($CSVArray[3] > $Max_WdS){$Max_WdS = $CSVArray[3];}
			array_push($Hum_data, $CSVArray[7]);
			array_push($Batt_data, $CSVArray[8]);
			array_push($Rain_data, $CSVArray[10]);
			IF ($WndS_MA_Ptr >= $WndS_MA_N){
				array_shift($WndS_MA_temp);
				array_push($WndS_MA_temp, $CSVArray[3]);				
				for($i=0; $i<=$WndS_MA_N; $i++) {
					$t = $t + $WndS_MA_temp[$i];
				}
				array_push($WndS_MA_data, $t/$WndS_MA_N); IF ($t/$WndS_MA_N > $Max_WdSMA){$Max_WdSMA = $t/$WndS_MA_N;}
				$t = 0;
			}else{	
				array_push($WndS_MA_temp, $CSVArray[3]);
				array_push($WndS_MA_data, '');	
				$WndS_MA_Ptr++;			
			}
					
			array_push($arr_xticks, $CSVArray[0]);			
		}
	}
	fclose($File);
}
$Ptrmon++;
IF ($Ptrmon == 13){
	$Ptrmon = 1;
	$Ptryr++;
	IF ($Ptryr < 10){$Ptryr = '0' . $Ptryr;}
}
$Ptruts_monyr = mktime(0,0,0,$Ptrmon,0,("20".$Ptryr));
}


db_connect();
sanitize($uts_start);
sanitize($uts_end);
$query = "SELECT * FROM records WHERE uts >= " . $uts_start . " AND uts <= " . $uts_end . " ORDER BY id ASC";
$result = mysql_query($query) or die ("query failed");
db_disconnect();

$t=0;
while($row = mysql_fetch_array($result)){
	array_push($Lgt_data, ($row['light']/255)*100);//	 IF ($row['light'] > $Max_Lgt){$Max_Lgt = $row['light'];}
	array_push($Moi_data, ($row['moisture']/255)*100);	 IF ($row['moisture'] > $Max_Moi){$Max_Moi = $row['moisture'];}
	array_push($Temp_data, $row['temp']);// IF ($row['temp'] > $Max_Tmp){$Max_Tmp = $row['temp'];}
	array_push($WndD_data, $row['wind_dir']);// IF ($row['wind_dir'] > $Max_WdD){$Max_WdD = $row['wind_dir'];}
	array_push($Prss_data, $row['pressure']);// IF ($row['pressure'] > $Max_Prs){$Max_Prs = $row['pressure'];}	
	array_push($WndS_data, $row['wind_spd']); IF ($row['wind_spd'] > $Max_WdS){$Max_WdS = $row['wind_spd'];}
	array_push($Hum_data, $row['humidity']);
	array_push($Batt_data, $row['batt']);
	array_push($Temp2_data, $row['temp2']);
	array_push($Rain_data, $row['rain']);
	
	IF ($WndS_MA_Ptr >= $WndS_MA_N){
		array_shift($WndS_MA_temp);
		array_push($WndS_MA_temp, $row[wind_spd]);				
		for($i=0; $i<=$WndS_MA_N; $i++) {
			$t = $t + $WndS_MA_temp[$i];
		}
		array_push($WndS_MA_data, $t/$WndS_MA_N); IF ($t/$WndS_MA_N > $Max_WdSMA){$Max_WdSMA = $t/$WndS_MA_N;}
		$t = 0;
	}else{	
		array_push($WndS_MA_temp, $row[wind_spd]);
		array_push($WndS_MA_data, '');	
		$WndS_MA_Ptr++;			
	}
	
	array_push($arr_xticks, $row['uts']);
}

IF ($_GET['s'] != 'on'){
	for ($i=0; $i<=($WndS_MA_N/2); $i++){
		array_shift($WndS_MA_data);
		array_push($WndS_MA_data,'');
	}
}



$Title = '';
$YScale = '';
$Y2Scale = '';

$AxisPtr = -1;
$PctAxis = -2;
$WSAxis = -2;
$PctTitle = '';


$graph = new Graph($GraphSizeX,$GraphSizeY,"auto");
$graph->SetScale("datint");
$graph->SetMargin(40,200,50,55);
$graph->SetMarginColor('white');

IF ($_GET['aa'] == 'on'){$graph->img->SetAntiAliasing();}


//Light
IF ($LineLight == 'on') {
//IF ($Scale_Lgt != 1){$graph->SetScale("datint",0,($Max_Lgt/255*100)/$Scale_Lgt);}
$p1 = new LinePlot($Lgt_data, $arr_xticks);
$p1->SetColor('orange');
$p1->mark->SetType(MARK_X, $MarkSize);  
$p1->mark->SetColor("orange");   
IF ($EnableMarkL == 'on'){$p1->mark->Show();}else{$p1->mark->Hide();}             
$p1->SetWeight($LineWeight);               
$p1->SetLegend("Light");
$graph->Add($p1);
$graph->yaxis->SetColor('orange');
$PctAxis = -1;
$AxisPtr = $AxisPtr +1;
$PctTitle = "Light";
$graph->yaxis->SetTitle('Light/%','middle');
}


//Temperature
IF ($LineTempe == 'on') {
$p2 = new LinePlot($Temp_data, $arr_xticks);
$p2->SetColor('red');
$p2->mark->SetType(MARK_X, $MarkSize);  
$p2->mark->SetColor("red");   
IF ($EnableMarkT == 'on'){$p2->mark->Show();}else{$p2->mark->Hide();}                
$p2->SetWeight($LineWeight);               
$p2->SetLegend("Temperature");
IF ($AxisPtr == -1){
	$graph->Add($p2);
	$graph->yaxis->SetColor('red');
	$graph->yaxis->SetTitle('Temperature/°C','middle');
	}else{
	$graph->SetYScale($AxisPtr,'lin');
	$graph->AddY($AxisPtr,$p2);
	$graph->ynaxis[$AxisPtr]->SetColor('red');
	$graph->ynaxis[$AxisPtr]->SetTitle('Temperature/°C','middle');
	}
$AxisPtr = $AxisPtr + 1;
}



//Moisture
IF ($LineMoist == 'on') {
$p3 = new LinePlot($Moi_data, $arr_xticks);
$p3->SetColor('teal');
$p3->mark->SetType(MARK_X, $MarkSize);  
$p3->mark->SetColor("teal");   
IF ($EnableMarkM == 'on'){$p3->mark->Show();}else{$p3->mark->Hide();}                
$p3->SetWeight($LineWeight);               
$p3->SetLegend("Moisture");
IF ($PctAxis == -2){
	IF ($AxisPtr == -1){
		$graph->SetScale("datint",0,($Max_Moi/255*100)/$Scale_Moi);
		$graph->Add($p3);
		$graph->yaxis->SetColor('teal');
		$graph->yaxis->SetTitle('Moisture/%','middle');	
	}else{
		$graph->SetYScale($AxisPtr,'lin',0,($Max_Moi/255*100)/$Scale_Moi);
		$graph->AddY($AxisPtr,$p3);
		$graph->ynaxis[$AxisPtr]->SetColor('teal');	
		$graph->ynaxis[$AxisPtr]->SetTitle('Moisture/%','middle');	
	}
	$PctAxis = $AxisPtr;
	$AxisPtr = $AxisPtr + 1;	
}else{
	IF ($PctAxis == -1){
		$graph->Add($p3);
		$graph->yaxis->SetTitle('Light; Moisture /%','middle');	
	}else{
		$graph->AddY($PctAxis,$p3);
		$graph->ynaxis[$PctAxis]->SetTitle('Light; Moisture /%','middle');	
	}
}
}


//Wind Speed
IF ($LineWindS == 'on') {
$p4 = new LinePlot($WndS_data, $arr_xticks);
$p4->SetColor('blue');
$p4->mark->SetType(MARK_X, $MarkSize);  
$p4->mark->SetColor("blue");   
IF ($EnableMarkWS == 'on'){$p4->mark->Show();}else{$p4->mark->Hide();}                
$p4->SetWeight($LineWeight);               
$p4->SetLegend("Wind Speed");
IF ($AxisPtr == -1){
	$graph->SetScale("datint",0,$Max_WdS/$Scale_Wds);
	$graph->Add($p4);
	$graph->yaxis->SetColor('blue');
	$graph->yaxis->SetTitle('Wind Speed/RPM','middle');		
}else{
	$graph->SetYScale($AxisPtr,'lin',0,$Max_WdS/$Scale_Wds);
	$graph->AddY($AxisPtr,$p4);
	$graph->ynaxis[$AxisPtr]->SetColor('blue');
	$graph->ynaxis[$AxisPtr]->SetTitle('Wind Speed/RPM','middle');
}
$WSAxis = $AxisPtr;
$AxisPtr = $AxisPtr + 1;
}


//Wind Direction
IF ($LineWindD == 'on') {
$p5 = new LinePlot($WndD_data, $arr_xticks);
$p5->SetColor('purple');
$p5->mark->SetType(MARK_X, $MarkSize);  
$p5->mark->SetColor("purple");   
IF ($EnableMarkWD == 'on'){$p5->mark->Show();}else{$p5->mark->Hide();}                
$p5->SetWeight($LineWeight);               
$p5->SetLegend("Wind Direction");
IF ($AxisPtr == -1){
	$graph->SetScale("datint");//,0,$Max_WdD/$Scale_Wdd);
	$graph->Add($p5);
	$graph->yaxis->SetColor('purple');
	$graph->yaxis->SetTitle('Wind Direction/°','middle');		
}else{
	$graph->SetYScale($AxisPtr,'lin');//,0,$Max_WdD/$Scale_Wdd);
	$graph->AddY($AxisPtr,$p5);
	$graph->ynaxis[$AxisPtr]->SetColor('purple');
	$graph->ynaxis[$AxisPtr]->SetTitle('Wind Direction/°','middle');
}
$AxisPtr = $AxisPtr + 1;
}



//Pressure
IF ($LinePress == 'on') {
$p6 = new LinePlot($Prss_data, $arr_xticks);
$p6->SetColor('green');
$p6->mark->SetType(MARK_X, $MarkSize);  
$p6->mark->SetColor("green");   
IF ($EnableMarkP == 'on'){$p6->mark->Show();}else{$p6->mark->Hide();}               
$p6->SetWeight($LineWeight);               
$p6->SetLegend("Pressure");
IF ($AxisPtr == -1){
	$graph->Add($p6);
	$graph->yaxis->SetColor('green');
	$graph->yaxis->SetTitle('Pressure/mBar','middle');		
}else{
	$graph->SetYScale($AxisPtr,'lin');
	$graph->AddY($AxisPtr,$p6);
	$graph->ynaxis[$AxisPtr]->SetColor('green');
	$graph->ynaxis[$AxisPtr]->SetTitle('Pressure/mBar','middle');
}
$AxisPtr = $AxisPtr + 1;
}


//MA
IF ($LineWindS_AM == 'on') {
$p7 = new LinePlot($WndS_MA_data, $arr_xticks);
$p7->SetColor('black');
$p7->mark->SetType(MARK_X, $MarkSize);  
$p7->mark->SetColor("black");   
IF ($EnableMarkP == 'on'){$p7->mark->Show();}else{$p7->mark->Hide();}               
$p7->SetWeight($LineWeight);               
$p7->SetLegend("Wind Speed Moving Average");
IF ($WSAxis == -2){
	IF ($AxisPtr == -1){
		$graph->SetScale("datint",0,$Max_WdSMA/$Scale_WdsMA);
		$graph->Add($p7);
		$graph->yaxis->SetColor('black');
		$graph->yaxis->SetTitle('Wind Speed/RPM','middle');	
	}else{
		$graph->SetYScale($AxisPtr,'lin',0,$Max_WdSMA/$Scale_WdsMA);
		$graph->AddY($AxisPtr,$p7);
		$graph->ynaxis[$AxisPtr]->SetColor('black');	
		$graph->ynaxis[$AxisPtr]->SetTitle('Wind Speed/RPM','middle');	
	}
	$WSAxis = $AxisPtr;	
	$AxisPtr = $AxisPtr + 1;
}else{
	IF ($WSAxis == -1){
		$graph->Add($p7);
		$graph->yaxis->SetTitle('Wind Speed/RPM','middle');
	}else{
		$graph->AddY($WSAxis,$p7);
		$graph->ynaxis[$WSAxis]->SetTitle('Wind Speed/RPM','middle');	
	}
}


}


//Humidity
IF ($LineHumid == 'on') {
$p9 = new LinePlot($Hum_data, $arr_xticks);
$p9->SetColor('purple');
$p9->mark->SetType(MARK_X, $MarkSize);  
$p9->mark->SetColor("purple");   
IF ($EnableMarkH == 'on'){$p9->mark->Show();}else{$p9->mark->Hide();}                
$p9->SetWeight($LineWeight);               
$p9->SetLegend("Humidity");
IF ($PctAxis == -2){
	IF ($AxisPtr == -1){
		$graph->SetScale("datint",0,100);
		$graph->Add($p9);
		$graph->yaxis->SetColor('purple');
		$graph->yaxis->SetTitle('Humidity/%','middle');	
	}else{
		$graph->SetYScale($AxisPtr,'lin',0,100);
		$graph->AddY($AxisPtr,$p9);
		$graph->ynaxis[$AxisPtr]->SetColor('purple');	
		$graph->ynaxis[$AxisPtr]->SetTitle('Humidity/%','middle');	
	}
	$PctAxis = $AxisPtr;	
	$AxisPtr = $AxisPtr + 1;
}else{
	IF ($PctAxis == -1){
		$graph->Add($p9);
		$graph->yaxis->SetTitle('Light; Moisture; Humidity /%','middle');	
	}else{
		$graph->AddY($PctAxis,$p9);
		$graph->ynaxis[$PctAxis]->SetTitle('Light; Moisture; Humidity /%','middle');	
	}
}
}


//Batt Voltage
IF ($LineBattV == 'on') {
$p10 = new LinePlot($Batt_data, $arr_xticks);
$p10->SetColor('pink');
$p10->mark->SetType(MARK_X, $MarkSize);  
$p10->mark->SetColor("pink");   
IF ($EnableMarkBV == 'on'){$p10->mark->Show();}else{$p10->mark->Hide();}               
$p10->SetWeight($LineWeight);               
$p10->SetLegend("Batt Voltage");
IF ($AxisPtr == -1){
	$graph->Add($p10);
	$graph->yaxis->SetColor('pink');
	$graph->yaxis->SetTitle('Batt Voltage/V','middle');		
}else{
	$graph->SetYScale($AxisPtr,'lin',0,6);
	$graph->AddY($AxisPtr,$p10);
	$graph->ynaxis[$AxisPtr]->SetColor('pink');
	$graph->ynaxis[$AxisPtr]->SetTitle('Batt Voltage/V','middle');
}
$AxisPtr = $AxisPtr + 1;
}

//RAIN
IF ($LineRain == 'on') {
$p11 = new LinePlot($Rain_data, $arr_xticks);
$p11->SetColor('yellow');
$p11->mark->SetType(MARK_X, $MarkSize);  
$p11->mark->SetColor("yellow");   
IF ($EnableMarkR == 'on'){$p11->mark->Show();}else{$p11->mark->Hide();}               
$p11->SetWeight($LineWeight);               
$p11->SetLegend("Batt Voltage");
IF ($AxisPtr == -1){
	$graph->Add($p11);
	$graph->yaxis->SetColor('yellow');
	$graph->yaxis->SetTitle('Rain/mm','middle');		
}else{
	$graph->SetYScale($AxisPtr,'lin');
	$graph->AddY($AxisPtr,$p11);
	$graph->ynaxis[$AxisPtr]->SetColor('yellow');
	$graph->ynaxis[$AxisPtr]->SetTitle('Rain/mm','middle');
}
$AxisPtr = $AxisPtr + 1;
}



$graph->xaxis->SetLabelAngle(90);
//$graph->xaxis->SetTextLabelInterval(10);
$graph->xaxis->scale-> SetTimeAlign(MINADJ_15);   // ALIGN TO A 5 MIN INTERVAL
$graph->xaxis->scale-> SetDateFormat( 'H:i');    // SET THE FORMAT FOR THE X-AXIS UTS CONVERSION

// SET UP TITLES
$graph->title->Set($GraphTitle . " - " . date("G:i, F j", $uts_start) . " to " . date("G:i, F j", $uts_end));
$graph->xaxis->SetTitle('Time','middle'); 
$mem = memory_get_usage()-$startmem;

//echo 'Time:'.(microtime(true) - $start)."\n";
//exit();
$graph->Stroke();

?>