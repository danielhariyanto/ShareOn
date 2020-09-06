<?php
session_start();
require '../classes/arangodb.php';
$us = new triagens\ArangoDb\users();
	if(isset($_POST['key'])){
		$key=$_POST['key'];
	}elseif(isset($_POST['username'])){
		$u=$us->userInfoByUsername(htmlentities($_POST['username']));
		$key=$u['_key'];
	}
	$sender=$us->userInfoByKey($_SESSION['uid']);
	$fr=new triagens\ArangoDb\user_relations();
	$fr->friendStatusUpdate($_SESSION['uid'],$key,"pending","received request");
	$i= new triagens\ArangoDb\notifications();
	$i->insert($key,$sender['name'],$sender['profile_picture'],$web."profiles/".$sender['username']."/posts","sent you a friend request.","people");
	echo "done";