<?php
session_start();
require "../../classes/arangodb.php";
	$pkey=htmlentities($_POST['key']);
	$ukey=$_SESSION['uid'];
	$vkey=htmlentities($_POST['vote']);
	$p=new triagens\ArangoDb\posts();
	$t=$p->getByKey($pkey);
	if(!in_array($vkey, $t['content']['options']))
	array_push($t['content']['options'],$vkey);
	$p->updateContent($pkey, $t['content']);
	$p=$p->getByKey($pkey);
	var_dump($p);
	$arr=$p['content']['options'];
	if(file_exists("../../files/".$p['_key'].".json")){
		$arr=(array)json_decode(file_get_contents("../../files/".$p['_key'].".json"));
		$newJSON=array();
		foreach ($p['content']['options'] as $key) {
			if(isset($arr[$key]))
				$newJSON[$key]=$arr[$key];
			else
				$newJSON[$key]=array();
		}
		$fp = fopen("../../files/".$p['_key'].".json", 'w');
		fwrite($fp, json_encode($newJSON));
		fclose($fp);
	}else{
		$fp = fopen("../../files/".$c['_key'].".json", 'w');
		$json=array();
		foreach ($p['content']['options'] as $key) {
			$json[$key]=array();
		}
		fwrite($fp, json_encode($json));
		fclose($fp);
	}
