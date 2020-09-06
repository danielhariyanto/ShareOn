<?php
require 'session_start.php';
require 'classes/arangodb.php';//loads arangodb classes
require 'classes/digitalocean.php';
if(isset($_SESSION['uid'])){//sets session info
	$sess= new triagens\ArangoDb\sessions($_SESSION['uid']);
$h=(array)$sess->getFilters();
unset($h['_key']);//in order not to add _key
	$_SESSION['filters']=$h;//sets up the filter array in session
	if(!isset($_SESSION['main_page']))//checks if main page is wall or all
    $_SESSION['main_page']="wall";
	$u= new triagens\ArangoDb\users();
	$t= $u->getByKey($_SESSION['uid']);
	$username=$t['username'];
	$name= $t['name'];
	$profile= $web.'profiles/'.$t['username']."/posts";//sets link to user profile
	$picture=(isset($t['profile_picture'])&&$t['profile_picture']!="")?$t['profile_picture']:"/icon/profile_picture.png";
}
$order="";
$flipped="";
if(isset($_SESSION['filters']['posts']['order'])){
$h=explode("-", $_SESSION['filters']['posts']['order']);
if($h[1]==1||$h[1]==0)
$flipped=$h[1];
$order=$h[0];
}
$out=[];
$ex=[];
$t=new triagens\ArangoDb\topics();
$b=new triagens\ArangoDb\toolBubble();
if(isset($_SESSION['filters']['posts']['out'])){
	for ($i=0; $i < count($_SESSION['filters']['posts']['out']); $i++) {
		if($_SESSION['filters']['posts']['out'][$i][0]=="#"){
			$temp=substr($_SESSION['filters']['posts']['out'][$i],1);
			$h=$t->getByKey($temp);
			if(!empty($h))
			array_push($ex,array("val"=>$_SESSION['filters']['posts']['out'][$i],"name"=>"#".$h['name']));
			else
			array_push($ex,array("val"=>$_SESSION['filters']['posts']['out'][$i],"name"=>$_SESSION['filters']['posts']['out'][$i]));
		}elseif ($_SESSION['filters']['posts']['out'][$i][0]=="*") {
			$temp=substr($_SESSION['filters']['posts']['out'][$i],1);
			$h=$b->getByKey($temp);
			if(!empty($h))
			array_push($out,array("val"=>$_SESSION['filters']['posts']['out'][$i],"name"=>"*".$h['name']));
		}elseif ($_SESSION['filters']['posts']['out'][$i][0]=="@") {
			$temp=substr($_SESSION['filters']['posts']['out'][$i],1);
			$h=$u->getByKey($temp);
			if(!empty($h))
			array_push($ex,array("val"=>$_SESSION['filters']['posts']['out'][$i],"name"=>"@".$h['username']));
			else
			array_push($ex,array("val"=>$_SESSION['filters']['posts']['out'][$i],"name"=>$_SESSION['filters']['posts']['out'][$i]));
		}else{
			array_push($out,array("val"=>$_SESSION['filters']['posts']['out'][$i],"name"=>$_SESSION['filters']['posts']['out'][$i]));
		}
	}
}
	if(isset($_SESSION['filters']['posts']['exclusively'])){
		for ($i=0; $i < count($_SESSION['filters']['posts']['exclusively']); $i++) {
			if($_SESSION['filters']['posts']['exclusively'][$i][0]=="#"){
				$temp=substr($_SESSION['filters']['posts']['exclusively'][$i],1);
				$h=$t->getByKey($temp);
				if(!empty($h))
				array_push($ex,array("val"=>$_SESSION['filters']['posts']['exclusively'][$i],"name"=>"#".$h['name']));
				else
				array_push($ex,array("val"=>$_SESSION['filters']['posts']['exclusively'][$i],"name"=>$_SESSION['filters']['posts']['exclusively'][$i]));
			}elseif ($_SESSION['filters']['posts']['exclusively'][$i][0]=="*") {
				$temp=substr($_SESSION['filters']['posts']['exclusively'][$i],1);
				$h=$b->getByKey($temp);
				if(!empty($h))
				array_push($ex,array("val"=>$_SESSION['filters']['posts']['exclusively'][$i],"name"=>"*".$h['name']));
			}elseif ($_SESSION['filters']['posts']['exclusively'][$i][0]=="@") {
				$temp=substr($_SESSION['filters']['posts']['exclusively'][$i],1);
				$h=$u->getByKey($temp);
				if(!empty($h))
				array_push($ex,array("val"=>$_SESSION['filters']['posts']['exclusively'][$i],"name"=>"@".$h['username']));
				else
				array_push($ex,array("val"=>$_SESSION['filters']['posts']['exclusively'][$i],"name"=>$_SESSION['filters']['posts']['exclusively'][$i]));
			}else{
				array_push($ex,array("val"=>$_SESSION['filters']['posts']['exclusively'][$i],"name"=>$temp));
			}
		}
}
$head="<link href='https://fonts.googleapis.com/css?family=Bree+Serif' rel='stylesheet' type='text/css'>
		<link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
		<link rel='stylesheet' href='path/to/font-awesome/css/font-awesome.min.css'>
		<link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css' integrity='sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T' crossorigin='anonymous'>
		<link rel='stylesheet' type='text/css' href='".$web."css/mystyle.css'>
		<link rel='stylesheet' type='text/css' href='".$web."css/posts.css'>
		<link rel='stylesheet' type='text/css' href='".$web."css/store.css'>
		<link rel='stylesheet' type='text/css' href='".$web."css/main.css'>
		<link rel='stylesheet' type='text/css' href='".$web."css/editor.css'>
		<link rel='stylesheet' type='text/css' href='".$web."css/topicInfo.css'>
		<link rel='stylesheet' type='text/css' href='".$web."css/infoBox.css'>
		<link rel='stylesheet' type='text/css' href='".$web."css/magnific-popup.css'>
		<script type='text/javascript' src='".$web."src/jquery-2.1.4.min.js'></script>
		  <link rel='stylesheet' href='http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css'>
			<link rel='stylesheet' type='text/css' href='https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.css'>
		<script type='text/javascript' src='".$web."src/file_upload.js'></script>
		<script type='text/javascript' src='".$web."src/functions.js'></script>
		<script type='text/javascript' src='".$web."src/jquery.magnific-popup.js'></script>
		<script type='text/javascript' src='".$web."src/jquery-ui/jquery-ui.js'></script>
		<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.js'></script>
		<script type='text/javascript' src='".$web."webcomponents/posts.js'></script>
		<script type='text/javascript' src='".$web."webcomponents/topicInfo.js'></script>
		<script type='text/javascript' src='".$web."webcomponents/main.js'></script>
		<script type='text/javascript' src='".$web."webcomponents/store.js'></script>
		<script type='text/javascript' src='".$web."webcomponents/order.js'></script>
		<script type='text/javascript' src='".$web."webcomponents/infoBox.js'></script>
		<script type='text/javascript' src='".$web."arango_functions/topTrends.js'></script>
        <script src='https://js.pusher.com/6.0/pusher.min.js'></script>
		<script type='text/javascript' src='".$web."webcomponents/editor.js'></script>
		<script type='text/javascript' src='".$web."arango_functions/topTrends.js'></script>
		<script src='https://kit.fontawesome.com/e59ac359f5.js' crossorigin='anonymous'></script>";
