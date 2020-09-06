<?php
session_start();
    require '../classes/arangodb.php';
	if(isset($_POST['filter'],$_POST['pkey'])){
		$sendto=(array)json_decode($_POST['filter']);
		$pkey=($_POST['pkey']);
		$p= new triagens\ArangoDb\posts;
		$u_r=new triagens\ArangoDb\user_relations;
		if($p->isGlobal($pkey)){
			$r= new triagens\ArangoDb\recommendations;
			if(isset($sendto['followers'])){
				foreach ($u_r->getTo($_SESSION['uid'], "r.follow=='1'") as $key) {
					$r->recommendTo($pkey, str_replace("users/", "", $key['_from']), $_SESSION['uid']);
				}
			}
			if(isset($sendto['friends'])){
				foreach ($u_r->getFrom($_SESSION['uid'], "r.friend_status=='1'") as $key) {
					$r->recommendTo($pkey, str_replace("users/", "", $key['_to']), $_SESSION['uid']);
				}
			}
		}
	}
?>