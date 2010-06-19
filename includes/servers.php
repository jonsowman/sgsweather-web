<html><head>
<title>Weather Station Servers</title>
<!--
<link rel="stylesheet" type="text/css" href="../main.css" />
-->
</head><body>
<h2>Weather Station Servers</h2>
<p>The weather station has a network of servers it uses to eventually push data to this server. This is to try and ensure some redundancy, usually for cases when the servers' IP addresses change or are otherwise inaccessible for some reason.</p>
<p>This page lists the values that may appear in the "server" field on the weather station homepage and what they mean. What is actually stored by this server is the IP address from which the HTTP GET request came, this is resolved to names by a text file and some PHP.</p>
<p>Direct hits from the station will appear as such, a value of anything other than "SGS" means that a forwarding script passed on the hit to this server. Of course, the current server that this website is running on will never appear in this field.</p>
<p>With the exception of "SGS", the station tries to connect to servers in the following order:</p>
<b><a href="http://kryten.hexoc.com" target="_blank">kryten</a></b> - this server. This is the main VPS run by Jon that this website and the MySQL database runs on.<br>
<b><a href="http://sheeva.hexoc.com" target="_blank">sheeva</a></b> - a Sheevaplug run by Jon at home. This has a static IP address and so is a reliable backup for the weather station.<br>
<b><a href="http://pippin.hexoc.com" target="_blank">pippin</a></b> - Jon's old VPS. The weather station website and MySQL used to run on this server. (Planned shutdown August 2010).<br>
<b>SGS</b> - Sutton Grammar School WAN IP addresses show up as SGS - ie. the hit came direct from the station at Sutton.<br>
</body></html>
