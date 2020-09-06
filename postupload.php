<?php
session_start();
require 'classes/arangodb.php';
require __DIR__ . '/vendor/autoload.php';
//only posts if there is content, a view option and a post type
if (isset($_POST['content'],$_POST['view_option'],$_POST['type'],$_SESSION['uid'])&&!empty($_POST['type'])&&!empty($_POST['view_option'])) {
	$u= new triagens\ArangoDb\users;
	$u= $u->getByKey($_SESSION['uid']);
	//gets username
	$username=$u['username'];
	//sets age restriction if none existent
	$agerestriction=(isset($_POST['age_restriction']) &&($_POST['age_restriction']=='t' || $_POST['age_restriction']=='m'))?$_POST['age_restriction']:"f";
	//sets view option
	$view=(!isset($_POST['dev']))?strtolower(htmlentities($_POST['view_option'])):"dev";
	//disables scripts completely
	$content=$_POST['content'];
	//post type
	$type=(!isset($_POST['dev']))?htmlentities($_POST['type']):htmlentities($_POST['dev']);
	$topics=(isset($_POST['topics']) && !empty($_POST['topics'])&&is_array($_POST['topics']))?$_POST['topics']:array();
	for($i=0;$i<count($topics);$i++)
		$topics[$i]=allFirstUppercase(sideSpaceCleaner($topics[$i]));
	$taggedPeople=(isset($_POST['taggedPeople']) && !empty($_POST['taggedPeople'])&&is_array($_POST['taggedPeople']))?$_POST['taggedPeople']:array();
	$p =new triagens\ArangoDb\posts();
    $n= new triagens\ArangoDb\notifications();
	$rep =(isset($_POST['replyTo'])&&!empty($_POST['replyTo'])&&is_array($_POST['replyTo']))?$_POST['replyTo']:array();
	$b=new triagens\ArangoDb\toolBubble();
	$b=$b->getByKey($type);
	$view=($b['published'])?$view:"dev";
	foreach ($rep as $key) {
		$temp=$p->getByKey($key);
		if(isset($temp['view_option'])){
			if($temp['view_option']=="personal"){//if og post has view option of personal
				if($temp['user_key']==$_SESSION['uid']&&($view!="comment"||$view!="personal"))//if view option isn't comment or personal
				$view="comment";
			}elseif(is_array($temp['view_option'])&&in_array($_SESSION['uid'],$temp['view_option'])&&$view!="personal")//if og post view option is restricted then make it comment
			$view="comment";
		}else
			$view="comment";
	}
	for($i=0;$i<count($rep);$i++)
		$rep[$i]=(string)$rep[$i];
	$portrait=(isset($_POST['portrait']))?htmlentities($_POST['portrait']):"";
	$w = new triagens\ArangoDb\walls();
	$top = new triagens\ArangoDb\topics();
	$t=$top->getAndSet($topics);
	if(isset($_POST['anonymous'])&& $_POST['anonymous']=="true" && isset($_POST['apass'])){
		$aname=(isset($_POST['aname']))?htmlentities($_POST['aname']):"";
		$apass= password($_POST['apass']);
        $apic=(isset($_POST['apic']) && filter_var($_POST['apic'], FILTER_VALIDATE_URL))?$_POST['apic']:"";
        $done=false;

		if($view == "personal"||$view=="temp") {
            $id = $p->submitPost(array("name" => $aname, "password" => $apass, "picture" => $apic), array($_SESSION['uid']), $agerestriction, $type, $content, $rep, $t, $taggedPeople, $portrait);
						if(!empty($rep))
						$p->replyLink($id['_key'],$rep);
            foreach ($rep as $key) {
                $p->updateCommentsCount($key);
            }
			foreach ($rep as $key) {
				$reply=$p->getByKey($key);
				if(!empty($reply) && $reply['view_option']!="global")
					$w->insertIntoWall($_SESSION['uid'], $id);
			}
			$done=true;
		}elseif($view == "global"){
			$id=$p->submitPost(array("name"=>$aname,"password"=>$apass,"picture"=>$apic),"global",$agerestriction,$type,$content,$rep,$t,$taggedPeople,$portrait);
			if(!empty($rep))
			$p->replyLink($id['_key'],$rep);
            foreach ($rep as $key) {
                $k = $p->getByKey($key);

				if($k["user_key"]!=$_SESSION['uid'])
				{

					$n->submit($k["user_key"],$_SESSION['uid'],$id["_key"],"comment");
				}

                $p->updateCommentsCount($key);
                $options = array(
                    'cluster' => 'us2',
                    'useTLS' => true
                );
                $pusher = new Pusher\Pusher(
                    '38c6fc55f6c3d7c3756d',
                    '74ae95834120a6919f87',
                    '1001901',
                    $options
                );


				if($k["user_key"]!=$_SESSION['uid'])
				{
			      if($portrait=="") {
			        $portrait=$web."post-types/".$type."/icon.png";
			      }
			        $description="Your post was commented on by";
			        $arr= array("left"=>array("portrait"=>$portrait,"link"=>$web."posts/".$id["s_key"]),"time"=>time(),"description"=>$description,
			            "right"=>array(array("link"=>$web."profiles/".$username."/posts","name"=>$u["name"],"picture"=>$u["profile_picture"])));
					$pusher->trigger('private-'.$k["user_key"], 'notifications', $arr);
				}
            }
			$fo= new triagens\ArangoDb\user_relations();
			$follower= $fo->getTo($_SESSION['uid'],"r.follow=='1'");
 			$w->insertIntoWall($_SESSION['uid'], $id);
			if(!empty($follower)){
			foreach($follower as $key){
				$w->insertIntoWall(str_replace("users/","",$key['_from']),$id);
			}
			}
            $done=true;
		}elseif($view == "comment" && $rep!=""){
            $id=$p->submitPost(array("name"=>$aname,"password"=>$apass,"picture"=>$apic),"comment",$agerestriction,$type,$content,$rep,$t,$taggedPeople,$portrait);
						if(!empty($rep))
						$p->replyLink($id['_key'],$rep);
            foreach ($rep as $key) {
                $k = $p->getByKey($key);

				if($k["user_key"]!=$_SESSION['uid'])
				{

					$n->submit($k["user_key"],$_SESSION['uid'],$id["_key"],"comment");
				}
                $p->updateCommentsCount($key);
                $options = array(
                    'cluster' => 'us2'
				,
                    'useTLS' => true
                );
                $pusher = new Pusher\Pusher(
                    '38c6fc55f6c3d7c3756d',
                    '74ae95834120a6919f87',
                    '1001901',
                    $options
                );


				if($k["user_key"]!=$_SESSION['uid'])
				{
					if($portrait=="") {
						$portrait=$web."post-types/".$type."/icon.png";
					}
						$description="Your post was commented on by";
						$arr= array("left"=>array("portrait"=>$portrait,"link"=>$web."posts/".$id["_key"]),"time"=>time(),"description"=>$description,
								"right"=>array(array("link"=>$web."profiles/".$username."/posts","name"=>$u["name"],"picture"=>$u["profile_picture"])));
								$pusher->trigger('private-'.$k["user_key"], 'notifications', $arr);
				}
            }
            $done=true;
        }
		if($done){
            $fi=new triagens\ArangoDb\files();
            $fi->delete($apic);
        }
	}else{
    	if($view == "personal"||$view=="dev"||$view=="temp"){
    		$v=($view=="personal")?array($_SESSION['uid']):$view;
			$id=$p->submitPost($_SESSION['uid'],$v,$agerestriction,$type,$content,$rep,$t,$taggedPeople,$portrait);
			if(!empty($rep))
			$p->replyLink($id['_key'],$rep);
            foreach ($rep as $key)
                $p->updateCommentsCount($key);
			if($view!="dev")
			$w->insertIntoWall($_SESSION['uid'], $id['_key']);
		}elseif($view == "global"){
			$id=$p->submitPost($_SESSION['uid'],$view,$agerestriction,$type,$content,$rep,$t,$taggedPeople,$portrait);
			if(!empty($rep))
			$p->replyLink($id['_key'],$rep);
            foreach ($rep as $key) {
                $k = $p->getByKey($key);

								if($k["user_key"]!=$_SESSION['uid']){
									$n->submit($k["user_key"],$_SESSION['uid'],$id["_key"],"comment");
								}
				                $p->updateCommentsCount($key);
				                $options = array(
				                    'cluster' => 'us2',
				                    'useTLS' => true
				                );
				                $pusher = new Pusher\Pusher(
				                    '38c6fc55f6c3d7c3756d',
				                    '74ae95834120a6919f87',
				                    '1001901',
				                    $options
				                );


								if($k["user_key"]!=$_SESSION['uid'])
								{
									if($portrait=="") {
										$portrait=$web."post-types/".$type."/icon.png";
									}
										$description="Your post was commented on by";
										$arr= array("left"=>array("portrait"=>$portrait,"link"=>$web."posts/".$id["_key"]),"time"=>time(),"description"=>$description,
												"right"=>array(array("link"=>$web."profiles/".$username."/posts","name"=>$u["name"],"picture"=>$u["profile_picture"])));
								$pusher->trigger('private-'.$k["user_key"], 'notifications', $arr);
								}
            }
			$fo= new triagens\ArangoDb\user_relations();
			$follower= $fo->getTo($_SESSION['uid'],"r.follow=='1'");
 			$w->insertIntoWall($_SESSION['uid'], $id['_key']);
			if(!empty($follower)){
			foreach($follower as $key){
				$w->insertIntoWall(str_replace("users/","",$key['_from']),$id['_key']);
			}
			}
		}elseif($view == "comment" && $rep!=""){
            $id=$p->submitPost($_SESSION['uid'],$view,$agerestriction,$type,$content,$rep,$t,$taggedPeople,$portrait);
						if(!empty($rep))
						$p->replyLink($id['_key'],$rep);
            foreach ($rep as $key) {
                $k = $p->getByKey($key);

				if($k["user_key"]!=$_SESSION['uid'])
				{

					$n->submit($k["user_key"],$_SESSION['uid'],$id["_key"],"comment");
				}
                $p->updateCommentsCount($key);

                $options = array(
                    'cluster' => 'us2',
                    'useTLS' => true
                );
                $pusher = new Pusher\Pusher(
                    '38c6fc55f6c3d7c3756d',
                    '74ae95834120a6919f87',
                    '1001901',
                    $options
                );

                if($k["user_key"]!=$_SESSION['uid'])
								{
									if($portrait=="") {
										$portrait=$web."post-types/".$type."/icon.png";
									}
										$description="Your post was commented on by";
										$arr= array("left"=>array("portrait"=>$portrait,"link"=>$web."posts/".$id["_key"]),"time"=>time(),"description"=>$description,
												"right"=>array(array("link"=>$web."profiles/".$username."/posts","name"=>$u["name"],"picture"=>$u["profile_picture"])));
								$pusher->trigger('private-'.$k["user_key"], 'notifications', $arr);
								}

            }
        }
	}
	if(isset($_POST['links'])){
	    $arr= json_decode($_POST['links']);
	    $f = new triagens\ArangoDb\files();
	    if(!empty($arr)) {
            foreach ($arr as $key) {
                $key = explode("/", $key);
				$key =$key[count($key)-1];
				$key =explode(".", $key);
				$h="";
				for($i=0;$i<count($key)-1;$i++)
                $h=$h.$key[$i];
                $f->in_use(htmlentities($h), 'posts/' . $id['_key']);
            }
        }
	}

	if (is_array($id['user_key'])) {
		$id['profile']['anonymous']=true;
		$id['profile']['name']=$id['user_key']['name'];
		$id['profile']['picture']=$id['user_key']['picture'];
	} else {
	    $us = new triagens\ArangoDb\users();
	    $user = $us->getByKey($id['user_key']);
		$id['profile']['name']=$user['name'];
		$id['profile']['picture']=$user['profile_picture'];
		$id['profile']['link']=$web."profiles/" . $user['username'] . "/posts";
	}
	$c = new triagens\ArangoDb\toolBubble();
	$c = $c->getByKey($id['type']);
	$id['frame']['size']=$c['size'];
	$id['frame']['scroll']=$c['scroll'];
	$id['frame']['process']=$c['post'];
	$id['frame']['edit']=$c['edit'];
	$id['frame']['type']=$id['type'];
	if(isset($id['topics'])&&!empty($id['topics']))
	$id['topics']=$top->keyToNameMany($id['topics']);
	echo json_encode($id);
}
?>
