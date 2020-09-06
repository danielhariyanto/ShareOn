<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Expose-Headers: Set-Cookie');
require 'session_start.php';
require 'classes/arangodb.php';
if(isset($_POST['username']) && isset($_POST['password']) && $_POST['username']!=="" && $_POST['password']!==""){
	$username=$_POST['username'];
	$password=password($_POST['password']);
	$u= new triagens\ArangoDb\users();
	$uid= $u->checkLog($username,$password);
	if(!empty($uid)){
		$_SESSION['uid']=(string)$uid['_key'];
		$arr=$u->getByKey($_SESSION['uid']);
		unset($arr['password']);
		$arr['cookie']=session_id();
		echo json_encode($arr);
	}
}
