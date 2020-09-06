class Editor extends HTMLElement {
	constructor() {
		super();
		this.type = "";
		this.url = "/post-types/";
		this.edit = false;
		this.dev = false;
		this.direct=false;
		var t = this;
		var qt = $(this);
		$(this).on('click', "#close-editor-button", function () {
			$(this).parent().parent().remove();
			$(".post").removeClass('comment-highlighter');
			$(".comment").removeClass('comment-highlighter');
		});
		$(this).on("click", "#submit-post", function (e) {
			e.preventDefault();
			var f = $("#tags-editor input").val().charAt(0);//first character of tag in process of being added
			if (f == "#" || f == ">" || f == "@")
				document.querySelector("tag-editor").addTag($("#tags-editor input").val());
			if (!t.edit) {
				t.submit(qt.parent());
			} else {
				t.update(qt.parent());
			}
		});
	}
	submit(toclose) {
		var t=this;
		$.ajax({
			url: '/postupload.php',
			type: 'post',
			data: this.data(),
			success: function (d) {
				if(t.direct){
					document.querySelector("type-display").post(d);
				}else{
					toclose.trigger("click");//remove post editor and reset it
					document.querySelector("main-container").insertPost(d);
				}
			},
			error: function (e) {
				alert(e);
			}
		});
	}
	update(toclose) {
		$.ajax({
			url: '/postupdate.php',
			type: 'post',
			data: this.data(),
			success: function (d) {
				toclose.trigger("click");
				var ob = JSON.parse(d);
				document.querySelector('regular-post[key="' + ob['_key'] + '"]').update(d);
			},
			error: function (e) {
				alert(e);
			}
		});
	}
	connectedCallback() {
		try {
			var bubble="";
			if (typeof $(this).attr("edit") != "undefined") {
				this.edit = parseInt($(this).attr("edit"));
			}else if(!(typeof $(this).attr("dev") != "undefined")&&!(typeof $(this).attr("edit") != "undefined")){
				bubble = "<bubble-container>"+$(this).text()+"</bubble-container>";
			}
			if (typeof $(this).attr("dev") != "undefined") {
				this.dev = parseInt($(this).attr("dev"));
			}
			//bubbles+=`<post-bubble id="seventh-posts-bubble"><div class="bubble-title"><div>modifier</div></div>
			//<img src="/icon/modifier.png"/></post-bubble>`;
			this.innerHTML = `${bubble}
    	<img id="close-editor-button" src="/icon/redx.png"/>
		<editor-iframe></editor-iframe>
		<tag-editor></tag-editor>
    <div id="post-editor-bottom">
      <select name="view_option" id="view-option">
				<option value="global">Global</option>
				<option value="personal">Personal</option>
				<option value="comment" class="invisible">Comment</option>
      </select>
      <select id="age-restriction" value="f" name="age_restriction">
        <option value="f" selected>Family friendly</option>
        <option value="t">Teenagers and above</option>
        <option value="m">Mature content</option>
      </select>
			<!--
      <label id="anonymous-checkbox">
        <input type="checkbox">
        <span class="text">Anonymous</span>
      </label>
-->

      <button id="submit-post">${(typeof $(this).attr("direct") != "undefined")?"Test":"Share On"}</button>
      </div>`;
			if(typeof $(this).attr("direct") != "undefined"){
				$(this).find("editor-iframe").attr("src", $(this).attr("direct"));
				this.direct=true;
			}
			if(typeof $(this).attr("type") != "undefined"){
				this.type=$(this).attr("type");
			}
			if($(this).attr("dev")!=undefined){
				this.type=$(this).attr("dev");
				this.dev=true;
				var edit=$("#dev-settings").find("input[name=edit]").val();
				if (is_url(edit)){
					$("editor-iframe").attr("src", edit);
				}else{
					$("editor-iframe").attr("src", "/post-types/"+this.type + "/" + edit);
				}
			}
			if(typeof $(this).attr("edit") != "undefined"){
				var j=document.querySelector("regular-post[key='"+this.edit+"']").json;
				this.type=j.type;
				$("editor-iframe iframe").attr("onload","this.contentWindow.postMessage("+JSON.stringify(j)+",\"*\");resizeIframe(this);");
				if (is_url(j.frame.edit))
					$("editor-iframe").attr("src", j.frame.edit);
				else
					$("editor-iframe").attr("src", "/post-types/"+j.frame.type + "/" + j.frame.edit);
			}
		} catch (e) {

		}

	}
	addTag(tag) {
		this.querySelector("tag-editor").addTag(tag);
		if ($("#view-option").find("option[value=comment]").hasClass("invisible") && tag.charAt(0) == ">") {
			$("#view-option").find("option[value=comment]").removeClass("invisible")
			$("#view-option").val("comment");
		}
	}

	data(){
		var data={};
		data['portrait']=($(this).find('editor-iframe iframe').contents().find('#portrait').length>0)?$(this).find('editor-iframe iframe').contents().find('#portrait').attr('content'):'';
		data['content']=JSON.parse($(this).find('editor-iframe iframe').contents().find('#content').attr('content'));
		if(this.dev)
        data['view_option']="dev";
        else if(this.direct)
        data['view_option']="temp";
        else
		data['view_option']=$(this).find('#view-option').val();
		data['age_restriction']=$(this).find('#age-restriction').val();
		data['type']=this.type;
        data['links']=$('#editor-container').contents().find('#links').attr('content');

        if(this.edit)
        data['key']=this.edit;
        var tags=this.querySelector("tag-editor").tags;
        data['topics']=[];
	    data['replyTo']=[];
	    data['taggedPeople']=[];
	    for(var i=0;i<tags.length;i++){
	    	if(tags[i][0]==='#')
	    	data['topics'].push(tags[i].substr(1));
	    	else if(tags[i][0]==='>')
	    	data['replyTo'].push(tags[i].substr(1));
	    	else if(tags[i][0]==='@')
	    	data['taggedPeople'].push(tags[i].substr(1));
	    }
        return data;
	}
}
window.customElements.define("post-editor", Editor);
class BubbleContainer extends HTMLElement {
	constructor() {
		super();
	}
	connectedCallback() {
		var t = this;
		var json = JSON.parse($(this).text());
		var bubbles = "<div id='sort'>";
		for (var i = 0; i < json.length; i++)
			bubbles += `<post-bubble key="${json[i]['_key']}">${JSON.stringify(json[i])}</post-bubble>`;
		if (typeof $(this).attr("edit") != "undefined") {
			var edit = $(this).attr("edit");
					json['edit'] += "?key=" + edit;
		} else {
				bubbles += "</div><options-bubble></options-bubble><additional-bubble></additional-bubble>";
		}
			t.innerHTML = bubbles;
			$("post-bubble").first().trigger("click");
	}
}
window.customElements.define("bubble-container", BubbleContainer);
class PostBubble extends HTMLElement {
	constructor() {
		super();
		this.pre = "/post-types/";
		this.type = "";
		this.url = '';
		this.enabled = true;
		$(this).on("click", function () {
			if (this.url != "" && this.enabled) {
				document.querySelector("post-editor").type = this.type;
				$("post-bubble").removeClass("selected");
				$(this).addClass("selected");
				if (is_url(this.url))
					$("editor-iframe").attr("src", this.url);
				else
					$("editor-iframe").attr("src", this.pre + this.type + "/" + this.url);
			}
		});
		$(this).on("click", ".x", function () {
			$(this).parent().remove();
		});
	}
	connectedCallback() {
		var json = JSON.parse($(this).text());
		this.type = json['_key'];
		this.url = json['edit'];
		this.innerHTML = `<div class="bubble-title"><div>${json["name"]}</div></div>
	  				<img src=${this.pre + this.type + "/icon.png"} /><div class='x'>x</div>`;
	}
	static get observedAttributes() {
		return ['class'];
	}
	smoothSelect() {
		this.enabled = false;
		$(this).addClass("selected");
		this.enabled = true;
	}
	attributeChangedCallback(name, oldVal, newVal) {
		if (this.enabled&&$(this).hasClass("selected")) {
				this.parentElement.parentElement.type = this.type;
		}
		if ($(this).hasClass("ui-sortable-handle")) {
			$(this).find(".x").css("display", "block");
			this.enabled = false;
		} else {
			$(this).find(".x").css("display", "none");
			this.enabled = true;
		}
	}
}
window.customElements.define("post-bubble", PostBubble);
class OptionsBubble extends HTMLElement {
	constructor() {
		super();
		this.pre = "/";
		this.clicked = false;
		$(this).on("click", function () {
			if (this.clicked) {//if this is clicked on
				this.click(false);
				var arr = [];
				var b = document.querySelectorAll("post-bubble");
				var session=JSON.parse(sessionStorage.bubbles);
				var finsession=[];
				for (var i = 0; i < b.length; i++){
					arr.push(b[i].type);
					var j=0;
					var found=false;
					while(j<session.length&&!found){
						if(session[j]['_key']==b[i].type){
							finsession.push(session[j]);
							session.splice(j, 1);
							found=true;
						}else{
							j++;
						}
					}
				}
				finsession=JSON.stringify(finsession);
				sessionStorage.bubbles=finsession;
				var data = { "bubbles": arr };
				$.post(this.pre + "writers/saveUserBubbles.php", data, function (d) {
				});
			} else {
				this.click(true);
			}
		});
	}
	click(bool) {
		if (bool) {
			this.clicked = true;
			document.querySelector("additional-bubble").click(true);
			$(this).find("img").attr("src", this.pre + "icon/v.png");
			$("#sort").sortable();
			$(this).find(".bubble-title div").text("save changes");
		} else {
			$("#sort").sortable("destroy");
			this.clicked = false;
			document.querySelector("additional-bubble").click(false);
			$(this).find("img").attr("src", this.pre + "icon/modifier.png");
			$(this).find(".bubble-title div").text("modify your bubbles");
		}
	}
	connectedCallback() {
		this.innerHTML = `<div class="bubble-title"><div>modify your bubbles</div></div>
	  				<img src=${this.pre + "icon/modifier.png"} />`;
	}
}
window.customElements.define("options-bubble", OptionsBubble);
class AdditionalBubble extends HTMLElement {
	constructor() {
		super();
		this.pre = "/";
		this.clicked = false;
		$(this).on("click", function () {
			if (this.clicked) {
				document.querySelector("options-bubble").click(false);
				this.click(false);
					$("post-bubble").remove();
					var json = JSON.parse(sessionStorage.bubbles);
					var bubbles = "<div id='sort'>";
					for (var i = 0; i < json.length; i++)
						$("#sort").append(`<post-bubble key="${json[i]['_key']}">${JSON.stringify(json[i])}</post-bubble>`);
					var key = document.querySelector("editor-iframe").type;
					document.querySelector("post-bubble[key='" + key + "']").smoothSelect();
			} else {
				$("body").append("<div class='editor-shadow'></div>");
				$(".editor-shadow").last().append('<post-store></post-store>');
				//$("#ptsbody").append("<div id='post-type-views'><div class='tab'>Most Popular</div></div>"); // this can be done with less code...

				// Details section as a method. move later as necessary to consolidate
				// Decided to do this to give Daniel more space to work, improving modularity
				//$("#ptsbody").append("<div id='MARKER'></div>");
				//replaceMarkerWithDetails('MARKER');
			}
		});
	}
	click(bool) {
		if (bool) {
			this.clicked = true;
			$(this).find("img").attr("src", this.pre + "icon/x.png");
			$(this).find(".bubble-title div").text("cancel");
		} else {
			this.clicked = false
			$(this).find("img").attr("src", this.pre + "icon/store-white.png");
			$(this).find(".bubble-title div").text("post type store");
		}
	}
	connectedCallback() {
		this.innerHTML = `<div class="bubble-title"><div>post type store</div></div>
	  				<img src=${this.pre + "icon/store-white.png"} />`;
	}
}
window.customElements.define("additional-bubble", AdditionalBubble);
class IframeEditor extends HTMLElement {
	constructor() {
		super();
		this.type;
	}
	connectedCallback() {
		this.innerHTML = `<iframe scrolling="no" allowfullscreen="true" webkitallowfullscreen="true" mozallowfullscreen="true"
		onload='resizeIframe(this);'></iframe>`;
		if($(this).attr("src")!=undefined){
			var s = $(this).attr("src").split('/');
			this.type = s[s.length - 2];
			$(this).find("iframe").attr("src", $(this).attr("src"));
		}
	}
	static get observedAttributes() {
		return ['src'];
	}
	attributeChangedCallback(name, oldVal, newVal) {
		var s = newVal.split('/');
		this.type = s[s.length - 2];
		$(this).find("iframe").attr("src", newVal);
	}
}
window.customElements.define("editor-iframe", IframeEditor);
class TagEditor extends HTMLElement {
	constructor() {
		super();
		this.tags = [];
		var t = this;
		$(document).on('click', '.tag-remover', function () {
			var d = $(this).parent().text().slice(0, -1);
			$(this).parent().remove();
			const index = t.tags.indexOf(d);
			if (index > -1) {
				t.tags.splice(index, 1);
			}
		});
		$(this).on("click", "#tags-editor .tag", function () {
			var s = $(this).text();
			$(this).parent().parent().find("#tags-editor .tag").remove();
			if (t.addTag(s))
				$(this).parent().parent().find("#tags-editor input").val('');
		});
		$(this).on('keydown', 'input', function (event) {
			var keycode = (event.keyCode ? event.keyCode : event.which);
			if (keycode == '13') {
				if (t.addTag($(this).val()))
					$(this).val('');
			} else {
				var th = $(this);
				setTimeout(()=> {
					var s = th.val();
					var l=s.charAt(s.length-1);//last character of user input
					if(l=="#"||l==">"||l=="@"){
							t.addTag(s.substring(0,s.length-1));
							$(this).val(l);
					}else {
						$.post('/geters/search.php', { search: s, bubbles: 1 }, (d)=> {
							$(this).parent().parent().find("#tags-editor .tag").remove();
							var j = JSON.parse(d);
							for (var i = 0; i < j.length; i++) {
								s = j[i][1];
								if(s!="*"){
									s += j[i][0]['name'];
									if (!t.tags.includes(s)){
										var img="";
										var c="picture";
										if(j[i][1] =='>'){//post key
											c="portrait";
											img=j[i][0]['portrait'];//object, picture of post
											link+="posts/"+j[i][0]['_key'];//direct to post and comments
										}else if(j[i][1] =='#'){//post type
											img=(j[i][0]['image']!=undefined&&j[i][0]['image']!="")?j[i][0]['image']:"/icon/topic.jfif";
										}else if(j[i][1]=='@'){//user
											img=(j[i][0]['profile_picture']!=undefined&&j[i][0]['profile_picture']!="")?j[i][0]['profile_picture']:"/icon/profile_picture.png";
										}
										if(img!="")
										$(this).parent().parent().find("#tags-editor").append(`<div class='tag'><img class='${c}' src='${img}'/><span>${s}</span></div>`);
										if(i<j.length-1)
										$(this).parent().parent().find("#tags-editor").append('<hr></hr>');
									}
								}
							}
						});
					}
				}, 1);
			}
		});
	}
	connectedCallback() {
		this.innerHTML = `<div id="tags-container" tags='[]' class="tags-container"></div>
			<div id="tags-editor"><input type="text" placeholder='Begin tag with @, #, or >'/></div>`;
	}
	addTag(tag) {
		if (tag.length > 0) {
			if ((tag.charAt(0) == "#" || tag.charAt(0) == ">" || tag.charAt(0) == "@") && !this.tags.includes(tag)) {
				$(this).parent().parent().find("#tags-container").append('<div class="tag">' + tag + '<span class="tag-remover">x</span>' + '</div>');
				this.tags.push(tag);
				return true;
			} else if (this.tags.includes(tag)) {
				return false;
			} else {
				alert("Make sure you use #, >, or @");
				return false;
			}
		}
	}
}
window.customElements.define("tag-editor", TagEditor);


$(document).on('click', '.editor-shadow', function (data, handler) {
	if (data.target == this) {
		$(this).remove();
	}
});
