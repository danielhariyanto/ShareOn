$(document).off('click',"#posts-bubble").on('click',"#posts-bubble.notclick", function() {
	if(!$(this).hasClass("disabled-feature")){
	$("#posts-bubble-container").removeClass("invisible");
	data={};
	var dev="";
	if($("body").attr("dev")!=undefined)
	dev=" dev='"+$("body").attr("dev")+"'";
	if($(".editor-shadow").length==0){
	$("body").append("<div class='editor-shadow'></div>");
	var t="<post-editor"+dev+">"+sessionStorage.bubbles+"</post-editor>";
	$( ".editor-shadow" ).last().append(t);
	}
	if($(this).attr("username")!=undefined){
		document.querySelector("post-editor").addTag("@"+$(this).attr("username"));
	}else if($(this).attr("topic")!=undefined){
		document.querySelector("post-editor").addTag("#"+$(this).attr("topic"));
	}else if($(this).attr("post")!=undefined){
		document.querySelector("post-editor").addTag(">"+$(this).attr("post"));
	}
 }
});
