<?php

/*
SGS WEATHER STATION 2009
JON SOWMAN
ALL RIGHTS RESERVED
*/

// better make sure the stuff in the txt file is escaped

define("IN_MODULE",true);
require_once("../includes/config.inc.php");
require_once("../includes/functions.inc.php");

// get the file in a variable

$ifile = "../issues.txt";
if(!file_exists($ifile)) die("oh dear, you twonk.");
$fh = fopen("../issues.txt", r);
$cval;
while(!feof($fh)) {
	$cval .= fgets($fh);
}
fclose($fh);
$c2val = file_get_contents($ifile);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $c_title; ?> - Issues Admin</title>
<link rel="stylesheet" type="text/css" href="../main.css" />

<script type="text/javascript"> 
function doIt() {
	var cval = "<?php echo trim($cval); ?>";
	document.form1.ta.value = cval;
}

</script>

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
		       
		       
<h2>Issues Admin</h2><br>

<form name="form1">
<textarea name="ta" cols=60 rows=15><?php echo str_replace("#","\n",$cval);?></textarea><br>
</form>

<?php echo $cval; ?>


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
