<?php
	$p= new triagens\ArangoDb\posts();
	$check=$p->checkAno($key);
	if($check){
	$arr=$p->getByKey($key);
	$arr=$arr['user_key'];
	$pic="<img width='100px' class='handler' heigth='60px' src='".$arr['picture']."' />";
	    ?>
<div id="anonymous-container" name="<?=$key?>">
<div id="anonymous-logo">
		<div id="anofile-bubble"><div>choose alternative picture</div></div>
		<input type="file" name="file" id="anofile" data='' class="file" />
		<label for='anofile'><?=$pic?></label>
	</div>
<input id="anonymous-name" type="text" readonly="readonly" value="<?=$arr['name']?>">
<input id="anonymous-password" placeholder="Please confirm old password:" type="password">
<button id="ghost-on">submit</button>
<script>
    $(document).off('click','#ghost-on').on('click','#ghost-on', function(){
        var password=$("#anonymous-password").val();
        $.post('http://localhost/ShareOn/geters/anonymouscheck.php',{_key: "<?=$key?>", password:password}, function(ddata) {
            if(ddata=="good") {
                $("header").attr('apass', password);
                $.magnificPopup.close();
                $("#anobox").remove();
            }else{
                alert("wrong password");
            }
        });
    });

</script>
</div>
<?php
    }
?>