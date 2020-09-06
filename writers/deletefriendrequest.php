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
	$fr=new triagens\ArangoDb\user_relations();
	$fr->friendStatusUpdate($_SESSION['uid'],$key,"","");
	echo "done";
