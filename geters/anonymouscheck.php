<?php
	$password= password($password);
	$check= new triagens\ArangoDb\posts();
	$check=$check->getByKey($key);
	if($check['user_key']['password']==$password){
		echo "good";
	}
?>