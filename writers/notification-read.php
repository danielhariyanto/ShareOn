<?php
    require '../classes/arangodb.php';
	$n=new triagens\ArangoDb\notifications();
	$n->read($_GET['key']);
	header("Location: ".$_GET['link']);
?>