<?php
    $t=new triagens\ArangoDb\topics();
	echo json_encode($t->getByKey($key));