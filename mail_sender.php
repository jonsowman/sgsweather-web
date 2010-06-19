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
<link rel="stylesheet" type="text/css" href="../main.css" />
<link href="http://weather.hexoc.com/main.css" rel="stylesheet" type="text/css" />
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
		       
	             <p><h2>Send Message</h2>
<form method="post" action="mime.php" name="form1">

      <br>
      <br />
    
    Subject  
    
    <br>
    <input type="text" size="60" name="subject">
    <br />
    <br>
    <br />
    
    Message
    <i><font size=1>- Write your message in the box</font></i><br>
    <textarea name="message" cols="60" rows="10"></textarea>
    <br />
    <br>
    <br />
    
    Reply Address <i><font size=1>- if you wish for a reply please supply your email address</font></i>
    <br>
    <input type="text" size="60" name="reply">
    <br />
    <br>
    <br />
    
    
    <input type="submit">
    <input type="reset">
    
  </form>
		       
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
