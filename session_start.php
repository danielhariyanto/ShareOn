<?php
if(isset($_POST['cookie'])){
  session_id($_POST['cookie']);
}
session_start();
?>
