<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: *');
require 'require.php';
unset($key);
	$arr=array_keys($_REQUEST);
	foreach ($arr as $qwert) {
		if(!is_array($_REQUEST[$qwert]))
		$$qwert=htmlentities($_REQUEST[$qwert]);

	}
	require 'geters/'.$read;
