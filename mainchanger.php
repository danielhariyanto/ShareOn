<?php
session_start();
if (isset($_GET['choice'])){
    $_SESSION['main_page']=$_GET['choice'];
    header("Location: http://localhost/ShareOn/");
}