<?php
require "../classes/digitalocean.php";
require "../classes/arangodb.php";
session_start();
$tb= new triagens\ArangoDB\toolBubble();
$post = $_POST;
$key = htmlentities($post["postID"]);
$returnKey = $tb->getByKey($key);
if (!empty($returnKey) && $returnKey["creator"] == $_SESSION['uid']) {
    $r = $tb->deleteByID(htmlentities($post["postID"]));
    $dgoc = new digitalO();
    $dgoc->deleteDir($key);
}
