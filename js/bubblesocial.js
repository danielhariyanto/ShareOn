$(document).on('click', '#first-social-bubble', function(){
		$.get("/geters/people.php",function(data){
			$( "body" ).append(data);
			$.magnificPopup.open({
  				items: {
			    src: $(data),
			    type: 'inline'
				},
				closeBtnInside:false,
				callbacks: {

					afterClose: function() {
						$("#social-box").remove();
					}
  				}
			});
		});
	$("#socialbubble").removeClass("isclick").addClass("notclick");
});
$(document).on('mouseenter', '#firstsocialbubble', function(){
	$("#tipsysocialfirst").animate({
		    		opacity: 1
				}, 200,function(){
				});
});
$(document).on('mouseleave', '#firstsocialbubble', function(){
	$("#tipsysocialfirst").animate({
		    		opacity: 0
				}, 50,function(){
				});
});
$(document).on('click', '#people-following-button',  function(){
	if(!$(this).hasClass('isclick')){
		$("#socialcontainer").empty();
		$("#people-box-search").replaceWith("<input id=\"people-box-search\" onkeyup=\"search(this.value,'social','following')\" type=\"search\">");
		$.get("/geters/followboxlist.php",function(data){
			$("#people-list-container").html(data);
		});
		$(".isclick").removeClass("isclick");
		$("#people-following-button").addClass("isclick");
	}
});
$(document).on('click', '#friends-button',  function(){
	if(!$(this).hasClass('isclick')){
		$("#people-list-container").empty();
		$("#people-box-search").replaceWith("<input id=\"people-box-search\" onkeyup=\"search(this.value,'social','friends')\" type=\"search\">");
		$.get("/geters/friendboxlist.php",function(data){
			$("#people-list-container").html(data);
		});
		$(".isclick").removeClass("isclick");
		$("#friends-button").addClass("isclick");
	}
});
$(document).on('click', '#friend-requests-button',  function(){
	if(!$(this).hasClass('isclick')){
		$("#socialcontainer").empty();
		$.post("/geters/requestsboxlist.php",{username:getCookie('username')},function(data){
			$("#people-list-container").html(data);
		});
		$(".isclick").removeClass("isclick");
		$("#friend-requests-button").addClass("isclick");
		$(".notification[name='friend request']").addClass("hide");
	}
});
$(document).on('click', '#discover-people-button',  function(){
	if(!$(this).hasClass('isclick')){
		$("#socialcontainer").empty();
		$("#people-box-search").replaceWith("<input id=\"people-box-search\" onkeyup=\"search(this.value,'social','discover')\" type=\"search\">");
		$.post("/geters/discoverboxlist.php",{username:getCookie('username')},function(data){
			$("#people-list-container").html(data);
		});
		$(".isclick").removeClass("isclick");
		$("#discover-people-button").addClass("isclick");
	}
});

