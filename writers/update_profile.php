<?php
session_start();
require '../classes/arangodb.php';
require '../functions.php';
echo ($_POST['image']);
if(isset($_POST['image']) && (!filter_var($_POST['image'], FILTER_VALIDATE_URL) === false)) {
    $f = new triagens\ArangoDb\files();
    $f->delete($_POST['image']);
$u = new triagens\ArangoDb\users;
$user=$u->userInfoByKey($_SESSION['uid']);
if (!empty($user['profile_picture']))
    unlink(str_replace($web, $_SERVER['DOCUMENT_ROOT'] . '/YUGIV/', $user['profile_picture']));
$u->updateProfilePicture($_SESSION['uid'], $_POST['image']);
}