$(document).on("click","#profile-edit-button",function(){
	if(!$("#profile-edit-button").hasClass("cooling")){
	$("#profile-edit-button").addClass("cooling");
	if(!$("#profile-edit-button").hasClass("cancel-edit")){
	$("#profile-picture img").addClass("editable");
	$("#profile-picture img").attr("original",$("#profile-picture img").attr("src"));
	$("#profile-picture").prepend('<input type="file" name="file" /><span class="text">Click Here to Edit!</span>');
	var name=$("#profile-name").text();
	$("#profile-name").replaceWith("<input type='text' original='"+name+"' style='background:rgba(0,0,0,0);border:0px;' id='profile-name'/>");
	$("#profile-name").val(name);
	$("#profile-name").addClass("editable");
	var username=$("#profile-username").text().slice(1);
	$("#profile-username").replaceWith("<div id='profile-username'><p>@</p><input type='text' original='"+username+"' style='background:rgba(0,0,0,0);border:0px;color:white;' value='"+username+"'/></div>");
	$("#profile-username").addClass("editable");
	$("#profile-edit-button img").attr("src","/icons/v.png");
	$("#profile-edit-button p").text("Done");
	setTimeout(function(){
		$("#profile-edit-button").addClass("cancel-edit");
		$("#profile-edit-button").removeClass("cooling");
	 }, 200);
	}else{
		var file=$('#profile-picture input').prop("files")[0];
		var fd = new FormData();
		fd.append("file", file);
		fd.append("name",$("#profile-name").val());
		fd.append('username',$("#profile-username input").val());
		var xhr = new XMLHttpRequest();
		xhr.open('POST', '/writers/profile_editor.php', true);
		xhr.upload.onprogress = function(e) {
		if (e.lengthComputable) {
		var percentComplete = (e.loaded / e.total) * 100;
		console.log(percentComplete + '% uploaded');
		}
		};
		xhr.onload = function() {
		if (this.status == 200) {
		}
		};
		xhr.send(fd);
		$("#profile-picture img").removeClass("editable");
		$("#profile-picture .text").remove();
		$("#profile-picture input").remove();
		$("#profile-name").replaceWith("<div id='profile-name'>"+$("#profile-name").val()+"</div>");
		$("#profile-name").removeClass("editable");
		$("#profile-username").replaceWith("<div id='profile-username'>@"+$("#profile-username input").val()+"</div>");
		$("#profile-username").removeClass("editable");
		$("#profile-edit-button img").attr("src","/icons/edit.png");
		$("#profile-edit-button p").text("Edit Profile");
		setTimeout(function(){
			$("#profile-edit-button").removeClass("cancel-edit");
			$("#profile-edit-button").removeClass("cooling");
		}, 200);
	}
	}
});
$(document).off('change','#profile-picture input').on('change','#profile-picture input', function(){
		var file=$(this).prop("files")[0];
		var url= URL.createObjectURL(file);
		$("#profile-picture img").attr("src",url);
});
$(document).on('click','#profile-picture img',function (e) {
    if ( !$(this).hasClass("editable")) {
    	var img=document.querySelector("#profile-picture img");
    	var d='';
    	if(img.naturalHeight>img.naturalWidth){
    		var width=(img.naturalWidth/img.naturalHeight)*680;
    		d='<img src="'+img.src+'" style="height:680px;left:50%;margin-left:-'+(width/2)+'px;position:absolute;top:50%;margin-top:-340px;"/>';
    	}else{
    		var height=(img.naturalHeight/img.naturalWidth)*680;
    		d='<img src="'+img.src+'" style="width:680px;left:50%;margin-left:-340px;position:absolute;margin-top:-'+(height/2)+'px;top:50%;"/>';
    	}
    	$.magnificPopup.open({
		items: {
	    src: $(d),
	    type: 'inline'
		},
		closeOnBgClick: true,
		closeBtnInside: true,
		showCloseBtn:true,
		callbacks: {

		}
		});
    }
});
$(document).on("focus","#profile-name.editable",function(){
	$(this).removeClass("editable");
});
$(document).on("blur","#profile-name",function(){
	$(this).addClass("editable");
	if($(this).val().split(" ").length<2||$(this).val().split(" ")[0].length<2||$(this).val().split(" ")[1].length<2){
		setTimeout(function(){
			$("#profile-name").focus();
		}, 200);
		alert("At least 1 First Name and 1 Last Name are necessary!");
	}
});
$(document).on("focus","#profile-username.editable",function(){
	$(this).removeClass("editable");
});
$(document).off("blur","#profile-username").on("blur","#profile-username",function(){
	var username=String($("#profile-username input").val()).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
	$(this).addClass("editable");
	if(username.length<5){
		setTimeout(function(){
			$("#profile-name").focus();
		}, 200);
		alert("Usernames must be at least 5 characters long");
	}else if(username!=$("#profile-username input").attr('original')){
		$.post("/geters/username_checker.php",{username:username},function(d){
			if(d==0){
				setTimeout(function(){
					$("#profile-name").focus();
				}, 200);
				alert("Username unavailable, please pick another.");
			}else{
				$("#profile-username").replaceWith("<div id='profile-username' class='editable'><p>@</p><input type='text' original='"+$("#profile-username input").attr('original')+"' style='background:rgba(0,0,0,0);border:0px;color:white;' value='"+username+"'/></div>");
			}
		});
	}else
	$("#profile-username").replaceWith("<div id='profile-username' class='editable'><p>@</p><input type='text' original='"+$("#profile-username input").attr('original')+"' style='background:rgba(0,0,0,0);border:0px;color:white;' value='"+usernaem+"'/></div>");
});
$(document).on("click","#follow-button",function(){
	var url = window.location.href;
	var state = url.split("profiles/");
	state= state[1].split("/");
	username=state[0];
	$(this).empty();
	$(this).append('<i class="fas fa-spinner fa-pulse loading"></i>');
	$.post('/writers/change_follow.php',{username: username},(data)=>{
		$(this).find(".loading").remove();
		$(this).append("<p class='text'></p>");
		if(data==1){
			$('#follow-button .text').text("Following");
			$('#follow-button img').attr('src',"/icons/v.png");
			$('#follow-button img').addClass("v");
		}else if(data==0){
			$('#follow-button .text').text("Follow");
			$('#follow-button img').attr('src',"/icons/follow.png");
			$('#follow-button img').removeClass("v");
		}else{
			alert(data);
		}
	});
});
$("#friend-button").on("click",function(){
	var url = window.location.href;
	var state = url.split("profiles/");
	state= state[1].split("/");
	username=state[0];
	$.post('/geters/friendbuttondetecter.php',{username:username},function(ddata){
		if(ddata!=""){
		$.post(ddata,{username:username}, function(data){
			if(data=="done"){
				if($("#friend-button .text").text()=="Friend Request Sent"){
					$("#friend-button .text").text("Add Friend");
				}else if($("#friend-button .text").text()=="Friend"){
						$('#friend-button .text').text("Add Friend");
						$('#friend-button').removeClass("v");
						$('#friend-button img').attr("src","/icons/add-friend.png");
						$('#follow-button .text').text("Follow");
						$('#follow-button img').attr("src","/icons/follow.png");
				}else if($('#friend-button .text').text()=="Accept Friend Request"){
					$('#friend-button .text').text("Friend");
					$('#friend-button').addClass("v");
					$('#friend-button img').attr("src","/icons/v.png");
					$('#follow-button .text').text("Following");
					$('#follow-button img').attr("src","/icons/v.png");
				}else if($('#friend-button .text').text()=="Add Friend"){
					$('#friend-button .text').text("Friend Request Sent");
				}
			}
		});
		}else{
			alert("fak u dolan");
		}
	});
});
$(".profile-selector").on("click",function(){
	var type=$(this).attr("data");
	if(!$(this).hasClass("isclick")){
		$(".profile-selector").removeClass("isclick");
		$("#main-content-container").empty();
		if(type==="friends"){
			$(".profile-selector").removeClass("isclick");
			$(".profile-selector[data="+type+"]").addClass("isclick");
			var url=window.location.href;
			history.pushState({
				    id: 'friends'
				}, 'user-friends', url.substring(0,url.lastIndexOf('/'))+"/"+type);
			$("#main-content-container").append("<div id='profile-social-box'></div>");
			$.post("/geters/profile_friends.php",{username:username},function(d){
				$("#profile-social-box").append(d);

			});
		}else if(type==="collections"){
			$(".profile-selector").removeClass("isclick");
			$(".profile-selector[data="+type+"]").addClass("isclick");
			var url=window.location.href;
			history.pushState({
				    id: 'collections'
				}, 'user-collections', url.substring(0,url.lastIndexOf('/'))+"/"+type);
			$.getJSON("/geters/collectionLoad.php",function(json){
				var d="<div id='collectionList'><div id='grid'><div class='collectionBox' id='create'>"+
				"<div class='portrait'><img src='/icons/+.png'/></div><p>Create Collection</p></div>"+collectionGrid(json)+"<br/></div></div>";
				$("#main-content-container").append(d);
			});
		}else if(type==="followers"){
			$(".profile-selector").removeClass("isclick");
				$(".profile-selector[data="+type+"]").addClass("isclick");
				var url=window.location.href;
				history.pushState({
					    id: 'followers'
					}, 'user-followers', url.substring(0,url.lastIndexOf('/'))+"/"+type);
			$("#main-content-container").append("<div id='profile-social-box'></div>");
			$.post("/geters/profile_followers.php",{username:username},function(d){
				$("#profile-social-box").append(d);
			});
		}else{
			$(".profile-selector").removeClass("isclick");
				$(".profile-selector[data="+type+"]").addClass("isclick");
				var url=window.location.href;
				history.pushState({
					    id: 'posts'
					}, 'user-posts', url.substring(0,url.lastIndexOf('/'))+"/"+type);
			$.post("/geters/profile_posts.php",{user:username},function(d){
				$("#main-content-container").append(d);
			});
		}
	}
});
$(".friendstuff").on("click", function () {
	var url = window.location.href;
	var username = url.split("profiles/");
	username= username[1].split("/");
	username= username[0];
	$.post('/geters/friendbuttondetecter.php',{username:username},function(ddata){
		if(ddata!=""){
		$.post(ddata,{username:username}, function(data){
			if(data=="done"){
				if($('.friendstuff').text()=="friend request sent"){
					$('.friendstuff').text("send friend request");
				}else if($('.friendstuff').text()=="friend"){
					$('.friendstuff').text("send friend request");
					$('.followstuff').text("follow?");
				}else if($('.friendstuff').text()=="accept friend request"){
					$('.friendstuff').text("friend");
					$('.followstuff').text("following");
				}else if($('.friendstuff').text()=="send friend request"){
					$('.friendstuff').text("friend request sent");
				}
			}
		});
		}else{
			alert("fak u dolan");
		}
	});
});
$('#profile_edit').on('click', function(){
	var url = window.location.href;
	var username = url.split("profiles/");
	username= username[1].split("/");
	username= username[0];
	$.get('/profile_html/profilepicture.php', function(data){
		$( "body" ).append(data);
		$.magnificPopup.open({
			items: {
		    src: $(data),
		    type: 'inline'
			},
			closeOnBgClick: false,
			closeBtnInside: false,
			callbacks: {
				afterClose: function() {
					$( "#alert" ).remove();
				}
			}
		});
	});
});
