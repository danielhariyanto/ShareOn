<?php
session_start();
require '../classes/arangodb.php';
$uid= $_SESSION['uid'];
$pid=htmlentities($_POST['key']);
$post=new triagens\ArangoDb\posts();
$key=$post->getByKey($pid);
if (is_array($key['user_key'])) {
    $ano = $key['user_key'];
    $name = (isset($ano['name']) && $ano['name']!="")?$ano['name']:"anonymous";
    $profilepicture = (isset($key['user_key']['picture']) && $key['user_key']['picture']!="")?$key['user_key']['picture']:$web."icons/anonymous.png";
	$profile = "<div key='".$key['_key']."' class=\"anonymous-holder op-holder\">
			        <img class=\"anonymous-picture\" src='$profilepicture'/>
			        <p class=\"anonymous-name\">".$name."</p>
			      </div>";
} else {
    $us = new triagens\ArangoDb\users();
    $user = $us->userInfoByKey($key['user_key']);
    $profilepicture = $user['profile_picture'];
    $profile = ($user['_key'] == 0) ? "" : "profiles/" . $user['username'] . "/posts";
    $name = $user['name'];
	$profile="<div onClick=\"window.location='".$profile."';\"  key='".$key['_key']."' class=\"op-holder\">
        <img class='op-picture' src='" . $profilepicture . "'/>
        <p class='op-name'>". $name . "</p>
      </div>";
	
}
echo $profile;


