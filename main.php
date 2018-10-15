<?php
require_once(__DIR__ . '/vendor/autoload.php');

$config = [
	'ken_all_csv'       => 'zips/KEN_ALL.CSV',
	'ken_all_utf8_csv'  => 'zips/KEN_ALL_UTF8.CSV',
	'jigyosyo_utf8_csv' => 'zips/JIGYOSYO_UTF8.CSV',
	'version_txt'       => '../jpostal-1006/src/htdocs/version.txt',
	'json_dir'          => '../jpostal-1006/src/htdocs/json',
];

$main = new Ninton\JpostalJson\Main($config);
$main->run();
$main->jsonp();