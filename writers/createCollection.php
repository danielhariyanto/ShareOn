<?php
if(isset($_POST['title'],$_POST['view_option'])){
    session_start();
    require '../classes/arangodb.php';
	$c= new triagens\ArangoDb\collections();
	$title=htmlentities($_POST['title']);
	$v=$_POST['view_option'];
	$pic=(isset($_POST['picture']) && !empty($_POST['picture']))?$_POST['picture']:"";
	$c->createNew($_SESSION['uid'], $title, $v, $pic);
}