<?php 


	$ipv = $_SERVER['REMOTE_ADDR'];
	$myFile = "trackers/ip.txt";
	$fh = fopen($myFile, 'w') or die("Can't open file! You mong");
	fwrite($fh, $ipv);
	fclose($fh);

	echo "done";

?>