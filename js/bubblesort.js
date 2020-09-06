$(document).on("change",".time",function(){
	if(sessionStorage.sort!=undefined)
	clearTimeout(sessionStorage.sort);
	sessionStorage.sort=setTimeout(function(){saveSort()},2000);
});
$(document).on("change","#sort-grid input",function(){
	if(sessionStorage.sort!=undefined)
	clearTimeout(sessionStorage.sort);
	sessionStorage.sort=setTimeout(function(){saveSort()},2000);
});

$(document).on("change",".points-check",function(){
	var split=[$("#points-range").attr("min"),$("#points-range").attr("max")];
	var range=$("#points-range").attr("range").split("_");
	$("#points-range").slider( "enable" );
	$("#points-range").slider("option","range",true);
	$("#points-range").slider("option","values",[]);
	var s1=($("#points-writemin").val().length==0)?split[0]:$("#points-writemin").val();
	var s2=($("#points-writemax").val().length==0)?split[1]:$("#points-writemax").val();
	if($('#points-checkmin input').is(":checked")&&$('#points-checkmax input').is(":checked")){
		$( "#points-range" ).slider("option","values", [s1,s2]);
		$("#points-writemin").val(s1);
	    $("#points-writemax").val(s2);
		$( "#points-range" ).slider({
		  slide: function( event, ui ) {
	      	$("#points-writemin").val(ui.values[0]);
	      	if($("#points-writemin").val().length!=0)
			$("#points-writemin").css("width",(($("#points-writemin").val().length + 1) * 8) + 'px');
			else
			$("#points-writemin").css("width",'20px');
	      	$("#points-writemax").val(ui.values[1]);
	      	if($("#points-writemax").val().length!=0)
			$("#points-writemax").css("width",(($("#points-writemax").val().length + 1) * 8) + 'px');
			else
			$("#points-writemax").css("width",'20px');
		  }
		});
		$("#points-write").val(split[0]+" : "+split[1]);
	}else if($('#points-checkmin input').is(":checked")){
		$( "#points-range" ).slider("option","range","max");
		$( "#points-range" ).slider("option","value", split[0]);
		$("#points-writemin").val(split[0]);
	    $("#points-writemax").val("");
		$( "#points-range" ).slider({
		  slide: function( event, ui ) {
	      	$("#points-writemin").val(ui.value);
	      	if($("#points-writemin").val().length!=0)
			$("#points-writemin").css("width",(($("#points-writemin").val().length + 1) * 8) + 'px');
			else
			$("#points-writemin").css("width",'20px');
		  }
		});
		$("#points-write").val(split[0]);
	}else if($('#points-checkmax input').is(":checked")){
		$( "#points-range" ).slider("option","range","min");
		$( "#points-range" ).slider("option","value", split[1]);
		$("#points-writemin").val("");
	    $("#points-writemax").val(split[1]);
		$( "#points-range" ).slider({
		  slide: function( event, ui ) {
	      	$("#points-writemax").val(ui.value);
	      	if($("#points-writemax").val().length!=0)
			$("#points-writemax").css("width",(($("#points-writemax").val().length + 1) * 8) + 'px');
			else
			$("#points-writemax").css("width",'20px');
		  }
		});
		$("#points-write").val(split[1]);
	}else{
		$( "#points-range" ).slider("disable");
		$("#points-writemin").val("");
	    $("#points-writemax").val("");
	}
	if(sessionStorage.sort!=undefined)
	clearTimeout(sessionStorage.sort);
	sessionStorage.sort=setTimeout(function(){saveSort()},2000);
});
$(document).on("change",".upvotes-check",function(){
	var split=[$("#upvotes-range").attr("min"),$("#upvotes-range").attr("max")];
	var range=$("#upvotes-range").attr("range").split("_");
	$("#upvotes-range").slider( "enable" );
	$( "#upvotes-range" ).slider("option","range",true);
	$( "#upvotes-range" ).slider("option","values",[]);
	var s1=($("#upvotes-writemin").val().length==0)?split[0]:$("#upvotes-writemin").val();
	var s2=($("#upvotes-writemax").val().length==0)?split[1]:$("#upvotes-writemax").val();
	if($('#upvotes-checkmin input').is(":checked")&&$('#upvotes-checkmax input').is(":checked")){
		$( "#upvotes-range" ).slider("option","values", [s1,s2]);
		$("#upvotes-writemin").val(s1);
	    $("#upvotes-writemax").val(s2);
		$( "#upvotes-range" ).slider({
		  slide: function( event, ui ) {
	      	$("#upvotes-writemin").val(ui.values[0]);
	      	if($("#upvotes-writemin").val().length!=0)
			$("#upvotes-writemin").css("width",(($("#upvotes-writemin").val().length + 1) * 8) + 'px');
			else
			$("#upvotes-writemin").css("width",'20px');
	      	$("#upvotes-writemax").val(ui.values[1]);
	      	if($("#upvotes-writemax").val().length!=0)
			$("#upvotes-writemax").css("width",(($("#upvotes-writemax").val().length + 1) * 8) + 'px');
			else
			$("#upvotes-writemax").css("width",'20px');
		  }
		});
		$("#upvotes-write").val(split[0]+" : "+split[1]);
	}else if($('#upvotes-checkmin input').is(":checked")){
		$( "#upvotes-range" ).slider("option","range","max");
		$( "#upvotes-range" ).slider("option","value", split[0]);
		$("#upvotes-writemin").val(split[0]);
	    $("#upvotes-writemax").val("");
		$( "#upvotes-range" ).slider({
		  slide: function( event, ui ) {
	      	$("#upvotes-writemin").val(ui.value);
	      	if($("#upvotes-writemin").val().length!=0)
			$("#upvotes-writemin").css("width",(($("#upvotes-writemin").val().length + 1) * 8) + 'px');
			else
			$("#upvotes-writemin").css("width",'20px');
		  }
		});
		$("#upvotes-write").val(split[0]);
	}else if($('#upvotes-checkmax input').is(":checked")){
		$( "#upvotes-range" ).slider("option","range","min");
		$( "#upvotes-range" ).slider("option","value", split[1]);
		$("#upvotes-writemin").val("");
	    $("#upvotes-writemax").val(split[1]);
		$( "#upvotes-range" ).slider({
		  slide: function( event, ui ) {
	      	$("#upvotes-writemax").val(ui.value);
	      	if($("#upvotes-writemax").val().length!=0)
			$("#upvotes-writemax").css("width",(($("#upvotes-writemax").val().length + 1) * 8) + 'px');
			else
			$("#upvotes-writemax").css("width",'20px');
		  }
		});
		$("#upvotes-write").val(split[1]);
	}else{
		$( "#upvotes-range" ).slider("disable");
		$("#upvotes-writemin").val("");
	    $("#upvotes-writemax").val("");
	}
	if(sessionStorage.sort!=undefined)
	clearTimeout(sessionStorage.sort);
	sessionStorage.sort=setTimeout(function(){saveSort()},2000);
});
$(document).on("change",".downvotes-check",function(){
	var split=[$("#downvotes-range").attr("min"),$("#downvotes-range").attr("max")];
	var range=$("#downvotes-range").attr("range").split("_");
	$("#downvotes-range").slider( "enable" );
	$( "#downvotes-range" ).slider("option","range",true);
	$( "#downvotes-range" ).slider("option","values",[]);
	var s1=($("#downvotes-writemin").val().length==0)?split[0]:$("#downvotes-writemin").val();
	var s2=($("#downvotes-writemax").val().length==0)?split[1]:$("#downvotes-writemax").val();
	if($('#downvotes-checkmin input').is(":checked")&&$('#downvotes-checkmax input').is(":checked")){
		$( "#downvotes-range" ).slider("option","values", [s1,s2]);
		$("#downvotes-writemin").val(s1);
	    $("#downvotes-writemax").val(s2);
		$( "#downvotes-range" ).slider({
		  slide: function( event, ui ) {
	      	$("#downvotes-writemin").val(ui.values[0]);
	      	if($("#downvotes-writemin").val().length!=0)
			$("#downvotes-writemin").css("width",(($("#downvotes-writemin").val().length + 1) * 8) + 'px');
			else
			$("#downvotes-writemin").css("width",'20px');
	      	$("#downvotes-writemax").val(ui.values[1]);
	      	if($("#downvotes-writemax").val().length!=0)
			$("#downvotes-writemax").css("width",(($("#downvotes-writemax").val().length + 1) * 8) + 'px');
			else
			$("#downvotes-writemax").css("width",'20px');
		  }
		});
		$("#downvotes-write").val(split[0]+" : "+split[1]);
	}else if($('#downvotes-checkmin input').is(":checked")){
		$( "#downvotes-range" ).slider("option","range","max");
		$( "#downvotes-range" ).slider("option","value", split[0]);
		$("#downvotes-writemin").val(split[0]);
	    $("#downvotes-writemax").val("");
		$( "#downvotes-range" ).slider({
		  slide: function( event, ui ) {
	      	$("#downvotes-writemin").val(ui.value);
	      	if($("#downvotes-writemin").val().length!=0)
			$("#downvotes-writemin").css("width",(($("#downvotes-writemin").val().length + 1) * 8) + 'px');
			else
			$("#downvotes-writemin").css("width",'20px');
		  }
		});
		$("#downvotes-write").val(split[0]);
	}else if($('#downvotes-checkmax input').is(":checked")){
		$( "#downvotes-range" ).slider("option","range","min");
		$( "#downvotes-range" ).slider("option","value", split[1]);
		$("#downvotes-writemin").val("");
	    $("#downvotes-writemax").val(split[1]);
		$( "#downvotes-range" ).slider({
		  slide: function( event, ui ) {
	      	$("#downvotes-writemax").val(ui.value);
	      	if($("#downvotes-writemax").val().length!=0)
			$("#downvotes-writemax").css("width",(($("#downvotes-writemax").val().length + 1) * 8) + 'px');
			else
			$("#downvotes-writemax").css("width",'20px');
		  }
		});
		$("#downvotes-write").val(split[1]);
	}else{
		$( "#downvotes-range" ).slider("disable");
		$("#downvotes-writemin").val("");
	    $("#downvotes-writemax").val("");
	}
	if(sessionStorage.sort!=undefined)
	clearTimeout(sessionStorage.sort);
	sessionStorage.sort=setTimeout(function(){saveSort()},2000);
});
$(document).on("change",".comments-check",function(){
	var split=[$("#comments-range").attr("min"),$("#comments-range").attr("max")];
	var range=$("#comments-range").attr("range").split("_");
	$("#comments-range").slider( "enable" );
	$( "#comments-range" ).slider("option","range",true);
	$( "#comments-range" ).slider("option","values",[]);
	var s1=($("#comments-writemin").val().length==0)?split[0]:$("#comments-writemin").val();
	var s2=($("#comments-writemax").val().length==0)?split[1]:$("#comments-writemax").val();
	if($('#comments-checkmin input').is(":checked")&&$('#comments-checkmax input').is(":checked")){
		$( "#comments-range" ).slider("option","values", [s1,s2]);
		$("#comments-writemin").val(s1);
	    $("#comments-writemax").val(s2);
		$( "#comments-range" ).slider({
		  slide: function( event, ui ) {
	      	$("#comments-writemin").val(ui.values[0]);
	      	if($("#comments-writemin").val().length!=0)
			$("#comments-writemin").css("width",(($("#comments-writemin").val().length + 1) * 8) + 'px');
			else
			$("#comments-writemin").css("width",'20px');
	      	$("#comments-writemax").val(ui.values[1]);
	      	if($("#comments-writemax").val().length!=0)
			$("#comments-writemax").css("width",(($("#comments-writemax").val().length + 1) * 8) + 'px');
			else
			$("#comments-writemax").css("width",'20px');
		  }
		});
		$("#comments-write").val(split[0]+" : "+split[1]);
	}else if($('#comments-checkmin input').is(":checked")){
		$( "#comments-range" ).slider("option","range","max");
		$( "#comments-range" ).slider("option","value", split[0]);
		$("#comments-writemin").val(split[0]);
	    $("#comments-writemax").val("");
		$( "#comments-range" ).slider({
		  slide: function( event, ui ) {
	      	$("#comments-writemin").val(ui.value);
	      	if($("#comments-writemin").val().length!=0)
			$("#comments-writemin").css("width",(($("#comments-writemin").val().length + 1) * 8) + 'px');
			else
			$("#comments-writemin").css("width",'20px');
		  }
		});
		$("#comments-write").val(split[0]);
	}else if($('#comments-checkmax input').is(":checked")){
		$( "#comments-range" ).slider("option","range","min");
		$( "#comments-range" ).slider("option","value", split[1]);
		$("#comments-writemin").val("");
	    $("#comments-writemax").val(split[1]);
		$( "#comments-range" ).slider({
		  slide: function( event, ui ) {
	      	$("#comments-writemax").val(ui.value);
	      	if($("#comments-writemax").val().length!=0)
			$("#comments-writemax").css("width",(($("#comments-writemax").val().length + 1) * 8) + 'px');
			else
			$("#comments-writemax").css("width",'20px');
		  }
		});
		$("#comments-write").val(split[1]);
	}else{
		$( "#comments-range" ).slider("disable");
		$("#comments-writemin").val("");
	    $("#comments-writemax").val("");
	}
	if(sessionStorage.sort!=undefined)
	clearTimeout(sessionStorage.sort);
	sessionStorage.sort=setTimeout(function(){saveSort()},2000);
});
function saveSort(){
	data={};
	var t1=($("#timesetter1").val()!="")?parseInt(new Date($("#timesetter1").val()).getTime())/1000:"";
	var t2=($("#timesetter2").val()!="")?parseInt(new Date($("#timesetter2").val()).getTime())/1000:"";
	data.time=t1+"_"+t2;
	data.points=$("#points-writemin").val()+"_"+$("#points-writemax").val();
	data.upvotes=$("#upvotes-writemin").val()+"_"+$("#upvotes-writemax").val();
	data.downvotes=$("#downvotes-writemin").val()+"_"+$("#downvotes-writemax").val();
	data.comments=$("#comments-writemin").val()+"_"+$("#comments-writemax").val();
	data.disabled_view=[];
	if(!$('#global-sort input').prop('checked'))
	data.disabled_view.push("global");
	if(!$('#personal-sort input').prop('checked'))
	data.disabled_view.push("personal");
	if(!$('#comment-sort input').prop('checked'))
	data.disabled_view.push("comment");
	if(typeof $("#sort-box").attr("key")==='undefined'){
		$("#main-content-container").empty();
		data.direct=1;
		$.post("/writers/filtersession.php", data,function (d) {
			document.querySelector("main-container").load({});
		});
	}else{
		var key=$("#sort-box").attr("key");
		$('.comment-sort.current-sort').parent().find(".outer-post").remove();
		var div=$('.comment-sort.current-sort').parent();
		data=JSON.stringify(data);
		$.post('/geters/commentsload.php',{name: key,sort:data}, function(d) {
			var json=JSON.parse(d);
			if(d!=""){
			for(var i=0;i<json.length;i++)
			div.append(postDisplay(json[i]));
			}else{
			div.append("<p>no comments.</p>");
			}
			$('.comment-sort.current-sort').attr("sort",data);
			$('html, body').animate({
				scrollTop: $(".comment-sort.current-sort").offset().top-80
			},50);
		});
	}
}
