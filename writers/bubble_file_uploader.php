<?php
require '../require.php';
$tmp= $_FILES['file']['tmp_name'];
	$name= new SplFileInfo($_FILES['file']['name']);
	$key= $_POST['file']."/";
	$oname= ".".$name->getExtension();
	$name=$_FILES['file']['name'];
	if(file_exists($name)==FALSE&& ($oname==".html"||$oname==".css"||$oname==".js")){
		$destination= '../tool_bubble/';
		if(move_uploaded_file($tmp, $destination.$key.$name)){
			//$f= new triagens\ArangoDb\files();
			//$f->insertFile($_SESSION['uid'], $web.'tool_bubble/'.$key.$name);
			echo $name;
		}
	}
?>