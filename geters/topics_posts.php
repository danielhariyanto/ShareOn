<?php
	$uid= $_SESSION['uid'];
	$un = new triagens\ArangoDb\users();
	$username= $un->userInfoByKey($uid);
	$username=$username['username'];
	$rules=" && (p.view_option=='global'";
	$start="";
	if(!isset($_SESSION['filters']['friends'])){
		$start="FOR r IN user_relations";
		$rules.=" || (p.view_option=='friends' && r._from=='users/".$_SESSION['uid']."' && r._to==CONCAT('users/',p.user_key) && r.friend_status=='1') || (p.user_key=='".$_SESSION['uid']."' && p.view_option=='friends')";
	}
	if(!isset($_SESSION['filters']['restricted'])){
		$rules.=" || (IS_ARRAY(p.view_option) &&  POSITION(p.view_option,'".$_SESSION['uid']."'))";
	}
	$rules.=")";
	if(isset($_GET['user'])){
		$ukey=$un->userInfoByUsername(htmlentities($_GET['user']));
		$rules.=" && p.user_key=='".$ukey['_key']."'";
	}
	if (isset($_SESSION['filters']) && !empty($_SESSION['filters'])) {
		$cleanget=$_SESSION['filters']['posts'];
		if(isset($cleanget->pointscheck)){
				$carr= explode('_', $cleanget->pointscheck);
				if(empty($carr[0]) && !is_numeric($carr[0])){
					$rules.=" && (p.upvotes-p.downvotes)<=".$carr[1];
				}elseif(empty($carr[1]) && !is_numeric($carr[1])){
					$rules.=" && (p.upvotes-p.downvotes)>=".$carr[0];
				}else{
					$rules.=" && (p.upvotes-p.downvotes)>=".$carr[0]." AND (posts.upvotes-posts.downvotes)<=".$carr[1];
				}
			}
			if(isset($cleanget->upvotescheck)){
				$pcarr= explode('_', $cleanget->upvotescheck);
				if(empty($pcarr[0]) && !is_numeric($pcarr[0])){
					$rules.=" && p.upvotes<=".$pcarr[1];
				}elseif(empty($pcarr[1]) && !is_numeric($pcarr[1])){
					$rules.=" && p.upvotes>=".$pcarr[0];
				}else{
					$rules.=" && p.upvotes>=".$pcarr[0]." && p.upvotes<=".$pcarr[1];
				}
			}	
			if(isset($cleanget->downvotescheck)){
				$ncarr= explode('_', $cleanget->downvotescheck);
				if(empty($ncarr[0]) && !is_numeric($ncarr[0])){
					$rules.=" && p.downvotes<=".$ncarr[1];
				}elseif(empty($ncarr[1]) && !is_numeric($ncarr[1])){
					$rules.=" && p.downvotes>=".$ncarr[0];
				}else{
					$rules.=" && p.downvotes>=".$ncarr[0]." && p.downvotes<=".$ncarr[1];
				}
			}
			if(isset($cleanget->global)){
				$rules.=" && p.view_option!='global'";
			}
			if(isset($cleanget->friends)){
				$rules.=" && p.view_option!='friends'";
			}
			if(isset($cleanget->personal)){
				$rules.=" && p.view_option!='personal'";
			}
	}
	
		$ps = new triagens\ArangoDb\posts();
		$parray= $ps->loadAllPosts($start,$rules,"time DESC");
		foreach($parray as $key){
		    if(isset($key['content'],$key['type'],$key['view_option'])) {
                $uv = new triagens\ArangoDb\votes($key['_key']);
                $uservote = $uv->userVote((string)$uid);
                $uservote = (!empty($uservote['vote'])) ? $uservote['vote'] : "";
                $pcount = $key['upvotes'];
                $ncount = $key['downvotes'];
                if ($key['user_key'] == "") {
                    $ano = new anonymous($key['_key']);
                    $ano = $ano->get();
                    $name = (isset($ano['name']) && $ano['name']!="")?$ano['name']:"anonymous";
                    $profilepicture = (isset($ano['picture']) && $ano['picture']!="")?$ano['picture']:$web."icons/anonymous.png";
					$profile = "<div class='nameholder' style='background: lightgrey'><img class='profilepicture' src='" . $profilepicture
					. "'/><span class='profilename'>" . $name . "</span></div>";
                } else {
                    $us = new triagens\ArangoDb\users();
                    $user = $us->userInfoByKey($key['user_key']);
                    $profilepicture = $user['profile_picture'];
                    $profile = ($user['_key'] == 0) ? "" : "profiles/" . $user['username'] . "/posts";
                    $name = $user['name'];
					$profile= "<a class='nameholder' href='" . $profile . "'><img class='profilepicture' src='" . $profilepicture . "'/><span class='profilename'>" . $name . "</span></a>";
					
                }
				$replyto=(isset($key['replyTo']) && !empty($key['replyTo']))?"<a class='replynotice' href='post/".$key['replyTo']."'> >>".$key['replyTo']."</a>":"";
                $ccount = bignumberkiller($key['comments']);
                $pcount = bignumberkiller($key['upvotes']);
                $ncount = bignumberkiller($key['downvotes']);
                $uc = ($uservote == 1) ? " clicked" : "";
                $dc = ($uservote == -1) ? " clicked" : '';
                $c = new triagens\ArangoDb\toolBubble();
                $c = $c->toolInfoByKey($key['type']);
                $content = (isset($c['processor'])) ? arrange($key['content'], $c['processor']) : $key['content'];
                    echo "<div class='postbox' key='" . $key['_key'] . "'>".$replyto.
						"<div class='content'>" . $content . "</div>
						".$profile."<br><br>
						<div key='" . $key['_key'] . "' class='settcontain notclick'><img name='" . $key['_key'] . "' class='settings' src='" . $web . "icons/settings.png'/></div>
							<div class='lineorder'>
								<div class='likecontainer unselectable'>
								<div class='upcount' key='" . $key['_key'] . "'>" . $pcount . "</div>
								<div class='up" . $uc . "' key='" . $key['_key'] . "' >
								<img class='img' src='";
                    if ($uservote == 1) {
                        echo $web . "icons/greenuparrow.png";
                    } else {
                        echo $web . "icons/greyuparrow.png";
                    };
                    echo "'/>
								</div>
								<div class='downcount' key='" . $key['_key'] . "'>" . $ncount . "</div>
								<div class='down" . $dc . "' key='" . $key['_key'] . "' >
									<img class='img' src='";
                    if ($uservote == -1) {
                        echo $web . "icons/reddownarrow.png";
                    } else {
                        echo $web . "icons/greydownarrow.png";
                    };
                    echo "'/>
								</div>
							</div>";
							if ($key['view_option'] == "global") {
                                echo "<div class='recommendcontainer unselectable' key='" . $key['_key'] . "'> recommend<div class='share' name='" . $key['_key'] . "'>
								<img class='img' src='" . $web . "icons/blackshare.png'/>
							</div></div>";
                            }
								echo "<div class='commentcontainer unselectable notclick' key='" . $key['_key'] . "'>" . $ccount . " comment(s)<div class='speech' name='" . $key['_key'] . "s'>
									<img class='img' src='" . $web . "icons/greyspeech.png'/>
								</div>
								</div>
							</div>
				</div>";
            }
		}