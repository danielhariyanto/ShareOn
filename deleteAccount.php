<?php
require "./classes/arangodb.php";
session_start();
$id = $_SESSION["uid"];
session_destroy();
header("Location: http://localhost/ShareOn/register.php");
$t= new triagens\ArangoDb\users();
$r=$t->deleteUserByID($id);