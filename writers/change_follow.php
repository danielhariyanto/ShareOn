<?php
    session_start();
    require '../classes/arangodb.php';
    require  '../vendor/autoload.php';
    $u = new triagens\ArangoDb\users();
	if(isset($_POST['username'])){
		$user= $u->userInfoByUsername($_POST['username']);
		$key=$user['_key'];
	}elseif(isset($_POST['key'])){
		$key=$_POST['key'];
	}

	$fo= new triagens\ArangoDb\user_relations();
	$follow= $fo->getFromTo($_SESSION['uid'],$key);
	if(isset($follow['follow']) && $follow['follow']=='1'){
		$fo->followStatusUpdate($_SESSION['uid'],$key,"");
		echo 0;
	}else{
    $n= new triagens\ArangoDb\notifications();
    $n->submit($key,$_SESSION['uid'],"","follow");
    $options = array(
        'cluster' => 'us2',
        'useTLS' => true
    );
    $pusher = new Pusher\Pusher(
        '38c6fc55f6c3d7c3756d',
        '74ae95834120a6919f87',
        '1001901',
        $options
    );
    $description="Has started following you";
    if(!isset($user))
    $user=$u->getByKey($key);
    $arr = array("left"=>array("picture"=>$user['profile_picture'],"link"=>$web."profiles/".$user["username"]."/posts","name"=>$user['name']),"time"=>time(),"description"=>$description);
    $pusher->trigger('private-'.$key, 'notifications', $arr);
		$fo->followStatusUpdate($_SESSION['uid'],$key,"1");
		echo 1;
	}
