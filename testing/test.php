<?php

define("IN_MODULE",true);
require_once("../includes/config.inc.php");
require_once("../includes/functions.inc.php");

$x_data[0] = 1;
$x_data[1] = 2;
$x_data[2] = 3;
$x_data[3] = 4;
$x_data[4] = 5;

$y_data[0] = 1;
$y_data[1] = 3;
$y_data[2] = 5;
$y_data[3] = 7;
$y_data[4] = 9;


echo calculateRegression($x_data, $y_data) . "   " . calculatePMCC($x_data, $y_data);



?>