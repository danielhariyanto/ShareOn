<?php
session_start();
if(isset($_SESSION['uid'])){
require '../classes/digitalocean.php';
require '../classes/arangodb.php';
$arr=[];
$do=new digitalO();
$f=new triagens\ArangoDb\files();
$t=new triagens\ArangoDb\topics();
if(isset($_FILES["file"])&&isset($_FILES["file"]['name'])){
  $temp=$t->getByKey(htmlentities($_POST['key']));
  if(isset($temp['image'])){
    $f->removeUse("topics/".$temp['_key']);
    $s=explode("/",$temp['image']);
    $do->delete($s[count($s)-1]);
  }
  $fi=$f->insertFile($_SESSION['uid'], $_FILES["file"]["name"],"topics/".htmlentities($_POST['key']));
  $fi=$fi[0];
  $oname= new SplFileInfo($_FILES["file"]["name"]);
  $p=$do->uploadPublic($fi['_key'].".".$oname->getExtension(),$_FILES['file']['tmp_name']);
  $arr['image']=$p;
}
if(isset($_POST['description'])){
  $d=$_POST['description'];
  if(strlen($d)>1000)
  $d=substr($d,0,999);
$arr['description']=htmlentities($d);
}
$t->update(htmlentities($_POST['key']),$arr);
echo json_encode($t->getByKey(htmlentities($_POST['key'])));
}
 ?>
