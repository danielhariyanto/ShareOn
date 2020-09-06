<?php
namespace triagens\ArangoDb;
require $_SERVER['DOCUMENT_ROOT'].'/functions.php';
	require $_SERVER['DOCUMENT_ROOT'].'/arangodb/autoload.php';
	$web="/";
	$connectionOptions =array(
    // server endpoint to connect to
    ConnectionOptions::OPTION_ENDPOINT => 'tcp://161.35.107.171:8529',
    // authorization type to use (currently supported: 'Basic')
    ConnectionOptions::OPTION_AUTH_TYPE => 'Basic',
    // user for basic authorization
    ConnectionOptions::OPTION_AUTH_USER => 'root',
    // password for basic authorization
    ConnectionOptions::OPTION_AUTH_PASSWD => 'anFzVQkmQj7_62%c',
    // connection persistence on server. can use either 'Close' (one-time connections) or 'Keep-Alive' (re-used connections)
    ConnectionOptions::OPTION_CONNECTION => 'Keep-Alive',
    // connect timeout in seconds
    ConnectionOptions::OPTION_DATABASE=>'ShareOn',
    ConnectionOptions::OPTION_TIMEOUT => 3,
    // whether or not to reconnect when a keep-alive connection has timed out on server
    ConnectionOptions::OPTION_RECONNECT => true,
    // optionally create new collections when inserting documents
    ConnectionOptions::OPTION_CREATE => true,
    // optionally create new collections when inserting documents
    ConnectionOptions::OPTION_UPDATE_POLICY => UpdatePolicy::LAST,
	);
	$connection        = new Connection($connectionOptions);
    $collectionHandler = new CollectionHandler($connection);
    $handler   = new DocumentHandler($connection);
	function getall($document){
		$transit=$document->getAll();
		$final=array();
			$i=0;
			if(!empty($transit)&&!empty($transit[0])){
			foreach ($transit as $key) {
				$final[$i]=$key->getAll();
				$i++;
			}
		return $final;
			}
			return array();
	}
	class users {
		public function insertNewUser($username,$password,$name,$birth) {
			global $connection;
			global $web;
			$query = 'INSERT {"username":"'.$username.'","birth":"'.$birth.'","name":"'.$name.'","password":"'.$password.'","profile_picture":"","joined":'.time().'} IN users';
			$statement = new Statement(
			    $connection,
			    array(
			        "query"=> $query
			    )
			);
			$cursor = $statement->execute();
		}
		public function getByKey($key){
			global $collectionHandler;
			try{
				$result = $collectionHandler->byExample("users", array("_key" => $key));
				$ar=$result->current();
				return $ar->getAll();
			} catch (Exception $e) {
				return [];
			}
		}
		public function userInfoByUsername($username){
			global $collectionHandler;
			$result = $collectionHandler->byExample("users", array("username" => $username));
			$ar=$result->current();
			return (!empty($ar))?$ar->getAll():array();
		}
		public function searchByName($input){
			global $connection;
			$query = 'FOR doc IN users FILTER LIKE(doc.name, "'.$input.'%", true) || LIKE(doc.username, "'.$input.'%", true)  RETURN doc';
			$statement = new Statement(
			    $connection,
			    array(
			        "query"=> $query
			    )
			);
			$cursor = $statement->execute();
			return getall($cursor);
		}
		//returns all user info including current user's relationship to him
		public function searchPeopleResults($input){
			global $connection;
			$query = 'FOR u IN users FILTER u._key!="'.$_SESSION['uid'].'"&&(LIKE(u.name,"'.$input.'%",true)||LIKE(u.username,"'.$input.'%",true))RETURN[u,(FOR f IN user_relations FILTER f._to==CONCAT("users/",u._key)&&f._from==CONCAT("users/","'.$_SESSION['uid'].'")LIMIT 1 RETURN f)[0]]';
			$statement = new Statement(
			    $connection,
			    array(
			        "query"=> $query
			    )
			);
			$cursor = $statement->execute();
			return getall($cursor);
		}
		public function updateProfilePicture($user_key,$image){
			global $connection;
			$query = 'UPDATE {"_key":"'.$user_key.'"} WITH {"profile_picture":"'.$image.'"} IN users';
			$statement = new Statement(
			    $connection,
			    array(
			        "query"=> $query
			    )
			);
			$cursor = $statement->execute();
		}
		public function update($key,$vals){
			global $handler;
			global $collectionHandler;
			$document = $handler->get('users', $key);
			foreach ($vals as $key => $value) {
				$document->set($key,$value);
			}
			$handler->update($document);
		}
		public function checkLog($username,$password) {
			global $collectionHandler;
			$result = $collectionHandler->byExample("users", array("username" => $username,"password"=>$password));
			$ar=$result->current();
			return (!empty($ar))?$ar->getAll():array();
		}
		public function usernameCheck($username)
		{
			global $collectionHandler;
			$result = $collectionHandler->byExample("users", array("username" => $username));
			$ar=$result->current();
			return (empty($ar))?true:false;
		}

		public function deleteUserByID($postID){
			global $connection;
			$query = "REMOVE '".$postID."' in users";
			$statement = new Statement(
			    $connection,
			    array(
			        "query"=> $query,
			    )
			);
			$cursor = $statement->execute();
			return getall($cursor);
		}
	}
	class walls {

		public function insertIntoWall($user_key,$post_key) {
			global $connection;
			$document = array(
			    "user_key"    => $user_key,
			    "post_key"    =>$post_key
			);
			$query = 'INSERT {"user_key":"'.$user_key.'","post_key":"'.$post_key.'"} INTO walls';
            $statement = new Statement(
                $connection,
                array(
                    "query"=> $query
                )
            );
			$cursor = $statement->execute();
		}
	}
	class posts {
		public function highestPoints($desc){
			global $connection;
			$desc=($desc)?"":" DESC";
			$query="FOR p IN posts SORT (p.upvotes-p.downvotes)".$desc." LIMIT 1 RETURN (p.upvotes-p.downvotes)";
			$statement = new Statement(
			    $connection,
			    array(
			        "query"=> $query
			    )
			);
			$cursor = $statement->execute();
			$transit=$cursor->getAll();
			return $transit[0];
		}
		public function highestUpvotes($desc){
			global $connection;
			$desc=($desc)?"":" DESC";
			$query="FOR p IN posts SORT p.upvotes".$desc." LIMIT 1 RETURN p.upvotes";
			$statement = new Statement(
			    $connection,
			    array(
			        "query"=> $query
			    )
			);
			$cursor = $statement->execute();
			$transit=$cursor->getAll();
			return $transit[0];
		}
		public function highestDownvotes($desc){
			global $connection;
			$desc=($desc)?"":" DESC";
			$query="FOR p IN posts SORT p.downvotes".$desc." LIMIT 1 RETURN p.downvotes";
			$statement = new Statement(
			    $connection,
			    array(
			        "query"=> $query
			    )
			);
			$cursor = $statement->execute();
			$transit=$cursor->getAll();
			return $transit[0];
		}
		public function highestComments($desc){
			global $connection;
			$desc=($desc)?"":" DESC";
			$query="FOR p IN posts SORT p.comments".$desc." LIMIT 1 RETURN p.comments";
			$statement = new Statement(
			    $connection,
			    array(
			        "query"=> $query
			    )
			);
			$cursor = $statement->execute();
			$transit=$cursor->getAll();
			return $transit[0];
		}
		public function commentHighestPoints($key,$desc){
			global $connection;
			$desc=($desc)?"":" DESC";
			$query="FOR p IN posts SORT p._key=='".$key."' && (p.upvotes-p.downvotes)".$desc." LIMIT 1 RETURN (p.upvotes-p.downvotes)";
			$statement = new Statement(
			    $connection,
			    array(
			        "query"=> $query
			    )
			);
			$cursor = $statement->execute();
			$transit=$cursor->getAll();
			return $transit[0];
		}
		public function replyLink($key,$arr){
			global $connection;
			$query='FOR r IN '.json_encode($arr).' INSERT {_from:"posts/'.$key.'",_to:CONCAT("posts/",r)} IN post_relations';
			$statement = new Statement(
			    $connection,
			    array(
			        "query"=> $query
			    )
			);
			$cursor = $statement->execute();
		}
		public function commentHighestUpvotes($key,$desc){
			global $connection;
			$desc=($desc)?"":" DESC";
			$query="FOR p IN posts SORT p._key=='".$key."' && p.upvotes".$desc." LIMIT 1 RETURN p.upvotes";
			$statement = new Statement(
			    $connection,
			    array(
			        "query"=> $query
			    )
			);
			$cursor = $statement->execute();
			$transit=$cursor->getAll();
			return $transit[0];
		}
		public function commentHighestDownvotes($key,$desc){
			global $connection;
			$desc=($desc)?"":" DESC";
			$query="FOR p IN posts SORT p._key=='".$key."' && p.downvotes".$desc." LIMIT 1 RETURN p.downvotes";
			$statement = new Statement(
			    $connection,
			    array(
			        "query"=> $query
			    )
			);
			$cursor = $statement->execute();
			$transit=$cursor->getAll();
			return $transit[0];
		}
		public function commentHighestComments($key,$desc){
			global $connection;
			$desc=($desc)?"":" DESC";
			$query="FOR p IN posts SORT p._key=='".$key."' && p.comments".$desc." LIMIT 1 RETURN p.comments";
			$statement = new Statement(
			    $connection,
			    array(
			        "query"=> $query
			    )
			);
			$cursor = $statement->execute();
			$transit=$cursor->getAll();
			return $transit[0];
		}
		public function getPostInfo($rules,$post_key){
			global $connection;
			$query = 'FOR p IN posts FILTER p._key=="'.$post_key.'" '.$rules.' LIMIT 1 RETURN DISTINCT MERGE(p,{"topics":(FOR t IN p.topics RETURN DOCUMENT(CONCAT("topics/",t)).name)})';
			$statement = new Statement(
			    $connection,
			    array(
			        "query"=> $query
			    )
			);
			$cursor = $statement->execute();
			return getall($cursor);
		}
		public function getByKey($post_key){
			global $connection;
			$query = 'FOR p IN posts FILTER p._key=="'.$post_key.'" LIMIT 1 RETURN MERGE(p,{"topics":(FOR t IN ((p.topics!=null)?p.topics:[]) RETURN DOCUMENT(CONCAT("topics/",t)))})';

			$statement = new Statement(
			    $connection,
			    array(
			        "query"=> $query
			    )
			);
			$cursor = $statement->execute();
			$transit=getall($cursor);
			return $transit[0];
		}
		public function getWall($rules,$order){
			global $connection;
			$query = 'FOR w IN walls FOR p IN posts FILTER (w.user_key=="'.$_SESSION['uid'].'" && w.post_key==p._key)'.$rules.' SORT p.'.$order.' RETURN DISTINCT MERGE(p,{"topics":(FOR t IN ((p.topics!=null)?p.topics:[]) RETURN DOCUMENT(CONCAT("topics/",t)).name)})';
			$statement = new Statement(
			    $connection,
			    array(
			        "query"=> $query
			    )
			);
			$cursor = $statement->execute();
			return getall($cursor);
		}
		public function getDevPost($bubble_key){
			global $connection;
			$query = 'LET a=(FOR p IN posts FILTER p.view_option=="dev"&&p.type=="'.$bubble_key.'" RETURN p) RETURN a';
			$statement = new Statement(
			    $connection,
			    array(
			        "query"=> $query
			    )
			);
			$cursor = $statement->execute();
			$transit=getall($cursor);
			return $transit[0];
		}
        public function searchPosts($string){
		    global $connection;
            $query = 'FOR p IN posts FILTER LIKE(TO_LIST(p.content), "%'.$string.'%", true) RETURN DISTINCT MERGE(p,{"topics":(FOR t IN ((p.topics!=null)?p.topics:[]) RETURN DOCUMENT(CONCAT("topics/",t)).name)})';
            $statement = new Statement(
                $connection,
                array(
                    "query"=> $query
                )
            );
            $cursor = $statement->execute();
            return getall($cursor);
        }
		public function getAll($rules,$order){
			global $connection;
			$query = 'FOR p IN posts FILTER 1==1'.$rules.' SORT p.'.$order.' LIMIT 0,30 RETURN MERGE(p,{"topics":(FOR t IN ((p.topics!=null)?p.topics:[]) RETURN DOCUMENT(CONCAT("topics/",t)).name)})';
			$statement = new Statement(
			    $connection,
			    array(
			        "query"=> $query
			    )
			);
			$cursor = $statement->execute();
			return getall($cursor);
		}
		public function commentsLoad($post_key,$rules,$order){
			global $connection;
			$query='FOR p IN posts FILTER POSITION(p.replyTo,"'.$post_key.'")'.$rules.' SORT'.$order.' RETURN MERGE(p,{"topics":(FOR t IN ((p.topics!=null)?p.topics:[]) RETURN DOCUMENT(CONCAT("topics/",t)).name)})';
			$statement = new Statement(
			    $connection,
			    array(
			        "query"=> $query,
			    )
			);
			$cursor = $statement->execute();
			return getall($cursor);
		}
		public function loadUserPosts($uid,$rules,$order){
			global $connection;
			$query = 'FOR p IN posts FILTER !IS_OBJECT(p.user_key) && p.user_key=="'.$uid.'"'.$rules.' SORT p.'.$order.' RETURN DISTINCT MERGE(p,{"topics":(FOR t IN ((p.topics!=null)?p.topics:[]) RETURN DOCUMENT(CONCAT("topics/",t)).name)})';
			$statement = new Statement(
			    $connection,
			    array(
			        "query"=> $query
			    )
			);
			$cursor = $statement->execute();
			return getall($cursor);
		}
        public function loadPostByKey($key){
            global $collectionHandler;
            $result = $collectionHandler->byExample("posts", array("_key" => $key));
            if($result->valid()){
                $ar=$result->current();
                $ar=(!empty($ar))?$ar->getAll():$ar;
                return $ar;
            }else{
                return;
            }
        }
        public function commentsCount($key){
            global $connection;
            $query='let u = (FOR p IN posts FILTER POSITION (p.replyTo,"'.$key.'") RETURN p) return length(u)';
            $statement = new Statement(
                $connection,
                array(
                    "query"=> $query,
                )
            );
            $cursor = $statement->execute();
            $transit= $cursor->getAll();
            return $transit[0];
        }
		public function submitPost($uid,$view,$agerestriction,$type,$content,$replyTo=array(),$topics=array(),$taggedPeople=array(),$portrait="") {
			global $connection;
			$time=time();
			$document = array(
			    "user_key"    => $uid,
			    'view_option' =>$view,
			    'type'=>$type,
			    'time'=>$time,
			    'content'=>$content,
			    'comments'=>0,
			    'upvotes'=>0,
			    'downvotes'=>0,
			    'age_res'=>$agerestriction
			);
			if($portrait!="")
			$document['portrait']=$portrait;
			if(!empty($replyTo)&&is_array($replyTo))
			$document['replyTo']=$replyTo;
			if(!empty($topics)&&is_array($topics))
			$document['topics']=$topics;
			if(!empty($taggedPeople)&&is_array($taggedPeople))
			$document['taggedPeople']=$taggedPeople;
			$query='INSERT @doc IN `posts` RETURN NEW';
			$statement = new Statement(
			    $connection,
			    array(
			        "query"     => $query,
			        "bindVars"  => array("doc" => $document)
			    )
			);
			$cursor = $statement->execute();
			$transit=getall($cursor);
			return $transit[0];
		}
		public function check($user_key,$post_key){
			global $connection;
			$query='FOR p IN posts FILTER p.user_key==@ukey && p._key==@key RETURN p';
			$statement = new Statement(
			    $connection,
			    array(
			        "query"=> $query,
			        "bindVars"=>array("ukey"=>$user_key,"key"=>$post_key)
			    )
			);
			$cursor = $statement->execute();
			$transit= $cursor->getAll();
			return (count($transit)>0)?true:false;
		}
		public function isGlobal($post_key){
			global $connection;
			$query='FOR p IN posts FILTER p.view_option=="global" && p._key=="'.$post_key.'" RETURN p';
			$statement = new Statement(
			    $connection,
			    array(
			        "query"=> $query
			    )
			);
			$cursor = $statement->execute();
			$transit= $cursor->getAll();
			return (count(getall($transit))>0)?1:0;
		}
		public function checkAno($post_key){
			global $connection;
			$query='FOR p IN posts FILTER IS_OBJECT(p.user_key) && p._key=="'.$post_key.'" RETURN p';
			$statement = new Statement(
			    $connection,
			    array(
			        "query"=> $query,
			    )
			);
			$cursor = $statement->execute();
			$transit= $cursor->getAll();
			return (count($transit)>0)?true:false;
		}
		public function update($pid,$uid,$view,$agerestriction,$type,$content,$replyTo=array(),$topics=array(),$taggedPeople=array(),$portrait=""){
			global $connection;
			$t=new toolBubble();
			$t=$t->getByKey($type);
			if(isset($t['unmodifiable'])){
				foreach($t['unmodifiable'] as $key) {
					if(isset($content[$key]))
					unset($content[$key]);
				}
			}
			$rep=(!empty($replyTo))?'"replyTo":@replyTo,':"";
			$top=(!empty($topics))?'"topics":@topics,':"";
			$tag=(!empty($taggedPeople))?'"taggedPeople":@taggedPeople,':"";
			$query = 'UPDATE {"_key":@pid} WITH {"user_key":@uid,"content":@content,"view_option":@view_option,"age_res":@age,'.$rep.$top.$tag.'"portrait":@portrait} IN posts RETURN NEW';
			$arr=array("pid"=>$pid,"uid"=>$uid,"content"=> $content,"view_option"=>$view,"age"=>$agerestriction,"portrait"=>$portrait);
			if($rep!="")
			$arr['replyTo']=$replyTo;
			if($top!="")
			$arr['topics']=$topics;
			if($tag!="")
			$arr['taggedPeople']=$taggedPeople;
			$statement = new Statement(
			    $connection,
			    array(
			        "query"=> $query,
			        "bindVars"=>$arr
			    )
			);
			$cursor = $statement->execute();
			$transit=getall($cursor);
			return $transit[0];
		}
		public function updateContent($pid,$content){
			global $connection;
			$query = 'UPDATE {"_key":@pid} WITH {"content":@content} IN posts';
			$statement = new Statement(
			    $connection,
			    array(
			        "query"=> $query,
			        "bindVars"=>array("pid"=>$pid,"content"=> $content)
			    )
			);

			$cursor = $statement->execute();
		}
        public function updateCommentsCount($key){
        	global $connection;

        	$trans=new Transaction($connection,array( 'collections' => array( 'write' => array( 'posts' ),'read' => array( 'posts' ) ), 'waitForSync' => true ));
			$trans->setAction('function(){
			var db= require("@arangodb").db;
			var count=db._query("FOR p IN posts FILTER POSITION(p.replyTo,\''.$key.'\') RETURN p").toArray().length;
			db.posts.update(db.posts.document({_key: "'.$key.'"}),{"comments":count});
			}');
			$trans->execute();
        }
		public function delete($pid){
			global $connection;
			$query = 'REMOVE {"_key":"'.$pid.'"} IN posts';
			$statement = new Statement(
			    $connection,
			    array(
			        "query"=> $query,
			    )
			);

			$cursor = $statement->execute();
		}
	}
	class votes {
		private $p;
		function __construct($post_key){
			$this->p=(string)$post_key;
		}
		public function voteCount($type){
			global $connection;
			$query='let u = (FOR v IN votes FILTER v.post_key=="'.$this->p.'" && v.vote=='.$type.' RETURN v) return length(u)';
			$statement = new Statement(
			    $connection,
			    array(
			        "query"=> $query,
			    )
			);
			$cursor = $statement->execute();
			$transit= $cursor->getAll();
			return $transit[0];
		}
		public function userVote($uid){
			global $collectionHandler;
			$result = $collectionHandler->byExample("votes", array("post_key" => $this->p,"user_key"=>(string)$uid));
			if($result->valid()){
			$ar=$result->current();
			$ar=(!empty($ar))?$ar->getAll():$ar;
			return $ar;
			}else{
				return;
			}
		}
		public function voteDelete($uid){
			global $connection;
			$ar=$this->userVote($uid);
			$query = 'REMOVE {"_key":"'.$ar['_key'].'"} IN votes';
			$statement = new Statement(
			    $connection,
			    array(
			        "query"=> $query,
			    )
			);

			$cursor = $statement->execute();
		}
		public function insertNew($uid,$vote){
			global $connection;
			$query = 'INSERT {"post_key":"'.$this->p.'","user_key":"'.$uid.'","vote":"'.$vote.'"} IN votes';
			$statement = new Statement(
			    $connection,
			    array(
			        "query"=> $query,
			    )
			);

			$cursor = $statement->execute();
		}
		public function update($uid,$vote){
			global $connection;
			$ar= $this->userVote($uid);
			$query = 'UPDATE {"_key":"'.$ar['_key'].'"} WITH {"vote":"'.$vote.'"} IN votes';
			$statement = new Statement(
			    $connection,
			    array(
			        "query"=> $query,
			    )
			);

			$cursor = $statement->execute();
		}
	}
	class toolBubble {
		public function deleteByID($postID){
			global $connection;
			$query = "REMOVE '".$postID."' in tool_bubble";
			$statement = new Statement(
			    $connection,
			    array(
			        "query"=> $query,
			    )
			);
			$cursor = $statement->execute();
			return getall($cursor);
		}

		public function createNew($uid, $name, $desc){
			global $connection;
			$query = "insert {creator: \"$uid\",name: \"$name\", description: \"$desc\"} INTO tool_bubble return NEW";
			$statement = new Statement(
			    $connection,
			    array(
			        "query"=> $query,
			    )
			);
			$cursor = $statement->execute();
			return getall($cursor);
		}

		public function getByCreator($userkey){
			global $connection;
			$query = 'for t in tool_bubble FILTER t.creator =="'.$userkey.'" Return t';
			$statement = new Statement(
			    $connection,
			    array(
			        "query"=> $query,
			    )
			);
			$cursor = $statement->execute();
			return getall($cursor);
		}
		public function getById($id){
			global $connection;
			$a=explode("/", $id);
			if(count($a)==2&& is_numeric($a[1])&&$a[0]=="tool_bubble"){
			$query = 'RETURN DOCUMENT("'.$id.'")';
			$statement = new Statement(
			    $connection,
			    array(
			        "query"=> $query,
			    )
			);
			$cursor = $statement->execute();
			return getall($cursor);
			}
			return array();
		}
		public function getAll(){
			global $connection;
			$query = 'FOR t IN tool_bubble FILTER t.published RETURN t';
			$statement = new Statement(
			    $connection,
			    array(
			        "query"=> $query,
			    )
			);
			$cursor = $statement->execute();
			return getall($cursor);
		}
		public function getByKey($key){
            global $collectionHandler;
            $result = $collectionHandler->byExample("tool_bubble", array("_key" => $key));
            $ar=$result->current();
            $ar=(!empty($ar))?$ar->getAll():array();
            return $ar;
		}

		public function update($key,$vals){
			global $handler;
			global $collectionHandler;
			$document = $handler->get('tool_bubble', $key);
			foreach ($vals as $key => $value) {
				$document->set($key,$value);
			}
			$handler->update($document);
		}
	}
	class notifications {
	    public function read($receiver_key){
            global $connection;
            $query = 'FOR n IN notifications FILTER !n.read && n.receiver_key=="'.$receiver_key.'" UPDATE n WITH {read:true} IN notifications';
            $statement = new Statement(
                $connection,
                array(
                    "query"=> $query
                )
            );
            $cursor = $statement->execute();
        }
	    public function get($receiver_key){
            global $connection;
            $query = 'FOR u IN notifications FILTER u.receiver_key=="'.$receiver_key.'" RETURN u';

            $statement = new Statement(
                $connection,
                array(
                    "query"=> $query,
                )
            );
            $cursor = $statement->execute();
            $transit=getall($cursor);
            return $transit;

        }

		public function insert($receiving_key,$sender_name,$sender_profile_picture,$sender_link,$text,$type){
			global $connection;
			$query = 'INSERT {"receiving_key":"'.$receiving_key.'","sender_name":"'.$sender_name.'","portrait":"'.$sender_profile_picture.'","link":"'.$sender_link.'","text":"'.$text.'","type":"'.$type.'","read":false,"time":'.time().'} IN notifications';
			$statement = new Statement(
			    $connection,
			    array(
			        "query"=> $query,
			    )
			);
			$cursor = $statement->execute();
		}

		public function getAllByReceivingKey($receiving_key){
            global $connection;
            $query = 'FOR n IN notifications FILTER n.receiving_key == "'.$receiving_key.'" SORT n.time RETURN n';
            $statement = new Statement(
                $connection,
                array(
                    "query"=> $query,
                )
            );
            $cursor = $statement->execute();
            return getall($cursor);
        }
		public function getNew($receiving_key){
            global $connection;
            $query = 'FOR n IN notifications FILTER n.receiving_key == "'.$receiving_key.'" && n.read==false SORT n.time RETURN n';
            $statement = new Statement(
                $connection,
                array(
                    "query"=> $query,
                )
            );
            $cursor = $statement->execute();
            $transit= $cursor->getAll();
            return getall($transit);
        }
        public function submit($receiver_key,$sender_key,$post_key,$action){
	        global $connection;
            $query = 'INSERT {"receiver_key":"'.$receiver_key.'","sender_key":"'.$sender_key.'","time":'.time().',"post_key":"'.$post_key.'","action":"'.$action.'","read":false} INTO notifications';
            $statement = new Statement(
                $connection,
                array(
                    "query"=> $query,
                )
            );
            $cursor = $statement->execute();
            return getall($cursor);
        }
	}
	class user_relations{
        public function check($fuser,$suser){
            global $connection;
            $query='FOR f IN user_relations FILTER (f.user_key=="'.$fuser.'" && f.friend_key=="'.$suser.'") || (f.friend_key=="'.$fuser.'" && f.user_key=="'.$suser.'") RETURN f';
            $statement = new Statement(
                $connection,
                array(
                    "query"=> $query,
                )
            );
            $cursor = $statement->execute();
            return (count(getall($cursor))>0)?true:false;
        }
        public function insert($from,$to,$add){
            global $connection;
            $query = 'INSERT {"_from":"'.$from.'","_to":"'.$to.'",'.$add.'} IN friends';
            $statement = new Statement(
                $connection,
                array(
                    "query"=> $query,
                )
            );
            $cursor = $statement->execute();
        }
        public function getFrom($uid,$add,$output="r"){
            global $connection;
            $query='FOR r IN user_relations FILTER r._from=="users/'.$uid.'" && '.$add.' RETURN '.$output;
            $statement = new Statement(
                $connection,
                array(
                    "query"=> $query,
                )
            );
            $cursor = $statement->execute();
            return getall($cursor);
        }
        public function getTo($uid,$add,$output="r"){
            global $connection;
            $query='FOR r IN user_relations FILTER r._to=="users/'.$uid.'" && '.$add.' RETURN '.$output;
            $statement = new Statement(
                $connection,
                array(
                    "query"=> $query,
                )
            );
            $cursor = $statement->execute();
            return getall($cursor);
        }
        public function followStatusUpdate($from,$to,$update){
            global $connection;
            $status=$this->getFromTo($from,$to);
            if(!empty($status)) {
                $query = 'UPDATE { _key: "'.$status['_key'].'" } WITH {"follow":"'.$update.'"} IN user_relations';
                $statement = new Statement(
                    $connection,
                    array(
                        "query" => $query,
                    )
                );
            }else{
                $query = 'INSERT { _from: "users/'.$from.'", _to: "users/'.$to.'", "follow":"'.$update.'"} IN user_relations';
                $statement = new Statement(
                    $connection,
                    array(
                        "query" => $query,
                    )
                );
            }
            $statement->execute();
        }
        public function getFromTo($from,$to){
            global $collectionHandler;
            $result = $collectionHandler->byExample("user_relations", array("_from" => "users/".$from, "_to" => "users/".$to));
            $ar=$result->current();
            $ar=(!empty($ar))?$ar->getAll():"";
            return $ar;
        }
        public function get($user,$friend,$add){
            global $connection;
            $query='FOR f IN friends FILTER (f.user_key=="'.$user.'" && f.friend_key=="'.$friend.'") || (f.user_key=="'.$friend.'" && f.friend_key=="'.$user.'") RETURN f';
            $statement = new Statement(
                $connection,
                array(
                    "query"=> $query,
                )
            );
            $cursor = $statement->execute();
            return getall($cursor);
        }
        public function delete($user,$friend){
            global $connection;
            $ar=$this->get($user,$friend,'');
            $query = 'REMOVE {"_key":"'.$ar[0]['_key'].'"} IN friends';
            $statement = new Statement(
                $connection,
                array(
                    "query"=> $query,
                )
            );
            $cursor = $statement->execute();
        }
    }
	class files{
		public function insertFile($user_key, $file,$use=""){
			global $connection;
			$query='INSERT {"name":"'.$file.'","user_key":"'.$user_key.'","time":'.time().',"in_use":"'.$use.'"} IN files RETURN NEW';
            $statement = new Statement(
                $connection,
                array(
                    "query"=> $query,
                )
            );
            $cursor = $statement->execute();
            return getall($cursor);

		}
		public function delete($key){
			global $connection;

			try{
				$query='REMOVE { _key: "'.$key.'" } IN files';
	            $statement = new Statement(
	                $connection,
	                array(
	                    "query"=> $query,
	                )
	            );
	            $cursor = $statement->execute();
			}catch(exception $e){
				return false;
			}
		}
		public function expired(){
			global $connection;
			global $web;
			$time=time()-86400;
			$query='FOR u IN files FILTER (u.in_use=="" || u.in_use==0) && u.time < '.$time.' return u';
			$statement = new Statement(
			    $connection,
			    array(
			        "query"=> $query,
			    )
			);
			$cursor = $statement->execute();
			return getAll($cursor);
		}
        public function in_use($key,$user){
            global $connection;
			try{
				$query='UPDATE {_key:"'.$key.'"} WITH {"in_use":"'.$user.'"} IN files';
	            $statement = new Statement(
	                $connection,
	                array(
	                    "query"=> $query,
	                )
	            );
	            $cursor = $statement->execute();
			}catch(exception $e){
				return false;
			}
		}
		public function check_in_use($key){
			$query='FOR f IN files FILTER f.in_use=="'.$key.'" RETURN f';
	            $statement = new Statement(
	                $connection,
	                array(
	                    "query"=> $query,
	                )
	            );
	            $cursor = $statement->execute();
				return (count(getall($cursor))>0)?true:false;
		}
		public function removeUse($key){
            global $connection;
			try{
				$query='FOR f IN files FILTER f.in_use=="'.$key.'" UPDATE f WITH { in_use: "" } IN files';
	            $statement = new Statement(
	                $connection,
	                array(
	                    "query"=> $query,
	                )
	            );
	            $cursor = $statement->execute();
			}catch(exception $e){
				return false;
			}
		}
		public function getByUser($id){
			global $connection;
            $query = 'FOR f IN files FILTER f.in_use=="'.$id.'" RETURN f';
            $statement = new Statement(
                $connection,
                array(
                    "query"=> $query,
                )
            );
            $cursor = $statement->execute();
            return getall($cursor);
		}
	}

	class recommendations{
		public function recommendTo($post_key,$receiving_key,$sending_key){
			global $connection;
			$trans=new Transaction($connection,array( 'collections' => array( 'write' => array( 'recommendations'), 'read' => array('recommendations')), 'waitForSync' => true ));
                $trans->setAction('function(){
                    var db= require("@arangodb").db;
					var check = db.recommendations.byExample({"post_key":"'.$post_key.'","user_key":"'.$receiving_key.'","recommended_by":"'.$sending_key.'"}).toArray();
					if(check.length>0){
                    db.recommendations.update({"_key":check[0]._key},{"time":'.time().'});
                    }else{
                    	db.recommendations.insert({"post_key":"'.$post_key.'","time":'.time().',"recommended_by":"'.$sending_key.'","user_key":"'.$receiving_key.'"});
                    }
                }');
			$trans->execute();
		}
		public function getSentRecommendations($post_key,$user_key){
            global $connection;
            $query='FOR r IN recommendations FILTER r.post_key == "'.$post_key.'" && r.user_key=="'.$user_key.'" return r';
            $statement = new Statement(
                $connection,
                array(
                    "query"=> $query,
                )
            );
            $cursor = $statement->execute();
            return getall($cursor);
        }
        public function getAllSentRecommendations($user_key){
            global $connection;
            $query='for r in recommendations
			filter r.user_key=="'.$user_key.'"
			return DISTINCT r.post_key';
            $statement = new Statement(
                $connection,
                array(
                    "query"=> $query,
                )
            );
            $cursor = $statement->execute();
            $transit= $cursor->getAll();
            return ($transit);
        }
	}
