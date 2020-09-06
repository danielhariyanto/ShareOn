<?php
require "require.php"; //loads the backend
$key=htmlentities($_GET['key']);
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>ShareOn</title>
		<meta charset="utf-8">
		<?=$head?>
		<script>

		</script>
	</head>

<body>
	<?=$header?>
  <?php
    if(isset($_SESSION['uid'])){
			$side=str_replace('<div id="posts-bubble"','<div id="posts-bubble" post="'.$key.'"',$side);
			echo $side;
		}
  ?>
    <main-container post="<?=$key?>"></main-container>
    <info-box></info-box>
	<?=$bottom?>
</body>
</html>
