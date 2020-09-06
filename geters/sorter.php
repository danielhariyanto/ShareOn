<div id="sort-box"<?=(isset($key))?" key='".$key."'":""?>>
	<?php
	$rel= new triagens\ArangoDb\posts();
	if(empty($_POST)){
		$pmax=$rel->highestPoints(0)+1;
		$pmin=$rel->highestPoints(1)-1;
		$umax=$rel->highestUpvotes(0)+1;
		$umin=$rel->highestUpvotes(1)-1;
		$dmax=$rel->highestDownvotes(0)+1;
		$dmin=$rel->highestDownvotes(1)-1;
		$cmax=$rel->highestComments(0)+1;
		$cmin=$rel->highestComments(1)-1;
	}else{
		$pmax=$rel->commentHighestPoints($key,0)+1;
		$pmin=$rel->commentHighestPoints($key,1)-1;
		$umax=$rel->commentHighestUpvotes($key,0)+1;
		$umin=$rel->commentHighestUpvotes($key,1)-1;
		$dmax=$rel->commentHighestDownvotes($key,0)+1;
		$dmin=$rel->commentHighestDownvotes($key,1)-1;
		$cmax=$rel->commentHighestComments($key,0)+1;
		$cmin=$rel->commentHighestComments($key,1)-1;
	}
	$tmax=0;
	$tmin=0;
	$global=" checked";
	$friends=" checked";
	$personal=" checked";
	$anonymous=" checked";
	if(!empty($_POST))
	$comment=" checked";
	$sort=isset($_SESSION['filters']['posts'])?$_SESSION['filters']['posts']:[];
	foreach ($sort as $key => $value) {
		if(!is_array($value)){
		//checks if the value has a range to set it up in html
		if(strpos($value, '_') !== FALSE){
		$$key=explode("_", $value);//creates object if key=points then $points is created
		$$key[2]=(strlen($$key[0])!=0&&strlen($$key[1])!=0)?" , ":'';//quoma or not in the max ranges
		$keyr=$key."r";//range type whether full bar, only min, only max or nothing
		$keyval=$key."val";//sets the input['text']s at specified values
		//both max and min are enabled
		if(strlen($$key[2])!=0){
		$$keyr="range:true,";
		$$keyval="$('#".$key."-writemin').val(ui.values[0]);
	      	$('#".$key."-writemax').val(ui.values[1]);";
		//max only enabled
		}elseif(strlen($$key[1])!=0){
		$$keyr='range:"min",';
		$$keyval="$('#".$key."-writemax').val(ui.value);";
		//min only enabled
		}elseif(strlen($$key[0])!=0){
		$$keyr="range:'max',";
		$$keyval="$('#".$key."-writemin').val(ui.value);";
		//everything disabled
		}else{
		$$keyr="disabled:true, range: true,";
		$$keyval="";
		}
		$vkey="v".$key;//for current handles of slider represented as $vpoints for example
		$min=$key[0]."min";//smallest possible
		$max=$key[0]."max";//biggest possible
		$a=($$key[0]!="")?$$key[0]:$$min;
		$b=($$key[1]!="")?$$key[1]:$$max;
		//sets values for current handles of slider sets $vpoints for example
		if((strlen($$key[2])!=0)){
			$$vkey="values: [".$a.",".$b."],";
		}elseif(strlen($key[1])!=0){
			$$vkey="value: ".$a.",";
		}elseif(strlen($$key[0])!=0){
			$$vkey="value: ".$a.",";
		}elseif((strlen($$key[2])==0)){
			$$vkey="";
		}
		}else{
			//simple value such as $global
			$$key=$value;
		}
		}
	}
	if(isset($sort['disabled_view'])){
	$global=(in_array("global", $sort['disabled_view']))?"":" checked";
	$personal=(in_array("personal", $sort['disabled_view']))?"":" checked";
	$anonymous=(in_array("anonymous", $sort['disabled_view']))?"":" checked";
	if(!empty($_POST))
	$comment=(in_array("comment", $sort['disabled_view']))?"":" checked";
}
	?>
	<div id="sort-top">
	<img id="box-icon" src="<?=$web?>icon/1sort.png"/><div id="sort-title">Filter Posts</div>
	</div>
	<div id="sort-grid">
	<label id="global-sort"><input type="checkbox" <?=$global?>/>Global</label>
	<label id="personal-sort"><input type="checkbox" <?=$personal?>/>Personal</label>
	<?php
	if(!empty($_POST))
	echo '<label id="comment-sort"><input type="checkbox" '.$anonymous.'/>Comment</label>';
	?>
	<!--<label id="anonymous-sort"><input type="checkbox" <?=$anonymous?>/>Anonymous</label>-->
	</div>
	<div class="title"><img src="<?=$web?>icon/time.png"/>Time range:</div><input placeholder="Start time" cl type="text" value="<?=(isset($time[0])&&$time[0]!="")?date("m/d/Y H:i",$time[0]):""?>" id="timesetter1" />
	<div class="sort-space-tag">-</div><input type="text" value="<?=(isset($time[1])&&$time[1]!="")?date("m/d/Y H:i",$time[1]):""?>" placeholder="End time" id="timesetter2" />
	<div class="title"><img src="<?=$web?>icon/points.png"/>Points:</div><div range="<?=$pmin."_".$pmax?>" min="<?=(isset($points[0])&&$points[0]!="")?$points[0]:$pmin?>" max="<?=(isset($points[1])&&$points[1]!="")?$points[1]:$pmax?>" class="slider" id="points-range"></div>
	<div id="points-writecontainer">
		<input type="text" value="<?=(isset($points[0]))?$points[0]:""?>" placeholder="Min" maxlength="12" class="write" id="points-writemin"/>
		<label id="points-checkmin" class="points-check"><input type="checkbox" <?=(isset($points[0])&&strlen($points[0])!=0)?'checked':""?>/></label>
		<div class="sort-space-tag">-</div>
		<input type="text" value="<?=(isset($points[1]))?$points[1]:""?>" maxlength="12" placeholder="Max" class="write" id="points-writemax"/>
		<label id="points-checkmax" class="points-check"><input type="checkbox" <?=(isset($points[1])&&strlen($points[1])!=0)?'checked':""?>/></label>
	</div>
	<div class="title"><img src="<?=$web?>icon/star-grey.png"/>Upvotes:</div><div range="<?=$umin."_".$umax?>" min="<?=(isset($upvotes[0])&&$upvotes[0]!="")?$upvotes[0]:$umin?>" max="<?=(isset($upvotes[1])&&$upvotes[1]!="")?$upvotes[1]:$umax?>" class="slider" id="upvotes-range"></div>
	<div id="upvotes-writecontainer">
		<input type="text" id="upvotes-writemin" maxlength="12" placeholder="Min" class="write" value="<?=(isset($upvotes[0]))?$upvotes[0]:""?>"/>
		<label id="upvotes-checkmin" class="upvotes-check"><input type="checkbox" <?=(isset($upvotes)&&strlen($upvotes[0])!=0)?'checked':""?>/></label>
		<div class="sort-space-tag">-</div>
		<input type="text" value="<?=(isset($upvotes[1]))?$upvotes[1]:""?>" maxlength="12" placeholder="Max" class="write" id="upvotes-writemax"/>
		<label id="upvotes-checkmax" class="upvotes-check"><input type="checkbox" <?=(isset($upvotes)&&strlen($upvotes[1])!=0)?'checked':""?>/></label>
	</div>
	<div class="title"><img src="<?=$web?>icon/cloud2.png"/>Downvotes:</div><div range="<?=$dmin."_".$dmax?>" min="<?=(isset($downvotes[0])&&$downvotes[0]!="")?$downvotes[0]:$dmin?>" max="<?=(isset($downvotes[1])&&$downvotes[1]!="")?$downvotes[1]:$dmax?>" class="slider" id="downvotes-range"></div>
	<div id="downvotes-writecontainer">
		<input type="text" id="downvotes-writemin" maxlength="12" placeholder="Min" class="write" value="<?=(isset($downvotes[0]))?$downvotes[0]:""?>"/>
		<label id="downvotes-checkmin" class="downvotes-check"><input type="checkbox" <?=(isset($downvotes[0])&&strlen($downvotes[0])!=0)?'checked':""?>/></label>
		<div class="sort-space-tag">-</div>
		<input type="text" id="downvotes-writemax" maxlength="12" placeholder="Max" class="write" value="<?=(isset($downvotes[1]))?$downvotes[1]:""?>"/>
		<label id="downvotes-checkmax" class="downvotes-check"><input type="checkbox" <?=(isset($downvotes[1])&&strlen($downvotes[1])!=0)?'checked':""?>/></label>
	</div>
	<div class="title"><img src="<?=$web?>icon/Comments-grey.png"/>Comments:</div><div range="<?=$cmin."_".$cmax?>" min="<?=(isset($comments[0])&&$comments[0]!="")?$comments[0]:$cmin?>" max="<?=(isset($comments[1])&&$comments[1]!="")?$comments[1]:$cmax?>" class="slider" id="comments-range"></div>
	<div id="comments-writecontainer">
		<input type="text" id="comments-writemin" maxlength="12" placeholder="Min" class="write" value="<?=(isset($comments[0]))?$comments[0]:""?>"/>
		<label id="comments-checkmin" class="comments-check"><input type="checkbox" <?=(isset($comments[0])&&strlen($comments[0])!=0)?'checked':""?>/></label>
		<div class="sort-space-tag">-</div>
		<input type="text" id="comments-writemax" maxlength="12" placeholder="Max" class="write" value="<?=(isset($comments[1]))?$comments[1]:""?>"/>
		<label id="comments-checkmax" class="comments-check"><input type="checkbox" <?=(isset($comments[1])&&strlen($comments[1])!=0)?'checked':""?>/></label>
	</div>
	<script>
            $( "#points-range" ).slider({
	      <?=(isset($pointsr))?$pointsr:"disabled:true, range: true,"?>
	      min: <?=$pmin?>,
	      max: <?=$pmax?>,
	      <?=(isset($vpoints))?$vpoints:""?>
	      slide: function( event, ui ) {
	      	<?=(isset($pointsval))?$pointsval:""?>
					if(sessionStorage.sort!=undefined)
					clearTimeout(sessionStorage.sort);
					sessionStorage.sort=setTimeout(function(){saveSort()},2000);
	      }
	    });
	    $( "#upvotes-range" ).slider({
	      <?=(isset($upvotesr))?$upvotesr:"disabled:true, range: true,"?>
	      min: <?=$umin?>,
	      max: <?=$umax?>,
	      <?=isset($vupvotes)?$vupvotes:""?>
	      slide: function( event, ui ) {
	      	<?=(isset($upvotesval))?$upvotesval:""?>
					if(sessionStorage.sort!=undefined)
					clearTimeout(sessionStorage.sort);
					sessionStorage.sort=setTimeout(function(){saveSort()},2000);
	      }
	    });
	    $( "#downvotes-range" ).slider({
	      <?=(isset($downvotesr))?$downvotesr:"disabled:true, range: true,"?>
	      min: <?=$dmin?>,
	      max: <?=$dmax?>,
	      <?=(isset($vdownvotes))?$vdownvotes:""?>
	      slide: function( event, ui ) {
	      	<?=(isset($downvotesval))?$downvotesval:""?>
					if(sessionStorage.sort!=undefined)
					clearTimeout(sessionStorage.sort);
					sessionStorage.sort=setTimeout(function(){saveSort()},2000);
	      }
	    });
	    $( "#comments-range" ).slider({
	      <?=(isset($commentsr))?$commentsr:"disabled:true, range: true,"?>
	      min: <?=$cmin?>,
	      max: <?=$cmax?>,
	      <?=(isset($vcomments))?$vcomments:""?>
	      slide: function( event, ui ) {
	      	<?=(isset($commentsval))?$commentsval:""?>
					if(sessionStorage.sort!=undefined)
					clearTimeout(sessionStorage.sort);
					sessionStorage.sort=setTimeout(function(){saveSort()},2000);
	      }
	    });
	    $('#points-range .ui-slider-range').css('background','rgba(13,76,169,0.9)');
	    $('#upvotes-range .ui-slider-range').css('background','rgba(13,76,169,0.9)');
	    $('#downvotes-range .ui-slider-range').css('background','rgba(13,76,169,0.9)');
	    $('#comments-range .ui-slider-range').css('background','rgba(13,76,169,0.9)');
			$("#timesetter1").datetimepicker();
			$("#timesetter2").datetimepicker();
	</script>
</div>
