function submit(data){
if(!$("#outer-post-editor").hasClass("pending")){
    if(typeof $("#outer-post-editor").attr("dev")!="undefined"&&$("#outer-post-editor").attr('dev')!="")
    data['dev']=$("#outer-post-editor").attr("dev");
	$("#outer-post-editor").addClass("pending");
    var name=($('input[name="replyToPost"]').attr('value'))?$('input[name="replyToPost"]').attr('value'):"";
	if(data['anonymous']==true){
		$.get("/geters/anonymous.php",function(d){
			$( "body" ).append(d);
			$.magnificPopup.open({
  				items: {
			    src: $(d),
			    type: 'inline'
				},
				closeOnBgClick: false,
				closeBtnInside: false,
				showCloseBtn:false,
				callbacks: {
					afterClose: function() {
						$( "#anonymous-container" ).remove();
						var aname=$('#post-editor').attr('aname');
                        var password=$('#post-editor').attr('apass');
                        var picture=$('#post-editor').attr('apic');
                            data['aname'] = (aname!=="")?aname:'';
                            data['apass'] = (password!=="")?password:'';
                            data['apic'] = (picture!=="")?picture:'';
                            if($('#post-editor').attr('ano')==null||$('#post-editor').attr('ano')==="yes"){
                        $.ajax({
                            url: '/postupload.php',
                            type:'post',
                            data: data,
                            success:function (response) {
			                    $('#outer-post-editor').remove();
			                    alert(d);
				                	if(data.view_option=="global"||!data.replyTo){
				                		if($("main-container").find("regular-post").length>0){
				                			$("regular-post:first").before("<regular-post>"+d+"</regular-post>");
				                		}else{
				                			$("main-container").append("<regular-post>"+d+"</regular-post>");
				                		}
				                	}else{
					                	for(var i=0;i<data.replyTo.length;i++){
					                		if($("main-container").find("regular-post[key="+data.replyTo[i]+"] .comments-button").length>0&&$("main-container").find("regular-post[key="+data.replyTo[i]+"] .comments-button").hasClass('notclick'))
					                		$("main-container").find("regular-post[key="+data.replyTo[i]+"] .comments-button").trigger("click");
					                		$("main-container").find("regular-post[key="+data.replyTo[i]+"]").after("<regular-post>"+d+"</regular-post>")
					                	}
					                }
				                	$("#editor-shadow").remove();
			                       $("regular-post[key='"+response+"']").addClass("comment-highlighter");
			                       $("regular-post[key='"+response+"']").eac(function() {
			                       	var th=$(this);
									  $('html, body').animate({
										scrollTop: th.offset().top-80
									},500);
									});
									setTimeout(function(){ $("regular-post[key='"+response+"']").removeClass("comment-highlighter"); }, 1000);
                            }
                        });
					}
					}
  				}
			});
		});
	}else{
		alert(1111);
        $.ajax({
            url: '/postupload.php',
            type:'post',
            data: data,
            success:function (d) {
            	alert(d);
            	d=JSON.parse(d);
				$('#outer-post-editor').remove();//remove post editor and reset it
	                	if(!data.replyTo||data.view_option!='comment'){// if there post isn't a reply
	                		if($("main-container").find("regular-post").length>0){//if main-container is not empty, append it at the top
	                			$("regular-post:first").before("<regular-post>"+JSON.stringify(d)+"</regular-post>");
	                		}else{//if main-container is empty, append it in main container
	                			$("main-container").append("<regular-post>"+JSON.stringify(d)+"</regular-post>");
	                		}
	                		window.scroll.toScroll.push(d['_key']);//for the namespace of things to scroll to
	                	}
	                	if(data.replyTo){//post it inn every post you replied to
		                	for(var i=0;i<data.replyTo.length;i++){
		                		//if comments of replied post closed
		                		if($("main-container").find("regular-post[key="+data.replyTo[i]+"] .comments-button").length>0&&$("main-container").find("regular-post[key="+data.replyTo[i]+"] .comments-button").hasClass('notclick')){
		                		$("main-container").find("regular-post[key="+data.replyTo[i]+"]").attr("scroll",data.replyTo[i]+">"+d['_key']);//pass data for the comments loader to handle
		                		$("main-container").find("regular-post[key="+data.replyTo[i]+"] .comments-button").trigger("click");//open the comments
		                		}else{
		                			if($("main-container").find("regular-post[key="+data.replyTo[i]+"]").next().hasClass(".no-comments"))
			                			$("main-container").find("regular-post[key="+data.replyTo[i]+"]").next().remove();
			                		$("main-container").find("regular-post[key="+data.replyTo[i]+"]").after("<regular-post>"+JSON.stringify(d)+"</regular-post>");//appends right after chosen post
			                		window.scroll.toScroll.push(data.replyTo[i]+">"+d['_key']);//adds the info of parent key and the child key for the postScroll in pages.js
		                		}
		                	}
		                }
		                $("#editor-shadow").remove();
		                $("body").trigger("postScroll");
            },
            error: function (e) {
                alert(e);
            }
        });
	}
	}
}
function update(data){
if(!$("#outer-post-editor").hasClass("pending")){
	data['_key']=$('#outer-post-editor').attr('key');
	alert(data['_key']);
	alert(111);
	data['topics']=[];
    data['replyTo']=[];
    data['taggedPeople']=[];
    $('#editor-tags span[contenteditable=false]').each(function(){
    	if($(this).text()[0]==='#')
    	data['topics'].push($(this).text().substr(1));
    	else if($(this).text()[0]==='>')
    	data['replyTo'].push($(this).text().substr(1));
    	else if($(this).text()[0]==='@')
    	data['taggedPeople'].push($(this).text().substr(1));
    });
$("#outer-post-editor").addClass("pending");
        var ano = $("#anonymous-checkbox").is(":checked");
        var fkey=$("#post-editor").attr("fkey");//full key
        if(data['anonymous']==true){
        	alert(55);
            $.post("/geters/anonymous.php",{key:data['_key']},function(daata){
                $("body").append(daata);
                $.magnificPopup.open({
                    items: {
                        src: $(daata),
                        type: 'inline'
                    },
                    closeOnBgClick: false,
					closeBtnInside: false,
					showCloseBtn:false,
                    callbacks: {

                        afterClose: function() {
                            $( "#anonymous-container" ).remove();
							var aname=$('#post-editor').attr('aname');
	                        var password=$('#post-editor').attr('apass');
	                        var picture=$('#post-editor').attr('apic');
                            data['aname'] = (aname!=="")?aname:'';
                            data['apass'] = (password!=="")?password:'';
                            data['apic'] = (picture!=="")?picture:'';
							if($('#post-editor').attr('ano')==null||$('#post-editor').attr('ano')==="yes"){
                            $.post('/geters/checkano.php',{key: data['_key']}, function(datta){
                                if(datta) {
                                    $("body").append(datta);
                                    $.magnificPopup.open({
                                        items: {
                                            src: $(datta),
                                            type: 'inline'
                                        },
                                        closeOnBgClick: false,
					closeBtnInside: false,
					showCloseBtn:false,
                                        callbacks: {

                                            afterClose: function () {
											$("#outer-post-editor").removeClass("pending");

                                            	$('#outer-post-editor').remove();
                                            	$("#anonymous-container").remove();
                                                data['ppass'] = $("header").attr("apass");
                                                $.ajax({
                                                    url: '/post_update.php',
                                                    type: 'post',
                                                    data: data,
                                                    success: function (daata) {

                                                    	$('#outer-post-editor').remove();
                                                        if (daata != "") {
                                                        	if(data['_key']==fkey[1]){
							                                $(".anonymous-holder[key='" + fkey[0]+"-"+fkey[1] + "']").remove();
							                                $(".comment-content[key='" +fkey[0]+"-"+fkey[1] + "']").attr("src",$(".comment-content[key='" +fkey[0]+"-"+fkey[1] + "']").attr("src"));
							                                }else{
							                                $(".anonymous-holder[key='" + fkey[0]+ "']").remove();
							                                $(".post-content[key='" + fkey[0] + "']").attr("src",$(".post-content[key='" + fkey[0] + "']").attr("src"));
							                                }
                                                            $.post('/geters/reloaduser.php', {key: data['_key']}, function (dattaa) {
                                                                if(data['_key']==fkey[1]){
							                                    $(".comment[key='" + fkey[0]+"-"+fkey[1] +"']").prepend(dattaa);
							                                    $(".comment[key='" + fkey[0]+"-"+fkey[1] +"']").addClass("comment-highlighter");
							                                    $('html, body').animate({
																    scrollTop: $(".comment[key='" + fkey[0]+"-"+fkey[1] +"']").offset().top-80
																},500);
																setTimeout(function(){ $(".comment[key='" + fkey[0]+"-"+fkey[1] +"']").removeClass("comment-highlighter"); }, 2000);
							                                    }else{
							                                    $(".post[key='" + fkey[0]+"'] .post-top").prepend(dattaa);
							                                    $(".post[key='" + fkey[0]+"']").addClass("comment-highlighter");
							                                    $('html, body').animate({
																    scrollTop: $(".post[key='" + fkey[0]+"']").offset().top-80
																},500);
																setTimeout(function(){ $(".post[key='" + fkey[0]+"']").removeClass("comment-highlighter"); }, 2000);
							                                    }
                                                            });
                                                        } else {
                                                            alert("error");
                                                        }
                                                    }
                                                });
                                            }
                                        }
                                    });
                                }else{
                                    $.ajax({
                                        url: 'post_update.php',
                                        type:'post',
                                        data: data,
                                        success:function (daata) {
                                        	$('#outer-post-editor').remove();
                                            if(daata!=""){
                                                if(data['_key']==fkey[1]){
				                                $(".op-holder[key='" + fkey[0]+"-"+fkey[1] + "']").remove();
				                                $(".comment-content[key='" +fkey[0]+"-"+fkey[1] + "']").attr("src",$(".comment-content[key='" +fkey[0]+"-"+fkey[1] + "']").attr("src"));
				                                }else{
				                                $(".op-holder[key='" + fkey[0]+ "']").remove();
				                                $(".post-content[key='" + fkey[0] + "']").attr("src",$(".post-content[key='" + fkey[0] + "']").attr("src"));
				                                }
                                                $.post('/geters/reloaduser.php', {key: data['_key']}, function (d) {
                                                	if(data['_key']==fkey[1]){
				                                    $(".comment[key='" + fkey[0]+"-"+fkey[1] +"']").prepend(d);
				                                    $(".comment[key='" + fkey[0]+"-"+fkey[1] +"']").addClass("comment-highlighter");
				                                    $('html, body').animate({
													    scrollTop: $(".comment[key='" + fkey[0]+"-"+fkey[1] +"']").offset().top-80
													},500);
													setTimeout(function(){ $(".comment[key='" + fkey[0]+"-"+fkey[1] +"']").removeClass("comment-highlighter"); }, 2000);
				                                    }else{
				                                    $(".post[key='" + fkey[0]+"'] .post-top").prepend(d);
				                                    $(".post[key='" + fkey[0]+"']").addClass("comment-highlighter");
				                                    $('html, body').animate({
													    scrollTop: $(".post[key='" + fkey[0]+"']").offset().top-80
													},500);
													setTimeout(function(){ $(".post[key='" + fkey[0]+"']").removeClass("comment-highlighter"); }, 2000);
				                                    }
	                                            });
                                            }else{
                                                alert("error");
                                            }
                                        }
                                    });
                                }
                            });
                        }
                    }
}
                });
            });
        }else{
            $.post('/geters/checkano.php',{key: data['_key']}, function(datta){
                if(datta) {
                    $("body").append(datta);
                    $.magnificPopup.open({
                        items: {
                            src: $(datta),
                            type: 'inline'
                        },
                        closeOnBgClick: false,
						closeBtnInside: false,
						showCloseBtn:false,
                        callbacks: {
                            afterClose: function () {
                                data['ppass'] = $("header").attr("apass");
								$("#outer-post-editor").removeClass("pending");

                                $.ajax({
                                    url: '/post_update.php',
                                    type: 'post',
                                    data: data,
                                    success: function (daata) {
                                    	alert(daata);
                                    	$('#outer-post-editor').remove();
                                        if (daata != "") {
                                            if(data['_key']==fkey[1]){
			                                $(".anonymous-holder[key='" + fkey[0]+"-"+fkey[1] + "']").remove();
			                                $(".comment-content[key='" +fkey[0]+"-"+fkey[1] + "']").attr("src",$(".comment-content[key='" +fkey[0]+"-"+fkey[1] + "']").attr("src"));
			                                }else{
			                                $(".anonymous-holder[key='"+fkey[0]+"']").remove();
			                                $(".post-content[key='"+fkey[0]+"']").attr("src",$(".post-content[key='" + fkey[0] + "']").attr("src"));
			                                }
                                            $.post('/geters/reloaduser.php', {key: data['_key']}, function (dattaa) {
                                                if(data['_key']==fkey[1]){
			                                    $(".comment[key='" + fkey[0]+"-"+fkey[1] +"']").prepend(dattaa);
			                                    $(".comment[key='" + fkey[0]+"-"+fkey[1] +"']").addClass("comment-highlighter");
			                                    $('html, body').animate({
												    scrollTop: $(".comment[key='" + fkey[0]+"-"+fkey[1] +"']").offset().top-80
												},500);
												setTimeout(function(){ $(".comment[key='" + fkey[0]+"-"+fkey[1] +"']").removeClass("comment-highlighter"); }, 2000);
			                                    }else{
			                                    $(".post[key='" + fkey[0]+"'] .post-top").prepend(dattaa);
			                                    $(".post[key='" + fkey[0]+"']").addClass("comment-highlighter");
			                                    $('html, body').animate({
												    scrollTop: $(".post[key='" + fkey[0]+"']").offset().top-80
												},500);
												setTimeout(function(){ $(".post[key='" + fkey[0]+"']").removeClass("comment-highlighter"); }, 2000);
			                                    }
                                            });
                                        } else {
                                            alert("error");
                                        }
                                    }
                                });
                            }
                        }
                    });
                }else{
                    $.ajax({
                        url: '/post_update.php',
                        type:'post',
                        data: data,
                        success:function (d){
                        	alert(d);
			            	d=JSON.parse(d);
							$('#outer-post-editor').remove();//remove post editor and reset it
		                	if(!data.replyTo||data.view_option!='comment'){// if there post isn't a reply
		                		$("regular-post[key='" + d['_key'] +"']").replaceWith("<regular-post>"+JSON.stringify(d)+"</regular-post>");
		                		window.scroll.toScroll.push(d['_key']);//for the namespace of things to scroll to
		                	}
		                	if(data.replyTo){//post it inn every post you replied to
			                	for(var i=0;i<data.replyTo.length;i++){
			                		//if comments of replied post closed
			                		if($("main-container").find("regular-post[key="+data.replyTo[i]+"] .comments-button").length>0&&$("main-container").find("regular-post[key="+data.replyTo[i]+"] .comments-button").hasClass('notclick')){
				                		$("main-container").find("regular-post[key="+data.replyTo[i]+"]").attr("scroll",data.replyTo[i]+">"+d['_key']);//pass data for the comments loader to handle
				                		$("main-container").find("regular-post[key="+data.replyTo[i]+"] .comments-button").trigger("click");//open the comments
			                		}else{
			                			var toF=$("main-container").find("regular-post[key="+data.replyTo[i]+"]");
			                			if(toF.nextAll().find("regular-post[key="+d['_key']+"]").length>0)//if modifying existing post
			                				toF.nextAll().find("regular-post[key="+d['_key']+"]").replaceWith("<regular-post>"+JSON.stringify(d)+"</regular-post>");
			                			else{//reply non existent
			                				if(toF.next().hasClass(".no-comments"))
			                				toF.next().remove();
			                				toF.after("<regular-post>"+JSON.stringify(d)+"</regular-post>");//appends right after chosen post
			                			}
			                			window.scroll.toScroll.push(data.replyTo[i]+">"+d['_key']);//adds the info of parent key and the child key for the postScroll in pages.js
			                		}
			                	}
			                }
			                $("#editor-shadow").remove();
			                $("body").trigger("postScroll");
                        }
                    });
                }
            });
        }
}
}
function search(x,category,type){
	if(category=="social"){
		if(type=="discover"){
			$("#people-list-container").show();
			$("#people-list-container").html("<img class='loading' src='https://shareon.me/icons/ajax-loader.gif'/>");
				$.post('/geters/people_search.php',{data: x},function(data){
						$("#people-list-container").html(data);
				});
			if(x==""){
				$("#people-list-container").hide();
			}
		}else if(type=="friends"){
			$("#people-list-container").empty();
			$.post("/geters/friendboxlist.php",{search:x},function(data){
				$("#people-list-container").html(data);
			});
		}else if(type=="following"){
			$("#people-list-container").empty();
			$.post("/geters/followboxlist.php",{search:x},function(data){
				$("#people-list-container").html(data);
			});
		}else if(type=="requests"){
			$("#people-list-container").empty();
			$.post("/geters/requestsboxlist.php",{search:x},function(data){
				$("#people-list-container").html(data);
			});
		}
	}
}
function iframeRef( frameRef ) {
    return (frameRef.contentWindow)? frameRef.contentWindow.document: frameRef.contentDocument;
}
function resizeIframe(obj) {
var h=obj.contentWindow.document.body.scrollHeight;
if(obj.contentWindow.document.body.offsetHeight>h)
    h = obj.contentWindow.document.body.scrollHeight+150 + 'px';
obj.style.height = h+10 + 'px';
  }
