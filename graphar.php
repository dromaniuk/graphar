#!/usr/bin/php
<?php

function unparse_url($parsed_url) { 
  $scheme   = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : ''; 
  $host     = isset($parsed_url['host']) ? $parsed_url['host'] : ''; 
  $port     = isset($parsed_url['port']) ? ':' . $parsed_url['port'] : ''; 
  $user     = isset($parsed_url['user']) ? $parsed_url['user'] : ''; 
  $pass     = isset($parsed_url['pass']) ? ':' . $parsed_url['pass']  : ''; 
  $pass     = ($user || $pass) ? "$pass@" : ''; 
  $path     = isset($parsed_url['path']) ? $parsed_url['path'] : ''; 
  $query    = isset($parsed_url['query']) ? '?' . $parsed_url['query'] : ''; 
  $fragment = isset($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : ''; 
  return "$scheme$user$pass$host$port$path$query$fragment"; 
} 

function base64url_encode($data) { 
  return rtrim(strtr(base64_encode($data), '+/', '-_'), '='); 
} 

function hash_url($url){
	$parsed_url = parse_url($url);
	return base64url_encode($url);
}
function clear_url($url){
	$parsed_url = parse_url($url);
	unset($parsed_url['query']);
	unset($parsed_url['fragment']);
	return unparse_url($parsed_url);
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