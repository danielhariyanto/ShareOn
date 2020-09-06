<?php
require '../require.php';
if(isset($_SESSION['uid'])){
	$tmp= $_FILES['file']['tmp_name'];
	$name=htmlentities($_POST['key'])."/".$_FILES['file']['name'];
	foreach ($_FILES['file'] as $key) {
		$t=new triagens\ArangoDb\toolBubble();
		$t=$t->getByKey(htmlentities($_POST['key']));
		if($t['creator']==$_SESSION['uid']){
			try{
			$do=new digitalO();
			$n=$do->uploadPublic($name,$tmp);
			$n=explode("/",$n);
			echo $n[count($n)-1];
			}catch(Exception $e){

			}
		}
	}
}
