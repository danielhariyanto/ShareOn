<?php
	$t=new triagens\ArangoDb\toolBubble();
	echo json_encode($t->getByKey($key));
