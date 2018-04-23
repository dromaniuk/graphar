#!/usr/bin/php
<?php
$stdin = fopen('php://stdin', 'r');
$stdout = fopen('php://stdout', 'w');

$har_string = "";
do {
	$row = fgets($stdin);
	$har_string .= $row;
} while ($row !== false);

$har_array = json_decode($har_string);
foreach ($har_array->log->entries as $entry) {
	print_r($entry->request->url."\n");
}
