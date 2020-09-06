<?php
	$array=($_POST['array']!="")?explode(",",$_POST['array']):"";
	$arr=array();
	if(!empty($array)){
	foreach ($array as $key) {
		$t= new triagens\ArangoDb\topics();
		$d=$t->keyToName($key);
		$t->mapping($d);
		$trace=$t->result;
		$title="";
		foreach ($trace as $k) {
			$title.=$k['name']."->";
		}
		
		$arr[$key]=$title.$d['name'];
	}
	}
	echo (!empty($arr))?json_encode($arr):"";
?>