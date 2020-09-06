<?php
    session_start();
    require '../classes/arangodb.php';
	$us = new triagens\ArangoDb\users();
	$receiverinfo=$us->userInfoByUsername($_POST['username']);
	$re= new triagens\ArangoDb\user_relations();
	$re->friendStatusUpdate($receiverinfo['_key'],$_SESSION['uid'],"","");
	echo "done";