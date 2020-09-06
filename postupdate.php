<?php
session_start();
require 'classes/arangodb.php';
$permission=FALSE;
if (isset($_POST['content'],$_POST['view_option'],$_POST['key'])&&is_array($_POST['content'])){
	$p= new triagens\ArangoDb\posts();
	$check= $p->getByKey($_POST['key']);
	$uid=$_SESSION['uid'];
	$pid=htmlentities($_POST['key']);
	$type=$check['type'];
	$view=htmlentities($_POST['view_option']);
	$agerestriction=(isset($_POST['age_restriction']))?$_POST['age_restriction']:"f";
	$topics=(isset($_POST['topics']) && !empty($_POST['topics'])&&is_array($_POST['topics']))?$_POST['topics']:array();
	for($i=0;$i<count($topics);$i++)
		$topics[$i]=allFirstUppercase(sideSpaceCleaner($topics[$i]));
	$rep=(isset($_POST['replyTo'])&&is_array($_POST['replyTo']))?$_POST['replyTo']:array();
	$taggedPeople=(isset($_POST['taggedPeople']) && !empty($_POST['taggedPeople'])&&is_array($_POST['taggedPeople']))?$_POST['taggedPeople']:array();
	foreach ($rep as $key) {
		$temp=$p->getByKey($key);
		if(isset($temp['view_option'])){
			if($temp['view_option']=="personal"){//if og post has view option of personal
				if($temp['user_key']==$_SESSION['uid']&&($view!="comment"||$view!="personal"))//if view option isn't comment or personal
				$view="comment";
			}elseif(is_array($temp['view_option'])&&in_array($_SESSION['uid'],$temp['view_option'])&&$view!="personal")//if og post view option is restricted then make it comment
			$view="comment";
		}else
			$view="comment";
	}

	if(is_array($check['user_key'])&&isset($check['user_key']['password'])&&isset($_POST['ppass'])&&password($_POST['ppass'])==$check['user_key']['password']){
        $f=new triagens\ArangoDb\files();
        $f->delete($check['user_key']['picture']);
		$permission=true;
	}elseif(!is_array($check['user_key']) && $_SESSION['uid']==$check['user_key']){
		$permission=TRUE;
	}else{
		exit;
	}
}
if($permission) {
	$uid=$_SESSION['uid'];
	$top = new triagens\ArangoDb\topics();
	$t=$top->getAndSet($topics);
	if(isset($_POST['anonymous'])&&$_POST['anonymous']=="true" && isset($_POST['apass'])){
		$aname=(isset($_POST['aname']) && !empty($_POST['aname']))?htmlentities($_POST['aname']):"";
		$apic=(isset($_POST['apic']) && filter_var($_POST['apic'], FILTER_VALIDATE_URL))?htmlentities($_POST['apic']):"";
		$uid=array("name"=>$aname,"password"=>password($_POST['apass']),"picture"=>$apic);
		$f=new triagens\ArangoDb\files();
		$f->delete($apic);
	}
	$f = new triagens\ArangoDb\files();
	$f->removeUse("posts/".$pid);
	if(isset($_POST['links'])){
	    $arr= json_decode($_POST['links']);
	    if(!empty($arr)) {
            foreach ($arr as $key) {
                $key = substr($key, 0, strpos($key, "."));
                $key = str_replace($web . "files/", "", $key);
                $f->in_use(htmlentities($key), 'posts/' . $pid);
            }
        }
	}
	if(isset($check['portrait'])&&$check['portrait']!=""&&isset($_POST['portrait'])){
		$f=new triagens\ArangoDb\files();
		$f->delete($check['portrait']);
	}
	$portrait=(isset($_POST['portrait']))?htmlentities($_POST['portrait']):"";
	$parray=$p->update($pid,$uid,$view, $agerestriction,$type,$_POST['content'],$rep,$t,$taggedPeople,$portrait); //returns new document
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
		if(isset($json['topics'])&&!empty($json['topics']))
		$json['topics']=$top->keyToNameMany($json['topics']);
    }
	echo json_encode($json);
}
