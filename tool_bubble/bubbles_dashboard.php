<?php
require "../require.php"; //loads the backend
$t= new triagens\ArangoDb\toolBubble();
$result=$t->getByCreator($_SESSION['uid']);
if(!isset($_SESSION['uid'])){ //if not logged in
    		header("Location: ".$web."/register/sign-on");
		}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>ShareOn</title>
		<meta charset="utf-8">
		<?=$head?>
		<script>
			$.fn.preload = function() {
    			this.each(function(){
			        $('<img/>')[0].src = this;
			    });
			}

			// Usage:


		</script>
	</head>
<body>
	<?=$header?>
  	<?php
    	if(isset($_SESSION['uid'])){
    		echo $side;
  		}
 	?>
	<div id = "dashboard_wrapper">
			<span class="imageBox" id="post-typeCreator">
				<img src="/icon/addPost.png">
				<figcaption>create post-type</figcaption>
			</span>
			<?php
				foreach($result as $value){
					echo
					'<span class="imageBox" key="'.$value['_key'].'">
						<a href="'.$web.'tool_bubble/bubble_developer.php?tool_bubble='.$value['_key'].'">
							<img src="https://'.$space.'.nyc3.digitaloceanspaces.com/'.$value['_key'].'/icon.png">
						</a>
						<div class = "cancelPost" id = "'.$value['_key'].'">
	  						<img src="/icon/orange-x.jpg">
						</div>
						<figcaption>'.$value['name'].'</figcaption>
					</span>';
				}
			?>
	</div>
  <script type='text/javascript' src='<?=$web?>js/dashboard.js'></script>

</body>
</html>
