<?php

$ipv = $_SERVER['REMOTE_ADDR'];

if(isset($_GET['v'])) {
	//$stringData = strip_tags($_GET['v']) . " from IP: " .$ipv;
	$stringData = $ipv . " said " . strip_tags($_GET['v']) . " on " . date("F j, Y, G:i");
} else {
	die("Unset variable.");
}
if(empty($v)) die ("Empty variable.");

$myFile = "last.txt";
$fh = fopen($myFile, 'w') or die("can't open file");
fwrite($fh, $stringData);
fclose($fh);

echo("Text written to file by IP: " . $ipv ."<br><br>File: <a href='last.txt'>last.txt</a><br><br>File Contents Follow:<br>------------------");

$fp=fopen($myFile,'r');
$content=fread($fp,filesize($myFile));
fclose($fp);
$content = "<p>".$content."</p>";
$content = str_replace(chr(10),"</p><p>",$content);
echo $content;

// end

?>