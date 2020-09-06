<?php
header("Access-Control-Allow-Origin: *");
if(isset($_POST))
require 'writers/filtersession.php';
$global=(isset($_SESSION['filters']['posts']['disabled_view'])&&in_array("global", $_SESSION['filters']['posts']['disabled_view']))?"":"p.view_option=='global' || ";
$anonymous=(isset($_SESSION['filters']['posts']['disabled_view'])&&in_array("anonymous", $_SESSION['filters']['posts']['disabled_view']))?"!IS_ARRAY(p.user_key) &&":"";
//$rules=" && ".$anonymous."(".$global."p.view_option=='deleted'";
$rules=" && ".$anonymous."(".$global."p.view_option=='deleted'";
	$order='time ASC';
	if(isset($_SESSION['filters']['posts']['order'])){
		$temp=explode("-",$_SESSION['filters']['posts']['order']);
		if($temp[1]==0)
		$asc=" ASC";
		elseif($temp[1]==1)
		$asc=" DESC";
		else
			die;
		if($temp[0]=="points")
		$temp[0]="upvotes-p.downvotes";
		if($temp[0]=="hot")
		$temp[0]="time/4500+((p.upvotes-p.downvotes>0)?1:(p.upvotes-p.downvotes<0)?-1:0)*LOG10(MAX([ABS(p.upvotes-p.downvotes),1]))";
		$order=$temp[0].$asc;
	}
	$un = new triagens\ArangoDb\users();
	if(isset($_SESSION['uid'])){
		$uid= $_SESSION['uid'];
	$username= $un->getByKey($uid);
	$username=$username['username'];
	if(isset($_SESSION['filters']['posts']['disabled_view'])&&!in_array("restricted", $_SESSION['filters']['posts']['disabled_view'])){
		$rules.=" || (IS_ARRAY(p.view_option) &&  POSITION(p.view_option,'".$_SESSION['uid']."'))";
	}
	if(isset($_SESSION['filters']['posts']['disabled_view'])&&!in_array("personal", $_SESSION['filters']['posts']['disabled_view'])){
		$rules.=" || p.view_option=='personal' ";
	}
	}
	$rules.=")";
	$rules.=" && p.view_option!='deleted' ";
    if(isset($_POST['username'])){
        $ukey=$un->userInfoByUsername(htmlentities($_POST['username']));
        $rules.=" && p.user_key=='".$ukey['_key']."'";
    }
    elseif(isset($_POST['topic']))
    {
        $rules.=" && POSITION(p.topics,'".htmlentities($_POST['topic'])."')";
    }
	if(isset($_SESSION['uid'])){
	if(isset($_POST['username'])){
		$ukey=$un->userInfoByUsername(htmlentities($_POST['username']));
		$rules.=" && p.user_key=='".$ukey['_key']."'";
	}
	elseif(isset($_POST['topic']))
	{
			$rules.=" && POSITION(p.topics,'".htmlentities($_POST['topic'])."')";
	}
	if (isset($_SESSION['filters']) && !empty($_SESSION['filters']) && isset($_SESSION['filters'])) {
		$cleanget=(isset($_SESSION['filters']['posts']))?$_SESSION['filters']['posts']:array();
		if(isset($cleanget['time'])){
			$carr= explode('_', $cleanget['time']);
			if((empty($carr[0]) && is_numeric($carr[0])) || !empty($carr[0])){
				$rules.=" && p.time>=".$carr[0];
			}
			if((empty($carr[1]) && is_numeric($carr[1])) || !empty($carr[1])){
				$rules.=" && p.time<=".$carr[1];
			}
		}
		if(isset($cleanget['points'])){
			$carr= explode('_', $cleanget['points']);
			if((empty($carr[0]) && is_numeric($carr[0])) || !empty($carr[0])){
				$rules.=" && (p.upvotes-p.downvotes)>=".$carr[0];
			}
			if((empty($carr[1]) && is_numeric($carr[1])) || !empty($carr[1])){
				$rules.=" && (p.upvotes-p.downvotes)<=".$carr[1];
			}
		}
		if(isset($cleanget['upvotes'])){
			$pcarr= explode('_', $cleanget['upvotes']);
			if((empty($pcarr[0]) && is_numeric($pcarr[0])) || !empty($pcarr[0])){
				$rules.=" && p.upvotes>=".$pcarr[0];
			}
			if((empty($pcarr[1]) && is_numeric($pcarr[1])) || !empty($pcarr[1])){
				$rules.=" && p.upvotes<=".$pcarr[1];
			}
		}
		if(isset($cleanget['downvotes'])){
			$ncarr= explode('_', $cleanget['downvotes']);
			if((empty($ncarr[0]) && is_numeric($ncarr[0])) || !empty($ncarr[0])){
				$rules.=" && p.downvotes>=".$ncarr[0];
			}
			if((empty($ncarr[1]) && is_numeric($ncarr[1])) || !empty($ncarr[1])){
				$rules.=" && p.downvotes<=".$ncarr[1];
			}
		}
		if(isset($cleanget['comments'])){
			$ccarr= explode('_', $cleanget['comments']);
			if((empty($ccarr[0]) && is_numeric($ccarr[0])) || !empty($ccarr[0])){
				$rules.=" && p.comments>=".$ccarr[0];
			}
			if((empty($ccarr[1]) && is_numeric($ccarr[1])) || !empty($ccarr[1])){
				$rules.=" && p.comments<=".$ccarr[1];
			}
		}
		if(isset($cleanget['out'])&&count($cleanget['out'])>0){
			for($i=0;$i<count($cleanget['out']);$i++){
				if($cleanget['out'][$i][0]=="#"){
					$rules.=' && !POSITION(p.topics,"'.ltrim($cleanget['out'][$i],"#").'")';
				}elseif($cleanget['out'][$i][0]=="@"){
					$rules.=' && p.user_key!="'.ltrim($cleanget['out'][$i],"@").'"';
				}elseif($cleanget['out'][$i][0]=="*"){
					$rules.=' && p.type!="'.ltrim($cleanget['out'][$i],"*").'"';
				}else{
					$rules.=' && !CONTAINS(TO_STRING(p.content),"'.$cleanget['out'][$i].'")';
				}
			}
		}
		if(isset($cleanget['exclusively'])&&count($cleanget['exclusively'])>0){
			$rules.=" && (";
			for($i=0;$i<count($cleanget['exclusively']);$i++){
				if($i>0)
				$rules.=" || ";
				if($cleanget['exclusively'][$i][0]=="#"){
					$rules.=' POSITION(p.topics,"'.ltrim($cleanget['exclusively'][$i],"#").'")';
				}elseif($cleanget['exclusively'][$i][0]=="@"){
					$rules.=' p.user_key=="'.ltrim($cleanget['exclusively'][$i],"@").'"';
				}elseif($cleanget['exclusively'][$i][0]=="*"){
					$rules.=' p.type=="'.ltrim($cleanget['exclusively'][$i],"*").'"';
				}else{
					$rules.=' CONTAINS(TO_STRING(p.content),"'.$cleanget['exclusively'][$i].'")';
				}
			}
			$rules.=")";
		}
		if(isset($_SESSION['filters']['posts']['disabled_view'])&&in_array("personal", $_SESSION['filters']['posts']['disabled_view'])){
			$rules.=" && FIRST(p.view_option)!='".$_SESSION['uid']."' && LENGTH(p.view_option)!=1";
		}
	}
		if(isset($search)){
			$rules.=" && (p.topics like '%".htmlentities($_POST['search'])."%' || TO_LIST(p.content) like '%".htmlentities($_POST['search'])."%')";
		}
		}
		if(isset($_SESSION['uid']) && $_SESSION['main_page']=="wall" && !isset($_POST['username'])){
			$ps = new triagens\ArangoDb\posts();
			$parray= $ps->getWall($rules,$order);
		}else{
			$ps = new triagens\ArangoDb\posts();
			$parray= $ps->getAll($rules,$order);
		}
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
