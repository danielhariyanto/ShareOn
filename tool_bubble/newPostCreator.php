<?php
require "../classes/digitalocean.php";
require "../classes/arangodb.php";
session_start();
$t= new triagens\ArangoDb\toolBubble();
$post = $_POST;
$r=$t->createNew($_SESSION['uid'], $post["name"], $post["desc"]);
$dgoc = new digitalO();
$key = $r[0]["_key"];
$dgoc->uploadPublic($key."/icon.png",$_FILES["file"]["tmp_name"]);
echo $key;
