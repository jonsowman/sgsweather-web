<?php
/*if(!defined("IN_MODULE")) {
die("Direct Call Disabled.");
}
*/
/////////////////////////////////////////////////////
// BASIC MYSQL DATABASE CONNECT AND DISCONNECT FUNCS
/////////////////////////////////////////////////////

function db_connect() {
define("IN_MODULE",true);
require("config.inc.php");
mysql_connect($db_host,$username,$db_password) or die("Could not connect to database<br>" . $username . mysql_error());
@mysql_select_db($database) or die("Unable to select database<br>". mysql_error());
}

function db_disconnect() {
mysql_close() or die ("Could not close database connection<br>" . mysql_error());
}

/////////////////////////////////////////////////
// USED TO SANITIZE USER INPUT BEFORE USE
// IN AN SQL QUERY
// IN ORDER TO PREVENT SQL INJECTION ATTACKS
// TAKES THE VALUE TO SANITIZE
/////////////////////////////////////////////////

function sanitize($value)
{

    if(get_magic_quotes_gpc())
    {
          $value = stripslashes($value);
    }
           //check if this function exists
    if( function_exists( "mysql_real_escape_string" ) )
    {
          $value = mysql_real_escape_string( $value );
	   //$value = $value;
    }
           //for PHP version < 4.3.0 use addslashes
    else
    {
          $value = addslashes( $value );
    }

    $value = htmlentities($value);
    return $value;

}

/////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////
// ADD.PHP INPUT VERIFICATION FUNCTIONS FOLLOW
/////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////

function checkVars($errmsg) {
	global $light;  //IMPORT GLOBAL VARIABLES
	global $light2;
	global $gain;		//#################
	global $moisture;
	global $wind_dir;
	global $wind_spd;
	global $pressure;
	global $temp;
	global $temp2;
	global $rain;
	global $batt;
	global $humidity;
	
	global $pressure_pot_v;
        global $ldr_cf;
        global $c_verifytemps;

	$light = $light*$ldr_cf;
	$wind_dir = adjustWindDir($wind_dir) or die ("Wind direction adjustment failed.");   // CHANGE THIS TO A BEARING
        // i took "or die"s out of here 21/04/10 (jon)
	$temp = adjustTemp($temp);  // CONVERT THE WORD TO A REAL DOUBLE
	$temp2 = adjustTemp($temp2);  // CONVERT THE WORD TO A REAL DOUBLE
	$wind_spd = adjustWindSpd($wind_spd);       // ADJUST THE WIND SPEED TO AN RPM VALUE
	$batt = adjustBatt($batt);
	$humidity = adjustHumidity($humidity);
	$pressure = adjustPressure($pressure, $pressure_pot_v);   // optional 3rd parameter to set Vcc, 5 otherwise
	$rain = adjustRain($rain);
	$light2 = adjustLuxLight($light2,$gain);		//##########################$light2 = adjustLuxLight($light2)

	$failed = 0;

	$l_f = 0;
	$m_f = 0;
	$wd_f = 0;
	$ws_f = 0;
	$t_f = 0;
	$p_f = 0;
	$b_f = 0;
	$h_f = 0;

	if($light < 0 || $light > 255) { $failed = 1; $l_f=1; }
	if($moisture < 0 || $moisture > 255) { $failed = 1; $m_f=1; }
	if($wind_dir < 0 || $wind_dir > 360) { $failed = 1; $wd_f=1; }
	if($wind_spd < 0 || $wind_spd > 1000) { $failed = 1; $ws_f=1; }
        if($c_verifytemps) {
            if($temp < -20 || $temp > 80) { $failed = 1; $t_f=1; }
            if($temp2 < -20 || $temp2 > 80) { $failed = 1; $t2_f=1; }
        }
	if($pressure < 500 || $pressure > 1500) { $failed = 1; $p_f=1; }
	if($batt < 0 || $batt > 7) { $failed = 1; $b_f=1; }
	if($humidity < 0 || $humidity > 255) { $failed = 1; $h_f=1; }

	if($failed != 0) {
		$errmsg = "The following variables failed:\n\n";
			if($l_f == 1) { $errmsg .= "Light: " . $light . "\n"; }
			if($m_f == 1) { $errmsg .= "Moisture: " . $moisture . "\n"; }
			if($wd_f == 1) { $errmsg .= "Wind Dir: " . $wind_dir . "\n"; }
			if($ws_f == 1) { $errmsg .= "Wind Spd: " . $wind_spd . "\n"; }
			if($t_f == 1) { $errmsg .= "Temp: " . $temp . "\n"; }
			if($p_f == 1) { $errmsg .= "Pressure: " . $pressure . "\n"; }
			if($b_f == 1) { $errmsg .= "Battery: " . $batt . "\n"; }
			if($h_f == 1) { $errmsg .= "Humidity: " . $humidity . "\n"; }
		return false;
	} else {
		return true;
	}
}

