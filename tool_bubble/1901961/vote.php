<?php
session_start();
require "../../classes/arangodb.php";
	$pkey=htmlentities($_POST['key']);
	$ukey=$_SESSION['uid'];
	$vkey=htmlentities($_POST['vote']);
	$vstatus=($_POST['click']=="true")?true:false;
	$p=new triagens\ArangoDb\posts();
	$p=$p->getByKey($pkey);
	$arr=$p['content']['options'];
    if(!file_exists("../../files/".$pkey.".json")){
    	$fp = fopen("../../files/".$pkey.".json", 'w');
		$json=array();
		foreach ($arr as $key) {
			$json[$key]=array();
		}
		if(isset($json[$vkey])){
			if($vstatus)
			array_push($json[$vkey],$_SESSION['uid']);
			else{
				unset($json[$vkey][array_search($_SESSION['uid'], $json[$vkey])]);
			}
		}
		$json=(array)$json;
		fwrite($fp, json_encode($json));
		fclose($fp);
		echo "1,1";
    }else{
    	if($p['content']['multiple']==='true'){
    	$arr=(array)json_decode(file_get_contents("../../files/".$p['_key'].".json"));
		if(isset($arr[$vkey])&&!in_array($_SESSION['uid'], $arr[$vkey])&&$vstatus)
			array_push($arr[$vkey],$_SESSION['uid']);
		elseif(isset($arr[$vkey])&&in_array($_SESSION['uid'], $arr[$vkey])&&!$vstatus){
			unset($arr[$vkey][array_search($_SESSION['uid'], $arr[$vkey])]);
		}
		$max=0;
		foreach ($arr as $key) {
			$c=count($key);
			if($max<$c)
				$max=$c;
		}
		$fp = fopen("../../files/".$pkey.".json", 'w');
		$arr=(array)$arr;
		fwrite($fp, json_encode($arr));
		fclose($fp);
		$count=(isset($arr[$vkey]))?count($arr[$vkey]):0;
		echo $max.",".$count;
		}else{
			$f=file_get_contents("../../files/".$p['_key'].".json");
			$arr=(array)json_decode($f);
			if(isset($arr[$vkey])&&!in_array($_SESSION['uid'], $arr[$vkey])&&$vstatus){
				foreach ($arr as $key => $value) {
					$s=array_search($_SESSION['uid'], $arr[$key]);
					if(!empty($s)){
						unset($arr[$key][$s]);
					}
				}
				array_push($arr[$vkey],$_SESSION['uid']);
			}elseif(isset($arr[$vkey])&&in_array($_SESSION['uid'], $arr[$vkey])&&!$vstatus){
				unset($arr[$vkey][array_search($_SESSION['uid'], $arr[$vkey])]);
			}
			$max=0;
			foreach ($arr as $key) {
				$c=count($key);
				if($max<$c)
					$max=$c;
			}
			$fp = fopen("../../files/".$pkey.".json", 'w');
			$arr=(array)$arr;
			fwrite($fp, json_encode($arr));
			fclose($fp);
			$count=(isset($arr[$vkey]))?count($arr[$vkey]):0;
			echo $max.",".$count;
		}
    }
