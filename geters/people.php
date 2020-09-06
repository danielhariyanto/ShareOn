<div id="social-box">
	<?php
	session_start();
	require '../classes/arangodb.php';
	$rel= new triagens\ArangoDb\user_relations();
	$fr= $rel->getFrom($_SESSION['uid'],'r.friend_status=="received request"');
	$r=(!empty($fr))?"":"hide";
	?>
	<img id="box-icon" src="<?=$web?>icons/1people.png"/>
	<p id="friends-button">friends</p>
    <p id="people-following-button">people you follow</p>
    <p id="friend-requests-button">friend requests</p>
    <p id="discover-people-button" class="isclick">discover people</p>
    <input id="people-box-search" onkeyup="search(this.value,'social','discover')" type="search">
	<img id="people-search-icon" src="http://localhost/ShareOn/icons/Loupe.png"/>
	<div id="people-list-container">
		<?php
		$array= $rel->friendsOfFriends($_SESSION['uid']);
		foreach ($array as $output) {
			echo "<div class='person-outer-container' key='".$output[0]['_key']."'>
        <div class='person-container' onClick='window.location=\"".$web."profiles/".$output[0]['username']."/posts\";'>
          <img class='picture' src='".$output[0]['profile_picture']."'/>
          <p class='name'>".$output[0]['name']."</p>
          <p class='username'>@".$output[0]['username']."</p>
        </div><div class='list-friend-button'>";
        	if(isset($output[1]['friend_status'])&&$output[1]['friend_status']==1){
			echo "<img class='v' src='".$web."icons/v.png'/>
          <p class='text'>Friend</p>";
		}elseif(isset($output[1]['friend_status'])&&$output[1]['friend_status']=="pending"){
				echo "<img src='".$web."icons/add-friend.png'/>
          <p class='text'>Friend Request Sent</p>";
		}elseif(isset($output[1]['friend_status'])&&$output[1]['friend_status']=="received request"){
				echo "<img src='".$web."icons/add-friend.png'/>
          <p class='text'>Accept Friend Request</p>";
		}else{
			echo "<img src='".$web."icons/add-friend.png'/>
          <p class='text'>Add Friend</p>";
		}
		echo "</div>
        <div class='list-follow-button'>";
		if(isset($output[1]['follow'])&&$output[1]['follow']==1){
			echo "<img class='v' src='".$web."icons/v.png'/>
	          <p class='text'>Following</p>";
		}else{
			echo "<img src='".$web."icons/follow.png'/>
	          <p class='text'>Follow</p>";
		}
		echo "</div>
	      </div>";
		}
		?>
	</div>
  </div>