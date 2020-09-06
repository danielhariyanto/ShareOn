<?php
session_start();
require '../classes/arangodb.php';
$t=new triagens\ArangoDb\toolBubble();
$key=htmlentities($_POST['key']);
if($t->getByKey($key))
array_push($_SESSION['filters']['bubbles'],"tool_bubble/".$key);
$s=new triagens\ArangoDb\sessions($_SESSION['uid']);
$s->setFilters($_SESSION['filters']);
