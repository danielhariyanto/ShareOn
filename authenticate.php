<?php
session_start();
require __DIR__ . '/vendor/autoload.php';
$options = array(
    'cluster' => 'us2',
    'useTLS' => true
);
$pusher = new Pusher\Pusher(
    '38c6fc55f6c3d7c3756d',
    '74ae95834120a6919f87',
    '1001901',
    $options
);
/*
Uncomment this to have internal Pusher PHP library logging
information echoed in the response to the incoming request
*/

// class EchoLogger {
//   public function log($msg) {
//     echo($msg);
//   }
// }
//
// $pusher->set_logger(new EchoLogger());

$channelName = $_POST['channel_name'];//private-4858
$socketId = $_POST['socket_id'];
$userId = explode("-",$channelName);
$userId = $userId[1];
/*
TODO: implement checks to determine if the user is:
1. Authenticated with the app
2. Allowed to subscribe to the $channelName
3. Sanitize any additional data that has been recieved and is to be used
If so, proceed...
*/
if($_SESSION['uid']==$userId){
    $auth = $pusher->socket_auth($channelName, $socketId);

    header('Content-Type: application/json');
    echo($auth);
}
