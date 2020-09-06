<?php
    require '../../classes/arangodb.php';
	$p = new triagens\ArangoDb\posts();
	$c=$p->getByKey($_GET['key']);
$regex = "\b(([\w-]+:\/\/?|www[.])[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|\/)))";
$c=preg_replace('!(http|ftp|scp)(s)?:\/\/[a-zA-Z0-9.?&_\-=/]+!', "<a href=\"\\0\" target=\"\_blank\">\\0</a>",$c['content']['text']);
	echo "<p style='font-family:IBMPlexSans, Arial, sans-serif;text-align:justify;width:100%;'>".$c."</p>";
?>