function checkTime() {
	global $t_mins;      //IMPORT GLOBAL VARIABLES
	global $t_hours;
	global $d_date;
	global $d_month;
	global $d_year;
	
	$failed = 0;

	if($t_mins < 0 || $t_mins > 60) { $failed = 1; }
	if($t_hours < 0 || $t_hours > 24) { $failed = 1; }
	if($d_date < 0 || $d_date > 31) { $failed = 1; }
	if($d_month < 0 || $d_month > 12) { $failed = 1; }
	if($d_year < 2008 || $d_year > 2010) { $failed = 1; }

	if($failed == 1) {
		return false;
	} else {
		return true;
	}
}

function adjustWindDir($IN) {
	IF ($IN > 159) {
		$TEMP = $IN - 160;
	} else {
		$TEMP = $IN + 96;
	}
	$OUT = 360 - ($TEMP*360/255);
	return $OUT;
}

function adjustTemp($InputWord) {
	IF ($InputWord < 3000) 
	{
		$finalNo = $InputWord/16;
	} else {
		$finalNo = -(65536 - $InputWord)/16;
	}
	$finalNo = round($finalNo, 2);
	return $finalNo;
}

function adjustWindSpd($inval) {
	$inval = $inval * 3;      // ADJUST THIS ACCORDING TO WIND SPEED TEST DURATION FOR RPM
	return $inval;
}

function adjustBatt($i_BV) {

	global $batt_adj_v;

	$r_BV = $i_BV / 100;
	$r_BV = $r_BV + $batt_adj_v;
	return $r_BV;
}

function adjustHumidity($i_H) {
	return $i_H;
}

function adjustPressure($p_in, $vneg, $batt=5) {   // params = adc reading, pot output, Vcc

	global $pressure_adj_mb;
	
	// $p_in = $p_in / 4096;
	$p_in = $p_in / 1000;   // convert to volts as using internal reference
	$p_in = $p_in - 2.5;
	$p_in = $p_in / 5.6;
	$p_in = $p_in + $vneg;
	$p_in = $p_in / $batt;
	$p_in = $p_in + 0.095;
	$p_in = $p_in / 0.009;	
	$p_in = $p_in * 10; // adjust to millibars

	$p_in = $p_in + $pressure_adj_mb; // ADJUSTMENT
	
	$p_in = round($p_in, 2);
	
	return $p_in;
}

function adjustRain($i) {
	global $rain_1mmtips;
	$i = $i / $rain_1mmtips;
	return $i;
}

function adjustLuxLight($i, $g) {
	//Intensity = (((l2/1000)/ 2^(g-1))/1000)/k
	global $luxCOP;
	$i = $i / 1000;
	$g = $g - 1;
	$divisor = pow(2, $g);
	$i = $i / $divisor;
	$i = $i / 1000;
	$i = $i / $luxCOP;
	return $i;
}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////
//////////////////Stats Stuff///////////////////////////
////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function CalSxx($data, $mean){
	for ($i=0; $i<=(count($data)-1); $i++){
		$sxx = $sxx + (($data[$i] - $mean)*($data[$i] - $mean));
	}
	return $sxx;
}

function CalSxy($data_x, $data_y, $mean_x, $mean_y){
	for ($i=0; $i<=(count($data_y)-1); $i++){
		$sxy = $sxy + (($data_y[$i] - $mean_y)*($data_x[$i] - $mean_x));
	}
	return $sxy;
}

function CalSyy($data, $mean){
	for ($i=0; $i<=(count($data)-1); $i++){
		$syy = $syy + (($data[$i] - $mean)*($data[$i] - $mean));
	}
	return $syy;
}


function ArrayAverage($data){
	for ($i=0; $i<=(count($data)-1); $i++){
		$tot = $tot + $data[$i];
	}
	return ($tot/count($data));
}



/////////////////////////////////////////////////////////
/////////////calculate regression line///////////////////
/////////////////////////////////////////////////////////

function calculateRegression($x_data, $y_data){


$xbar = ArrayAverage($x_data);
$ybar = ArrayAverage($y_data);


$sxx = CalSxx($x_data, $xbar);
$syy = CalSyy($y_data, $ybar);
$sxy = CalSxy($x_data, $y_data, $xbar, $ybar);


$b = $sxy/$sxx;
return $b;
}