$side='<div id="bubble-container">
  <div id="posts-bubble" class="notclick bubble-option"><img src="'.$web.'icon/posts.png"/><p>Share your content</p></div>
  <!--
  <div id="social-bubble" class="notclick bubble-option"><img src="'.$web.'icon/social.png"/><p>People</p></div>
  <div id="chats-bubble" class="notclick bubble-option"><img src="'.$web.'icon/chats.png"/><p>Chats</p></div>
  <div id="recommend-bubble" class="notclick bubble-option"><img src="'.$web.'icon/recommend.png"/><p>Recommendations</p></div>
  <div id="collections-bubble" class="notclick bubble-option"><img src="'.$web.'icon/collections.png"/><p>Collections</p></div>
  <div id="world-bubble" class="notclick bubble-option"><img  /></div>-->
  </div>';
$header="<header id='background-header'>
    <a id='header-logo' href='$web'><img style='width: 100%;height: 100%' src='".$web."icon/logo-white.png'/></a>
  <header id='transparent-header'>
    <div id='main-search-bar'><input placeholder='search' type='search'></div>
    <div id='right-header-block'>";
    	if(isset($_SESSION['uid'])){
    		$header.="<div id='header-dropdown-content' class='invisible'>
    		<ul><li id='sign-out'>Sign Out</li>
    		<!--<li id='delete-account'>Delete Account</li>-->
    		</ul>
    		</div>
    		<div id='alert-box' class='invisible'>
			</div>
    		<img id='header-dropdown' src='".$web."icon/white-arrow.png'/>
    		<a id='header-profile' href='$profile'>
		      <img class='picture' src='$picture'/>
		      <p>$name</p>
		    </a>
		    <img src='".$web."icon/bell-white.png' id='main-notification-bell' />";
    	}else{
    		$header.= "<div id='header-sign-on' class='btn btn-success'>
	        <p>Sign On</p>
	      </div>

	      <div id='header-sign-up' class='btn btn-primary'>
	        <p>Sign Up</p>
	      </div>";
		}

  $header.="</div>
  </header>
  </header>";