class collections {
	public function getUserCollection($user_key){
            global $connection;
            $query='FOR c IN collections FILTER c.user_key=="'.$user_key.'" return c';
            $statement = new Statement(
                $connection,
                array(
                    "query"=> $query,
                )
            );
            $cursor = $statement->execute();
            return getall($cursor);
        }
	public function getCollection($key){
            global $connection;
            $query='FOR c IN collections FILTER c._key=="'.$key.'" return c';
            $statement = new Statement(
                $connection,
                array(
                    "query"=> $query,
                )
            );
            $cursor = $statement->execute();
			$d=getall($cursor);
            return $d[0];
        }
	public function createNew($user_key,$title,$view_option,$picture){
			global $connection;
            $query='INSERT {"user_key":"'.$user_key.'","title":"'.$title.'","view_option":"'.$view_option.'","background":"'.$picture.'","time":'.time().',"contents":[]} IN collections';
            $statement = new Statement(
                $connection,
                array(
                    "query"=> $query,
                )
            );
            $cursor = $statement->execute();
	}
	public function getPostsByKey($rules,$order,$collection_key){
			global $connection;
			$query = 'FOR c IN collections FOR p IN posts FILTER c._key=="'.$collection_key.'" && CONTAINS(TO_STRING(c.contents),p._key) '.$rules.' SORT p.'.$order.' RETURN DISTINCT p';
			$statement = new Statement(
			    $connection,
			    array(
			        "query"=> $query
			    )
			);
			$cursor = $statement->execute();
			return getall($cursor);
		}
	public function addPostToCollection($post_key,$collection_key){
            global $connection;
            $query='FOR c IN collections
			UPDATE c WITH { contents: UNIQUE(UNION(c.contents,["'.$collection_key.'"]))  } IN collections';
            $statement = new Statement(
                $connection,
                array(
                    "query"=> $query,
                )
            );
            $cursor = $statement->execute();
            return getall($cursor);
        }
}
class topics {
	protected $storage=array();
	public $map_result=array();
	public function getByKey($key){
			global $collectionHandler;
			try {
				$result = $collectionHandler->byExample("topics", array("_key" => $key));
				$ar=$result->current();
				if(!empty($ar))
				return $ar->getAll();
				return [];
			} catch (\Exception $e) {
					return [];
			}

		}
	public function getAndSet($names) {
		global $connection;
		$fin="";
		for($i=0;$i<count($names);$i++){
			if($i+1<count($names))
				$fin.='"'.allFirstUppercase(sideSpaceCleaner($names[$i])).'",';
			else
				$fin.='"'.allFirstUppercase(sideSpaceCleaner($names[$i])).'"';
		}
        $trans=new Transaction($connection,array( 'collections' => array( 'write' => array( 'topics' ),'read'=> array( 'topics' ) ), 'waitForSync' => true ));
        $trans->setAction('function(){
            var db= require("@arangodb").db;
			var arr=['.$fin.'];
			var fin=[];
			for(var i=0;i<arr.length;i++){
			var key=db.topics.firstExample({"name":arr[i]});
			if(!key)
				fin[fin.length]=db.topics.insert([{"name":arr[i]}])[0]._key;
			else
				fin[fin.length]=key._key;
			}
			return fin;
        }');
        return $trans->execute();
	}
	public function mapping($topic_key) {
		global $connection;
        $query="FOR p IN INBOUND 'topics/".$topic_key['_key']."' GRAPH 'topic_relations' RETURN p";
        $statement = new Statement(
            $connection,
            array(
                "query"=> $query,
            )
        );
        $cursor = $statement->execute();
		$d=getall($cursor);
		$store=$this->storage;
		if(!empty($d[0])){
		array_push($store,$d[0]);
		$this->storage=$store;
		$this->mapping($d[0]);
		}else{
		$store=array_reverse($store);
		$arr=($this->storage!="")?$store:array();
		$this->result=$arr;
		}
	}
	public function keyToName($key){
		global $collectionHandler;
		$key=(!empty($key))?$key:"";
		try{
		$result = $collectionHandler->byExample("topics", array("_key" => $key));
		$ar=$result->current();
		return $ar->getAll();
		}catch(exception $e){

		}
	}
	public function keyToNameMany($keys){
		global $connection;
		$arr=implode(",", $keys);
        $query='FOR t IN ['.$arr.'] RETURN DOCUMENT(CONCAT("topics/",t)).name';
        $statement = new Statement(
            $connection,
            array(
                "query"=> $query,
            )
        );
        $cursor = $statement->execute();
        $transit= $cursor->getAll();
        return $transit;
	}
	public function searchByName($input){
		global $connection;
		$query = 'FOR doc IN topics FILTER LIKE(doc.name, "'.$input.'%", true) SORT doc.name RETURN {"name":doc.name,"_key":doc._key,"picture":doc.picture}';
		$statement = new Statement(
                $connection,
                array(
                    "query"=> $query,
                )
            );
            $cursor = $statement->execute();
            return getall($cursor);
	}
	public function check_in_use($key){
		$query='FOR f IN files FILTER f.in_use=="'.$key.'" RETURN f';
            $statement = new Statement(
                $connection,
                array(
                    "query"=> $query,
                )
            );
            $cursor = $statement->execute();
			return (count(getall($cursor))>0)?true:false;
	}
	public function topTrends($filter,$order){
		global $connection;
		$query = 'LET r=(FOR p IN posts
		FILTER IS_ARRAY(p.topics)
		FOR t IN p.topics
		COLLECT topic= t INTO pp
		RETURN MERGE(DOCUMENT(CONCAT("topics/",topic)),{points:'.$filter.'}))
		FOR f IN r
		SORT f.points '.$order.'
		RETURN f';
		$statement = new Statement(
            $connection,
            array(
                "query"=> $query,
            )
        );
        $cursor = $statement->execute();
		$arr=getall($cursor);
        return $arr;
	}
	public function update($key,$vals){
		global $handler;
		global $collectionHandler;
		$document = $handler->get('topics', $key);
		foreach ($vals as $key => $value) {
			$document->set($key,$value);
		}
		$handler->update($document);
	}
	public function getKeyByName($name){
        global $connection;
        $query = 'FOR t IN topics FILTER t.name=="'.$name.'" RETURN t._key';
        $statement = new Statement(
            $connection,
            array(
                "query"=> $query,
            )
        );
        $cursor = $statement->execute();
        $transit= $cursor->getAll();
        return $transit;
	}
}
class groups{
    public function createNew($name, $type, $restriction,$picture){
            global $connection;
            if (in_array($type, array("hidden","public","closed","private","secret")) && in_array($restriction, array("noano","onlyano","none"))){
            $trans=new Transaction($connection,array( 'collections' => array( 'write' => array( 'users','group_relations','groups' ),'read'=> array( 'users','group_relations','groups' ) ), 'waitForSync' => true ));
            $trans->setAction('function(){
                var db= require("@arangodb").db;
				var ids = [];
				db.groups.insert([{"name":"'.$name.'","type":"'.$type.'","restriction":"'.$restriction.'","picture":"'.$picture.'"}]).forEach(
				  function(obj) {ids.push(obj._key);});
                db.group_relations.insert({"_from":"users/'.$_SESSION['uid'].'","_to":"groups/"+ids["0"],"status":"admin"});
				return ids["0"];
            }');
            return $trans->execute();
            }
        }
	public function getByKey($key){
		try{
		global $connection;
		$query = 'FOR g IN groups FILTER g._key=="'.$key.'" LIMIT 1 RETURN DISTINCT g';
		$statement = new Statement(
		    $connection,
		    array(
		        "query"=> $query
		    )
		);
		$cursor = $statement->execute();
		$transit=getall($cursor);
		return $transit[0];
		}catch(exception $e){
			return false;
		}
	}
}
class sessions{
	private $uid;
	function __construct($uid) {
        $this->uid=$uid;
    }
	public function getFilters(){
		try{
		global $connection;
		$query = 'FOR s IN sessions FILTER s._key=="'.$this->uid.'" LIMIT 1 RETURN DISTINCT s';
		$statement = new Statement(
		    $connection,
		    array(
		        "query"=> $query
		    )
		);
		$cursor = $statement->execute();
		$transit=getall($cursor);
		return $transit[0];
		}catch(exception $e){
			return false;
		}
	}
	public function getBubbles(){
		try{
		global $connection;
		$query = 'FOR s IN sessions FILTER s._key=="'.$this->uid.'" LIMIT 1 RETURN DISTINCT s.bubbles';
		$statement = new Statement(
		    $connection,
		    array(
		        "query"=> $query
		    )
		);
		$cursor = $statement->execute();
		$transit=getall($cursor);
		return $transit[0];
		}catch(exception $e){
			return false;
		}
	}
	public function newUserSession(){
		global $connection;
		$query = 'INSERT {"_key":"'.$this->uid.'","bubbles":["tool_bubble/4379","tool_bubble/7829","tool_bubble/287487"]} INTO sessions';
		$statement = new Statement(
			    $connection,
			    array(
			        "query"=> $query
			    )
			);
			$cursor = $statement->execute();
	}
	public function setFilters($filters){
			global $connection;
			$query = 'UPDATE {"_key":"'.$this->uid.'"} WITH '.json_encode($filters).' IN sessions';
			$statement = new Statement(
			    $connection,
			    array(
			        "query"=> $query
			    )
			);
			$cursor = $statement->execute();
		}
}
