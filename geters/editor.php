<?php
if(isset($_SESSION['uid'])){
$b=$_SESSION['filters']['bubbles'];
	$i=0;
	$bubble= new triagens\ArangoDb\toolBubble();
	$arr=array();
	foreach($b as $key){
		$a= $bubble->getById($key);
		if (!empty($a))
		array_push($arr,$a[0]);
		$i++;
	}
	echo json_encode($arr);
}else{
	echo json_encode([]);
}
