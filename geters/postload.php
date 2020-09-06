<?php
$ps = new triagens\ArangoDb\posts();
$parray= $ps->loadPostByKey($key);
$json=$parray;
if(isset($parray['content'],$parray['type'],$parray['view_option'])) {
    $uv = new triagens\ArangoDb\votes($parray['_key']);
    $uservote = (isset($uid))?$uv->userVote((string)$uid):'';
    $uservote = (!empty($uservote['vote'])) ? $uservote['vote'] : "";
	$json['vote']=$uservote;
    if (is_array($parray['user_key'])) {
    	$json['profile']['anonymous']=true;
		$json['profile']['name']=$parray['user_key']['name'];
		$json['profile']['picture']=$parray['user_key']['picture'];
    } else {
        $us = new triagens\ArangoDb\users();
        $user = $us->getByKey($parray['user_key']);
		$json['profile']['name']=$user['name'];
		$json['profile']['picture']=$user['profile_picture'];
		$json['profile']['link']=$web."profiles/" . $user['username'] . "/posts";
    }
	unset($json["user_key"]);
	$c = new triagens\ArangoDb\toolBubble();
    $c = $c->getByKey($parray['type']);
	$json['frame']['size']=$c['size'];
	$json['frame']['scroll']=$c['scroll'];
	$json['frame']['type']=$parray['type'];
	$json['frame']['process']=$c['post'];
  $json['frame']['edit']=$c['edit'];
}
echo json_encode($json);