$(document).off('click','.requestaccept').on('click', '.requestaccept',function(e){
	var key= $(this).parent().attr('key');
	$.post("/writers/acceptfriendrequest.php",{key:key},function(data){
		if(data=="done")
		$(".person-outer-container[data='"+key+"']").remove();
	});
});
$(document).off('click','.requestrefuse').on('click','.requestrefuse', function(e){
	var key= $(this).parent().attr('key');
	$.post("/writers/refusefriendrequest.php",{key:key},function(data){
		if(data=="done")
		$(".person-outer-container[data='"+key+"']").remove();
	});
});
$(document).off('click','.list-friend-button').on('click','.list-friend-button', function(e){
	var key= $(this).parent().attr('key');
	$.post('/geters/friendbuttondetecter.php',{key:key},function(ddata){
		if(ddata!=""){
		$.post(ddata,{key:key}, function(data){
			if(data=="done"){
				if($('.person-outer-container[key='+key+'] .list-friend-button .text').html()=="Friend Request Sent"){
					$('.person-outer-container[key='+key+'] .list-friend-button .text').text("Add Friend");
					$('.person-outer-container[key='+key+'] .list-friend-button img').attr("src","https://shareon.me/icons/add-friend.png");
				}else if($('.person-outer-container[key='+key+'] .list-friend-button .text').html()=="Friend"){
					if($("#friends-button").hasClass('isclick')){
						$('.person-outer-container[key='+key+']').remove();
					}else{
						$('.person-outer-container[key='+key+'] .list-friend-button .text').text("Add Friend");
						$('.person-outer-container[key='+key+'] .list-friend-button img').attr("src","https://shareon.me/icons/add-friend.png");
						$('.person-outer-container[key='+key+'] .list-follow-button .text').text("Follow");
						$('.person-outer-container[key='+key+'] .list-follow-button img').attr("src","https://shareon.me/icons/follow.png");
					}
				}else if($('.person-outer-container[key='+key+'] .list-friend-button .text').html()=="Accept Friend Request"){
					$('.person-outer-container[key='+key+'] .list-friend-button .text').text("Friend");
					$('.person-outer-container[key='+key+'] .list-friend-button img').attr("src","https://shareon.me/icons/v.png");
					$('.person-outer-container[key='+key+'] .list-follow-button .text').text("Following");
					$('.person-outer-container[key='+key+'] .list-follow-button img').attr("src","https://shareon.me/icons/v.png");
				}else if($('.person-outer-container[key='+key+'] .list-friend-button .text').html()=="Add Friend"){
					$('.person-outer-container[key='+key+'] .list-friend-button .text').text("Friend Request Sent");
					$('.person-outer-container[key='+key+'] .list-friend-button img').attr("src","https://shareon.me/icons/clock-circular-outline.png");
				}
			}
		});
		}else{
			alert("fak u dolan");
		}
	});
});
$(document).off('click','.list-follow-button').on('click','.list-follow-button', function(e){
	var key= $(this).parent().attr('key');
	$.post('/writers/change_follow.php',{key: key},function(data){
		if(data=="Following"){
			$('.person-outer-container[key='+key+'] .list-follow-button .text').text("Following");
			$('.person-outer-container[key='+key+'] .list-follow-button img').attr('src',"https://shareon.me/icons/v.png");
			$('.person-outer-container[key='+key+'] .list-follow-button img').addClass("v");
		}else if(data=="Follow"){
			$('.person-outer-container[key='+key+'] .list-follow-button .text').text("Follow");
			$('.person-outer-container[key='+key+'] .list-follow-button img').attr('src',"https://shareon.me/icons/follow.png");
			$('.person-outer-container[key='+key+'] .list-follow-button img').removeClass("v");
		}else{
			alert(data);
		}
	});
});
$(document).on('click','.plist',function(){
window.location=$(this).find("a").attr("href");
return false;
});
$(document).on('click', '#second-social-bubble', function(){
	alert();
		$.get("/geters/groups.php",function(data){
			$( "body" ).append(data);
			$.magnificPopup.open({
  				items: {
			    src: $(data),
			    type: 'inline'
				},
				closeBtnInside:false,
				callbacks: {

					afterClose: function() {
						$( "#social-box" ).remove();
					}
  				}
			});
		});
	$("#socialbubble").removeClass("isclick").addClass("notclick");
});
$(document).on('click', '#new-group-button',  function(){
	if(!$(this).hasClass('isclick')){
		$("#social-container").empty();
		$.get("/geters/group_creator.php",function(data){
			$("#social-box").html(data);
		});
		$(".isclick").removeClass("isclick");
		$("#friends-button").addClass("isclick");
	}
});
$(document).on('mouseenter', '#secondsocialbubble', function(){
	$("#tipsysocialsecond").animate({
		    		opacity: 1
				}, 200,function(){
				});
});
$(document).on('mouseleave', '#secondsocialbubble', function(){
	$("#tipsysocialsecond").animate({
		    		opacity: 0
				}, 50,function(){
				});
});
$(document).off('submit','#groupcreator').on('submit', '#postform', function(event) {
    event.preventDefault();
    var that = $(this), data = {};
    that.find('[name]').each(function (index, value) {
        var that=$(this),
            name=that.attr('name'),
            value=that.val();
        data[name]=value;
    });
    var res= $('#post').attr('agerestriction');
    data['agerestriction']=$('#post').attr('agerestriction');
    submit(data);
    $.ajax({
        url: '/writers/create_group.php',
        type:'post',
        data: data,
        success:function (response) {
            if(data.replyTo){
                $(".commentsblock[key='"+name+"']").empty();
                var writebar = "<button class='comment notclick' placeholder='comment here' key='"+name+"'></button><br>";
                $(".commentsblock[key='"+name+"']").append(writebar);
                $(".commentsblock[key='"+name+"']").append("<img class='loading' src='https://shareon.me/icons/ajax-loader.gif'/>");
                $.post('/geters/commentsload.php',{name: data.replyTo},function(d){
                    $(".commentsblock[key='"+name+"']").empty();
                    $(".commentsblock[key='"+name+"']").append(writebar);
                    $(".commentsblock[key='"+name+"']").append(d);
                });
            }
            $('#post').remove();
        }
    });
});

$(document).on('mouseenter', '#thirdsocialbubble', function(){
	$("#tipsysocialthird").animate({
		    		opacity: 1
				}, 200,function(){
				});
});
$(document).on('mouseleave', '#thirdsocialbubble', function(){
	$("#tipsysocialthird").animate({
		    		opacity: 0
				}, 50,function(){
				});
});
