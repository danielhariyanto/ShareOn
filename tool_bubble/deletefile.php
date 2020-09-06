<?php
require '../require.php';
if(isset($_SESSION['uid'])){
	$path=htmlentities($_POST['key'])."/".htmlentities($_POST['name']);
	$t=new triagens\ArangoDb\toolBubble();
	$t=$t->getByKey(htmlentities($_POST['key']));
	if($t['creator']==$_SESSION['uid']){
		$do=new digitalO();
		$do->delete($path);
		echo htmlentities($_POST['name']);
	}
}
