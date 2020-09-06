<?php
session_start();
require '../classes/arangodb.php';
if(isset($_POST['key'])&&isset($_POST['name'])&&isset($_POST['editor'])&&isset($_POST['post'])&&isset($_POST['size'])&&isset($_POST['scroll'])){
$key=htmlentities($_POST['key']);
$name=htmlentities($_POST['name']);
$editor=htmlentities($_POST['editor']);
$post=htmlentities($_POST['post']);
$size=htmlentities($_POST['size']);
$scroll=($_POST['scroll']=="true")?"true":"FALSE";
$c = new triagens\ArangoDb\toolBubble();
$check=$c->toolInfoByKey($key);
if(!empty($check)&&$check['creator']==$_SESSION['uid']){
$c->update($key,$name,$editor,$post,$size,$scroll);
}
}