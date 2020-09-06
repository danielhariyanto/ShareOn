<?php
    $t= new triagens\ArangoDb\topics();
	$output= $t->searchByName($data);
	$json=array();
	$i=0;
	foreach ($output as $key) {
		$t= new triagens\ArangoDb\topics();
		$t->mapping($key);
		$trace=$t->result;
		$title="";
		foreach ($trace as $k) {
			$title.=$k['name']."->";
		}
		$title.=$key['name'];
		$json[$i]= "<div class='topicsearchresult'  key='".$key['_key']."' info='".$title."'><img class='searchimage' src='".$key['picture']."'/><div class='searchname' key='".$key['_key']."' >".$key['name']."</div></div>";
		$i++;
		
	}
	echo json_encode($json);
?>