$bottom="<script type='text/javascript' src='".$web."src/functions.js'></script>
	<script type='text/javascript' src='".$web."js/post-buttons.js'></script>
	<script type='text/javascript' src='".$web."js/bubbleposts.js'></script>
	<script type='text/javascript' src='".$web."js/bubblesocial.js'></script>
	<script type='text/javascript' src='".$web."js/bubblerecommend.js'></script>
	<script type='text/javascript' src='".$web."js/bubblesort.js'></script>
	<script type='text/javascript' src='".$web."js/collection.js'></script>
	<script type='text/javascript' src='".$web."js/searchbar.js'></script>
	<script type='text/javascript' src='".$web."js/pages.js'></script>";
    if(isset($_SESSION['uid']))
    {
			$currentPageUrl =$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
			$c=explode("/",$currentPageUrl);
			$c=count($c);
			$f="";
			for($i=0;$i<$c-2;$i++){
				$f.="../";
			}
			$f.='./authenticate.php';
			$b=$_SESSION['filters']['bubbles'];
				$bubble= new triagens\ArangoDb\toolBubble();
				$arr=array();
				foreach($b as $key){
					$a= $bubble->getById($key);
					if (!empty($a))
					array_push($arr,$a[0]);
				}
				$bubbles=json_encode($arr);
        $bottom.= "<script>
				Pusher.log = function() {
				if (window.console) window.console.log.apply(window.console, arguments);
				};
				var options = {
					authEndpoint: '".$f."',
	        cluster: 'us2'
	      };
	      var appKey = '38c6fc55f6c3d7c3756d';
	      var pusher = new Pusher(appKey, options);

	      /*
	      Subscribe to the presence channel
	      */
      var privateChannel = pusher.subscribe('private-".$_SESSION['uid']."');
      /*
      Bind to the subscription success event and handle it with an inline function.
      */
      privateChannel.bind('notifications', function(e) {

					$('body').append('<div id=\"notification_box\" style=\"opacity:0\"></div>');
					$('#notification_box').animate({opacity:1},200);
					addNotification('#notification_box',e);
          $('#main-notification-bell').attr('src','".$web."icon/bell-color.png');
					setTimeout(function(){
						$('#notification_box').animate({opacity:0},200,function(){
						$(this).remove();
						});
					},8000);
      });
			sessionStorage.bubbles=JSON.stringify(".$bubbles.");
			var t=JSON.parse(sessionStorage.bubbles);
			for(var i=0;i<t.length;i++)
			preloadImage('/post-types/'+t[i]['_key']+'/icon.png');";
    $bottom.="</script>";
    }
