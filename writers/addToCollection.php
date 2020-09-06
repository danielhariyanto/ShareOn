<?php
    require '../require.php';
	$pkey=htmlentities($_POST['post_key']);
	$ckey=htmlentities($_POST['key']);
	$c= new triagens\ArangoDb\collections();
	$collections= $c->addPostToCollection($_SESSION['uid'],$pkey,$ckey);//adds to collection first user key, then postkey, then collection key
	
