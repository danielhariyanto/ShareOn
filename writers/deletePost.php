<?php
require '../require.php';
    $uid = $_SESSION['uid'];
    $name = (string)htmlentities($_POST['key']);
    $password=(isset($_POST['password']))?password($_POST['password']):"";
    $ch = new triagens\ArangoDb\posts();
    $exis = $ch->check($uid, $name);
    $exis2=$ch->checkAno($name);
	$rcheck="false";
	$ccount=0;
    if ($exis) {
        $post=$ch->getByKey($name);
		$reply="";
		$replyupdate="";
		if(isset($post['replyTo'])){
      foreach ($post['replyTo'] as $top) {
        $rcheck="true";
  			$ccount=$ch->commentsCount($top)-1;
  			$reply=',"replyTo":"'.$top.'"';
  			$replyupdate=$top;
      }
		}
        $trans=new triagens\ArangoDb\Transaction($connection,array( 'collections' => array( 'write' => array( 'posts','votes','post_relations' ) ), 'waitForSync' => true ));
        $trans->setAction('function(){
            var db= require("@arangodb").db;
            db.posts.removeByExample({"_key":"'.$name.'"});
            db.votes.removeByExample({"post_key":"'.$name.'"});
			if('.$post['comments'].'>0||'.$post['upvotes'].'>0||'.$post['downvotes'].'>0)
            db.posts.insert({"_key":"'.$post['_key'].'","view_option":"deleted","comments":'.$post['comments'].',"upvotes":'.$post['upvotes'].',"downvotes":'.$post['downvotes'].',"time":"'.$post['time'].'"'.$reply.'});
			else if('.$rcheck.'){
			db.posts.update({"_key":"'.$replyupdate.'"},{"comments":'.$ccount.'});
      db.post_relations.removeByExample({"_from":"posts/'.$name.'"});
    }
        }');
        $trans->execute();
		$f= new triagens\ArangoDb\files();
		$files=$f->getByUser("posts/".$name);
        foreach ($files as $key){
        	$ext=explode(".", $key['name']);
			$ext=$ext[count($ext)-1];
            $path=$key['_key'].".".$ext;
			// Upload file to Space
			$d=new digitalO;
			$d->delete($path);
			$f->delete($key['_key']);
        }
		if($post['comments']>0||$post['upvotes']>0||$post['downvotes']>0)
		echo "update";
		else
		echo "delete";
    }elseif ($exis2) {
    	$post = $ch->getByKey($name);
        $check = $post['user_key'];
        if ($password == $check['password']) {
		    if($post['replyTo']!=''){
				$rcheck="true";
				$ccount=$ch->commentsCount($post['replyTo'])-1;
			}
			$curls=implode("", $post['content']);
            $trans=new triagens\ArangoDb\Transaction($connection,array( 'collections' => array( 'write' => array( 'posts','votes' ) ), 'waitForSync' => true ));
            $trans->setAction('function(){
            var db= require("@arangodb").db;
            db.posts.removeByExample({"_key":"'.$name.'"});
            db.votes.removeByExample({"post_key":"'.$name.'"});
            if('.$post['comments'].'>0||'.$post['upvotes'].'>0||'.$post['downvotes'].'>0)
            db.posts.insert({"_key":"'.$post['_key'].'","view_option":"deleted","comments":'.$post['comments'].',"upvotes":'.$post['upvotes'].',"downvotes":'.$post['downvotes'].',"time":"'.$post['time'].'","replyTo":"'.$post['replyTo'].'"});
			else if('.$rcheck.')
			db.posts.update({"_key":"'.$post['replyTo'].'"},{"comments":'.$ccount.'});
        }');
        $trans->execute();
        $f= new triagens\ArangoDb\files();
		$files=$f->getByUser("posts/".$name);
        foreach ($files as $key){
        	$ext=explode(".", $key['name']);
			$ext=$ext[count($ext)-1];
            $path=$key['_key'].".".$ext;
			// Upload file to Space
			$d=new digitalO;
			$d->delete($path);
			$f->delete($key['_key']);
        }
		if($post['comments']>0||$post['upvotes']>0||$post['downvotes']>0)
		echo "update";
		else
		echo "delete";
    }
    }
