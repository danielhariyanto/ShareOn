<?php
if(isset($_GET['key'])){
	require "require.php";
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
    $t= new triagens\ArangoDb\topics();
    if(is_numeric($_GET['key'])){

        $topic=$_GET['key'];
				$tname=$t->getByKey($topic);
				$tname=$tname['name'];
    }
    else{
			$k=explode(" ",$_GET['key']);
			$f="";
			for($i=0;$i<count($k);$i++){
				$f.=ucfirst($k[$i]);
				if($i<count($k)-1)
				$f.=" ";
			}
        $topic=$t->getKeyByName($f);
        $topic=$topic[0];
				$tname=$f;
    }
		if(isset($_SESSION['uid'])){
			$side=str_replace('<div id="posts-bubble"','<div id="posts-bubble" topic="'.$tname.'"',$side);
			echo $side;
  ?>
    <main-container url='<?=$web?>geters/postsload.php' out='<?=json_encode($out)?>' exclusively='<?=json_encode($ex)?>' order='<?=$order?>' flipped='<?=$flipped?>' switcher="<?=$switcher?>" topic="<?=$topic?>"></main-container>
		<?php
	}else{
		 ?>
		 <main-container url='<?=$web?>geters/postsload.php' topic="<?=$topic?>"></main-container>
		 <?php
	 } ?>
		<topic-info id="box" key="<?=$topic?>"></topic-info>
    <?=$bottom?>
</body>
</html>
<?php
}
?>
