<?php
if(isset($_GET['user'])){
	require "require.php";
	$us= new triagens\ArangoDb\users();
	$usern= $us->userInfoByUsername($_GET['user']);
	$birthday=$usern['birth'];
	$birthday=explode("-", $birthday);
	if($birthday[1]==1)
		$birthday[1]="January";
  	elseif($birthday[1]== 2)
  		$birthday[1]="February";
  	elseif($birthday[1]==3)
  		$birthday[1]="March";
  	elseif($birthday[1]==4)
  		$birthday[1]="April";
  	elseif($birthday[1]==5)
  		$birthday[1]="May";
  	elseif($birthday[1]==6)
  		$birthday[1]="June";
  	elseif($birthday[1]==7)
  		$birthday[1]="July";
  	elseif($birthday[1]==8)
  		$birthday[1]="August";
  	elseif($birthday[1]==9)
  		$birthday[1]="September";
  	elseif($birthday[1]==10)
  		$birthday[1]="October";
  	elseif($birthday[1]==11)
  		$birthday[1]="November";
  	elseif($birthday[1]==12)
  		$birthday[1]="December";
	$birthday=$birthday[1]." ".$birthday[2]." ".$birthday[0];
	$key=(!empty($usern))?$usern['_key']:array();
	if(!empty($key)){
	$puser= $usern;
	if(!isset($_GET['tag']) || (isset($_GET['tag']) && $_GET['tag'] != "collections" && $_GET['tag'] != "friends" && $_GET['tag'] != "followers" && $_GET['tag'] != "info")){
		$click="posts";
	}elseif(isset($_GET['tag']) && $_GET['tag'] == "collections"){
		$click="collections";
	}elseif(isset($_GET['tag']) && $_GET['tag'] == "friends"){
		$click="friends";
	}elseif(isset($_GET['tag']) && $_GET['tag'] == "followers"){
		$click="followers";
	}elseif(isset($_GET['tag']) && $_GET['tag'] == "info"){
		$click="info";
	}
	if(isset($username) && $username != $puser['username']){
	$rel= new triagens\ArangoDb\user_relations();
	$status= $rel->getFromTo($_SESSION['uid'],$usern['_key']) ;
	}
	}
	$follow=(isset($status['follow']))?$status['follow']:"";
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>ShareOn</title>
		<meta charset="utf-8">
		<?=$head?>
		<link rel='stylesheet' type='text/css' href='<?=$web?>css/profile.css'>
		<script type='text/javascript' src='<?=$web?>js/profilespecialbuttons.js'></script>
		<script>
			$.fn.preload = function() {
    			this.each(function(){
			        $('<img/>')[0].src = this;
			    });
			}

			// Usage:

			$(['alexblattner/profile_picture/surf.jpg']).preload();

		</script>
	</head>
<body>
	<?=$header?>
	<?php
    if(isset($_SESSION['uid'])){
			$side=str_replace('<div id="posts-bubble"','<div id="posts-bubble" username="'.$_GET['user'].'"',$side);
		echo $side;
	}
	$order="";
	$flipped="";
	if(isset($_SESSION['filters']['posts']['order'])){
	$h=explode("-", $_SESSION['filters']['posts']['order']);
	if($h[1]==1||$h[1]==0)
	$flipped=$h[1];
	$order=$h[0];
	}
  ?>
		<div id="top-profile-container">
				<div id="profile-picture"><img src="<?=(isset($puser['profile_picture'])&&$puser['profile_picture']!="")?$puser['profile_picture']:"/icon/profile_picture.png"?>"/></div>
				<div id="name-container">
					<p id="profile-name"><?=$puser['name']?></p>

				</div>
				<div id="profile-info">
				<div id="profile-username"><p>@</p><?=$_GET['user']?></div>
				<div id="profile-member-since"> Member since: <script>document.write(timeSince(<?=$usern['joined']?>));</script></div>
				<div id="profile-birthday"> Birthday: <?=$birthday?></div>
				</div>
							<div id="infobox"></div>
							<?php
							if(isset($_SESSION['uid'])){
								if($_SESSION['uid']==$key){
									echo '<div id="profile-edit-button"><p class="text">Edit Profile</p></div>';
								}elseif($follow==1){
									echo "<div id='follow-button'><p class='text'>Following</p></div>";
								}else {
									echo "<div id='follow-button'><p class='text'>Follow</p></div>";
								}
							}
					 ?>
				</div><!--<div id="profile-selector-container">
				<div class="profile-selector${(select==0)?' isclick':''}" data="posts">
					<p class="text">Posts</p>
				</div>
				<div class="profile-selector${(select==1)?' isclick':''}" data="collections">
					<p class="text">Collections</p>
				</div>
				<div class="profile-selector${(select==2)?' isclick':''}" data="friends">
					<p class="text">Friends</p>
				</div>
				<div class="profile-selector${(select==3)?' isclick':''}" data="followers">
					<p class="text">Followers</p>
				</div>
				<div class="profile-selector${(select==4)?' isclick':''}" data="info">
					<p class="text">Info</p>
				</div>
			</div>-->
    <main-container url='<?=$web?>geters/postsload.php' username="<?=$_GET['user']?>"></main-container>
    <?=$bottom?>
</body>
</html>
<?php
}
?>
