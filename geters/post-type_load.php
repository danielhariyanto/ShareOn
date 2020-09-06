<?php
$t=new triagens\ArangoDb\toolBubble();
$t=$t->getAll();
$u=new triagens\ArangoDb\users();
for ($i=0;$i<count($t);$i++) {
  $temp=$u->getByKey($t[$i]['creator']);
  $t[$i]['profile']['link']=$web."profiles/".$temp['username']."/posts";
  $t[$i]['profile']['name']=$temp['name'];
  $t[$i]['profile']['picture']=$temp['profile_picture'];
}
echo json_encode($t);
