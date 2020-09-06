<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: *');
require '../session_start.php';
require "../classes/arangodb.php";
$uid= (isset($_SESSION['uid']))?$_SESSION['uid']:"";
$pid= $_POST['key'];
$vot= new triagens\ArangoDb\votes($pid);
$vote =$vot->userVote((string)$uid);
$vote=(!empty($vote[0]))?$vote[0]:$vote;
$f=new triagens\ArangoDb\posts();
$d=$f->getByKey($pid);
if($d['view_option']!="deleted"&& $uid!=""){
if (!empty($vote) && $vote['vote']==1) {
	$cu= $vot->voteCount(1)-1;
	$cd= $vot->voteCount(-1);
	$trans=new triagens\ArangoDb\Transaction($connection,array( 'collections' => array( 'write' => array( 'posts','votes' ) ), 'waitForSync' => true ));
	$trans->setAction('function(){
	var db= require("@arangodb").db;
	db.posts.update({"_key":"'.$pid.'"},{"downvotes":'.$cd.',"upvotes":'.$cu.'});
	db.votes.removeByExample({"post_key":"'.$pid.'","user_key":"'.$uid.'"});
	}');
	$trans->execute();
}elseif (!empty($vote) && $vote['vote']==-1) {
	$cu= $vot->voteCount(1)+1;
	$cd= $vot->voteCount(-1)-1;
	$trans=new triagens\ArangoDb\Transaction($connection,array( 'collections' => array( 'write' => array( 'posts','votes' ) ), 'waitForSync' => true ));
	$trans->setAction('function(){
	var db= require("@arangodb").db;
	db.posts.update({"_key":"'.$pid.'"},{"downvotes":'.$cd.',"upvotes":'.$cu.'});
	db.votes.updateByExample({"post_key":"'.$pid.'","user_key":"'.$uid.'"},{"vote":1});
	}');
	$trans->execute();
}else{
	$cu= $vot->voteCount(1)+1;
	$cd= $vot->voteCount(-1);
	$trans=new triagens\ArangoDb\Transaction($connection,array( 'collections' => array( 'write' => array( 'posts','votes' ) ), 'waitForSync' => true ));
	$trans->setAction('function(){
	var db= require("@arangodb").db;
	db.posts.update({"_key":"'.$pid.'"},{"downvotes":'.$cd.',"upvotes":'.$cu.'});
	db.votes.insert({"post_key":"'.$pid.'","user_key":"'.$uid.'","vote":1});
	}');
	$trans->execute();
}
$v=array();
//total upvotes
$v['up']=$vot->voteCount(1);
//total downvotes
$v['down']=$vot->voteCount(-1);
//user vote
$t=$vot->userVote($_SESSION['uid']);
//user vote
$v['vote']=(empty($t['vote']))?0:$t['vote'];
echo json_encode($v);
}
