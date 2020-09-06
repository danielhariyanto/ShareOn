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
	$ur=new triagens\ArangoDb\user_relations();
	$st1=$ur->getFromTo($_SESSION['uid'], $key);
	$st2=$ur->getFromTo($key,$_SESSION['uid']);
	$trans=new triagens\ArangoDb\Transaction($connection,array( 'collections' => array( 'write' => array( 'user_relations','users' ) ), 'waitForSync' => true ));
    $trans->setAction('function(){
        var db= require("@arangodb").db;
        db.user_relations.update({"_key":"'.$st1['_key'].'"},{"friend_status":"","follow":""});
        db.user_relations.update({"_key":"'.$st2['_key'].'"},{"friend_status":"","follow":""});	
    }');
    $trans->execute();
	echo "done";
