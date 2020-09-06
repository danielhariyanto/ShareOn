<?php
session_start();
	require "xml/mysql_classes.php";
?>
<div id="notificationbox">
	<div id="notificationalign">
	<?php
	$noti= new loadNotifications($_SESSION['uid']);
	$noti= $noti->array;
	foreach ($noti as $key) {
		echo "<div class='plist unselectable'><a href='".$key['link']."'><img class='profilepicture' src='".$key['picture']."'/>".$key['sender_name']."<br>
		".$key['text']."</a></div>";
	}
	?>
	</div>
</div>