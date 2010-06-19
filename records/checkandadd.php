<?php

if(!defined("IN_MODULE")) {
die("Direct Call Disabled.");
}

define("IN_MODULE",true);


//post like so:  varn0=temp  varv0=23.6   varn1=light  varv=57   uts=1846464867


function checkandadd (&$uPOST){

$a=0;


while(isset($uPOST["varn".$a])){


	if(isset($uPOST["varv".$a])){
		if (extExists($uPOST["varn".$a],1)){
			db_connect();			
			$query = "SELECT `value` from `ext` WHERE `data`='".$uPOST["varn".$a]."' AND `maxmin`='1' LIMIT 1";
			sanitize($uPOST["varn".$a]);
			$result = mysql_query($query) or die ("query failed");
			db_disconnect();
			$c_row = mysql_fetch_assoc($result);
						
			if ($uPOST["varv".$a] > $c_row['value']){
			//echo "highest";
				extInsert($uPOST["varn".$a],1,$uPOST["varv".$a],$uPOST['uts']);
			}
		}
	}

	if(isset($uPOST["varv".$a])){
		if (extExists($uPOST["varn".$a],0)){
			db_connect();
			$query = "SELECT `value` from `ext` WHERE `data`='".$uPOST["varn".$a]."' AND `maxmin`='0' LIMIT 1";
			sanitize($uPOST["varn".$a]);
			$result = mysql_query($query) or die ("query failed");
			db_disconnect();
			$c_row = mysql_fetch_assoc($result);
					
			if ($uPOST["varv".$a] < $c_row['value']){
			//echo "lowest";
				extInsert($uPOST["varn".$a],0,$uPOST["varv".$a],$uPOST['uts']);
			}
		}
	}


$a++;
}
}
?>