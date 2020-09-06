<?php
require '../require.php';
if(isset($_SESSION['uid'])){
	foreach ($_FILES as $k){
		$tmp= $k['tmp_name'];
		$oname= new SplFileInfo($k['name']);
		$file=$k;
		break;
    }
	$f= new triagens\ArangoDb\files();
	$f=$f->insertFile($_SESSION['uid'], $oname);
// Upload file to Space
$d=new digitalO();
$filename = $f[0]['_key'].'.'.$oname->getExtension();
$name = $tmp;
echo $d->uploadPublic($filename,$tmp);
}