//////////////////////////////////////////////
/////////////calculate PMCC///////////////////
//////////////////////////////////////////////
function calculatePMCC($x_data, $y_data){

//calculate means

$xbar = ArrayAverage($x_data);
$ybar = ArrayAverage($y_data);

$sxx = CalSxx($x_data, $xbar);
$syy = CalSyy($y_data, $ybar);
$sxy = CalSxy($x_data, $y_data, $xbar, $ybar);

$r = $sxy/sqrt($sxx*$syy);
return $r;
}

// INDEP_Tr4 PMCCCLASS

class PMCC {
    //Variables Used, for range x and range y
    var $DataX;
    var $DataY;

    //Function to create Sxx
    function Sxx(){
    //Disable Error Reporting
    error_reporting(0);
        $NumOfAll = count($this->DataX);
        $SumOfAll = array_sum($this->DataX);
        $MeanOfAll = $SumOfAll/$NumOfAll;
        //Calculate sum(Xi-[Mean Of All X's])^2
        $RunningTotal = 0;
            foreach($this->DataX as $Sxx){
                $RunningTotal += pow(($Sxx-$MeanOfAll),2);
            }
        return $RunningTotal;
    }
    
    //Function to create Syy
    function Syy(){
    //Disable Error Reporting
    error_reporting(0);
        $NumOfAll = count($this->DataY);
        $SumOfAll = array_sum($this->DataY);
        $MeanOfAll = $SumOfAll/$NumOfAll;
        //Calculate sum(Yi-[Mean Of All Y's])^2
        $RunningTotal = 0;
            foreach($this->DataY as $Syy){
                $RunningTotal += pow(($Syy-$MeanOfAll),2);
            }
        return $RunningTotal;
    }
    
    //Function to create Sxy
    function Sxy(){
    //Disable Error Reporting
    error_reporting(0);
        $NumOfAll_X = count($this->DataX);
        $SumOfAll_X = array_sum($this->DataX);
        $MeanOfAll_X = $SumOfAll_X/$NumOfAll_X;
        $NumOfAll_Y = count($this->DataX);
        $SumOfAll_Y = array_sum($this->DataX);
        $MeanOfAll_Y = $SumOfAll_Y/$NumOfAll_Y;
        $NumOfAll = min($NumOfAll_X,$NumOfAll_Y);
        //Loop Through and create sum(Xi-[Mean Of All X's])(Yi-[Mean Of All Y's])
        $i = 0;
        $RunningTotal = 0;
        while($i < $NumOfAll){
            $X = $this->DataX[$i] - $MeanOfAll_X;
            $Y = ($this->DataY[$i]-$MeanOfAll_Y);
            $RunningTotal+= $X * $Y;
            $i++;
        }
        return $RunningTotal;
    }
    
    //Combine All Functions to Create PMCC Value
    function PMCC(){
    //Disable Error Reporting
    error_reporting(0);
        //Calculate Lower Part
        $SxxSyy = $this->Sxx()*$this->Syy();
        $SxxSyy = sqrt($SxxSyy);
        //Do Calculation
        $PMCC = $this->Sxy()/$SxxSyy;
        //Return Answer
        return $PMCC;
    }
};

class SMS {

var $param = array();
var $request = "";
var $response;

function __construct($r_str, $msg_str, $issim) {       // RUN A CONSTRUCT

	$this->param["username"] = "jsowman";
	$this->param["password"] = "sophie";
	$this->param["type"] = "broadcast";
	$this->param["version"] = "2.0";
	$this->param["msg"] = $msg_str;
	$this->param["to"] = $r_str;
	$this->param["from"] = "SGSWeather";
	$this->param["route"] = "GD01";
	if($issim) {
		$this->param["sim"] = "yes";
	}
	
	foreach($this->param as $key=>$val){
		$this->request.= $key."=".urlencode($val);
		$this->request.= "&";
	}
	$this->request = substr($this->request, 0, strlen($this->request)-1);  // del final &
	
}

public function send() {

	$url = "http://www.tm4b.com/client/api/http.php";
	$ch = curl_init();  // its a METHOD you nonce
	curl_setopt($ch, CURLOPT_URL, $url); //set the url
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); //return as a variable
	curl_setopt($ch, CURLOPT_POST, 1); //POST for multi recips YEHH
	curl_setopt($ch, CURLOPT_POSTFIELDS, $this->request); //set the POST variables
	$this->response = curl_exec($ch); //run the whole process and return the response
	curl_close($ch); //close the curl handle
	
}

