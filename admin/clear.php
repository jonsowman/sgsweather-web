<link rel="stylesheet" type="text/css" href="styles.css" />
<?php

// USED TO EMPTY THE DATABASE ENTIRELY
// NO RECORDS WILL BE RETAINED!

// BE CAREFUL WHEN USING!!

define("IN_MODULE",true);
require_once("includes/config.inc.php");
require_once("includes/functions.inc.php");

if($_GET['pwd'] != $set_pwd) { die("Authentication Failure"); }

db_connect();

$query = "TRUNCATE TABLE records";  
$result = mysql_query($query) or die("Query failed with error: " . mysql_error());

db_disconnect();

echo "Database was emptied successfully. No records remain.";

?>