<?php
	function classer($info){
		if(strpos($info, '[!')!==FALSE && strpos($info, ']')!==FALSE){
			$array= explode("[", $info);
			$final=array();
			foreach ($array as $key) {
				if($key!="" && $key!= null && !empty($key)){
					$type=explode("!", $key);
					$rules=substr($type[2], 0, -1);
					$rules=explode(",", $rules);
					foreach ($rules as $keye) {
						$keye=explode("=", $keye);
						$final[$type[1]][$keye[0]]= $keye[1];
					}
				}
			}
			return $final;
		}else{
			return strpos($info, '[!');
		}
	}
	function generateRandomString() {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < 12; $i++) {
	        $randomString .= $characters[rand(0, 11)];
	    }
	    return $randomString;
	}
	function password($password){
		$password= sha1($password);
		$salt='d0be2dc421be4fcd0172e5afceea3970e2f3d940';
		$new='';
		$d=0;
		for($i=0;$i < strlen($password); $i++) {
		    $new .= $password[$d].$salt[$i];
			$d++;
		}
		return $new;
	}
	function bignumberkiller($number) {
			$pcount=$number;
			if($pcount>=1000000000000){
				$pcount=(substr(substr(($pcount/1000000000000), 0, 4), -1)==".")?substr(($pcount/1000000000000), 0, 3):substr(($pcount/1000000000000), 0, 4);
				$pcount .='T';
			}else if($pcount>=1000000000){
				$pcount=(substr(substr(($pcount/1000000000), 0, 4), -1)==".")?substr(($pcount/1000000000), 0, 3):substr(($pcount/1000000000), 0, 4);
				$pcount .='B';
		    }else if($pcount>=1000000){
		    	$pcount=(substr(substr(($pcount/1000000), 0, 4), -1)==".")?substr(($pcount/1000000), 0, 3):substr(($pcount/1000000), 0, 4);
		    	$pcount .='M';
		    }else if($pcount>=1000){
		    	$pcount=(substr(substr(($pcount/1000), 0, 4), -1)==".")?substr(($pcount/1000), 0, 3):substr(($pcount/1000), 0, 4);
		    	$pcount .='K';
			}
			return $pcount;
		}
function isJson($string) {
    return ((is_string($string) &&
            (is_object(json_decode($string)) ||
            is_array(json_decode($string))))) ? true : false;
}
function isLink($string){
    $regex = "((https?|ftp)\:\/\/)?"; // SCHEME
    $regex .= "([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?"; // User and Pass
    $regex .= "([a-z0-9-.]*)\.([a-z]{2,3})"; // Host or IP
    $regex .= "(\:[0-9]{2,5})?"; // Port
    $regex .= "(\/([a-z0-9+\$_-]\.?)+)*\/?"; // Path
    $regex .= "(\?[a-z+&\$_.-][a-z0-9;:@&%=+\/\$_.-]*)?"; // GET Query
    $regex .= "(#[a-z_.-][a-z0-9+\$_.-]*)?"; // Anchor

    if(preg_match("/^$regex$/i", $string)) // `i` flag for case-insensitive
    {
        return true;
    }else{
        return false;
    }
}
function getUrls($string) {
    $regex = '/https?\:\/\/[^\" ]+/i';
    preg_match_all($regex, $string, $matches);
    return ($matches[0]);
}
function poster($string){
	while (strpos($string, '[[')&& strpos($string, ']]')) {
		$string=str_replace(get_pseudo($string), '', $string);
	}
	return $string;
}
function get_pseudo($string){
	return strstr(strstr($string, '[['),']]',true).']]';
}
function get_string_between($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}
function removeInBetween($string,$part1,$part2){
    $output=$string;
    $count=(substr_count($string,$part1));
    $i=0;
    while ($i<=$count){
        $mid=get_string_between($string,$part1,$part2);
        $output=str_replace($part1.$mid.$part2,'',$output);
        $i++;
    }
    return $output;
}
function arrange($array, $processor){
    $array= (array)$array;
    $final=$processor;
    foreach ($array as $key => $value){
        $final=str_replace("[[".$key."]]",$value,$final);
    }
    return $final;
}
function entities_exceptions($string,$exceptions){
    $escaped_str = htmlentities( $string );

    $replace_what = array_map( function($v){ return "~&lt;(/?)$v(.*?)&gt;~"; }, $exceptions );
    $replace_with = array_map( function($v){ return "<$1$v$2>"; }, $exceptions );
    return preg_replace( $replace_what, $replace_with, $escaped_str );
}
function check_session(){
	if(!isset($_SESSION['uid'])){
		die;
	}
}
function curl_post($url, $post)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    $data = curl_exec($ch);

    curl_close($ch);
    return $data;
}
function sideSpaceCleaner($t){
	while(0<strlen($t)&&$t[0]==" ")
		$t=substr($t, 1);
	while(0<strlen($t)&&$t[strlen($t)-1]==" ")
		$t=substr($t,0, -1);
	return $t;
}
function allFirstUppercase($t){
	$t=explode(" ", $t);
	$fin="";
	for($i=0;$i<count($t);$i++){
		if($i<count($t)-1)
		$fin.=ucfirst($t[$i]).' ';
		else
			$fin.=ucfirst($t[$i]);
	}
	return $fin;
}
?>