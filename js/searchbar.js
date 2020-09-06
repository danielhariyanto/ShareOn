$(document).on("keydown","#main-search-bar input", function(){
	var t=$(this);
	setTimeout(function(){
		var text=t.val();
		$("#main-search-bar .result").remove();
		$("#main-search-bar hr").remove();
		$("#main-search-bar").append('<i class="fas fa-spinner fa-pulse"></i>');
		$.post('/geters/search.php',{search:text},function(d) {
			$("#main-search-bar i").remove();
			var json=JSON.parse(d);
			for(var i=0;i<json.length;i++){
				var img="";
				var link="/";
				var c="picture";
				if(json[i][1] =='>'){//post key
					c="portrait";
					img=json[i][0]['portrait'];//object, picture of post
					link+="posts/"+json[i][0]['_key'];//direct to post and comments
				}else if(json[i][1] =='#'){//post type
					img=(json[i][0]['image']!=undefined&&json[i][0]['image']!="")?json[i][0]['image']:"/icon/topic.jfif";
					link+="topics/"+json[i][0]['_key'];
				}else if(json[i][1]=='@'){//user
					img=(json[i][0]['profile_picture']!=undefined&&json[i][0]['profile_picture']!="")?json[i][0]['profile_picture']:"/icon/profile_picture.png";
					link+="profiles/"+json[i][0]['username']+"/posts";
				}
				if(img!="")
			$("#main-search-bar").append(`<div class='result' onclick="window.open('${link}')"><img class='${c}' src='${img}'/><span>${json[i][0]['name']}</span></div>`);
			if(i<json.length-1)
			$("#main-search-bar").append('<hr></hr>');
			}
		});
	}, 10);
});
$(document).on("click","body",function(){
	$(".result").remove();
});

//* => post type
//# => topics
//@ => user
//> => post key
