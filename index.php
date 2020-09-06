<?php
require "require.php"; //loads the backend
$switcher="";
if(isset($_SESSION['uid'])){
$switcher=$_SESSION['main_page'];
}
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
    if(isset($_SESSION['uid']))
		echo $side;
		if(isset($_SESSION['uid'])){
  ?>
    <main-container url='<?=$web?>geters/postsload.php' out='<?=json_encode($out)?>' exclusively='<?=json_encode($ex)?>' order='<?=$order?>' flipped='<?=$flipped?>' switcher="<?=$switcher?>"></main-container>
		<?php
	}else{
		 ?>
		 <main-container url='<?=$web?>geters/postsload.php'></main-container>
		 <?php
	 } ?>
    <info-box></info-box>
	<?=$bottom?>
</body>
</html>
