<?php
if (!isset($search)) 
$search="";
$rel= new triagens\ArangoDb\user_relations();
$array= $rel->getFrom($_SESSION['uid'],'r.follow=="1" && LIKE(DOCUMENT(r._to).name, "'.$search.'%", true)',"[DOCUMENT(r._to),r]");
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
          <p class='text'>friend request sent</p>";
		}elseif(isset($output[1]['friend_status'])&&$output[1]['friend_status']=="received request"){
				echo "<img src='".$web."icons/add-friend.png'/>
          <p class='text'>accept friend request</p>";
		}else{
			echo "<img src='".$web."icons/add-friend.png'/>
          <p class='text'>add friend</p>";
		}
		echo "</div>
        <div class='list-follow-button'><img class='v' src='".$web."icons/v.png'/>
	          <p class='text'>Following</p></div>
	      </div>";
		}
?>