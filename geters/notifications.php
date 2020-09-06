<?php
$n= new triagens\ArangoDb\notifications();
$p= new triagens\ArangoDb\posts();
$u= new triagens\ArangoDb\users();
$notifications=$n->get($_SESSION['uid']);
$arr=array();
$i=0;
$n->read($_SESSION['uid']);
foreach ($notifications as $v){
    $user=$u->getByKey($v["sender_key"]);
    if($v["action"]=="comment"){
      $post=$p->getByKey($v["post_key"]);
      if(isset($post["portrait"])) {
        $portrait=$post["portrait"];
      }
      else{
        $portrait=$web."post-types/".$post["type"]."/icon.png";
      }
        $description="Your post was commented on by";
        $arr[$i] = array("left"=>array("portrait"=>$portrait,"link"=>$web."posts/".$post["_key"]),"time"=>$v["time"],"description"=>$description,
            "right"=>array(array("link"=>$web."profiles/".$user["username"]."/posts","name"=>$user["name"],"picture"=>$user["profile_picture"])));
    }
    elseif ($v["action"]=="follow"){
      $description="Has started following you";
      $arr[$i] = array("left"=>array("picture"=>$user['profile_picture'],"link"=>$web."profiles/".$user["username"]."/posts","name"=>$user['name']),"time"=>$v["time"],"description"=>$description);
    }


    $i++;
}
echo json_encode($arr);
require "writers/notification_read.php";
