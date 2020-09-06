<?php
	$uid= $_SESSION['uid'];
	echo "<ul>";
	$p= new triagens\ArangoDb\posts();		
		$id=$p->getByKey(htmlentities($key));
		if(!empty($id)&& ($id['user_key']==$uid || is_array($id['user_key']))){
		echo "<li class='edit' key='".$key."'>edit</li><li class='delete' key='".$key."'>delete</li>";
		}
?>