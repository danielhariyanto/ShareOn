<?php
if(isset($key,$add)){
$uid=(isset($_SESSION['uid']))?$_SESSION['uid']:"";
$c= new triagens\ArangoDb\posts();
$ckey= $c->getByKey(htmlentities($key));
if(isset($ckey['content'],$ckey['type'],$ckey['view_option'])) {
$uv = new triagens\ArangoDb\votes($ckey['_key']);
$uservote = $uv->userVote((string)$uid);
$uservote = (!empty($uservote['vote'])) ? $uservote['vote'] : "";
$pcount = $ckey['upvotes'];
$ncount = $ckey['downvotes'];
if (is_array($ckey['user_key'])) {
    $name = (isset($ckey['user_key']['name']) && $ckey['user_key']['name']!="")?$ckey['user_key']['name']:"anonymous";
    $profilepicture = (isset($ckey['user_key']['picture']) && $ckey['user_key']['picture']!="")?$ckey['user_key']['picture']:$web."icons/anonymous.png";
	$profile = "<div class=\"anonymous-holder\" key='".$add."-".$ckey['_key']."'>
    <img class=\"anonymous-picture\" src='$profilepicture'/>
    <p class=\"anonymous-name\">".$name."</p>
  </div>";
} else {
    $us = new triagens\ArangoDb\users();
    $user = $us->userInfoByKey($ckey['user_key']);
    $profilepicture = $user['profile_picture'];
    $profile = ($user['_key'] == 0) ? "" : "profiles/" . $user['username'] . "/posts";
    $name = $user['name'];
	$profile="<div onClick=\"window.location='".$profile."';\" key='".$add."-".$ckey['_key']."' class=\"op-holder\">
        <img class='op-picture' src='" . $profilepicture . "'/>
        <p class='op-name'>". $name . "</p>
      </div>";					
}
$ccount = bignumberkiller($ckey['comments']);
$pcount = bignumberkiller($ckey['upvotes']);
$ncount = bignumberkiller($ckey['downvotes']);
$uc = ($uservote == 1) ? " clicked" : "";
$dc = ($uservote == -1) ? " clicked" : '';
$c = new triagens\ArangoDb\toolBubble();
$c = $c->toolInfoByKey($ckey['type']);
$size=($c['size']=="auto")?"onload='resizeIframe(this)'":"height='".$c['size']."'";
$content = "<iframe class='comment-content' key='".$add."-".$ckey['_key']."' scrolling='".$c['scroll']."' ".$size." src='".$c['post']."?key=".$ckey['_key']."'></iframe>";
echo "<div class='comment' key='".$add."-".$ckey['_key']."'>" . $profile . "
<img class='comment-options' src='".$web."icons/clockwork.png' key='".$add."-".$ckey['_key']."'/>" . $content ."
						<div class='post-bottom'>
							<p class='upvote-count' key='" .$add."-". $ckey['_key'] . "'>" . $pcount . "</p>
							<div class='upvote-icon" . $uc . "' key='" .$add."-". $ckey['_key'] . "'></div>
							<p class='downvote-count' key='" .$add."-". $ckey['_key'] . "'>" . $ncount . "</p>
							<div class='downvote-icon" . $dc . "' key='".$add."-". $ckey['_key'] . "'></div>
                    <div class='comments-button notclick' key='" .$add."-". $ckey['_key'] . "'>
					<img class='icon' src='" . $web . "icons/Comments.png'/>
        			<p class='comments-button-text' key='" . $add ."-". $ckey['_key'] .  "'>" . $ccount . " comment(s)</p>
					</div>";
					  if ($ckey['view_option'] == "global") {
				      echo "<div class='recommend-button' key='" . $add . "'>
				        <img class='icon' src='".$web . "icons/Recommend.png'>
				        <p class='text'>recommend</p>
				        </div>
				      </div>";
					  }
			echo "</div></div>";
	}elseif(isset($ckey['view_option']) && $ckey['view_option']=="deleted"){
        	$uv = new triagens\ArangoDb\votes($ckey['_key']);
            $uservote = (isset($uid))?$uv->userVote((string)$uid):'';
            $uservote = (!empty($uservote['vote'])) ? $uservote['vote'] : "";
            $pcount = $ckey['upvotes'];
            $ncount = $ckey['downvotes'];
			$replyto=(isset($ckey['replyTo']) && !empty($ckey['replyTo']))?"<p class='reply-text'>Reply To:</p>":"<p class='reply-text'> gs</p>";
            $ccount = bignumberkiller($ckey['comments']);
            $pcount = bignumberkiller($ckey['upvotes']);
            $ncount = bignumberkiller($ckey['downvotes']);
            $uc = "";
            $dc = '';
            $content = "<div class='comment-content'>deleted</div>";
                echo "<div class='comment' key='".$add."-".$ckey['_key']."'>
                <div class='post-content deleted'>" . $content . "</div>
                <div class='post-bottom'>
			      <p class='upvote-count' key='" .$add."-". $ckey['_key'] . "'>" . $pcount . "</p>
					<div class='upvote-icon" . $uc . "' key='" .$add."-". $ckey['_key'] . "'></div>
					<p class='downvote-count' key='" .$add."-". $ckey['_key'] . "'>" . $ncount . "</p>
					<div class='downvote-icon" . $dc . "' key='".$add."-". $ckey['_key'] . "'></div>
			      <div class='comments-button notclick' key='".$add."-". $ckey['_key'] . "'>
			        <img class='icon' src='" . $web . "icons/Comments.png'/>
			        <p class='comments-button-text' key='".$add."-". $ckey['_key'] . "'>" . $ccount . " comment(s)</p>
			      </div>";
				  echo "</div></div></div>";
        }
}