<?php
if(isset($_POST['name'],$_POST['type'],$_POST['restriction'])){
    session_start();
    require '../classes/arangodb.php';
	check_session();
	$g= new triagens\ArangoDb\groups();
	$name=htmlentities($_POST['name']);
	$type=$_POST['type'];
	$res=$_POST['restriction'];
	$pic=(isset($_POST['picture']) && !empty($_POST['picture']))?$_POST['picture']:"";
	var_dump($g->createNew($name, $type, $res, $pic));
}