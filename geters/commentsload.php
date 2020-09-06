<?php
if(isset($key)){
if(isset($_POST['sort']))
$sort=(array)json_decode($_POST['sort']);
if(isset($sort['order'])){
	$temp=explode("-", $sort['order']);
	if($temp[1]==0)
	$asc=" ASC";
	elseif($temp[1]==1)
	$asc=" DESC";
	else
		die;
	if($temp[0]=="points")
	$temp[0]="upvotes-p.downvotes";
	$sort['order']=$temp[0].$asc;
$reset=true;
}
$global=(isset($sort)&&in_array("global", $sort['disabled_view']))?"":"p.view_option=='global' || ";
$anonymous=(isset($sort)&&in_array("anonymous", $sort['disabled_view']))?"!IS_ARRAY(p.user_key) &&":"";
$comment=(isset($sort)&&in_array("comment", $sort['disabled_view']))?"":"p.view_option=='comment' || ";
$rules=" && (".$comment.$global."p.view_option=='deleted'";
	$order=(isset($sort['order']))?$sort['order']:' p.time DESC';
	if(isset($_SESSION['uid'])){
		$uid= $_SESSION['uid'];
	$un = new triagens\ArangoDb\users();
	$username= $un->getByKey($uid);
	$username=$username['username'];
	}
	$rules.=")";
	if(isset($_SESSION['uid'])){
	if (isset($sort) && !empty($sort)) {
		if(isset($sort['points'])){
				$carr= explode('_', $sort['points']);
				if((empty($carr[0]) && is_numeric($carr[0])) || !empty($carr[0])){
					$rules.=" && (p.upvotes-p.downvotes)>=".$carr[0];
				}
				if((empty($carr[1]) && is_numeric($carr[1])) || !empty($carr[1])){
					$rules.=" && (p.upvotes-p.downvotes)<=".$carr[1];
				}
			}
			if(isset($sort['upvotes'])){
				$pcarr= explode('_', $sort['upvotes']);
				if((empty($pcarr[0]) && is_numeric($pcarr[0])) || !empty($pcarr[0])){
					$rules.=" && p.upvotes>=".$pcarr[0];
				}
				if((empty($pcarr[1]) && is_numeric($pcarr[1])) || !empty($pcarr[1])){
					$rules.=" && p.upvotes<=".$pcarr[1];
				}
			}
			if(isset($sort['downvotes'])){
				$ncarr= explode('_', $sort['downvotes']);
				if((empty($ncarr[0]) && is_numeric($ncarr[0])) || !empty($ncarr[0])){
					$rules.=" && p.downvotes>=".$ncarr[0];
				}
				if((empty($ncarr[1]) && is_numeric($ncarr[1])) || !empty($ncarr[1])){
					$rules.=" && p.downvotes<=".$ncarr[1];
				}
			}
			if(isset($sort['comments'])){
				$ccarr= explode('_', $sort['comments']);
				if((empty($ccarr[0]) && is_numeric($ccarr[0])) || !empty($ccarr[0])){
					$rules.=" && p.comments>=".$ccarr[0];
				}
				if((empty($ccarr[1]) && is_numeric($ccarr[1])) || !empty($ccarr[1])){
					$rules.=" && p.comments<=".$ccarr[1];
				}
			}
			if(isset($sort)&&in_array("personal", $sort['disabled_view'])){
				$rules.=" && LENGTH(p.view_option)!=1 && FIRST(p.view_option)!='".$_SESSION['uid']."'";
			}
		}
		}
		$ps = new triagens\ArangoDb\posts();
		$parray= $ps->commentsLoad($key, $rules, $order);
		$i=count($parray)-1;
		if($i!=-1){
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
}
