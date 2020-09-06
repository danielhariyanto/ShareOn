<?php
require "../require.php"; //loads the backend
$match=false;
if(isset($_GET['tool_bubble'])&&isset($_SESSION['uid'])){
	$t= new triagens\ArangoDb\toolBubble();
	$t=$t->getByKey(htmlentities($_GET['tool_bubble']));
	if($t['creator']==$_SESSION['uid'])
	$match=true;
}
if(!$match)
 header("Location: /");
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>ShareOn</title>
		<meta charset="utf-8">
		<?=$head?>
	</head>
<body dev="<?=$_GET['tool_bubble']?>">
	<?=$header?>
	  <?php
			echo $side;
		$order="";
		$flipped="";
		if(isset($_SESSION['filters']['posts']['order'])){
		$h=explode("-", $_SESSION['filters']['posts']['order']);
		if($h[1]==1||$h[1]==0)
		$flipped=$h[1];
		$order=$h[0];
		}
	  ?>
    <main-container url='<?=$web?>geters/devPost.php?key=<?=$t['_key']?>'></main-container>
    <div id="dev-settings">
    	<form>
    	<input type="hidden" name="key" value="<?=(isset($t['_key']))?$t['_key']:""?>"/>
		<input type="text" name="name" value="<?=(isset($t['name']))?$t['name']:""?>" placeholder="name"/>
		<input type="text" name="edit" value="<?=(isset($t['edit']))?$t['edit']:""?>" placeholder="editor url"/>
		<input type="text" name="post" value="<?=(isset($t['post']))?$t['post']:""?>" placeholder="processor url"/>
		<input type="number" name="size" value="<?=(isset($t['size']))?$t['size']:""?>" placeholder="height in px or auto if 0" />
		<select name="scroll">
			<option disabled=""<?=(!isset($t['scroll']))?" selected":""?>>scroll option:</option>
			<option value="scroll"<?=(isset($t['scroll'])&&$t['scroll'])?" selected":""?>>Scrollable</option>
			<option value="noscroll"<?=(isset($t['scroll'])&&!$t['scroll'])?" selected":""?>>Unscrollable</option>
		</select>
		<textarea placeholder="please writer your description here" name="description"><?=(isset($t['description']))?$t['description']:""?></textarea>
		<button id="save">Save</button><button id="publish">Publish</button>
	</form>
			<div id="bigger-button" src="<?=$web?>icons/bigger_arrow.png"/></div>
			<div id="filelist" key="<?=$t['_key']?>" placeholder="add file" data="/tool_bubble/">
				<label for="file" id="add">add file<input type="file" id="file"class="invisible" name="file" key="test"/></label>
				<?php
				$do=new digitalO();
				$contents=$do->listContents($t['_key']."/",true);
				foreach ($contents as $object) {
				    echo "<div class='fileoption'><span>".$object['basename']."</span><button>delete</button></div>";
				}
				?>
			</div>
			</div>
    <?=$bottom?>
    <script src="<?=$web?>tool_bubble/development.js"></script>
	<script>
	$("#posts-bubble").addClass("dev");
	$("#posts-bubble").attr("type","<?=$_GET['tool_bubble']?>");
	$("#posts-bubble").attr("edit",$("input[name=editor]").val());
	</script>
</body>
</html>
