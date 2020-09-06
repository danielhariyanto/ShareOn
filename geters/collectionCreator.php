<div id="collectionCreator">
	<div id='ccreatortitle'>Choose a name for your collection</div>
	<div id="collectionfileupload">
		<input type="file" name="file" class="file" />
		<p>click or drop file to choose a picture for your collection</p>
        
	</div>
	<form id="collectionSubmit">
		<div id="collectionfilename">Select a file</div>
		<progress class="prog" style="visibility: hidden;" value="0" min="0" max="100"></progress>
		<input id="collectiontitlecreator" placeholder="choose title for collection" type="text"/>
		<select class="view_option" name="view_option">
			<option>global</option>
			<option>friends</option>
			<option>personal</option>
		</select>
		<input style="position: absolute; bottom: 10px; right: 50px; width: 100px;height: 40px; border-radius:10px; border:0.5px solid grey;" type="submit" value="submit" />
	</form>
	<script>
	$(document).off('submit','#collectionSubmit').on('submit', '#collectionSubmit', function(event) {
    event.preventDefault();
    var view=$("#collectionSubmit .view_option option:selected").val();
	var pic=$('#collectionfilename').attr('data');
	var title=$('#collectiontitlecreator').val();
    	$.post('http://localhost/YUGIV/writers/createCollection.php',{view_option:view,picture:pic,title:title}, function(d){
    		alert(d);
    	});
     });
		
        $(document).off('change','.file').on('change','.file',function (e) {
            var file= $('.file').val();
            var name= text = file.substring(file.lastIndexOf("\\") + 1, file.length);
            var exts = ['jpeg','gif','bmp','png','jpg'];
            if ( file ) {
                var get_ext = file.split('.');
                get_ext = get_ext.reverse();
                if ( $.inArray ( get_ext[0].toLowerCase(), exts ) > -1 ){
                    $('#collectionfilename').html(name);
                    $("#prog").css('visibility', 'visible');
                    $(".file").upload('/ShareOn/writers/uploadfile.php',function(data){
                    	alert(data);
                        $("#collectionfileupload p").remove();
                        $(".handler").remove();
                        $("#collectionfileupload").append("<img width='100px' class='handler' heigth='60px' src='"+data+"' />");
                        $('#collectionfilename').attr('data',data);
                    },$(".prog"));
                    setInterval(function(){  $(".prog").css('visibility', 'hidden');}, 500);
                } else {
                    $(this).val('');
                }
            }
        });
	</script>
</div>