function grab() {
	return $this->response;
}

}

function leading_zeros($value, $places){

    if(is_numeric($value)){
        for($x = 1; $x <= $places; $x++){
            $ceiling = pow(10, $x);
            if($value < $ceiling){
                $zeros = $places - $x;
                for($y = 1; $y <= $zeros; $y++){
                    $leading .= "0";
                }
            $x = $places + 1;
            }
        }
        $output = $leading . $value;
    }
    else{
        $output = $value;
    }
    return $output;
}


















function getArrEXTUTS($iarr, &$time_arr, $maxmin=1, $nonzero=false) {
	$min_v = 999999; // a very positive number
	$max_v = -9999999; // a very negative number
	$ptr_min_v = 0;
	$ptr_max_v = 0;
	$iarr_l = count($iarr);
	
	if ( $maxmin == 0 ) {							 // looking for min
		for($i=0; $i<$iarr_l; $i++) {
			$ptr_v = floatval($iarr[$i]);
			if ($ptr_v < $min_v) {
				if($nonzero && $ptr_v == 0) {
					// skip this
				} else {
					$min_v = $ptr_v;
					$ptr_min_v = $time_arr[$i];
				}
			}
		}
		return array ($min_v, $ptr_min_v);
	} else if ( $maxmin == 1 ) {        			// looking for max
		for($i=0; $i<$iarr_l; $i++) {
			$ptr_v = $iarr[$i];
			if ($ptr_v > $max_v) {
				$max_v = $ptr_v;
				$ptr_max_v = $time_arr[$i];
			}
		}
		return array ($max_v, $ptr_max_v);
	} else {
		die("maxmin value in getArrEXTUTS was not recognised.");
	}
}

/////////////////////////

function extExists($data, $maxmin) {
	$query = "SELECT * FROM `ext` WHERE data='" . $data . "' AND maxmin='" . $maxmin . "' LIMIT 1";
	db_connect();
	$result = mysql_num_rows(mysql_query($query));
	db_disconnect();
	if ($result <= 0) {
		return false;
	} else {
		return true;
	}
}

function extInsert($data, $maxmin, $value, $uts) {
	if (extExists($data, $maxmin)) {
		$query = "UPDATE `ext` SET value='" . $value . "', uts='" . $uts . "' WHERE data='" . $data . "' AND maxmin='" . $maxmin . "'";
	} else {
		$query = "INSERT INTO `ext` VALUES (null, '" . $uts . "', '" . $data . "', '" . $maxmin . "', '" . $value . "')";
	}
	db_connect();
	if(mysql_query($query)) {
		db_disconnect();
		return true;
	} else {
		die(mysql_error());
		db_disconnect();
		return false;
	}
}

function twit($message) {
	// Set username and password
	$username = 'sgsweather';
	$password = 'weathercat2';
	// The twitter API address
	$url = 'http://twitter.com/statuses/update.xml';
	// Alternative JSON version
	// $url = 'http://twitter.com/statuses/update.json';
	// Set up and execute the curl process
	$curl_handle = curl_init();
	curl_setopt($curl_handle, CURLOPT_URL, "$url");
	curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
	curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl_handle, CURLOPT_POST, 1);
	curl_setopt($curl_handle, CURLOPT_POSTFIELDS, "status=$message");
	curl_setopt($curl_handle, CURLOPT_USERPWD, "$username:$password");
	$buffer = curl_exec($curl_handle);
	curl_close($curl_handle);
	// check for success or failure
	if (empty($buffer)) {
		return false;
	} else {
		return true;
	}	
}

