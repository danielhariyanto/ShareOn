<?php
$h='';
if(isset($_GET['key']))
$h=($_GET['key']);
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<link rel="stylesheet" type="text/css" href="style.css">
		<title>index</title>
		<script type='text/javascript' src='https://code.jquery.com/jquery-3.3.1.min.js'></script>
		<script>
			function changer(){
				$("#content").attr('content','{"text":"'+$("textarea").val().replace(/\n\r?/g, '<br />')+'"}');
			}
		</script>
	</head>
	<body>
		<textarea onchange="changer();" autofocus placeholder="Write your thoughts here"></textarea>
		<div id='content' content='{"text":""}' style="visibility: hidden"></div>
		<div id='links' content='' style="visibility: hidden"></div>
		<script>
			$(document).ready(function(){
				document.querySelector('textarea').focus();
				console.log($.get('http://localhost/ShareOn/arango3rd.php?post_key=<?=$h?>',function(d){
				$('#content').attr('content','{"text":"'+d['data']['content']['text']+'"}');
				var t=d['data']['content']['text'].replace('<br />','\n').replace('<br />',"\n");
				$("textarea").val(t);
			}));
			});
		</script>
	</body>
</html>
