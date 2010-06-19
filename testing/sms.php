<?php
define("IN_MODULE",true);
include("../includes/config.inc.php");
include("../includes/functions.inc.php");

$newsms = new SMS("447729366996","This is a test SMS from the SGS Weather Station.",false);
$newsms->send();
$mv = $newsms->grab();
echo $mv;

?>