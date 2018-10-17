<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config.php';

$config = [
	'ken_all_csv'       => 'zips/KEN_ALL.CSV',
	'ken_all_utf8_csv'  => 'zips/KEN_ALL_UTF8.CSV',
	'jigyosyo_utf8_csv' => 'zips/JIGYOSYO_UTF8.CSV',
	'version_txt'       => JPOSTAL_VERSION_TXT,
	'json_dir'          => JPOSTAL_JSON_DIR,
];

$main = new Ninton\JpostalJson\Main($config);
$main->run();
$main->jsonp();