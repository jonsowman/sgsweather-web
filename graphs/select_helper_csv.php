<?php
define("IN_MODULE",true);
require_once("../includes/config.inc.php");
require_once("../includes/functions.inc.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $c_title; ?></title>
<link rel="stylesheet" type="text/css" href="../main.css" />
</head>

<body>

   <!-- Begin Wrapper -->
   <div id="wrapper">
   
         <!-- Begin Header -->
         <div id="header">
		 
		       <h1 style="font-size:45px;"><center><?php echo $c_title; ?></center></h1>		 
			   
		 </div>
		 <!-- End Header -->
		 
         <!-- Begin Faux Columns -->
		 <div id="faux">
		 
		       <!-- Begin Left Column -->
		       <div id="leftcolumn">
		       
<h2>View Records as CSV</h2><br>
<form method="get" action="select_all_csv.php" name="form1">


<b>All Data</b> <br />
  <p><b>Start</b> <br>
  

    <input type="text" size=2 name="sd">
    /
    <input type="text" size=2 name="sm">
    /
    <input type="text" size=2 name="sy">   
    Hour: 
    <input type="text" size=2 name="sh"> 
    <br>
    <br />

    <b>End</b> <br>

    <input type="text" size=2 name="ed">
    /
    <input type="text" size=2 name="em">
    /
    <input type="text" size=2 name="ey">   
    Hour: 
    <input type="text" size=2 name="eh">  
    Now
    <input type="checkbox" name="n">
    <br>
    <br>
    <br />
    
    <b>Settings</b><br />
    File Name
    <input type="text" size=50 value="csv_data" name="fn">
    <br />
    
    
    
    <br>
    <br>
    <input type="submit">
    <input type="reset">
  </p>
		       
			   <div class="clear"></div>
			   
		       </div>
		       <!-- End Left Column -->
		 
		       <!-- Begin Right Column -->
		       <div id="rightcolumn">
		 
		                        <?php include("../menu.php"); ?>
							
				<div class="clear"></div>
				
		       </div>
		       <!-- End Right Column -->
			   
         </div>	   
         <!-- End Faux Columns --> 
		 
   </div>
   <!-- End Wrapper -->
</body>
</html>
