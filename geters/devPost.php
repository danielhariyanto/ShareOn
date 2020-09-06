<?php
$t= new triagens\ArangoDb\toolBubble();
$t=$t->getByKey($key);
if($t['creator']==$_SESSION['uid']){
$p=new triagens\ArangoDb\posts;
$p=$p->getDevPost($key);
$i=0;
$json=array();
foreach($p as $key){
	$json[$i]=$key;
    if(isset($key['content'],$key['type'],$key['view_option'])) {
        $uv = new triagens\ArangoDb\votes($key['_key']);
        $uservote = (isset($uid))?$uv->userVote((string)$uid):'';
        $uservote = (!empty($uservote['vote'])) ? $uservote['vote'] : "";
		$json[$i]['vote']=$uservote;
        if (is_array($key['user_key'])) {
        	$json[$i]['profile']['anonymous']=true;
			$json[$i]['profile']['name']=$key['user_key']['name'];
			$json[$i]['profile']['picture']=$key['user_key']['picture'];
        } else {
            $us = new triagens\ArangoDb\users();
            $user = $us->getByKey($key['user_key']);
			$json[$i]['profile']['name']=$user['name'];
			$json[$i]['profile']['picture']=$user['profile_picture'];
			$json[$i]['profile']['link']=$web."profiles/" . $user['username'] . "/posts";
        }
		unset($json[$i]["user_key"]);
		$json[$i]['frame']['size']=$t['size'];
		$json[$i]['frame']['scroll']=(isset($t['scroll']))?$t['scroll']:false;
		$json[$i]['frame']['type']=$key['type'];
		$json[$i]['frame']['process']=$t['post'];
		$json[$i]['frame']['edit']=$t['edit'];
    }
	$i++;
}
echo json_encode($json);
}
