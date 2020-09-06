<?php
require '../require.php';
$vals=array();
foreach ($_POST as $key => $value) {
	if(in_array($key, array("name","edit","post","size","description")))
	$vals[$key]=htmlentities($value);
}
if(isset($_POST['scroll']))
$vals['scroll']=($_POST['scroll']=="scroll")?true:false;
if(isset($p))
$vals['published']=time();
var_dump($vals);
$key=htmlentities($_POST['key']);
$t=new triagens\ArangoDb\toolBubble();
$t->update($key, $vals);
