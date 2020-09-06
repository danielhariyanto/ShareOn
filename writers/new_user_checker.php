<?php
session_start();
require '../classes/arangodb.php';
$fname= preg_replace('/\P{L}+/u', '', $_POST['fname']);
$lname= preg_replace('/\P{L}+/u', '', $_POST['lname']);
$pass = $_POST['pass'];
	$name=ucfirst(strtolower($fname)).' '.ucfirst(strtolower($lname));
	$username=strtolower($fname.$lname);
	$usern= new triagens\ArangoDb\users();
	$username= $_POST['username'];
	$password= password($pass);
	$birth= $_POST['year']."-".$_POST['month']."-".$_POST['day'];
	if($username!="" && $name!="" && $birth !=""){
		$usern->insertNewUser($username, $password, $name, $birth);
		$id=$usern->userInfoByUsername($username);
		$_SESSION['uid']= $id['_key'];
		$_SESSION['main_page']="all";
		$sess=new triagens\ArangoDb\sessions($_SESSION['uid']);
		$sess->newUserSession();
	}else{
		echo "fuck you hacker";
	}
?>