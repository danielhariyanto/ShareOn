<?php
require '../require.php';
$f=new triagens\ArangoDb\files();
$o=$f->expired();
$arr=[];
for($i=0;$i<count($o);$i++){
  $oname= new SplFileInfo($o[$i]['name']);
  $oname=$o[$i]['_key'].".".$oname->getExtension();
  array_push($arr,$oname);
}
$do=new digitalO();
for ($i=0; $i < count($arr); $i++) {
  $do->delete($arr[$i]);
  $f->delete($o[$i]['_key']);
}
