<?php
//Version 0.9.1
define("IN_MODULE",true);
require_once("includes/config.inc.php");
require_once("includes/functions.inc.php");
require("includes/Mail/mime.php");
require("includes/Mail.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<?php if ($_POST['message'] != ''){echo "<meta http-equiv=\"REFRESH\" content=\"3;url=http://weather.hexoc.com\">"; } ?>
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


<?php

//$recips = 'me@me.com, hello@world.net';
$subject = $_POST['subject'];

//$text = "the text version";

//$html = "the <b>html</b> version";

if ($_POST['message'] != ''){

$text = $_POST['message'];
$text = trim($text);
$text = substr($text, 0, 20000);

$text=preg_replace("/\n[^\w]*\n/","\n",$text);  
$html = nl2br($text);

$text = strip_tags($html);
$html = strip_tags($html, "<p><b><i><a><br>");

$text = $text . "\n\n [Sender's IP: " . $_SERVER['REMOTE_ADDR'] . " ]"; 
$html = $html . "<br><br> [Sender's IP: " . $_SERVER['REMOTE_ADDR'] . " ]"; 

$recipients = "weather@hexoc.com";
$hdrs = array(
              'From'    => 'SGS Weather Enquiry <\''.$_POST['reply'].'\'>',
              'To'      => $recipients,
              'Subject' => $subject,
              'Reply-To' => $_POST['reply'] //'weather@hexoc.com'
              );
$mime = new Mail_mime();
$mime->setTXTBody($text);
$mime->setHTMLBody($html);
$body = $mime->get();
$hdrs = $mime->headers($hdrs);
$mail =& Mail::factory('mail');
$res = $mail->send($recipients, $hdrs, $body);
if (PEAR::isError($res)) { print($res->getMessage());}else{echo "Message Sent<br><br><i><font size=1><a href=\"".$c_siteroot."/index.php\">If you are not redirected, click here</a></font></i>";}
}else{echo "No message entered<br><br><i><font size=1><a href=\"".$c_siteroot."/mail_sender.php\">Go back</a></font></i>";}
?>

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