function urlFixer(url,ptype){
	return (is_url(url))?url:"/tool_bubble/"+ptype+"/"+url;
}
function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca;
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
    }
    return "";
}
function writerange(e){
	if(e.value.length!=0)
	e.style.width = ((e.value.length + 1) * 8) + 'px';
	else
	e.style.width="20px";
}
function urlSetter(){
		var url=window.location.href;
		url=url.split("https://shareon.me/")[1];
		var spacer="";
		if(url!==""){
			url=url.split("/");
		for(var i=1;i<url.length;i++)
		spacer+="../";
		}
		return spacer;
	}
function collectionGrid(json){
	var p="";
    for (var i=0;i<json.length;i++)
        p+= "<div key="+json[i]['_key']+" class='collectionBox'><img class='portrait' src='"+json[i]['background']+"'/><p>"+json[i]['title']+"</p></div>";
    return p;
}
function timeSince(date) {
	date*=1000;
  var seconds = Math.floor((new Date() - date) / 1000);
  var interval = Math.floor(seconds / 86400);
  if(interval >7){
  	var d=new Date(date);
  	var m=d.getMonth()+1;
  	var min=d.getMinutes();
  	var h=d.getHours();
  	var y=d.getFullYear();
  	var d=d.getDate();
  	if (m==1)
  		m="January";
  	else if(m== 2)
  		m="February";
  	else if(m==3)
  		m="March";
  	else if(m==4)
  		m="April";
  	else if(m==5)
  		m="May";
  	else if(m==6)
  		m="June";
  	else if(m==7)
  		m="July";
  	else if(m==8)
  		m="August";
  	else if(m==9)
  		m="September";
  	else if(m==10)
  		m="October";
  	else if(m==11)
  		m="November";
  	else if(m==12)
  		m="December";
  	var c=new Date();
  	h=(h<10)?"0"+""+h:h;
  	min=(min<10)?"0"+""+min:min;
  	if(y!=c.getFullYear())
  	return m+" "+d+" "+y;
  	else
  	return m+" "+d+" at "+h+":"+min;
  }
  if (interval > 1) {
    return interval + " days ago";
  }
  if (interval == 1) {
    return interval + " day ago";
  }
  interval = Math.floor(seconds / 3600);
  if (interval > 1) {
    return interval + " hours ago";
  }
  if (interval == 1) {
    return interval + " hour ago";
  }
  interval = Math.floor(seconds / 60);
  if (interval > 1) {
    return interval + " minutes ago";
  }
  if (interval == 1) {
    return interval + " minute ago";
  }
  return Math.floor(seconds) + " seconds ago";
}
function placeCaretAtEnd(el) {
    el.focus();
    if (typeof window.getSelection != "undefined"
            && typeof document.createRange != "undefined") {
        var range = document.createRange();
        range.selectNodeContents(el);
        range.collapse(false);
        var sel = window.getSelection();
        sel.removeAllRanges();
        sel.addRange(range);
    } else if (typeof document.body.createTextRange != "undefined") {
        var textRange = document.body.createTextRange();
        textRange.moveToElementText(el);
        textRange.collapse(false);
        textRange.select();
    }
}
function bignumberkiller(number) {
			var count=number;
			if(count>=1000000000000){
				var h=""+(count/1000000000000);
				return h.substring(0,5)+"T";
			}else if(count>=1000000000){
				var h=""+(count/1000000000);
				return h.substring(0,5)+"B";
		    }else if(count>=1000000){
		    	var h=""+(count/1000000);
		    	return h.substring(0,5)+"M";
		    }else if(count>=1000){
		    	var h=""+(count/1000);
		    	return h.substring(0,5)+"K";
			}
			return count;
		}
