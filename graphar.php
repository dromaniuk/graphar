#!/usr/bin/php
<?php

function base64url_encode($data) { 
  return rtrim(strtr(base64_encode($data), '+/', '-_'), '='); 
} 

function hash_url($url){
	$parsed_url = parse_url($url);
	return base64url_encode($url);
}
function clear_url($url){
	$parsed_url = parse_url($url);
	return $parsed_url['host'].$parsed_url['path'];
}

$stdin = fopen('php://stdin', 'r');
$stdout = fopen('php://stdout', 'w');

$har_string = "";
$har_map = [];

do {
	$row = fgets($stdin);
	$har_string .= $row;
} while ($row !== false);

$har_array = json_decode($har_string);

echo "digraph {\nrankdir=LR;\n";
foreach ($har_array->log->entries as $entry) {
	$url = $entry->request->url;

	if(preg_match('/\.(jpg|woff2?|webp|png)$/', $url, $matches)) continue;

	$hash = hash_url($url);
	// echo "\t".$hash.'[label=<<pre>"'.$url.'"</pre>> shape=rectangle]'.";\n";
	echo "\t".$hash.'[label="'.clear_url($url).'"]'.";\n";

	$referer = false;
	foreach ($entry->request->headers as $header) {
		if(strcasecmp($header->name,'Referer')==0){
			$referer = $header->value;
		}
	}
	if($referer){
		$referer_hash = hash_url($referer);
		echo "\t".$referer_hash.' -> '.$hash.";\n";
	}
}
echo "}";