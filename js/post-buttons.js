$(document).on('click','.comment-sort',function(){
	var th=$(this);
	setTimeout(function(){th.addClass("current-sort");},5);
	var sort=$(this).attr('sort');
	var key=th.attr('key');
	$.post("/geters/sorter.php",{sort:sort,key:key},function(data){
		$( "body" ).append(data);
	});
});
$(document).on('click', '.comment-options', function(){
	var name = $(this).attr('key');
	var key=name.split("-")[1];
	if($(".comment-options-bubble[key='"+key+"']").html()==undefined){
	$(".comment[key="+name+"]").prepend("<div class='comment-options-bubble' key='"+name+"'></div>");
	$.post('/geters/poptions.php',{name: key}, function(data) {
		$(".comment-options-bubble[key="+name+"]").append(data);
	});
	}
	setTimeout(function(){
    	$(".post-options-bubble[key="+name+"]").addClass("done");
	},500);
});
$(document).on('click', '.comments-button.isclick', function(){
	var name= $(this).attr('key');
	$(this).removeClass("isclick").addClass("notclick");
	$(".comments-button[key="+name+"] .icon").attr('src','/icons/Comments.png');
	$(".comments-container[key='"+name+"']").remove();
	$(this).parent().parent().parent().find(".comment-sort").remove();
	$(this).parent().parent().find(".outer-post").each(function(){
			$(this).remove();
		});
});
$(document).on('click', '.recommendcontainer', function(){
	var name = $(this).attr('key');
	$.post('/geters/recommend_option.php',{ key: name}, function(d){
	$.magnificPopup.open({
		items: {
	    src: $(d),
	    type: 'inline'
		},
		closeBtnInside: false,
		callbacks: {
			afterClose: function() {
				$( "#anobox" ).remove();
			}
		}
	});
});
});
$(document).on('click', '.collectionadd', function(){
	var name = $(this).parent().parent().attr('key');
	var key=(name.indexOf("-")>-1)?name.split("-")[1]:name;
	$.getJSON("/geters/collectionLoad.php",function(json){
	var d="<div id='collectionList' key='"+key+"'><div id='grid'><div class='collectionBox' id='create'>"+
	"<div class='portrait'><img src='/icons/+.png'/></div><p>Create Collection</p></div>"+collectionGrid(json)+"<br/></div></div>";
	$.magnificPopup.open({
		items: {
	    src: $(d),
	    type: 'inline'
		},
		closeBtnInside: false,
		callbacks: {
			afterClose: function() {
				$( "#collectionList" ).remove();
			}
		}
	});
	});
});
