<?php
$temp=explode("-", $_POST['order']);
if($temp[1]==0)
$asc="DESC";
elseif($temp[1]==1)
$asc="ASC";
else
	die;
$order=1;
if($temp[0]=="upvotes"||$temp[0]=="downvotes"||$temp[0]=="time"||$temp[0]=="comments"){
$order="SUM(pp[*].p.".$temp[0].")";
if($temp[0]=="time")
$order.="/COUNT(pp[*].p)";
}else if($temp[0]=="points")
$order="SUM(pp[*].p.upvotes)-SUM(pp[*].p.downvotes)";
else if($temp[0]=="hot")
$order="(SUM(pp[*].p.time)/450000+((SUM(pp[*].p.upvotes)-SUM(pp[*].p.downvotes)>0)?1:(SUM(pp[*].p.upvotes)-SUM(pp[*].p.downvotes)<0)?-1:0)*LOG10(MAX([ABS(SUM(pp[*].p.upvotes)-SUM(pp[*].p.downvotes)),1])))/COUNT(pp[*].p)";
$t=new triagens\ArangoDb\topics();
try{
$t=$t->topTrends($order, $asc);
echo json_encode($t);
}catch(Exception $e){
 echo "[]";
}
