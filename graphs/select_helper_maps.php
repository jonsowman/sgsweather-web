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
<style type="text/css">
<!--
.style1 {font-size: 9px}
-->
</style></head>

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
		       
	             <h2>View BBC Map Archives</h2>
	             <p>&nbsp;</p>
	             <p>&nbsp;</p>
	             <p><a href="http://www.bbc.co.uk/weather/ukweather/">Current UK Maps</a> | <a href="http://www.bbc.co.uk/weather/coast/pressure/">Current Atlantic Pressure Maps</a></p>
	             <p><br>
                                                   </p>
	             <form method="get" action="select_map.php" name="form1">

<?php $uts_lstweek = (mktime()-604800); ?>


  <p><b>Start</b> <br>
    
      <input type="text" size=2 value="2" name="sd">
    /
    <input type="text" size=2 value="3" name="sm">
    /
    <input type="text" size=2 value="09" name="sy">   
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
    <input type="checkbox" checked="checked" name="n"><table width="176" border="0">
    <tr>
      <th width="27" scope="row"><input type="checkbox" checked="checked" name="p" id="p" /></th>
      <th width="139" scope="row"><div align="left">Pressure</div></th>
      </tr>
    <tr>
      <th scope="row"><input type="checkbox" checked="checked" name="m" id="m" /></th>
      <th scope="row"><div align="left">Rain</div></th>
      </tr>
    <tr>
      <th scope="row"><input name="ws" type="checkbox" id="ws" checked="checked" /></th>
      <th scope="row"><div align="left">Wind</div></th>
      </tr>
    <tr>
      <th scope="row"><input type="checkbox" checked="checked" name="t" id="t" /></th>
      <th scope="row"><div align="left">Temperature</div></th>
      </tr>
  </table>
    <br><strong>Image Size</strong></br>
    <br><input name="size" type="radio" value="iss" checked="checked" />Small
     	<input name="size" type="radio" value="ism"  />Medium
     	<input name="size" type="radio" value="isl" />Large
    
    <p><br>
        <input type="submit">
        <input type="reset">
      </p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p><span class="style1">Maps are produced by the BBC and archieves are provided here for reference</span></p>
    </form>
		       
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
