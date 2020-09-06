<?php
error_reporting(0);
$s=$search;
if(!empty($s)){
	if($s[0]=="#"){
		$s=substr($s, 1);
		$query='LET t=(FOR t IN topics FILTER LIKE(LOWER(t.name),CONCAT(CONCAT("%",LOWER("'.$s.'")),"%")) LIMIT 9 RETURN [t,"#"])
		RETURN t';
	}elseif($s[0]=="*"){
		$s=substr($s, 1);
		$query='LET b=(FOR b IN tool_bubble FILTER (LIKE(LOWER(b.name),CONCAT(CONCAT("%",LOWER("'.$s.'")),"%"))&& b.published LIMIT 9 RETURN [b,"*"])
		RETURN b';
	}elseif($s[0]=="@"){
		$s=substr($s, 1);
		$query='LET u=(FOR u IN users FILTER LIKE(LOWER(u.name),CONCAT(CONCAT("%",LOWER("'.$s.'")),"%"))&&u._key!="'.$_SESSION['uid'].'" LIMIT 9 RETURN [u,"@"])
		RETURN u';
	}else{
		$t='FOR t IN topics FILTER LIKE(LOWER(t.name),CONCAT(CONCAT("%",LOWER("'.$s.'")),"%")) LIMIT 4 RETURN [t,"#"]';
		$u='FOR u IN users FILTER LIKE(LOWER(u.name),CONCAT(CONCAT("%",LOWER("'.$s.'")),"%"))&&u._key!="'.$_SESSION['uid'].'" LIMIT 4 RETURN [u,"@"]';
		$b='FOR b IN tool_bubble FILTER LIKE(LOWER(b.name),CONCAT(CONCAT("%",LOWER("'.$s.'")),"%")) && b.published LIMIT 4 RETURN [b,"*"]';
		$query='LET t=('.$t.')
		LET u=('.$u.')
		LET b=('.$b.')
		RETURN UNION(b,UNION(u,t))';
	}
$statement = new triagens\ArangoDb\Statement(
    $connection,
    array(
        "query"=> $query,
    )
);
$cursor = $statement->execute();
$transit= $cursor->getAll();
$final=array();
$i=0;
foreach ($transit as $key) {
	$final[$i]=$key->getAll();
	$i++;
}
echo json_encode($final[0]);
}else {
	echo json_encode(array());
}
