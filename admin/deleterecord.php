<?php
define("IN_MODULE",true);
include("../includes/config.inc.php");
include("../includes/functions.inc.php");

if(!isset($_GET['d']) || empty($_GET['d'])) { die ("empty vars"); }

$utd = trim($_GET['d']);
db_connect();
$query = "DELETE FROM `records` WHERE `uts` = '" . $utd . "'";
mysql_query($query) or die("it didnt work lolz");
db_disconnect();

echo "it got dleteeddd";

?>