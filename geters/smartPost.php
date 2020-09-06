<?php
$query="LET f=(for v in 1..110 OUTBOUND 'posts/".$key."' GRAPH 'post_relations' PRUNE v.view_option=='global'
return v)
LET fid=((LENGTH(f)==0)?DOCUMENT('posts/".$key."'):(RETURN f[LENGTH(f)-1])[0])
FOR v, e IN INBOUND SHORTEST_PATH fid TO 'posts/".$key."' GRAPH 'post_relations' RETURN ".'DISTINCT MERGE(v,{"topics":(FOR t IN ((v.topics!=null)?v.topics:[]) RETURN DOCUMENT(CONCAT("topics/",t)).name)})';
$statement = new triagens\ArangoDb\Statement(
		$connection,
		array(
				"query"=> $query
		)
);
$cursor = $statement->execute();
$parray=triagens\ArangoDb\getall($cursor);
$i=count($parray)-1;
$json=[];
if($i>=0){
	$json=array_fill(0, $i, '');
	foreach($parray as $key){
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
			$c = new triagens\ArangoDb\toolBubble();
							$c = $c->getByKey($key['type']);
			$json[$i]['frame']['size']=$c['size'];
			$json[$i]['frame']['scroll']=$c['scroll'];
			$json[$i]['frame']['type']=$key['type'];
			$json[$i]['frame']['process']=$c['post'];
			$json[$i]['frame']['edit']=$c['edit'];
					}
		$i--;
	}
}else{
$json=array();
}
echo json_encode($json);
 ?>
