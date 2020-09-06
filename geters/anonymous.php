<div id="anonymous-container">
    <?php
	    if(isset($_POST['key'])){
			$pid=htmlentities($_POST['key']);
			$post=new triagens\ArangoDb\posts();
			$key=$post->getByKey($pid);
		}
    	$piclink= (isset($key['user_key']['picture']))?$key['user_key']['picture']:"http://localhost/ShareOn/icons/Ghost-On.png";
        $pic=($piclink=="")?"<img src='http://localhost/ShareOn/icons/Ghost-On.png'/>":"<img class='handler' src='".$piclink."' />";
        $name= (isset($key['user_key']['name']))?" value='".$key['user_key']['name']."'":" value='anonymous'";
    ?>
	<div id="anonymous-logo">
		<div id="anofile-bubble"><div>choose alternative picture</div></div>
		<input type="file" name="file" id="anofile" data='<?=$piclink?>' class="file" />
		<label for='anofile'><?=$pic?></label>
	</div>
    <input id="anonymous-name"<?=$name?>  placeholder="Name:" type="text">
    <input id="anonymous-password" placeholder="Password:" type="password">
    <button id="ghost-on">Ghost On</button><button id="cancel-anonymous">Cancel</button>
	<script>
	$(document).off('click','#ghost-on').on('click', '#ghost-on', function() {
    var password=$("#anonymous-password").val();
			var pic=$('#anofile').attr('data');
            var name=$("#anonymous-name").val();
			if(password!="") {
                $("#post-editor").attr('apic', pic);
                $("#post-editor").attr('aname', name);
                $("#post-editor").attr('apass', password);
                $("#anonymous-container").remove();
                $("#anonymous-container").remove();
                $.magnificPopup.close();
            }
     });
     $(document).off('click','#cancel-anonymous').on('click', '#cancel-anonymous', function() {
                $("#post-editor").attr('ano', "no");
                $("#anonymous-container").remove();
                $("#anonymous-container").remove();
                $.magnificPopup.close();
     });
     $(document).off('change','#anofile').on('change','#anofile',function (e) {
            var file= $(this).val();
            var exts = ['jpeg','gif','bmp','png','jpg'];
            if ( file ) {
                var get_ext = file.split('.');
                get_ext = get_ext.reverse();
                if ( $.inArray ( get_ext[0].toLowerCase(), exts ) > -1 ){
                    $("#anofile").upload('<?=$web?>writers/uploadfile.php',function(data){
                        $("#anonymous-logo img").attr("src",data);
                        $('#anofile').attr('data',data);
                    },$(".prog"));
                    setInterval(function(){  $(".prog").css('visibility', 'hidden');}, 500);
                } else {
                    $(this).val('');
                }
            }
        });
	</script>
</div>