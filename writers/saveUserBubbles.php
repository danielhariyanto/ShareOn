<?php
session_start();
require '../classes/arangodb.php';
if(is_array($_POST['bubbles'])){
$t=new triagens\ArangoDb\toolBubble();
$b=$_POST['bubbles'];
$_SESSION['filters']['bubbles']=array();
foreach ($b as $key) {
if($t->getByKey($key))
array_push($_SESSION['filters']['bubbles'],"tool_bubble/".$key);
}
$s=new triagens\ArangoDb\sessions($_SESSION['uid']);
$s->setFilters($_SESSION['filters']);
}