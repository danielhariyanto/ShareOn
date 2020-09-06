<?php
require "require.php"; //loads the backend
$g= new triagens\ArangoDb\groups();
$g=$g->getByKey($_GET['key']);
var_dump($_GET['key']);
//if(!$g)
//header("Location: ".$web	);

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
		<script type="text/javascript" src="<?=$web?>src/jquery.tipsy.js"></script>
		<script type='text/javascript' src='<?=$web?>src/file_upload.js'></script>
		<script type='text/javascript' src='<?=$web?>src/fileupload.js'></script>
		<script type='text/javascript' src='<?=$web?>src/jquery.magnific-popup.js'></script>
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
	<header><input id="mainsearchbar" type="search" /><input id="mainsearchbutton" type="button" /><div id="profilecontainer"><img id='profilepictureheader' src="<?=$picture?>"/>
	<span class="ctext"><?= $name?><img src="<?=$web?>icons/notification.png" class="notification hide"/></span></div></header>
	<div id="mainlist" class="hide">
		<a href="<?=$profile?>"><div class="op">profile</div></a>
		<div id="notification" class="op">notifications<img src="<?=$web?>icons/notification.png" name="index" id="notificationbubble" class="notification hide"/></div>
		<div class="op" onclick="signout()">sign out</div>
	</div>
	<div id="mainlist" class="hide">
		<a href="<?=$profile?>"><div class="op">profile</div></a>
		<div id="notification" class="op">notifications<img src="<?=$web?>icons/notification.png" id="notificationbubble" class="notification hide"/></div>
		<div class="op" onclick="signout()">sign out</div>
	</div>
	<div id="mainprofilecontainer">
		<div><img id="profilepicture" src="<?=$g['picture']?>" />
			<div id="profile_name">
				<div style="font-size: 20pt;"><?=$g['name']?></div><br>
				<div>@<?=$g['_key']?></div>
			</div>
	<div id="profile_edit" class="unselectable">choose profile picture</div>
	<div id="recommendbubble" class="notclick"><img src="<?=$web?>icons/whiteshare.png" id="recommendbubbleicon" /><span name="recommend" people="" groups="" pages="" class="notification"></span></div>
	<div id="toolbubble" class="notclick"><img src="<?=$web?>icons/tool.png" id="toolbubbleicon" /></div>
	<div id="socialbubble" class="notclick"><img src="<?=$web?>icons/social.png" id="socialbubbleicon" /><span name="social" people="" groups="" pages="" class="notification"></span></div>
	<div id="classerbubble" class="notclick"><img src="<?=$web?>icons/class.png" id="classbubbleicon" /></div>
    
    <script type='text/javascript' src='<?=$web?>src/function.js'></script>
	<script type="text/javascript" src="<?=$web?>src/rotate.js"></script>
	<script type='text/javascript' src='<?=$web?>ajax/postbutton.js'></script>
	<script type='text/javascript' src='<?=$web?>ajax/commentsbuttons.js'></script>
	<script type='text/javascript' src='<?=$web?>ajax/settincons.js'></script>
	<script type='text/javascript' src='<?=$web?>ajax/bubbletool.js'></script>
	<script type='text/javascript' src='<?=$web?>ajax/bubblesocial.js'></script>
	<script type='text/javascript' src='<?=$web?>ajax/bubblerecommend.js'></script>
	<script type='text/javascript' src='<?=$web?>ajax/bubbleclasser.js'></script>
	<script type='text/javascript' src='<?=$web?>ajax/profilelister.js'></script>
	<script type='text/javascript' src='<?=$web?>ajax/collection.js'></script>
	<script type='text/javascript' src='<?=$web?>ajax/searchbar.js'></script>
	<script>
	if(window.EventSource){
        var source = new EventSource("<?=$web?>geters/notifications.php", { withCredentials: true });
        source.onmessage= function(event){
            if(event.data != ""){
                var data=JSON.parse(event.data);
                $(".notification[name='social']").empty();
                $(".notification[name='social']").append(data.friend)
                $(".notification[name='social']").attr('people',data.friend);
                $(".notification[name='index']").empty();
                $(".notification[name='index']").append(data.notifications)
            }
        };
    } else {
        alert("event source does not work in this browser, author a fallback technology");
    }
        $(document).on('click',"#notification", function(){
		$.get("http://localhost/YUGIV/notificationbox.php",function(data){
			$( "body" ).append(data);
			$.get("http://localhost/YUGIV/xml/notificationtrue.php",function(data){
			});
			$.magnificPopup.open({
  				items: {
			    src: $(data),
			    type: 'inline'
				},
				closeBtnInside: false,
				callbacks: {
					
					afterClose: function() {
						$( "#notificationbox" ).remove();
						$("#notificationbubble").addClass("hide");
						$("#profilecontainer .notification").addClass("hide");
					}
  				}
			});
		});
	});
	$(document).on('click',".socialbubble",function () {
		$("#notificationsocial").addClass("hide");
 	});
		function signout(){
			window.location.href = "http://localhost/YUGIV/logout.php";	
		}
    $(document).on("click",".ploader", function () {
       var name=$(this).attr("name");
    });
	</script>
</body>
</html>