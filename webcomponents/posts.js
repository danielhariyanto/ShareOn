class Post extends HTMLElement{
	constructor(){
		super();
		this.key;
		this.type="";
		this.json={};
		var t=this;
		$(this).on('mouseenter','.post-options',function(){
			$(this).attr('src',"/icon/clockwork-hover.png");
		});
		$(this).on('mouseleave','.post-options',function(){
			$(this).attr('src',"/icon/clockwork.png");
		});
		$(this).on("comments",function(){
			if($(this).parent().hasClass("multi")){
				$(this).find('.comments-button').trigger("click");
			}
			setTimeout(()=>{
				$(this).find('.comments-button').trigger("click");
			},11);
		});
		$(this).on("scroll",function(){
			$(this).addClass("comment-highlighter");
			var th=$(this);
			$('html, body').animate({
				scrollTop: th.offset().top-120
			},1000);
			setTimeout(function(){
				th.removeClass("comment-highlighter");
			}, 2000);
		});
		$(document).on('click','.edit',function(){
			if($(".editor-shadow").length==0){
				var key = $(this).attr('key');
				var dev="";
				if($("body").attr("dev")!=undefined)
				dev=" dev='"+$("body").attr("dev")+"'";
				var type=t.type;
				$("body").append("<div class='editor-shadow'></div>");
					$( ".editor-shadow" ).last().append("<post-editor edit='"+key+"'"+dev+"></post-editor>");
			}
		});
		//delete button for all posts
		$(document).on('click','.delete', function(){
			var key = $(this).parent().parent().attr('key');
			var toDel = $("regular-post[key='"+key+"']");
			$(this).empty();
			$(this).append('<i class="fas fa-spinner fa-pulse"></i>');
			$.post('/geters/checkano.php',{key: key}, function(ddata) {
				if(ddata!="") {
		            $("body").append(ddata);
		            $.magnificPopup.open({
		                items: {
		                    src: $(ddata),
		                    type: 'inline'
		                },
		                closeBtnInside: false,
		                callbacks: {
		                    afterClose: function () {
		                    	$("#anonymous-container").remove();
		                        var password = $("header").attr("apass");
		                        $.post('/writers/deletePost.php', {key: key,password: password}, function (data) {
		                        	if(data=="update"){
		                        		if(name.indexOf("-")>-1){
		                        			$(".anonymous-holder[key='"+name+"']").remove();
		                        			$(".comment-options[key='"+name+"']").remove();
		                        			$(".comment-content[key='"+name+"']").replaceWith("<div class='comment-content deleted'>deleted</div>");
		                        		}else{
		                        			$(".anonymous-holder[key='"+key+"']").remove();
		                        			$(".post-options[key='"+key+"']").remove();
		                        			$(".post-content[key='"+key+"']").replaceWith("<div class='post-content deleted'>deleted</div>");
		                        		}
		                        	}else if(data=="delete"){
		                        		var cont=$(".comment[key='"+name+"']").parent().parent();//container
		                        		if(name.indexOf("-")>-1){//iscomment
		                        			if($(".comments-containter[key='"+name+"']").parent().hasClass("post")){
		                        				if(!cont.has(".comment")){
		                        					if($(".comment[key='"+name+"']").hasClass("second"))
			                        				cont.append('<div class="comment none second"><p style="margin-top:-10px;margin-bottom:0px;text-align:center;">no comments</p></div>');
			                        				else if($(".comment[key='"+name+"']").hasClass("third"))
			                        				cont.append('<div class="comment none third"><p style="margin-top:-10px;margin-bottom:0px;text-align:center;">no comments</p></div>');
			                        				else
			                        				cont.append('<div class="comment none"><p style="margin-top:-10px;margin-bottom:0px;text-align:center;">no comments</p></div>');
			                        			}
		                        			}
		                        		}
		                        		toDel.animate({
		                        			opacity:0
		                        		},1000,function(){
		                        			toDel.remove();
				                            if(name.indexOf("-")>-1)
				                            $("div[key='"+key+"']").remove();
				                            $("header").attr("apass", "");
		                        		});
		                        	}
		                        });
		                    }
		                }
		            });
		        }else{
		            $.post('/writers/deletePost.php', {key: key}, function (data) {
		            	if(data=="update"){
		            		if(name.indexOf("-")>-1){
		            			$(".op-holder[key='"+name+"']").remove();
		            			$(".comment-options[key='"+name+"']").remove();
		            			$(".comment-content[key='"+name+"']").replaceWith("<div class='comment-content deleted'>deleted</div>");
		            		}else{
		            			$(".op-holder[key='"+key+"']").remove();
		            			$(".post-options[key='"+key+"']").remove();
		            			$(".post-content[key='"+key+"']").replaceWith("<div class='post-content deleted'>deleted</div>");
		            		}
		            	}else if(data=="delete"){
		            		toDel.animate({opacity:0},500,function(){
		            			$(this).remove();
		            		});
		            	}
		            });
							}
		    });
		});
	}
	connectedCallback() {
		try{
		this.json=$(this).text();
	    var t=JSON.parse(this.json);
	    this.type=t['type'];
		this.key=t['_key'];
		$(this).attr('key',this.key);
	    this.innerHTML = `
	    <div class="comment-line"></div>
	    <inner-post key=${this.key}>${this.json}
	    </inner-post>`;
			this.json=t;
	    } catch (e) {

	    }

	}
	update(json){
		try{
	    var t=JSON.parse(json);
	    this.type=t['type'];
		this.key=t['_key'];
		$(this).attr('key',this.key);
	    this.innerHTML = `
	    <div class="comment-line"></div>
	    <inner-post key=${this.key}>${json}
	    </inner-post>`;
	    } catch (e) {

	    }
	}
}
window.customElements.define("regular-post",Post);
	class InnerPost extends HTMLElement{
		constructor() {
		  super();
		  this.json={};
		  this.key;
		}
		connectedCallback() {
			try{
				var t=$(this).text();
			this.key=$(this).attr('key');
			this.json=JSON.parse(t);
			var jtop={'profile':def(this.json['profile']),'vote':def(this.json['vote']),'upvotes':def(this.json['upvotes']),
			'downvotes':def(this.json['downvotes']),'time':def(this.json['time'])};
			var jtag={'topics':def(this.json['topics']),'replyTo':def(this.json['replyTo']),'taggedPeople':def(this.json['taggedPeople'])};
			var scroll=(this.json['frame']['scroll'])?"yes":"no";
			this.innerHTML = `
	    	<back-post key=${this.key}></back-post>
	    	<post-top key=${this.key}>${JSON.stringify(jtop)}</post-top>
				<i class="fas fa-spinner fa-pulse loading"></i>
	    	<post-tags>${JSON.stringify(jtag)}</post-tags>
	    	<post-bottom key=${this.key} comments=${this.json['comments']}>
	    	</post-bottom>`;
				if($("iframe[type='"+this.json['type']+"']").last().attr("loaded")=="false"){
					$("iframe[type='"+this.json['type']+"']").last().on("load",()=>{
						$("iframe[type='"+this.json['type']+"']").last().clone().insertBefore($(this).find("i"));
						var i=$(this).find("iframe[type='"+this.json['type']+"']").last();
						i.attr("key",this.key);
						i.attr("scrolling",scroll);
						if(this.json['frame']['size']!=0){
							i.css("height",this.json['frame']['size']+"px");
							i.attr("height",this.json['frame']['size']);
						}
						i.addClass("post-content");
							i.attr("onload",'this.contentWindow.postMessage('+t+',"*");');
							i.trigger("load");
					});
				}else{
					$("iframe[type='"+this.json['type']+"']").last().clone().insertBefore($(this).find("i"));
					var i=$(this).find("iframe[type='"+this.json['type']+"']").last();
					i.attr("key",this.key);
					i.attr("scrolling",scroll);
					if(this.json['frame']['size']!=0){
						i.css("height",this.json['frame']['size']+"px");
						i.attr("height",this.json['frame']['size']);
					}
					i.css("display","block");
					i.addClass("post-content");
					i.attr("onload",'this.contentWindow.postMessage('+t+',"*");');
					i.trigger("load");
				}
	    	}catch (e) {

		    }
		}
	}
	window.customElements.define("inner-post",InnerPost);
		class BackPost extends HTMLElement{
			constructor() {
			  super();
			  $(this).attr("active",true);
			  this.active=true;
			  $(this).on('click', function() {
			  	var t=$(this);
			  	var post=$(this).parent().parent();
			  	var th=$(this).parent().find(".comments-button");
			  	var key=th.attr("key");
				if($(this).attr("active")=="true"){
					t.attr("active",false);
					//puts regular-post in a wrapper in order to handle multiple comments
					if(!post.parent().hasClass("multi")){
						post.parent().addClass("multi");
						post.addClass("selected");
					}
					var passed=false;
					post.parent().find(".no-comments").remove();
					post.parent().find("regular-post").each(function(){
						if(passed){
							$(this).remove();
						}else if($(this).attr("key")!=key)
						$(this).find("back-post").attr("active",true);
						if($(this).attr("key")==key)
						passed=true;
					});
					//determines the mode of the comment button
					post.find(".comments-button").removeClass("notclick");
					//prevents multi click
					post.find(".comments-button").addClass("disabled");
					//loading image appended before comments are loaded
					post.parent().append('<i class="fas fa-spinner fa-pulse loading"></i>');
					$.post('/geters/commentsload.php',{key: key}, function(data) {
						post.parent().find("regular-post").each(function(){
							if($(this).isAfter(".selected")&&$(this).attr("key")!=key){
								$(this).find("inner-post").animate({
	                    			opacity:0
	                    		},1000,function(){
	                    			$(this).parent().animate({height:0},1000);
	                    			$(this).parent().remove();
	                    		});

							}
						});
						var json=JSON.parse(data);
						post.find(".comments-button-text").text(json.length+" comment(s)");
						t.find(".comments-button-text").css("color","blue");
						t.find(".comments-button-text").text(json.length+" comment(s)");
						//deletes loading image
						post.parent().find(".loading").remove();
						//if there are posts, do something otherwise, print that there are no comments
						if(json.length>0){
							for(var i=0;i<json.length;i++)
							post.parent().append("<regular-post>"+JSON.stringify(json[i])+"</regular-post>");
						}else
						post.parent().append("<p class='no-comments'>no comments.</p>");
						post.parent().find(".selected").removeClass("selected");
						post.addClass("selected");
						if(post.attr("scroll")!=""&&typeof post.attr("scroll")!="undefined"){
							window.scroll.toScroll.push(post.attr("scroll"));
							post.attr("scroll","");
							$("body").trigger("postScroll");
						}
					});
				}
			  });
			}
		}
		window.customElements.define("back-post",BackPost);
		class PostTop extends HTMLElement{
			constructor() {
			  super();
			  this.json={};
			  this.key;
			  $(this).on('click', '.post-options', function(){
					var name = $(this).attr('key');
					if($(".post-options-bubble[key='"+name+"']").html()==undefined){
						$(this).parent().parent().parent().prepend("<div class='post-options-bubble invisible' key='"+name+"'></div>");
						$.post('/geters/poptions.php',{key: name}, function(data) {
							$(".post-options-bubble[key="+name+"]").append(data);
							$(".post-options-bubble[key="+name+"] ul").append("<li key='"+name+"'><a style='color:white;' target='_blank' href='/posts/"+name+"'>open in new tab</a></li>");
							var width=$(".post-options-bubble[key="+name+"]").width();
							$(".post-options-bubble[key="+name+"]").css("margin-right","-"+((width/2)-20)+"px");
							$(".post-options-bubble[key="+name+"]").removeClass("invisible");
						});
					}
					setTimeout(function(){
				    	$(".post-options-bubble[key="+name+"]").addClass("done");
					},100);
				});

			}
			click(url){
				window.open(url, '_blank').focus();
			}
			connectedCallback() {
				try{
				this.json=JSON.parse($(this).text());
				var d=(def(this.json['time'])!="")?timeSince(this.json['time']):"";
				var img=(def(this.json['profile']['picture'])!="")?`<img src=${this.json['profile']['picture']} />`:"<img src='/icon/profile_picture.png'/>";
				var date=(d!="")?`<span style="margin-left:10px">Published: ${d}</span>`:"";
				this.key=$(this).parent().attr("key");
				var vote=(this.json['vote']&&this.json['vote']!=="")?this.json['vote']:0;
				var pointsbar=`<points-bar upvotes=${this.json['upvotes']} downvotes=${this.json['downvotes']} vote=${vote} key=${this.key}></points-bar>`;
				if(def(this.json['profile']['anonymous'])==""){
					img="<a href='"+this.json['profile']['link']+"'>"+img+"</a>";
					var name=(def(this.json['profile']['name'])!="")?`<span class="name">By: <a href='${this.json['profile']['link']}'>${this.json['profile']['name']}</a></span>`:"";
				}else{
					$(this).addClass("anonymous");
					var name=(def(this.json['profile']['name'])!="")?`<span class="name">By: ${this.json['profile']['name']}</span>`:`<span class="name">By: anonymous</span>`;
				}
				this.innerHTML = `
				<p class="name-holder">
					${img}
					${name}${date}
				</p>
				${pointsbar}
				<img class="post-options" key=${this.key} src="/icon/clockwork.png">
		    	`;
				}catch (e) {

			    }
			}
		}
		window.customElements.define("post-top",PostTop);
			class PointsBar extends HTMLElement {
				constructor() {
				  super();
				  this.key;
				}
				static get observedAttributes() {
				  return ['upvotes','downvotes','click'];
				}
				attributeChangedCallback(name, oldVal, newVal) {
					if(name=='upvotes'){
						this.upvotes=parseInt(newVal);
						$(this).find("upvote-button").attr("upvotes",this.upvotes);
						$(this).find(".upvote-count").text(this.upvotes);
					}else if(name=='downvotes'){
						this.downvotes=parseInt(newVal);
						$(this).find("downvote-button").attr("upvotes",this.downvotes);
						$(this).find(".downvote-count").text(this.downvotes);
					}else if(name=='click'){
						if(newVal==1){
							$(this).find('upvote-button').attr('click',1);
							$(this).find('downvote-button').attr('click',0);
						}else if(newVal==-1){
							$(this).find('upvote-button').attr('click',0);
							$(this).find('downvote-button').attr('click',1);
						}else{
							$(this).find('upvote-button').attr('click',0);
							$(this).find('downvote-button').attr('click',0);
						}
					}
				}
				upclick(){
					return (this.vote==1)?1:0;
				}
				downclick(){
					return (this.vote==-1)?1:0;
				}
				connectedCallback() {
						try{
							this.upvotes=parseInt(this.getAttribute("upvotes"));
							this.downvotes=parseInt(this.getAttribute("downvotes"));
							this.vote=(this.getAttribute("vote")=="")?0:this.getAttribute("vote");
							this.key=this.getAttribute("key");
							this.innerHTML=`
							<upvote-button click=${this.upclick()} key=${this.key} upvotes=${this.upvotes}></upvote-button>
						    <p class="upvote-count">${bignumberkiller(this.upvotes)}</p>
						    <downvote-button click=${this.downclick()} key=${this.key} downvotes=${this.downvotes}></downvote-button>
						    <p class="downvote-count">${bignumberkiller(this.downvotes)}</p>
							`;
						}catch (e) {

					    }
					}
			};
			window.customElements.define("points-bar",PointsBar);
				class UpvoteButton extends HTMLElement{
					constructor(){
						super();
						this.upvotes=0;
						this.click=0;
						$(this).on('click', function(){
						    var key=$(this).parent().attr('key');
						    var pointsbar=$(this).parent();
							$.post('/writers/postupcount.php', {key: key}, function(data){
						        var obj = JSON.parse(data);
						        pointsbar.attr('upvotes',obj['up']);
						        pointsbar.attr("downvotes",obj['down']);
						        pointsbar.attr("click",obj['vote']);
							});
						});
					}
					static get observedAttributes() {
					  return ['upvotes','click'];
					}
					attributeChangedCallback(name, oldVal, newVal) {
						if(name=='upvotes')
						this.upvotes=parseInt(this.getAttribute("upvotes"));
						else if(name=='click'){
							if(newVal==1){
								$(this).addClass('clicked');
								this.click=1;
							}else{
								$(this).removeClass('clicked');
								this.click=0;
							}
						}
					}
					connectedCallback() {
						this.upvotes=parseInt(this.getAttribute("upvotes"));
						this.click=this.getAttribute("click");
						this.key=this.getAttribute("key");
						if(this.click==1)
						$(this).addClass('clicked');
					}
				}
				window.customElements.define("upvote-button",UpvoteButton);
				class DownvoteButton extends HTMLElement{
					constructor(){
						super();
						this.downvotes=0;
						this.click=0;
						$(this).on('click', function(){
							var key=$(this).parent().attr('key');
						    var pointsbar=$(this).parent();
							$.post('/writers/postdowncount.php', {key: key}, function(data){
						        var obj = JSON.parse(data);
						        pointsbar.attr('upvotes',obj['up']);
						        pointsbar.attr("downvotes",obj['down']);
						        pointsbar.attr("click",obj['vote']);
							});
						});
					}
					static get observedAttributes() {
					  return ['downvotes','click'];
					}
					attributeChangedCallback(name, oldVal, newVal) {
						if(name=='downvotes')
						this.downvotes=parseInt(this.getAttribute("downvotes"));
						else if(name=='click'){
							if(newVal==1){
								$(this).addClass('clicked');
								this.click=1;
							}else{
								$(this).removeClass('clicked');
								this.click=0;
							}
						}
					}
					connectedCallback() {
						this.downvotes=parseInt(this.getAttribute("downvotes"));
						this.click=this.getAttribute("click");
						this.key=this.getAttribute("key");
						if(this.click==1)
						$(this).addClass('clicked');
					}
				}
				window.customElements.define("downvote-button",DownvoteButton);
		class PostTags extends HTMLElement{
			constructor() {
			  super();
			}
			connectedCallback() {
				try{
					this.json=JSON.parse($(this).text());
					var posts='',people='',topics='';
					if(def(this.json['replyTo'])!=""){
						for(var i=0;i<this.json['replyTo'].length;i++)
							posts+="<a href='/posts/"+this.json['replyTo'][i]+"' >>"+this.json['replyTo'][i]+"</a>"
					}
					if(def(this.json['taggedPeople'])!=""){
						for(var i=0;i<this.json['taggedPeople'].length;i++)
							people+="<a href='/profiles/"+this.json['taggedPeople'][i]+"/posts'>@"+this.json['taggedPeople'][i]+"</a>"
					}
					if(def(this.json['topics'])!=""){
						for(var i=0;i<this.json['topics'].length;i++)
							topics+="<a href='/topics/"+this.json['topics'][i]+"'>#"+this.json['topics'][i]+"</a>"
					}
					this.innerHTML = `${posts}${people}${topics}`;
				}catch (e) {

			    }
			}
		}
		window.customElements.define("post-tags",PostTags);
		class PostBottom extends HTMLElement{
			constructor() {
			  super();
			  this.key=$(this).attr("key");
			  this.comments=$(this).attr("comments");
			  $(this).on('click',".commentor", function() {
			  	if($("post-editor").length==0){
			    var key= $(this).attr('key');
				$.get("/geters/editor.php",function(data){
					if(data!="[]"){
					var dev="";
					if($("body").attr("dev")!=undefined)
					dev=" dev='"+$("body").attr("dev")+"'";
					$("body").append("<div class='editor-shadow'></div>");
					$(".editor-shadow").last().append("<post-editor"+dev+">"+data+"</post-editor>");
					document.querySelector("post-editor").addTag(">"+key);
				}
				});
				}
			});
			var t=this;
				$(this).on('click', '.comments-button', function() {
					//regular-post
					var post=$(this).parent().parent().parent();
					//comment button
					var th=$(this);
					var key=$(this).attr('key');
					if(!th.hasClass("disabled")){
						if(th.hasClass("notclick")){
							//puts regular-post in a wrapper in order to handle multiple comments
							if(!post.parent().hasClass("multi")){
								post.parent().addClass("multi");
								post.addClass("selected");
							}
							//determines the mode of the comment button
							post.find(".comments-button").removeClass("notclick");
							//prevents multi click
							post.find(".comments-button").addClass("disabled");
							//loading image appended before comments are loaded
							post.after('<i class="fas fa-spinner fa-pulse loading"></i>');
							$.post('/geters/commentsload.php',{key: key}, (data)=> {
								post.parent().find("regular-post").each(function(){
									if($(this).isAfter(".selected")&&$(this).attr("key")!=key){
										$(this).find("inner-post").animate({
		                        			opacity:0
		                        		},1000,function(){
		                        			$(this).parent().animate({height:0},1000);
		                        			$(this).parent().remove();
		                        		});

									}
								});
								var json=JSON.parse(data);
								post.find(".comments-button-text").text(json.length+" comment(s)");
								//deletes loading image
								post.parent().find(".loading").remove();
								//if there are posts, do something otherwise, print that there are no comments
								if(json.length>0){
									for(var i=0;i<json.length;i++){
										if($("iframe[type='"+json[i]['type']+"']").length==0){
											var url=(is_url(json[i]['frame']['process']))?json[i]['frame']['process']:"/post-types/"+json[i]['type']+"/"+json[i]['frame']['process'];
											$("body").append("<iframe type='"+json[i]['type']+"' style='display:none;' onload=\"$(this).attr('loaded',true);\" loaded='false' src='"+url+"' allowfullscreen='true' webkitallowfullscreen='true' mozallowfullscreen='true'></iframe>");
										}
										post.after("<regular-post>"+JSON.stringify(json[i])+"</regular-post>");
									}
								}else
								post.parent().append("<p class='no-comments'>no comments.</p>");
								post.parent().find(".selected").removeClass("selected");
								post.addClass("selected");
								if(post.attr("scroll")!=""&&typeof post.attr("scroll")!="undefined"){
									window.scroll.toScroll.push(post.attr("scroll"));
									post.attr("scroll","");
									$("body").trigger("postScroll");
								}
							});
						}else{
							post.parent().find("regular-post").each(function(){
								if($(this).isAfter("regular-post[key="+key+"]"))
								$(this).remove();
							});
							post.parent().find(".no-comments").remove();
							th.addClass("notclick");
							th.addClass("disabled");
							if(post.parent().find("regular-post").length==1)
							post.parent().removeClass("multi")
						}
					}
					setTimeout(function(){post.find(".comments-button").removeClass("disabled")}, 10);
				});
			}
			connectedCallback() {
				try{
					this.key=$(this).attr('key');
					this.innerHTML = `
			    		<!--<div class="addToCollection-button" key=${this.key}>
			    			<img class="icon" src="/icon/album-grey.png"><p class="text">Add to collection</p></div>-->
			    		<div class="comments-button notclick" key=${this.key}>
			    			<img class="icon" src="/icon/Comments.png">
			    			<p class="comments-button-text" key=${this.key}>${bignumberkiller(this.comments)} comment(s)</p>
			    		</div>
			    		<!--<div class="recommend-button" key=${this.key}>
			    			<img class="icon" src="/icon/recommend-grey.png">
			    			<p class="text">recommend</p>
			    		</div>-->
			    		<div class="commentor" key=${this.key}><img class="icon" src="/icon/posts.png">Reply</div>
			    	`;
		    	}catch (e) {

			    }
			}
		}
		window.customElements.define("post-bottom",PostBottom);
class PostShadow extends HTMLElement{
	constructor() {
	  super();
	  this.key;
	  $(this).on("click",function(){
	  	$("back-post[key="+this.key+"]").attr("active","true");
	  	$(this).remove();
	  });
	}

}
window.customElements.define("post-shadow",PostShadow);
//edit button for all posts
$.fn.isAfter = function(sel){
  return this.prevAll(sel).length !== 0;
}
$.fn.isBefore= function(sel){
  return this.nextAll(sel).length !== 0;
}
window.onmessage = (e)=>{
	if($("regular-post[key="+e.data.key+"]").find("iframe").attr("height")==undefined){
		$("regular-post[key="+e.data.key+"]").find("iframe").css("height",e.data.height);
	}
		$("regular-post[key="+e.data.key+"]").find("iframe").css("display","block");
		$("regular-post[key="+e.data.key+"]").find("i").remove();
}
$(document).on("click",function(){
	$(".post-options-bubble.done").remove();
});
