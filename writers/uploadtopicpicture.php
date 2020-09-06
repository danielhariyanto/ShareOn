<?php
require '../require.php';
if(isset($_SESSION['uid'])){
	foreach ($_FILES as $k){
		$tmp= $k['tmp_name'];
		$oname= new SplFileInfo($k['name']);
		$file=$k;
		break;
    }
	$destination= '../files/';
	$ff= new triagens\ArangoDb\files();
	$f=$ff->insertFile($_SESSION['uid'], $oname);
// Upload file to Space
$imageName = $f[0]['_key'].'.'.$oname->getExtension();
$name = $file['tmp_name'];
$do=new digitalO();
$h=$do->uploadPublic($imageName,$name);
$key=htmlentities($_POST['key']);
$t=new triagens\ArangoDb\topics();
$t->update($key, array("image"=>$h));
$ff->removeUse('topics/'.$key);
$ff->in_use($f[0]['_key'], 'topics/' . $key);
echo $h;
}
?>
