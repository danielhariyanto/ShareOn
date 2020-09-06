<?php
session_start();
if(isset($_SESSION['uid'])){
require '../classes/digitalocean.php';
require '../classes/arangodb.php';
$arr=[];
$do=new digitalO();
$f=new triagens\ArangoDb\files();
$u= new triagens\ArangoDb\users();
$current=$u->getByKey($_SESSION['uid']);
if(isset($_FILES["file"])&&isset($_FILES["file"]['name'])){
  if(isset($temp['image'])){
    $f->removeUse("users/".$current['_key']);
    $s=explode("/",$current['profile_picture']);
    $do->delete($s[count($s)-1]);
  }
  $fi=$f->insertFile($_SESSION['uid'], $_FILES["file"]["name"],"users/".$_SESSION['uid']);
  $fi=$fi[0];
  $oname= new SplFileInfo($_FILES["file"]["name"]);
  $p=$do->uploadPublic($fi['_key'].".".$oname->getExtension(),$_FILES['file']['tmp_name']);
  $arr['profile_picture']=$p;
}
if(isset($_POST['username'])&&!preg_match("/[^a-z_\-0-9]/i", $_POST['username']) &&!empty($_POST['username']))
$arr['username']=htmlentities($_POST['username']);
if(isset($_POST['name'])){
	$tname= explode(" ", $_POST['name']);
	if(count($tname)>1){
		$name="";
		foreach ($tname as $key) {
			$name.=ucfirst(preg_replace('/\P{L}+/u', '', $key))." ";
		}
		$arr['name']=substr($name, 0, -1);
	}
}
$u->update($_SESSION['uid'], $arr);
}
