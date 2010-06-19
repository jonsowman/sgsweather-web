<?php
define("IN_MODULE",true);
require_once("includes/config.inc.php");
require_once("includes/functions.inc.php");
?>
<style type="text/css">
<!--
.style2 {font-size: 10px}
-->
</style>


<p><b>
  <a href="<?php echo $c_siteroot ?>/index.php">Home</a><br>
  <a href="<?php echo $c_siteroot ?>/station.php">The Station (Pictures)</a><br>
  <a href="<?php echo $c_siteroot ?>/search.php">Data Search</a><br>
  <a href="<?php echo $c_siteroot ?>/records.php">Records</a><br>
  <a href="<?php echo $c_siteroot ?>/issues.php">Issues</a><br>
  <a href="<?php echo $c_siteroot ?>/twitter.php">Twitter</a><br>
  <a href="<?php echo $c_siteroot ?>/rss.php">RSS Feed&nbsp<img src="<?php echo $c_siteroot ?>/images/rss_small.png" border=0 width=15 height=15></a><br>
<?php if ($c_usecwop) {
// only display the CWOP link if we've enabled CWOP operation in config    
// also you really need to get this to work - lazy git
echo "<a href='http://www.findu.com/cgi-bin/wxpage.cgi?call=DW1367' target'_blank'>Our CWOP Page (findU)</a><br>";
} ?>
  <a href="<?php echo $c_siteroot ?>/mail_sender.php">Contact</a><br>
  <br>
  </b>
    
  <b>Create</b><br>
  <a href="<?php echo $c_siteroot ?>/graphs/select_helper.php">Create A Graph</a><br>
  <a href="<?php echo $c_siteroot ?>/graphs/select_helper_csv.php">Create A CSV</a><br>
  <br>
    
  <?php // we need to check data exists before giving options to display it ?>

  <b>1 Day Single Graphs</b><br>
  <a href="<?php echo $c_siteroot ?>/graphs/lightpclx_c.php?range=1&xm=1">Light</a><br>
  <a href="<?php echo $c_siteroot ?>/graphs/1d/temp2.php">Temperature</a><br>
  <a href="<?php echo $c_siteroot ?>/graphs/1d/pressure.php">Pressure</a><br>
  <a href="<?php echo $c_siteroot ?>/graphs/1d/moisture.php">Moisture</a><br>
  <a href="<?php echo $c_siteroot ?>/graphs/1d/windspd.php">Wind Speed</a><br>
  <a href="<?php echo $c_siteroot ?>/graphs/1d/winddir.php">Wind Direction</a><br>
  <a href="<?php echo $c_siteroot ?>/graphs/1d/humidity.php">Humidity</a><br>
  <a href="<?php echo $c_siteroot ?>/graphs/1d/batt.php">System Voltage</a><br>
  <br>
    
  <b>1 Day Combined Graphs</b><br>
  <a href="<?php echo $c_siteroot ?>/graphs/lightluxtemp_c.php?range=1&xm=1">Light/Temperature</a><br>
  <a href="<?php echo $c_siteroot ?>/graphs/prstemp_c.php?range=1&xm=1">Pressure/Temperature</a><br>
  <br>
    
  <b>7 Day Combined Graphs</b><br>
  <a href="<?php echo $c_siteroot ?>/graphs/lighttemp_c.php?range=7&xm=0">Light/Temperature</a><br>
  <br>
  
<a href="<?php echo $c_siteroot ?>/admin">Admin</a></p>
<p>&nbsp;</p>
<p><span class="style2">&copy; 2008-<?php echo date("Y"); ?> <a href="http://www.hexoc.com" target="_blank">HEXOC.com</a> | All Rights Reserved</span><br>
</p>
