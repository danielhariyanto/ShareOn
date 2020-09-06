/*$(document).on("click","#create",function(){
	if($(this).parent().parent().parent().hasClass("mfp-content")){
	$("#collectionList").empty();
	var d="<div id='pictureSelectorBox'><input id='anofile' type='file' name='file' data=''/><label for='anofile'>"+
	"<img src='/icons/+.png'/></label>	</div><input type='text' placeholder='Choose a title for your collection' id='newCollectionTitle'/>"+
	"<select><option value='global'>Global</option><option value='friends'>Friends</option><option value='personal'>Personal</option></select><button>Create</button>";
	$("#collectionList").append(d);
	$("#collectionList").attr("id",'collectionCreator');
	$("#collectionList").remove();
	}else{
	var d="<div id='collectionCreator' class='p'><div id='pictureSelectorBox'><input id='anofile' type='file' name='file' data=''/><label for='anofile'>"+
	"<img src='/icons/+.png'/></label>	</div><input type='text' placeholder='Choose a title for your collection' id='newCollectionTitle'/>"+
	"<select><option value='global'>Global</option><option value='friends'>Friends</option><option value='personal'>Personal</option></select><button>Create</button></div>";
	$.magnificPopup.open({
		items: {
	    src: $(d),
	    type: 'inline'
		},
		closeBtnInside: false,
		callbacks: {
			afterClose: function() {
				$( "#collectionCreator" ).remove();
			}
		}
	});
	}
});*/
$(document).on('click',".mfp-content .collectionBox",function(){
	if(typeof $(this).attr('id')==='undefined'){
	var c=$(this).parent().parent().attr("key");
	var p=$(this).attr("key");
	$.post("/writers/addToCollection.php",{key:c,post_key:p},function(data){
		$.magnificPopup.close();
	});
	}
});
$(document).on('click','#collectionCreator button',function(){
	var title=$("#newCollectionTitle").val();
	var picture=$("#anofile").attr('data');
	var view_option=$('#collectionCreator select').val();
	$.post("/writers/createCollection.php",{title:title,picture:picture,view_option:view_option},function(d){
		if($('#collectionCreator').hasClass('p')){
			$.magnificPopup.close();
			$('#collectionList').empty();
			$.getJSON("/geters/collectionLoad.php",function(json){
				var d="<div id='grid'><div class='collectionBox' id='create'>"+
				"<div class='portrait'><img src='/icons/+.png'/></div><p>Create Collection</p></div>"+collectionGrid(json)+"<br/></div>";
				$("#collectionList").append(d);
			});
		}else{
		$('#collectionCreator').empty();
		$('#collectionCreator').attr("id","collectionList");
		$.getJSON("/geters/collectionLoad.php",function(json){
			var d="<div id='grid'><div class='collectionBox' id='create'>"+
			"<div class='portrait'><img src='/icons/+.png'/></div><p>Create Collection</p></div>"+collectionGrid(json)+"<br/></div>";
			$("#collectionList").append(d);
		});
		}
	});
});
