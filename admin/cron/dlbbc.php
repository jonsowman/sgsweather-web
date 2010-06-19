<?php

$genericstr = "http://www.bbc.co.uk/weather/charts/uk/uk_";

$imgstr_p = $genericstr . "pressure_" . date('ymd') . "12.jpg";
$imgstr_r = $genericstr . "rain_" . date('ymd') . "12.jpg";
$imgstr_w = $genericstr . "wind_" . date('ymd') . "12.jpg";
$imgstr_t = $genericstr . "temperature_" . date('ymd') . "12.jpg";

$imgstr_atl = "http://www.bbc.co.uk/weather/charts/uk/atlantic_pressure_" . date('ymd') . ".jpg";

$img_p = imagecreatefromjpeg($imgstr_p);
$img_r = imagecreatefromjpeg($imgstr_r);
$img_w = imagecreatefromjpeg($imgstr_w);
$img_t = imagecreatefromjpeg($imgstr_t);

$img_atl = imagecreatefromjpeg($imgstr_atl);

$savepth = "/home/jsowman/public_html/weather/archive_maps/";

imagejpeg($img_p, $savepth . "pressure_" . date('ymd') . "12.jpg");
imagejpeg($img_r, $savepth . "rain_" . date('ymd') . "12.jpg");
imagejpeg($img_w, $savepth . "wind_" . date('ymd') . "12.jpg");
imagejpeg($img_t, $savepth . "temperature_" . date('ymd') . "12.jpg");

imagejpeg($img_atl, $savepth . "atlanticpress_" . date('ymd') . "12.jpg");


?>