<?php
session_start();
require "arangodb_classes.php";
require 'predis_classes.php';
require 'original_link.php';
$pid=$_POST['name'];
$content=$_POST['content'];
$uid= $_SESSION['uid'];
if(isset($_POST['aname'],$_POST['apass'])){
	$f=new triagens\ArangoDb\comments();
	$in=$f->write($pid,"", $content);
	$a= new anonymousPosts($pid,$in[0]['_key']);
	$array = $f->commentsLoad($pid,'',' c.time DESC');
}else{
	$f=new triagens\ArangoDb\comments();
	$f->write($pid,$uid,$content);
	$array = $f->commentsLoad($pid,'',' c.time DESC');
}
foreach ($array as $key) {
	$vote= new triagens\ArangoDb\votes($_POST['name'], $key['_key'],"");
	$vote=$vote->userVote($uid);
	$vote=(!empty($vote))?$vote['vote']:'';
	$pcount= $key['upvotes'];
	$ncount= $key['downvotes'];
	if($key['user_key']==""){
		$ano= new anonymousPosts($key['_key'],"","");
		$ano=$ano->get();
		$name=$ano['name'];
		$profile="";
		$profilepicture="";
	}else{
		$us= new triagens\ArangoDb\users();
		$user= $us->userInfoByKey($key['user_key']);
		$profilepicture=$user['profile_picture'];
		$profile= ($user['_key']==0)?"":"profiles/".$user['username']."/posts";
		$name=$user['name'];
	}
	$uarrow= ($vote=="upvote")?$originallink."icons/greenuparrow.png":$originallink."icons/greyuparrow.png";
	$darrow= ($vote=="downvote")?$originallink."icons/reddownarrow.png":$originallink."icons/greydownarrow.png";
	echo "<div class='commentbox' name='".$key['post_key']."-".$key['_key']."'>
							<a class='commentprofile' href='".$originallink."profiles/".$user['username']."/posts'><img class='sprofilepicture' src='".$user['profile_picture']."'/><p class='stext'>".$user['name']."</p></a>
							<div name='".$key['post_key']."-".$key['_key']."' class='settcontain notclick'><img name='".$key['post_key']."' class='settings' src='".$originallink."icons/settings.png'/></div>
							<article name='".$key['post_key']."-".$key['_key']."' class='content'>".$key['content']."</article>
							<div>
								<div class='slikecontainer'>
								<div class='supcount' name='".$key['post_key']."-".$key['_key']."'>".$pcount."</div>
								<div class='sup imgs' name='".$key['post_key']."-".$key['_key']."'><img height='10px' width='20px' type='image' src='".$uarrow."' /></div>
								<div class='sdowncount' name='".$key['post_key']."-".$key['_key']."'>".$ncount."</div>
								<div class='sdown imgs' name='".$key['post_key']."-".$key['_key']."'><img height='10px' width='20px' type='image' src='".$darrow."' /></div>
								</div>
								<div>
									comming soon, replies.
								</div>
							</div>
						
					</div>";
	
}
?>