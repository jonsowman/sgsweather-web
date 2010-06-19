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
		       
	             <h2>View Records as a Graph</h2><br>
<form method="get" action="select_all.php" name="form1">

<?php $uts_lstweek = (mktime()-604800); ?>


  <p><b>Start</b> <br>
    
      <input type="text" size=2 value="<?php echo date('d',$uts_lstweek); ?>" name="sd">
    /
    <input type="text" size=2 value="<?php echo date('m',$uts_lstweek); ?>" name="sm">
    /
    <input type="text" size=2 value="<?php echo date('y',$uts_lstweek); ?>" name="sy">   
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
    <input type="checkbox" checked="checked" name="n">
<br />
  <table width="472" border="0">
    <tr>
      <th width="28" scope="col">&nbsp;</th>
      <th width="139" scope="col">&nbsp;</th>
      <th width="141" scope="col">&nbsp;</th>
      <th width="123" scope="col"><div align="left"></div></th>
      <th width="20" scope="col">&nbsp;</th>
    </tr>
    <tr>
      <th scope="row"><input type="checkbox" checked="checked" name="l" /></th>
      <th scope="row"><div align="left">Light</div></th>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <th scope="row"><input type="checkbox" checked="checked" name="t" /></th>
      <th scope="row"><div align="left">Temperature</div></th>
      <td>&nbsp;</td>
      <td>Scale Axis By</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <th scope="row"><input type="checkbox" name="ws" /></th>
      <th scope="row"><div align="left">Wind Speed</div></th>
      <td>&nbsp;</td>
      <td><input type="text" size="2" name="wss" value="0.5" /></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <th scope="row"><input type="checkbox" checked="checked" name="wsa" /></th>
      <th scope="row"><div align="right">&#9562; Moving Average</div></th>
      <td>&nbsp; period:
        <input type="text" size=1 name="wsan" value="20" /></td>
      <td><input type="text" size=2 name="wsas" value="0.5" /></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <th scope="row"><input type="checkbox" name="wd" /></th>
      <th scope="row"><div align="left">Wind Direction</div></th>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <th scope="row"><input type="checkbox" checked="checked" name="m" /></th>
      <th scope="row"><div align="left">Moisture</div></th>
      <td>&nbsp;</td>
      <td><input type="text" size="2" name="ms" value="0.5" /></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <th scope="row"><input type="checkbox" name="p" /></th>
      <th scope="row"><div align="left">Pressure</div></th>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <th scope="row"><input type="checkbox" name="h" /></th>
      <th scope="row"><div align="left">Humidity</div></th>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <th scope="row"><input type="checkbox" name="bv" /></th>
      <th scope="row"><div align="left">Battery Voltage</div></th>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <th scope="row"><input type="checkbox" name="t2" /></th>
      <th scope="row"><div align="left">Temperature2</div></th>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <th scope="row"><input type="checkbox" name="r" /></th>
      <th scope="row"><div align="left">Rain</div></th>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <br />
  <p><b>Settings</b><br>
    Size:
    <input type="text" size=2 name="x" value="1000">
    X
    <input type="text" size=2 name="y" value="600">
        <br />
    Enable Xs
    <input type="checkbox" name="ex">
    Size:
    <input type="text" size=2 name="xs" value="0.3">
        <br />
    Line Thickness
    <input type="text" size=2 name="lw" value="1">
        <br />
    Graph Title
    <input type="text" size=50 value="Combined Graph" name="title">
        <br />
    AntiAliasing
    <input type="checkbox" checked="checked" name="aa">
        <br />
    
    
    
    
        <br>
        <br>
        <input type="submit">
        <input type="reset">
      </p>
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
