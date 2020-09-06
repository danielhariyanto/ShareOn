	<?php
    $c= new triagens\ArangoDb\collections();
	$collections= $c->getUserCollection($_SESSION['uid']);
	
	echo json_encode($collections);