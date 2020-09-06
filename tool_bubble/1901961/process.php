<?php
	session_start();
    require '../../classes/arangodb.php';
	$p = new triagens\ArangoDb\posts();
	$c=$p->getByKey($_GET['key']);
	$arr=array();
	if(file_exists("../../files/".$c['_key'].".json")){
		$arr=(array)json_decode(file_get_contents("../../files/".$c['_key'].".json"));
		$newJSON=array();
		$ar=array();
		$i=0;
		foreach ($c['content']['options'] as $key) {
			if(isset($arr[$key])){
				$newJSON[$key]=$arr[$key];
				$ar[$key][0]=count($arr[$key]);
				$ar[$key][2]=(in_array($_SESSION['uid'],$arr[$key]))?true:FALSE;
			}else{
				$newJSON[$key]=array();
				$ar[$key][0]=0;
				$ar[$key][2]=false;
			}
			$ar[$key][1]=$key;
		}
		$list=new SplDoublyLinkedList();
		foreach ($ar as $key) {
			$list=insert($list, $key);
		}
		$list->rewind();
		$fp = fopen("../../files/".$c['_key'].".json", 'w');
		fwrite($fp, json_encode($newJSON));
		fclose($fp);
	}else{
		$fp = fopen("../../files/".$c['_key'].".json", 'w');
		$json=array();
		foreach ($c['content']['options'] as $key) {
			$json[$key]=array();
		}
		fwrite($fp, json_encode($json));
		fclose($fp);
	}
	function insert($list,$element){
		if($list->isEmpty()){
			$list->push($element);
			return $list;
		}else if($list->count()==1){
			$temp=$list->offsetGet(0);
			if($temp[0]>$element[0])
				$list->add(1,$element);
			else
				$list->add(0,$element);
			return $list;
		}else{
			$low=0;
			$high=$list->count()-1;
			$mid=0;
			$last="l";
			while($low<=$high){		
				$mid=floor(($low+$high)/2);
				$temp=$list->offsetGet($mid);
				if($temp[0]>=$element[0]){
					$low=$mid+1;
					$last="l";
				}else{
					$high=$mid-1;
					$last="h";
				}
			}
			if($high>0&&$last=="l")
			$mid=floor(($low+$high)/2)+1;
			$list->add($mid,$element);
			return $list;
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<link rel="stylesheet" type="text/css" href="style.css">
		<title>index</title>
		<style>
			ul li{
				width:90%;position: relative;
			}
			.bar{
				display:block;background:rgb(250,250,250);border:1px solid rgb(100,100,100);border-radius:2px;width:85%;
				 position:relative;margin-bottom: 20px;cursor:pointer;
			}
			.bar:hover{
				box-shadow: inset 0px 0px 1px 0px rgb(100,100,100);
			}
			.bar p{
				width:90%;overflow-wrap: break-word;margin-left: 7.5%;position:relative;
			}
			.filler{
				width:100%;background:rgba(200,200,200,0.3);position:absolute;border:1px solid black;height: 100%;border-radius:2px;border-style:none;
			}
			input{
				height:14px;width: 14px;position: absolute; top:50%; margin-top: -6px; margin-left: 3%;
			}
			.count{position:absolute;right:15px;top:50%;margin-top:-9px;}
			#textadd{
				width:438px;height:48px;position: relative;margin-left:0%;margin-top: 0px; padding-left: 50px;
				border:1px solid rgb(100,100,100);border-radius:2px;
			}
			#textadd:focus{
				box-shadow: inset 0px 0px 2px 0px rgb(100,100,100);
			}
			#submit{
				position: absolute;height: 99%;width:50px;z-index:2;margin-left:0;top: 6px;background: none; border-style:none;
				color:rgb(80, 80, 80);font-size:20pt; font-weight: 600;margin-top: -1.5%;
			}
		</style>
		<script type='text/javascript' src='https://code.jquery.com/jquery-3.3.1.min.js'></script>
		<script>
			function changer(){
				$("#content").attr('content','{"text":"'+$("textarea").val().replace(/\n\r?/g, '<br />')+'"}');
			}
		</script>
	</head>
	<body>
		<p style='word-break:break-all;font-size:30pt;width:100%;text-align:center;font-weight:bold;unicode-bidi: embed;'><?=$c['content']['title']?></p>
		<ul style="list-style:none;">
		<?php
			if($c['content']['multiple']==='true'){
			if(isset($list)){
			$max=$list->current();
			$max=($max[0]>0)?$max[0]:1;
			while($list->valid()){
				$a=$list->current();
				$check=($a[2])?"checked":"";
				echo '<li><label class="bar"><div class="filler" count="'.$a[0].'" style="width: '.(($a[0]/$max)*100).'%;"></div><input '.$check.' type="checkbox" /><p>'.
				$a[1].'</p></label><div class="count">'.bignumberkiller($a[0]).'</div></li>';
				$list->next();
			}
			}else{
				foreach ($c['content']['options'] as $key) {
					echo '<li><label class="bar"><div class="filler" count="0" style="width: 0%;"></div><input type="checkbox" /><p>'.
					$key.'</p></label><div class="count">0</div></li>';
				}
			}
			}else{
				if(isset($list)){
			$max=$list->current();
			$max=($max[0]>0)?$max[0]:1;
			while($list->valid()){
				$a=$list->current();
				$check=($a[2])?"checked":"";
				$cl=($a[2])?'class="prev" ':"";
				echo '<li><label class="bar"><div class="filler" count="'.$a[0].'" style="width: '.(($a[0]/$max)*100).'%;"></div><input '.$cl.'name="choice" '.$check.' type="radio" /><p>'.
				$a[1].'</p></label><div class="count">'.bignumberkiller($a[0]).'</div></li>';
				$list->next();
			}
			}else{
				foreach ($c['content']['options'] as $key) {
					echo '<li><label class="bar"><div class="filler" count="0" style="width: 0%;"></div><input name="choice" type="radio" /><p>'.
					$key.'</p></label><div class="count">0</div></li>';
				}
			}
			}
			if($c['content']['add']==='true'){
				echo '<li><div class="bar" style="border-style:none;"><div class="filler"></div><form id="adder">
				<input type="submit" value="+" id="submit"><input id="textadd" type="text"/></form></div></li>';
			}
		?>
		</ul>
		<br /><br />
		<script>
			$('.bar input').click(function(){
				var th=$(this);
				var vote=$(this).parent().text();
			    if (th.hasClass('prev')) {
			        this.checked = false;
			        $.post("vote.php",{key:"<?=$_GET['key']?>",vote:vote,click:false},function(d){
			        	alert(d);
					var info=d.split(",");
					info[0]=(info[0]==0)?1:info[0];
					th.parent().find(".filler").attr("count",info[1]);
					$("ul").find(".filler").each(function(){
						$(this).attr("style","width:"+(($(this).attr("count")/info[0])*100)+"%;");
						$(this).parent().parent().find(".count").text(bignumberkiller($(this).attr("count")));
					});
					th.removeClass("prev");
				});
			    }
			});
			$(document).on("change",".bar input",function(){
				var th=$(this);
				if(th.is(':checked'))
				var click=true;
				else
				var click=false;
				var vote=$(this).parent().text();
				$.post("vote.php",{key:"<?=$_GET['key']?>",vote:vote,click:click},function(d){
					alert(d);
					var info=d.split(",");
					info[0]=(info[0]==0)?1:info[0];
					th.parent().find(".filler").attr("count",info[1]);
					if($(".bar input[type=radio]").length>0){
						$(".prev").parent().find(".filler").attr("count",$(".prev").parent().find(".filler").attr("count")-1);
						$(".prev").removeClass("prev");
						th.addClass("prev");
					}
					$("ul").find(".filler").each(function(){
						$(this).attr("style","width:"+(($(this).attr("count")/info[0])*100)+"%;");
						$(this).parent().parent().find(".count").text(bignumberkiller($(this).attr("count")));
					});
				});
			});
			$(document).off('submit','#adder').on('submit', '#adder', function(event) {
		    	event.preventDefault();
		    	var vote=$("#textadd").val();
		    	$("#textadd").val('');
				$.post("optionAdd.php",{key:"<?=$_GET['key']?>",vote:vote},function(dd){
					$.post("vote.php",{key:"<?=$_GET['key']?>",vote:vote,click:true},function(d){
						alert(d);
						location.reload();
					});
				});
		  	});
			function bignumberkiller(number) {
			var pcount= number;
			if(pcount>=1000000000000){
				var a=(pcount/1000000000000);
				pcount=(a.substr(0, 4).slice(-1)==".")?a.substr(0, 3):a.substr(0, 4);
				pcount +='T';
			}else if(pcount>=1000000000){
				var a=(pcount/1000000000);
				pcount=(a.substr(0, 4).slice(-1)==".")?a.substr(0, 3):a.substr(0, 4);
				pcount +='B';
		    }else if(pcount>=1000000){
		    	var a=(pcount/1000000);
				pcount=(a.substr(0, 4).slice(-1)==".")?a.substr(0, 3):a.substr(0, 4);
				pcount +='M';
		    }else if(pcount>=1000){
		    	var a=(pcount/1000);
				pcount=(a.substr(0, 4).slice(-1)==".")?a.substr(0, 3):a.substr(0, 4);
				pcount +='K';
			}
			return pcount;
		}
		</script>
	</body>
</html>
