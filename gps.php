<?php
$GoogleApiKey = "<API Key>";

$Lat = 43.260769;
$Lon = -2.899639;

$GoogleUrl = "https://maps.googleapis.com/maps/api/geocode/json?latlng=".$Lat.",".$Lon."&key=".$GoogleApiKey;

// create curl resource 
$ch = curl_init(); 

// set url 
curl_setopt($ch, CURLOPT_URL, $GoogleUrl); 

//return the transfer as a string 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

// $output contains the output string 
$output_json = curl_exec($ch); 

// close curl resource to free up system resources 
curl_close($ch); 


file_put_contents("serializado.data", $output_json);