function callTwitter($light, $light2, $temp, $pressure, $t_hours, $t_mins){

    global $c_twithours; // hours to tweet on
    global $c_tweetnext; // forced tweet?
    global $c_eureqa_alg; // which eureqa alg to use

    if( $temp == 0 || $temp > 80) { // info is wrong, use predicted
        $using_pred = true;
        $temp = eureqa($c_eureqa_alg, $light2, $pressure);
    } else {
        $using_pred = false;
    }

    $twits = "Weather Report: ";
    switch($temp) {                 // temperature stuff
            case($temp < 0):
                $a_temp = "frozen";
                break;
            case($temp < 10):
                $a_temp = "cold";
                break;
            case($temp < 20):
                $a_temp = "mild";
                break;
            case($temp < 25):
                $a_temp = "warm";
                break;
            case($temp < 50):
                $a_temp = "hot";
                break;
            default:
                $a_temp = "ERROR";
                break;
    }
    switch($light2) {        // light stuff
            case($light2 < 200):
                $a_light = "pitch black";
                break;
            case($light2 < 2000):
                $a_light = "gloomy";
                break;
            case($light2 < 5000):
                $a_light = "cloudy";
                break;
            case($light2 < 10000):
                $a_light = "light";
                break;
            case($light2 >= 10000):
                $a_light = "bright";
                break;
            default:
                $a_light = "ERROR";
                break;
    }

    //pressure stuff
    $a_pressure = getPressureAdjective();
    
    // now piece together the twitter string
    $twits .= "It's $a_light and $a_temp at ".round($temp)." degrees";
    $twits .= ($using_pred ? " (predicted)" : "");
    $twits .= ", pressure ".round($pressure)."mb and $a_pressure.";
    $twits .= ($c_tweetnext ? ' [forced tweet]' : '');
    if( in_array($t_hours, $c_twithours) || $c_tweetnext) {
            if($t_mins >= 53 || $t_mins <= 7 || $c_tweetnext) {
                if(twit($twits)) {
                    resetTweetNext();
                    return true;
                } else {
                    return false;
                }
            }
    }


}

function resetTweetNext() {
    require_once('FileScopeReplacer.php');
    $params = array();
    $params['dir'] = '/var/www/weather/includes/';
    $params['include_nested'] = 0;
    $params['search_what'] = '$c_tweetnext = true';
    $params['replace_to'] = '$c_tweetnext = false';
    $params['file_name_match'] = '/^config.inc.php/';  // <-- this mean beginning from 'test'
    $replacer = new FileScopeReplacer( $params );
    $replacer->doWork();
}

function getPressureAdjective(){

    global $c_pressure_change_fast;
    global $c_pressure_change_slow;

    db_connect();

    $query = "SELECT * FROM records ORDER BY `id` DESC LIMIT 1";
    $result = mysql_query($query) or die("Query failed with error: " . mysql_error());
    $row = mysql_fetch_array($result) or die(mysql_error());

    $uts_now = time();
    $last_read = $row['uts'];

    $uts_l_b = $uts_now - (60*60*4);
    $uts_l_t = $uts_now;

    $query = "SELECT * FROM records WHERE `uts` > " . $uts_l_b . " AND `uts` < " . $uts_l_t . " ORDER BY uts ASC";
    $result = mysql_query($query) or die("Query 2 failed with error: " . mysql_error());
    if (mysql_num_rows($result) == 0){die();}
    $row2 = mysql_fetch_array($result) or die(mysql_error());

    db_disconnect();


    $press_data = array();
    $uts_data = array();
    while($row2 = mysql_fetch_array($result)){
            array_push($press_data, $row2['pressure']);	
            array_push($uts_data, $row2['uts']);
    }

    $g = 1000 * calculateRegression($uts_data, $press_data);
    $r = calculatePMCC($uts_data, $press_data);

    //echo $g . "<br>";

    $fast = $c_pressure_change_fast;
    $slow = $c_pressure_change_slow;

    //echo $c_pressure_change_fast . "<br>";

    if($g>=$fast) {
        return "rising quickly";
    } else if ($g>=$slow) {
        return "rising slowly";
    } else if ($g>=-$slow) {
        return "unchanging";
    } else if ($g>-$fast) {
        return "falling slowly";
    } else { 
        return "falling quickly";
    }

}

function resolveIP($ip) {
    $returnval;
    switch($ip) {
        case "62.18.44.156":
            $returnval = "pippin";
            break;
        case "93.97.184.163":
            $returnval = "sheeva";
            break;
        case "82.43.7.254":
            $returnval = "SGS (direct)";
            break;
        case "212.85.13.143":
            $returnval = "SGS (direct)";
            break;
        case "82.43.7.254":
            $returnval = "SGS (direct)";
            break; 
        default:
            $returnval = "Unknown";
            break;
        } 
    return $returnval;
}

function eureqa($alg, $l, $p) {
    switch($alg) {
    case 1:
        $pred_temp = 2.18912e-006*$l2*$p + 11.6748*$p - 4.0645e-009*$l2*$l2 
            - 0.00180449*$l2 - 0.00576235*$p*$p - 5898.97;
        break;
    case 2:
        $offset = -9;
        $pred_temp = 5.99415*log((9732.62 + $l + 237.254/($l - 171.14))/$p) + $offset;
        break;
    default:
        return false;
        break;
    }
    return number_format($pred_temp,1);
}

function calcPercentageError($guess, $real) {
    if( $real == 0 ) { // can't /0
        return false;
    } else {
        $myerror = 100*(($guess-$real)/$real);
        return number_format($myerror,2);
    }
}

?>