function toObject(string){
	string=string.replace(/(\r\n|\n|\r)/gm, "");
	string=string.replace(/\s/g, '');
	var arr=string.split(';');
	var obj={};
	for(var i=0;i<arr.length;i++){
		var s=arr[i].split(/:(.+)/);
		obj[s[0]]=s[1];
	}
	return obj;
}
function def(ob){
	return (typeof ob==='undefined')?"":ob;
}
//goes to a post and highlights it
function postScroller(string){
	var s=string.split(">");
	var t=(s.length>1)?$("regular-post[key='"+s[0]+"']").parent().find("regular-post[key='"+s[1]+"']"):$("regular-post[key='"+s[0]+"']");
	t.addClass("comment-highlighter");
	$('html, body').animate({
		scrollTop: t.offset().top-80
	},1000);
	setTimeout(function(){
		t.removeClass("comment-highlighter");
	}, 5000);
}
//stops js for time
function sleep(ms) {
  return new Promise(resolve => setTimeout(resolve, ms));
}
function findGetParameter(parameterName) {
    var result = null,
        tmp = [];
    location.search
        .substr(1)
        .split("&")
        .forEach(function (item) {
          tmp = item.split("=");
          if (tmp[0] === parameterName) result = decodeURIComponent(tmp[1]);
        });
    return result;
}
function IsJsonString(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}
function is_url(str) {
  try {
    new URL(str);
  } catch (_) {
    return false;
  }

  return true;
}
$.fn.isInViewport = function() {
  var elementTop = $(this).offset().top;
  var elementBottom = elementTop + $(this).outerHeight();

  var viewportTop = $(window).scrollTop();
  var viewportBottom = viewportTop + $(window).height();

  return elementBottom > viewportTop && elementTop < viewportBottom;
};
function addNotification(e,data){
  $(e).prepend("<div class='notification'> </div>");
  $(e+" .notification:first").append("<a target='_blank' href='"+data.left.link+"' class='background'></a>");
  if (data['left'].hasOwnProperty('portrait'))
  {
    $(e+" .notification:first").append("<a target='_blank' href='"+data.left.link+"'><img class='portrait' src=" + data.left.portrait + "></a><div class='description'></div>");
  } else if (data['left'].hasOwnProperty('picture')) {
    $(e+" .notification:first").append("<a target='_blank' href='"+data.left.link+"'><img class='picture' src=" + data.left.picture + "></a>");
    $(e+" .notification:first").append("<div class='description'><a target='_blank' href='"+data.left.link+"' class='title'>"+ data.left.name +"</a><br/></div>");
    }
    $(e+" .notification .description:first").append(data.description);
    $(e+" .notification:first").append("<div class='time'>" + timeSince(data.time) + "</div>");
      if(data.right!=undefined){
    if (data.right.length == 1) {
      $(e+" .notification .description:first").append(" <a target='_blank' href='"+data.right[0].link+"' class='title'>"+data.right[0].name+"</span>");
    } else if(data.right.length>1) {
      $(e+" .notification .description:first").append(" <a target='_blank' href='"+data.right[0].link+"' class='title'>" + data.right[0].name + "</a> and <span class='title'>" + data.right.length + " other people</span>");
    }
  }
}
function preloadImage(url)
{
    var img=new Image();
    img.src=url;
}
