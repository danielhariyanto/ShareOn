$("#add").on("change",function(){
	var file=$(this).find("input").prop("files")[0];
var fd = new FormData();
fd.append("file", file);
fd.append("key",$("#filelist").attr("key"));
var xhr = new XMLHttpRequest();
xhr.open('POST', 'uploadfile.php', true);
xhr.upload.onprogress = function(e) {
if (e.lengthComputable) {
var percentComplete = (e.loaded / e.total) * 100;
console.log(percentComplete + '% uploaded');
}
};
xhr.onload = function() {
if (this.status == 200) {
$("#filelist").append("<div class='fileoption'><span>"+this.response+"</span><button>delete</button></div>");
}
};
xhr.send(fd);
});
$(document).on("click",".fileoption button",function(){
	var th=$(this);
	var name=$(this).parent().find("span").text();
	var key=$("#filelist").attr("key");
	$.post("deletefile.php",{name:name,key:key},function(d){
		th.parent().remove();
	});
});
$("#dev-settings form").submit(function(e)
{
    var postData = $(this).serializeArray();
    var formURL = "/writers/update_bubble.php";
    $.ajax(
    {
        url : formURL,
        type: "POST",
        data : postData,
        success:function(data){
            alert(data);
        }
    });
    e.preventDefault(); //STOP default action
});
$("#save").click(function(e){
	var postData = $("#dev-settings form").serializeArray();
	var formURL = "/writers/update_bubble.php";
	$.ajax(
	{
			url : formURL,
			type: "POST",
			data : postData,
			success:function(data){
					alert(data);
			}
	});
	e.preventDefault(); //STOP default action
});
$("#publish").click(function(e){
	var postData = $("#dev-settings form").serializeArray();
	var formURL = "/writers/publish.php";
	$.ajax(
	{
			url : formURL,
			type: "POST",
			data : postData,
			success:function(data){
					alert(data);
			}
	});
	e.preventDefault(); //STOP default action
});
$("#dev-settings [name=post]").on({
    keypress: function(){
    	var th=$(this);
		setTimeout(function(){
			$("inner-post iframe").each(function(){
				var pload=(is_url(th.val()))?th.val()+"?key="+$(this).attr("key"):"/ShareOn/tool_bubble/"+$(this).attr('type')+"/"+th.val()+"?key="+$(this).attr("key");
				$(this).attr("src",pload);
			});
		},5);
    },keydown: function(){
    	var th=$(this);
		setTimeout(function(){
			$("inner-post iframe").each(function(){
				var pload=(is_url(th.val()))?th.val()+"?key="+$(this).attr("key"):"/ShareOn/tool_bubble/"+$(this).attr('type')+"/"+th.val()+"?key="+$(this).attr("key");
				$(this).attr("src",pload);
			});
		},5);
   	},keyup: function(){
   		var th=$(this);
		setTimeout(function(){
			$("inner-post iframe").each(function(){
				var pload=(is_url(th.val()))?th.val()+"?key="+$(this).attr("key"):"/ShareOn/tool_bubble/"+$(this).attr('type')+"/"+th.val()+"?key="+$(this).attr("key");
				$(this).attr("src",pload);
			});
		},5);
}});
$("#dev-settings [name=size]").on({
    keypress: function(){
    	var th=$(this);
		setTimeout(function(){
			if(th.val()<=0)
			$("inner-post iframe").height( $("inner-post iframe").contents().find("body").height() );
			else
			$("inner-post iframe").height(th.val()+"px");
		},5);
    },keydown: function(){
    	var th=$(this);
		setTimeout(function(){
			if(th.val()<=0)
			$("inner-post iframe").height( $("inner-post iframe").contents().find("body").height() );
			else
			$("inner-post iframe").height(th.val()+"px");
		},5);
   	},keyup: function(){
   		var th=$(this);
		setTimeout(function(){
			if(th.val()<=0)
			$("inner-post iframe").height( $("inner-post iframe").contents().find("body").height() );
			else
			$("inner-post iframe").height(th.val()+"px");
		},5);
}});
$("#dev-settings select").on("change",function(){
	if($(this).val()=="scroll")
	$("inner-post iframe").attr("scrolling","yes");
	else
	$("inner-post iframe").attr("scrolling","no");
});
