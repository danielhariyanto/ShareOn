<?php
$tmp= $_FILES['file']['tmp_name'];
function fileUploader($tmp){
	global $web;
	$oname= new SplFileInfo($_FILES['file']['name']);
	$oname= ".".$oname->getExtension();
	$name= generateRandomString();
	if(file_exists($name)==FALSE){
		if(move_uploaded_file($tmp, $name.$oname)){
			echo $name.$oname;
		}
	}else{
		fileUploader($tmp);
	}
}
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
fileUploader($tmp);
?>