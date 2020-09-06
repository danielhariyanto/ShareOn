<?php
require "require.php"; //loads the backend
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>ShareOn</title>
		<meta charset="utf-8">
		<link href='https://fonts.googleapis.com/css?family=Bree+Serif' rel='stylesheet' type='text/css'>
		<link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" type="text/css" href="<?=$web?>css/mystyle.css">
		<link rel="stylesheet" type="text/css" href="<?=$web?>css/magnific-popup.css">		
		<script type='text/javascript' src='<?=$web?>src/jquery-2.1.4.min.js'></script>
		<link rel="stylesheet" type="text/css" href="<?=$web?>css/tipsy.css">
		<link rel="stylesheet" type="text/css" href="<?=$web?>css/jquery-ui.css">
		<script type="text/javascript" src="<?=$web?>src/jquery.tipsy.js"></script>
		<script type='text/javascript' src='<?=$web?>src/file_upload.js'></script>
		<script type='text/javascript' src='<?=$web?>src/fileupload.js'></script>
		<script type='text/javascript' src='<?=$web?>js/clickoutside.js'></script>
		<script type='text/javascript' src='<?=$web?>src/jquery.magnific-popup.js'></script>
		<script type='text/javascript' src='<?=$web?>src/jquery-ui/jquery-ui.js'></script>
		<script>
		</script>
	</head>
<body>
	<header id="background-header">
    <a id="header-logo" href="<?=$web?>"><img style="width: 100%;height: 100%" src="<?=$web?>icons/logo-white.png"/></a>
  </header>
  <header id="transparent-header">
    <input id="main-search-bar" placeholder="search" type="search">
    <div id="right-header-block">
    <?php
    	if(isset($_SESSION['uid'])){
    		echo "<div id='header-dropdown-content' class='invisible'>
    		<ul><li id='sign-out'>Sign Out</li>
    		<li id='delete-account'>Delete Account</li>
    		</ul>
    		</div>
    		<img id='header-dropdown' src='".$web."icons/main-dropdown.png'/>
    		<img src='".$web."icons/bell-black.png' id='main-notification-bell' />
    		<div id='header-profile' onClick=\"window.location='".$profile."';\">
		      <img class='picture' src='".$picture."'/>
		      <p>".$name."</p>
		    </div>";
    	}else{
    		echo '<div id="header-sign-on">
	        <p>Sign On</p>
	      </div>
	      <div id="header-sign-up">
	        <p>Sign Up</p>
	      </div>';
		}
    ?>
      
    </div>
  </header>	
    <div id='main-content-container'>
    <div id="error-text" style="width: 100%;font-size: 30pt;background: white;border-radius: 30px;text-align: justify;padding: 10px;color:rgb(2, 21, 61);margin-top:100px;">
    	This page doesn't exist, please try something else. Thank you.</div>
    </div>
    <script type='text/javascript' src='<?=$web?>src/functions.js'></script>
	<script type="text/javascript" src="<?=$web?>src/rotate.js"></script>
	<script type='text/javascript' src='<?=$web?>js/post-buttons.js'></script>
	<script type='text/javascript' src='<?=$web?>js/commentsbutons.js'></script>
	<script type='text/javascript' src='<?=$web?>js/setincons.js'></script>
	<script type='text/javascript' src='<?=$web?>js/bubbleposts.js'></script>
	<script type='text/javascript' src='<?=$web?>js/bubblesocial.js'></script>
	<script type='text/javascript' src='<?=$web?>js/bubblerecommend.js'></script>
	<script type='text/javascript' src='<?=$web?>js/bubblesort.js'></script>
	<script type='text/javascript' src='<?=$web?>js/profilelister.js'></script>
	<script type='text/javascript' src='<?=$web?>js/collection.js'></script>
	<script type='text/javascript' src='<?=$web?>js/searchbar.js'></script>
	<script type='text/javascript' src='<?=$web?>js/pages.js'></script>
	<script>
	</script>
</body>
</html>