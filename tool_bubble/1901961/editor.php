<?php
$title="";
$options=array();
$multiple="false";
$cmultiple="";
$add="false";
$cadd="";
if(isset($_GET['key'])){
	require '../../classes/arangodb.php';
	$h=new triagens\ArangoDb\posts();
$h=$h->getByKey($_GET['key']);
$title=$h['content']['title'];
$options=$h['content']['options'];
$multiple=($h['content']['multiple']==='true')?"true":"false";
$add=($h['content']['add']==='true')?"true":"false";
$cadd=($h['content']['add']==='true')?"checked":"";
}
$options=json_encode($options);
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<link rel="stylesheet" type="text/css" href="style.css">
		<title>index</title>
		<script type='text/javascript' src='https://code.jquery.com/jquery-3.3.1.min.js'></script>
		<script type='text/javascript' src='file_upload.js'></script>
		<style>
			#file{
				width:250px;height:200px;opacity: 0;position:absolute;
			}
			#container{
				width:250px;height: 200px;border:2px rgb(200, 200, 200) dashed;margin-top:20px;border-radius:5px;float:left;position: relative;
			}
			textarea{
				float:left;width:320px; height:120px;resize:none;margin-top: 20px;color:rgb(100, 100, 100);
				border:1px solid rgb(200, 200, 200);margin-left:0px;border-radius:8px;
			}
			textarea:focus{
				border:1px solid rgb(100, 100, 100);
			}
			#title{
				width:598px;height: 30px;border-radius: 5px;border: 1px rgb(200, 200, 200) solid;color:rgb(100, 100, 100);
			}
			#title:focus{
				border:1px rgb(100, 100, 100) solid;
			}
			#instructions{
				margin-left:50px;margin-top:70px;font-size:12pt; color:rgb(120, 120, 120);
				width:150px;text-align: center;font-family:Arial;position:absolute;pointer-events: none;
			}
			img{
				position: absolute;width:100%;height: 100%;border-style: none;border-radius:5px;
			}
			#pollarea{
				margin-left:20px;position: relative;border: 1px black solid;float:right;width:300px;margin-top:20px;height:200px;
			}
			.option{
				max-width: 550px;padding:3px;border-top:1px solid rgb(200,200,200); display:block;position:relative;margin-bottom: -10px;
			}
			.close{
				font-size:10pt;color:white;margin-top:-2.5px;margin-left:5px;cursor: pointer;width:15px;height:15px;border-radius:2px;
				position:relative;font-weight:1000;font-family:"MS Sans Serif", Geneva, sans-serif;background:rgb(200, 200, 200); text-align:center;margin-top:6px;
				display:inline-block;top:0;position:absolute;
			}
			.title{
				max-width: 520px;overflow-wrap: break-word;margin-left: 30px;margin-top:-1px;margin-bottom:20px;display:inline-block;
			}
			#options{
				height: 200px; overflow-y:scroll;list-style:none;position:relative;margin-left: -37.5px;
			}
			#addbar{
				width:450px;margin-top:5px;height: 25px;border-radius:5px;border:1px solid rgb(200, 200, 200);color:rgb(100, 100, 100);padding-left:40px;
			}
			input[type="submit"] {
			    margin-right: -40px;font-weight:1000;
			    height: 25px;color:rgb(180, 180, 180);font-size:15pt;z-index:2;position:relative;
			    width: 40px;background: none;border-style:none;cursor:pointer;
			}
			label{
				color:rgb(95, 95, 95);
			}
		</style>
		<script>
			function changert(){
				var json=JSON.parse($("#content").attr('content'));
				json['title']=$("input[type=text]").val();
				$("#content").attr('content',JSON.stringify(json));
			}
		</script>
	</head>
	<body>
		<input type="text" placeholder="Enter title (optional)" value="<?=$title?>" onchange="changert();" id='title' />
		<form id="adder">
			<input type="submit" value="+"/><input type="text" id="addbar" placeholder="Add Option"/>
		</form>
		<ul id="options">
			<?php
			$arr=json_decode($options);
				foreach ($arr as $key) {
					echo "<li class='option'><div class='close'>X</div><div class='title'>".$key."</div></li>";
				}
			?>
		</ul>
		<label><input type="checkbox" id="optionAdd" <?=$cadd?>/>Allow additional options</label><br />
		<?php
			if(!isset($h))
			echo '<label><input type="checkbox" id="multiAdd" />Allow multiple choices</label>';
		?>
		<div id='content' content='{"options":<?=$options?>,"title":"<?=$title?>","add":<?=$add?>,"multiple":<?=$multiple?>}' style="visibility: hidden;"></div>
		<div id='links' content='' style="visibility: hidden"></div>
		<script>
			$(document).off('submit','#adder').on('submit', '#adder', function(event) {
		    	event.preventDefault();
				var json=JSON.parse($("#content").attr('content'));
				var arr=json['options'];
				arr=new Set(arr);
				if(!arr.has($("#adder input[type=text]").val()))
					$("#options").append("<li class='option'><div class='close'>X</div><div class='title'>"+$("#adder input[type=text]").val()+"</div></li>");
				arr.add($("#adder input[type=text]").val());
				arr=Array.from(arr);
				json['options']=arr;
				$("#content").attr('content',JSON.stringify(json));
				$("#addbar").val("");
		    });
		    $(document).on('click','.close',function(){
		    	var json=JSON.parse($("#content").attr('content'));
				var arr=json['options'];
				arr=new Set(arr);
		    	var text=$(this).parent().find('.title').text();
		    	$(this).parent().remove();
		    	if(arr.has(text))
		    	arr.delete(text);
		    	arr=Array.from(arr);
		    	json['options']=arr;
		    	$("#content").attr('content',JSON.stringify(json));
		    });
		    $(document).on("change","#optionAdd",function(){
		    	var json=JSON.parse($("#content").attr('content'));
		    	if($(this).is(":checked"))
		    	json['add']=true;
		    	else
		    	json['add']=false;
		    	$("#content").attr('content',JSON.stringify(json));
		    });
		    $(document).on("change","#multiAdd",function(){
		    	var json=JSON.parse($("#content").attr('content'));
		    	if($(this).is(":checked"))
		    	json['multiple']=true;
		    	else
		    	json['multiple']=false;
		    	$("#content").attr('content',JSON.stringify(json));
		    });
		</script>
	</body>
</html>
