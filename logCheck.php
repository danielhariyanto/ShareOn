<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: *');
require 'session_start.php';
if(isset($_SESSION['uid']))
echo 1;
else {
  echo 0;
}
 ?>
