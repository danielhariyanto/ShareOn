<?php
session_start();
require '../classes/arangodb.php';
require '../functions.php';
if(isset($_POST['post']) && isset($_POST['name'])){
	$ids=explode("-", $_POST['name']);
	if(is_numeric($ids[0])&& is_numeric($ids[1])){
	$ch= new triagens\ArangoDb\comments($ids[0]);
	$check= $ch->commentByKey($ids[1]);
	if(!empty($check) && $_SESSION['uid']==$check['user_key']){
		$cont=$_POST['post'];
		if (strpos($cont, '<br />')) {
	    	$cont=explode( "<br />",$cont);
			$content="";
			foreach ($cont as $key){
				$key=htmlentities($key);
				$content .=$key."<br />";
			}
			$content=coder($content);
			$content=substr_replace($content, '', -6);
		}else{
			$content=coder($cont);
		}
		$ch->update($ids[1],$content);
		echo $content;
	}
	}
}
