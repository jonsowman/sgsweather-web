<?php
define("IN_MODULE",true);
require_once("includes/config.inc.php");
require_once("includes/functions.inc.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $c_title; ?></title>
<link rel="stylesheet" type="text/css" href="main.css" />
</head>

<body>

   <!-- Begin Wrapper -->
   <div id="wrapper">
   
         <!-- Begin Header -->
         <div id="header">
		 
		       <h1><center>SGS Weather Station</center></h1>		 
			   
		 </div>
		 <!-- End Header -->
		 
         <!-- Begin Faux Columns -->
		 <div id="faux">
		 
		       <!-- Begin Left Column -->
		       <div id="leftcolumn">
		       
	             <?php

define("IN_MODULE",true);
require_once("includes/config.inc.php");
require_once("includes/functions.inc.php");

?>

<b><h2>The Station</h2></b><br>

As of July 2009 (upgraded XPORT and new photodiode light sensor)<br>
<img src="images/wxphotodiode.jpg"><br><br>
As of January 2009 (new LDR casing & programming bugfixes)
<img src="images/wxfrost.jpg"><br><br>
As of July 2008 (internet connectivity & barometric pressure sensor added)
<img src="images/3.JPG"><br><br>
As of February 2008 (Mark I)
<img src="images/2.JPG"><br><br>
		       
			   <div class="clear"></div>
			   
		       </div>
		       <!-- End Left Column -->
		 
		       <!-- Begin Right Column -->
		       <div id="rightcolumn">
		 
		             <?php include("menu.php"); ?>
							
				<div class="clear"></div>
				
		       </div>
		       <!-- End Right Column -->
			   
         </div>	   
         <!-- End Faux Columns --> 
		 
   </div>
   <!-- End Wrapper -->
</body>
</html>
