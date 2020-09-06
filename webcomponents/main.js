class Main extends HTMLElement{
	constructor(){
		super();
		this.switcher;
		this.insertQueue=[];
		this.state;
		this.topic;
		var t=this;
		$(this).on("change","order-select",function(){
			var order=$(this).attr("order")+"-"+$(this).attr("flipped");
			var o={order:order};
			t.load(o);
		});
		$(this).on("click","#wall-button",function(){
			if(!$(this).hasClass("selected")){
				$.get('/mainchanger.php?choice=wall',function(){});
				$(this).addClass("selected")
				var data={};
				if(t.topic!=undefined)
				{
					data['topic']=t.topic;
				}
				$("#all-posts-button").removeClass("selected");
				t.load(data);
			}
		});
		$(this).on("click","#all-posts-button",function(){
			if(!$(this).hasClass("selected")){
				$.get('/mainchanger.php?choice=all',function(){});
				$(this).addClass("selected")
				var data={};
				if(t.topic!=undefined)
				{
					data['topic']=t.topic;
				}
				$("#wall-button").removeClass("selected");
				t.load(data);
			}
		});
	}
	reload(){
		var o=this.state;
		$("main-container").find(".post-wrapper").remove();
		$("main-container").find("regular-post").remove();
		$("main-container").append('<i class="fas fa-spinner fa-pulse loading"></i>');
		var url=this.url;
		$.post(url,o,function(d) {
			$("main-container").find(".loading").remove();
			var json=JSON.parse(d);
			for(var i=0;i<json.length;i++){
				if(json[i]['view_option']!="deleted"){
					url=(is_url(json[i]['frame']['process']))?json[i]['frame']['process']:"/post-types/"+json[i]['type']+"/"+json[i]['frame']['process'];
					if($("iframe[type='"+json[i]['type']+"']").length==0){
						$("body").append("<iframe type='"+json[i]['type']+"' style='display:none;' onload=\"$(this).attr('loaded',true);\" loaded='false' src='"+url+"' allowfullscreen='true' webkitallowfullscreen='true' mozallowfullscreen='true'></iframe>");
					}
				}
				$("main-container").append("<div class='post-wrapper'><regular-post>"+JSON.stringify(json[i])+"</regular-post></div>");
			}
		});
	}
	load(o){
		$("main-container").find(".post-wrapper").remove();
		$("main-container").find("regular-post").remove();
		$("main-container").append('<i class="fas fa-spinner fa-pulse loading"></i>');
		var url=this.url;
		this.state=o;
		$.post(url,o,function(d) {
			$("main-container").find(".loading").remove();
			var json=JSON.parse(d);
			for(var i=0;i<json.length;i++){
				if(json[i]['view_option']!="deleted"){
					url=(is_url(json[i]['frame']['process']))?json[i]['frame']['process']:"/post-types/"+json[i]['type']+"/"+json[i]['frame']['process'];
					if($("iframe[type='"+json[i]['type']+"']").length==0){
						$("body").append("<iframe type='"+json[i]['type']+"' style='display:none;' onload=\"$(this).attr('loaded',true);\" loaded='false' src='"+url+"' allowfullscreen='true' webkitallowfullscreen='true' mozallowfullscreen='true'></iframe>");
					}
				}
				$("main-container").append("<div class='post-wrapper'><regular-post>"+JSON.stringify(json[i])+"</regular-post></div>");
			}
		});
	}
	connectedCallback() {
		this.switcher=$(this).attr("switcher");
		this.username=$(this).attr("username");
		this.url=$(this).attr("url");
		this.topic=$(this).attr("topic");
		this.post=$(this).attr("post");
		var top="", url=this.url;
		var data={};
		if($(this).text()!=""&&this.post==""){
			$(this).trigger("append");
		}else{
			if(this.username!==""&&typeof this.username!=="undefined")
			data['username']=this.username;
			if(typeof $(this).attr("order")!="undefined"){
				top+=`<main-sort ${(typeof $(this).attr("order")!="undefined"&&$(this).attr("order")!="")?"order='"+$(this).attr("order")+"'":""}
					${(typeof $(this).attr("flipped")!="undefined"&&$(this).attr("flipped")!="")?"flipped='"+$(this).attr("flipped")+"'":""} out='${($(this).attr("out")!=undefined)?$(this).attr("out"):"[]"}'
					 exclusively='${($(this).attr("exclusively")!=undefined)?$(this).attr("exclusively"):"[]"}'></main-sort>`;
			}
			if(this.switcher!=""&& typeof this.switcher!=='undefined'){
				top += `
				<div id="main-wall-switcher">
			    <div id="wall-button" class='${(this.switcher=="wall")?"selected":""}'>
			      MY WALL
			    </div><div id="all-posts-button" class='${(this.switcher=="all")?"selected":""}'>
			      ALL POSTS
			    </div>
			  </div>`;
			}
			this.innerHTML=top;
			if(this.topic!=undefined)
			{
				data['topic']=this.topic;
			}
			if(url!=undefined){
				if(url.includes("?")){
				url=url.split("?");
				data['key']=url[1].substring(4);
				url=url[0];
				}
				this.load(data);
				$.get("/geters/sorter.php",function(d){
					$("main-container").append(d);
					$("#sort-box").css("display",'none');
				});
			}else if(this.post!=undefined){
				$("main-container").append('<i class="fas fa-spinner fa-pulse loading"></i>');
				var o={key:this.post};
				$.post("/geters/smartPost.php",o,function(d) {
					var json=JSON.parse(d);
					$("main-container").find(".loading").remove();
					$("main-container").append("<div class='post-wrapper'></div>");
					for(var i=json.length-1;i>=0;i--){
						url=(is_url(json[i]['frame']['process']))?json[i]['frame']['process']:"/post-types/"+json[i]['type']+"/"+json[i]['frame']['process'];
						if($("iframe[type='"+json[i]['type']+"']").length==0){
							$("body").append("<iframe type='"+json[i]['type']+"' style='display:none;' onload=\"$(this).attr('loaded',true);\" loaded='false' src='"+url+"' allowfullscreen='true' webkitallowfullscreen='true' mozallowfullscreen='true'></iframe>");
						}
						if($("link[href='"+url+"']").length==0){
							var preloadLink = document.createElement("link");
							preloadLink.href = url;
							preloadLink.rel = "preload";
							preloadLink.as = "document";
							document.head.appendChild(preloadLink);
						}
						$(".post-wrapper").append("<regular-post>"+JSON.stringify(json[i])+"</regular-post>");
						if($("regular-post").length>1)
						$(".post-wrapper").addClass("multi");
					}
					$("regular-post").last().addClass("selected");
					$("regular-post").last().trigger("scroll");
				});
			}
		}
	}
	insertPost(json){
		var d=JSON.parse(json);
		if(d.view_option!="comment")
		this.insertQueue.push(["",json]);
		if(typeof d.replyTo!="undefined"){
		var replyTo=d.replyTo;
		for(var i=0;i<replyTo.length;i++)
		this.insertQueue.push([replyTo[i],json]);
		}
		this.insert();
	}
	insert(){
		var t=this;
		if(this.insertQueue.length!=0&&!$(this).hasClass("hold")){
			var key=this.insertQueue[0][0];
			var json=this.insertQueue[0][1];
			var j=JSON.parse(json);
			if($("iframe[type='"+j['type']+"']").length==0){
				var url=(is_url(j['frame']['process']))?j['frame']['process']:"/post-types/"+j['type']+"/"+j['frame']['process'];
				$("body").append("<iframe type='"+j['type']+"' style='display:none;' onload=\"$(this).attr('loaded',true);\" loaded='false' src='"+url+"' allowfullscreen='true' webkitallowfullscreen='true' mozallowfullscreen='true'></iframe>");
			}
			if(key==""){
				if($("regular-post").length>0){
					if($("regular-post").first().parent().hasClass("post-wrapper"))
					$(".post-wrapper").first().before("<div class='post-wrapper'><regular-post>"+json+"</regular-post></div>");
					else
					$("regular-post").first().before("<div class='post-wrapper'><regular-post>"+json+"</regular-post></div>");
					$("regular-post").first().trigger("scroll");
				}else{
					$(this).append("<div class='post-wrapper'><regular-post>"+json+"</regular-post></div>");
					$("regular-post").first().trigger("scroll");
				}
			}else{
				var j=JSON.parse(json);
				$("regular-post[key="+key+"]").trigger("comments");
				setTimeout(function(){$("regular-post[key="+j['_key']+"]").trigger("scroll");},1000);
			}
			this.insertQueue.shift();
			var th=$(this);
			setTimeout(function(){
				th.removeClass("hold");
				t.insert();
			},3000);
			th.addClass("hold");
		}
	}
}
window.customElements.define("main-container",Main);
	class Sort extends HTMLElement{
		constructor(){
			super();
		}
		connectedCallback() {
			this.innerHTML=`
			<more-sort out='${$(this).attr("out")}' exclusively='${$(this).attr("exclusively")}'></more-sort><order-select ${(typeof $(this).attr("order")!="undefined"&&$(this).attr("order")!="")?"order='"+$(this).attr("order")+"'":""}${(typeof $(this).attr("flipped")!="undefined"&&$(this).attr("flipped")!="")?"flipped='"+$(this).attr("flipped")+"'":""}></order-select>`;
		}
	}
	window.customElements.define("main-sort",Sort);
		class MoreSort extends HTMLElement{
			constructor(){
				super();
				this.json={};
				var t=this;
				$(this).on('click',function(){
					if($("sort-options").length==0){
						$("#sort-box").css("display",'block');
						$("info-box").css("display",'none');
						$("topic-info").css("display",'none');
						$(this).parent().append(`<sort-options>${JSON.stringify(t.json)}</sort-options>`);
					}else{
						$("#sort-box").css("display",'none');
						$("info-box").css("display",'block');
						$("topic-info").css("display",'block');
						$("sort-options").remove();
					}
				});
			}
			connectedCallback() {
				this.json={out:JSON.parse($(this).attr("out")),exclusively:JSON.parse($(this).attr("exclusively"))};
				this.innerHTML=`
				<img src='/icon/sort.png'/>FILTER`;
			}
		}
		window.customElements.define("more-sort",MoreSort);
		class SortOptions extends HTMLElement{
			constructor(){
				super();
				var t=this;

				this.tagDisplay1 = $(this).find('#tag-display-out');
				this.tagDisplay2 = $(this).find('#tag-display-in');

				//handle click on result out
				$(this).on("click",'#tag-container-out .result',function(){
					var tag=$(this).find("span").text();
					var val=$(this).attr("key");
						var b=false;
						var arr=[];
						$("#tag-display-out .tag").each(function(){
							arr.push($(this).attr("val"));
							if($(this).attr("val")==val){
								b=true;
							}
						});
						arr.push(val);
						if(!b){
					    $("#tag-display-out").append(t.createTag(tag,val));
							if(!$("#tag-display-out").is(":visible"))
					    $(".errow-out").trigger("click");
						}
						$("#tag-container-out input").val('');
						arr=JSON.stringify(arr);
						var o={out:arr,direct:1};
						$.post("/writers/filtersession.php",o,function(d){
						});
						clearTimeout(sessionStorage.sort);
						sessionStorage.sort=setTimeout(function(){
								document.querySelector("main-container").reload();
						},2000);
				});

				//handle click on result in
				$(this).on("click",'#tag-container-in .result',function(){
					var tag=$(this).find("span").text();
					var val=$(this).attr("key");
						var b=false;
						var arr=[];
						$("#tag-display-in .tag").each(function(){
							arr.push($(this).attr("val"));
							if($(this).attr("val")==val){
								b=true;
							}
						});
						arr.push(val);
						if(!b){
					    $("#tag-display-in").append(t.createTag(tag,val));
							if(!$("#tag-display-in").is(":visible"))
					    $(".errow-in").trigger("click");
						}
						$("#tag-container-in input").val('');
						arr=JSON.stringify(arr);
						var o={exclusively:arr,direct:1};
						$.post("/writers/filtersession.php",o,function(d){
						});
						clearTimeout(sessionStorage.sort);
						sessionStorage.sort=setTimeout(function(){
								document.querySelector("main-container").reload();
						},2000);
				});

				//create tag
				$(this).on("keyup",'#tag-container-out input',function(e) {
					var tag = $(this).val();
					var val="";
					if (e.key === 'Enter') {
						if(tag.charAt(0)=="*"){
							var r=$("#tag-container-out .result").first();
							val=r.attr('key');
							tag=r.find("span").text();
						}else {
							val=tag;
						}
					 if(val!=""){
						 var b=false;
						 var arr=[];
						 $("#tag-display-out .tag").each(function(){
							 arr.push($(this).attr("val"));
							 if($(this).attr("val")==val){
								 b=true;
							 }
						 });
						 arr.push(val);
						 if(!b){
							 $(this).val('');
							$("#tag-display-out").append(t.createTag(tag,val));
							if(!$("#tag-display-out").is(":visible"))
							$(".errow-out").trigger("click");
							arr=JSON.stringify(arr);
							var o={out:arr,direct:1};
							$.post("/writers/filtersession.php",o,function(d){
							});
							clearTimeout(sessionStorage.sort);
							sessionStorage.sort=setTimeout(function(){
									document.querySelector("main-container").reload();
							},2000);
						}
					 }
				}else{ //show search options
					var th=$(this);
					setTimeout(function(){
						var text=th.val();
						$("#tag-container-out .result").remove();
						$("#tag-container-out hr").remove();
						$("#tag-container-out").append('<i class="fas fa-spinner fa-pulse"></i>');
						$.post('/geters/search.php',{search:text},function(d) {
							$("#tag-container-out i").remove();
							var json=JSON.parse(d);
							$("#tag-container-out .result").remove();
							for(var i=0;i<json.length;i++){
								var img="";
								var link="/";
								var name="";
								var key="";
								if(json[i][1] =='>'){//post key
									img=json[i][0]['portrait'];//object, picture of post
									link+="posts/"+json[i][0]['_key'];//direct to post and comments
									name=">"+json[i][0]['name'];
									key=">"+json[i][0]['_key'];
								}else if(json[i][1] =='#'){//post type
									img=(json[i][0]['image']!=undefined&&json[i][0]['image']!="")?json[i][0]['image']:"/icon/topic.jfif";
									link+="topics/"+json[i][0]['_key'];
									name="#"+json[i][0]['name'];
									key="#"+json[i][0]['_key'];
								}else if(json[i][1]=='@'){//user
									img=(json[i][0]['profile_picture']!=undefined&&json[i][0]['profile_picture']!="")?json[i][0]['profile_picture']:"/icon/profile_picture.png";
									link+="profiles/"+json[i][0]['username']+"/posts";
									name="@"+json[i][0]['name'];
									key="@"+json[i][0]['_key'];
								}else if(json[i][1]=='*'){//user
									img="/post-types/"+json[i][0]['_key']+"/icon.png";
									name="*"+json[i][0]['name'];
									key="*"+json[i][0]['_key'];
								}
							$("#tag-container-out").append(`<div class='result' key=${key}><img src='${img}'/><span>${name}</span></div>`);
							if(i<json.length-1)
							$("#tag-container-out").append('<hr></hr>');
							}
						});
					}, 10);
				}
				});
				//create tag
				$(this).on("keyup",'#tag-container-in input',function(e) {
					if (e.key === 'Enter') {
						var tag = $(this).val();
						var val="";
						if(tag.charAt(0)=="*"){
							var r=$("#tag-container-in .result").first();
							val=r.attr('key');
							tag=r.find("span").text();
						}else {
							val=tag;
						}
					 if(val!=""){
						 var b=false;
						 var arr=[];
						 $("#tag-display-in .tag").each(function(){
							 arr.push($(this).attr("val"));
							 if($(this).attr("val")==val){
								 b=true;
							 }
						 });
						 arr.push(val);
						 if(!b){
							 $(this).val('');
							$("#tag-display-in").append(t.createTag(tag,val));
							if(!$("#tag-display-in").is(":visible"))
							$(".errow-in").trigger("click");
							arr=JSON.stringify(arr);
							var o={exclusively:arr,direct:1};
							$.post("/writers/filtersession.php",o,function(d){
							});
							clearTimeout(sessionStorage.sort);
							sessionStorage.sort=setTimeout(function(){
									document.querySelector("main-container").reload();
							},2000);
						}
					 }
				}else{ //show search options
					var th=$(this);
					setTimeout(function(){
						var text=th.val();
						$("#tag-container-in .result").remove();
						$("#tag-container-in hr").remove();
						$("#tag-container-in").append('<i class="fas fa-spinner fa-pulse"></i>');
						$.post('/geters/search.php',{search:text},function(d) {
							$("#tag-container-in i").remove();
							var json=JSON.parse(d);
							for(var i=0;i<json.length;i++){
								var img="";
								var link="/";
								var name="";
								var key="";
								if(json[i][1] =='>'){//post key
									img=json[i][0]['portrait'];//object, picture of post
									link+="posts/"+json[i][0]['_key'];//direct to post and comments
									name=">"+json[i][0]['name'];
									key=">"+json[i][0]['_key'];
								}else if(json[i][1] =='#'){//post type
									img=(json[i][0]['image']!=undefined&&json[i][0]['image']!="")?json[i][0]['image']:"/icon/topic.jfif";
									link+="topics/"+json[i][0]['_key'];
									name="#"+json[i][0]['name'];
									key="#"+json[i][0]['_key'];
								}else if(json[i][1]=='@'){//user
									img=(json[i][0]['profile_picture']!=undefined&&json[i][0]['profile_picture']!="")?json[i][0]['profile_picture']:"/icon/profile_picture.png";
									link+="profiles/"+json[i][0]['username']+"/posts";
									name="@"+json[i][0]['name'];
									key="@"+json[i][0]['_key'];
								}else if(json[i][1]=='*'){//user
									img="/post-types/"+json[i][0]['_key']+"/icon.png";
									name="*"+json[i][0]['name'];
									key="*"+json[i][0]['_key'];
								}
							$("#tag-container-in").append(`<div class='result' key=${key}><img src='${img}'/><span>${name}</span></div>`);
							if(i<json.length-1)
							$("#tag-container-in").append('<hr></hr>');
							}
						});
					}, 10);
				}
			});

				//click on x
				$(this).on("click",".tag", function(){
					var arr=[];
					var parent=$(this).parent();
				  $(this).remove();
					parent.find(".tag").each(function(){
						arr.push($(this).attr("val"));
					});
					arr=JSON.stringify(arr);
					if(parent.attr("id")=="tag-display-out")
					var o={out:arr,direct:1};
					else
					var o={exclusively:arr,direct:1};
					$.post("/writers/filtersession.php",o,function(d){
					});
					clearTimeout(sessionStorage.sort);
					sessionStorage.sort=setTimeout(function(){
							document.querySelector("main-container").reload();
					},2000);
				})

				$(this).on("click",".errow-out", function(){
				  if($("#tag-display-out").is(":visible")){
				    $("#tag-display-out").hide();
				    $(".errow-out").html("<i class='fas fa-angle-up'></i>");
				  }
				  else{
				    $("#tag-display-out").show();
				    $(".errow-out").html("<i class='fas fa-angle-down'></i>");
				    $("#tag-display-in").hide();
				    $(".errow-in").html("<i class='fas fa-angle-up'></i>");
				  }
				});

				$(this).on("click",".errow-in",function(){
				  if($("#tag-display-in").is(":visible")){
				    $("#tag-display-in").hide();
				    $(".errow-in").html("<i class='fas fa-angle-up'></i>");
				  }
				  else{
				    $("#tag-display-in").show();
				    $(".errow-in").html("<i class='fas fa-angle-down'></i>");
				    $("#tag-display-out").hide();
				    $(".errow-out").html("<i class='fas fa-angle-up'></i>");
				  }
				});
			}
			createTag(label,val) {
				const div = document.createElement('div');
				div.setAttribute('class', 'tag');
				div.setAttribute("val",val);
				const span = document.createElement('span');
				span.innerHTML = label;
				const closeIcon = document.createElement('i');
				closeIcon.innerHTML = 'x';
				closeIcon.setAttribute('class', 'material-icon');
				closeIcon.setAttribute('data-item', label);
				div.appendChild(span);
				div.appendChild(closeIcon);
				return div;
			}
			connectedCallback() {
				var json=JSON.parse($(this).text());
				var out="";
				for(var i=0;i<json['out'].length;i++){
					out+="<div class='tag' val='"+json['out'][i]['val']+"'><span>"+json['out'][i]['name']+"</span><i class='material-icon' data-item='f'>x</i></div>"
				}
				var exclusively="";
				for(var i=0;i<json['exclusively'].length;i++){
					exclusively+="<div class='tag' val='"+json['exclusively'][i]['val']+"'><span>"+json['exclusively'][i]['name']+"</span><i class='material-icon' data-item='f'>x</i></div>"
				}
				this.innerHTML=`
				<div class="filter" >
				  <div class="container" style="z-index:3;">
				    <div class="filter-title">Filter posts out of your feed</div>
				    <br />
				    <div class="tag-container" id="tag-container-out">
							<span class="fa fa-search form-control-feedback"></span>
				      <input placeholder="Search"/>
				    </div>
				  </div>
				  <div class="display" >
				    <div class="filter-title">Filtered Out</div>
				    <button class="errow-out" ><i class='fas fa-angle-up'></i></button>
				    <br />
				    <div class="tag-display" id="tag-display-out">
						${out}
				    </div>
				  </div>
				</div>

				<div class="filter" >
				  <div class="container" >
				    <div class="filter-title">Filter posts on of your feed</div>
				    <br />
				    <div class="tag-container" id="tag-container-in">
							<span class="fa fa-search form-control-feedback"></span>
				    <input placeholder="Search"/>
				    </div>
				  </div>
				  <div class="display" >
				    <div class="filter-title">Filtered Exclusively</div>
				    <button class="errow-in" ><i class='fas fa-angle-up'></i></button>
				    <br />
				    <div id="tag-display-in" class="tag-display">${exclusively}</div>
				  </div>
				</div>
				<!--<div class="filter-button">
					<button  > Save this filter </button>
				</div>-->
			`;
			$(this).find('#tag-container-out input').focus();
			$(this).find('#tag-container-in input').focus();
			}
		}
		window.customElements.define("sort-options",SortOptions);
