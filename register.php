<?php
require ("require.php");
if(isset($_SESSION['uid'])){
	header("Location: ".$web);
	exit;
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>ShareOn</title>
		<meta charset="utf8_unicode_ci">
		<link href='https://fonts.googleapis.com/css?family=Bree+Serif' rel='stylesheet' type='text/css'>
		<link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" type="text/css" href="<?=$web?>css/mystyle.css">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
		<script type='text/javascript' src='<?=$web?>src/jquery-2.1.4.min.js'></script>
		<link rel="stylesheet" type="text/css" href="<?=$web?>css/tipsy.css">
		<script type="text/javascript" src="<?=$web?>src/jquery.tipsy.js"></script>
		<script type="text/javascript" src="<?=$web?>js/register.js"></script>
		<script>
			$.fn.tipsy.defaults = {
			    delayIn: 0,      // delay before showing tooltip (ms)
			    delayOut: 0,     // delay before hiding tooltip (ms)
			    fade: true,     // fade tooltips in/out?
			    fallback: '',    // fallback text to use when no tooltip text
			    gravity: 'n',    // gravity
			    html: true,     // is tooltip content HTML?
			    live: false,     // use live event support?
			    offset: 0,       // pixel offset of tooltip from element
			    opacity: 0.8,    // opacity of tooltip
			    title: 'title',  // attribute/callback containing tooltip text
			    trigger: 'manual' // how tooltip is triggered - hover | focus | manual
			};
		</script>
	</head>

	<body style="background-image: url('<?=$web?>icons/space-2.svg');background-size:100%;background-position: 0px -235px;">
		<div class = "row align-items-end text-center" style="height: 35vh;">	
			<div class = "col">
				<a href="<?=$web?>register"><img src="<?=$web?>icons/logo-white.png" style="top: 150px;"></a>
			</div>
		</div>	
		<div class = "row" style="width: 50vw; margin-left: auto; margin-right: auto; margin-top: 150px">	
			<div class = "col-sm">
				<div id="big-signup-button" class="btn btn-lg btn-danger text-center"  style="float: right;">Sign Up</div>
			</div>
			<div class = "col-sm">
				<div id="big-signon-button" class="btn btn-lg btn-success text-center">Sign On</div>
			</div>
		</div>
	<script>
		$( document ).ready(function() {
			if(window.location.href =="<?=$web?>register/sign-up"){
				$("#big-signup-button").remove();
				$("#big-signon-button").remove();
				$("body").append("<div id='register-box' style='opacity:0;'></div>");
				$.get("http://localhost/ShareOn/geters/signup-form.php",function(data){
					history.pushState({
					    id: 'sign-up'
					}, 'Sign-Up', 'http://localhost/ShareOn/register/sign-up');
			
					$("#register-box").append(data);
					$("#register-box").animate({
						opacity:1
					},1000);
				});
			}else if(window.location.href.indexOf("sign-on") > -1){
				$("#big-signup-button").remove();
				$("#big-signon-button").remove();
				$("body").append("<div id='login-box' style='opacity:0;'></div>");
				$.get("http://localhost/ShareOn/geters/signon-form.php",function(data){
					history.pushState({
					    id: 'sign-up'
					}, 'Sign-On', 'http://localhost/ShareOn/register/sign-on');
			
					$("#login-box").append(data);
					$("#login-box").animate({
						opacity:1
					},1000);
				});
			}
		});
		$(".fu[name='lname']").tipsy("show");
	function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length, c.length);
    }
    return "";
}
		function log(){
		  var x= document.forms["loginform"]["username"].value;
		  var y= document.forms["loginform"]["password"].value;
		  if(x!=="" && y!==""){
		  document.cookie="username="+x+";";
		  document.cookie="password="+y+";";
		  window.location.replace("localhost/YUGIV/index.php");
		  }
		}
		function reg(){
		  var x= document.forms["regisform"]["one"].value;
		  var y= document.forms["regisform"]["two"].value;
		  var z= document.forms["regisform"]["three"].value;
		  var w= document.forms["regisform"]["four"].value;
		  var t= document.forms["regisform"]["five"].value;
		  var u= document.forms["regisform"]["year"].value;
		  var str= x+y;
		  var res = str.toLowerCase(); 
		  if(x!=="" && y!=="" && z!=="" && w!=="" && t!=="" && t===w && u<=2005){
		  document.cookie="username="+res+";";
		  document.cookie="password="+t+";";
		  
		  }
		}
	</script>
	</body>
</html>