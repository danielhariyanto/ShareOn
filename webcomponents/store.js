class PostStore extends HTMLElement {
	constructor() {
		super();
		this.pre = "/";
		this.json={};
		$(this).on("keydown","#post-type-store-search-bar input",()=>{
			setTimeout(()=>{
				var p = this.pre;
				var val=$("#post-type-store-search-bar input").val();
				var url = p + "geters/post-type_load.php";
				$.post(url,{search:val},(d)=>{
					this.json=JSON.parse(d);
					this.load();
				})
			},10);
		});
		$(this).on("click","header .x",()=>{
			$(".editor-shadow").last().trigger("click");
		});
	}
	connectedCallback() {
			this.innerHTML = `<header><img src="${this.pre}icon/store-white.png"/><div>Post Type Store</div><span class="x">x</span></header>
			<a href="/tool_bubble/bubbles_dashboard.php" target="_blank" class='btn' id="create">create new</a>
				<div id='post-type-store-search-bar'>
				<input placeholder='Search a post type' type='search'>
				</div>
				<div id="post-type-store-body"></div>
			`;
			var p = this.pre;
	    var url = p + "geters/post-type_load.php";
			$.get(url, (d)=> {
	      this.json = JSON.parse(d);
				this.load();
	    });
	}
  load(){
		$(this).find(".row").remove();
		var b = "";
		for (var i = 0; i < this.json.length; i++){
			if(i%6==0)
			b+="<div class='row'>";
			b += "<store-bubble>"+JSON.stringify(this.json[i])+"</store-bubble>";
			if(i%6==5)
			b+="</div>";
		}
			$("#post-type-store-body").append(b);
  }
}
window.customElements.define("post-store", PostStore);
class StoreBubble extends HTMLElement {
	constructor() {
		super();
		this.json={};
		var t=this;
		$(this).on("click", function () {
			if($("type-display").length>0){
				$("type-display").remove();
			}
			var b=false;
			if(!$(this).hasClass("selected")){
				$(this).parent().append("<type-display>"+JSON.stringify(t.json)+"</type-display>");
				$("store-bubble").removeClass("selected");
				$(this).addClass("selected");
				b=true;
			}
			if(!b)
			$("store-bubble").removeClass("selected");
		});
	}
	connectedCallback() {
		this.json=JSON.parse($(this).text());
		this.innerHTML=`<img src='/post-types/${this.json["_key"]}/icon.png'/><figcaption>${this.json['name']}</figcaption>`;
	}
}
window.customElements.define("store-bubble", StoreBubble);
class TypeDisplay extends HTMLElement {
	constructor() {
		super();
		this.json={};
		var t=this;
		$(this).on("click",".adder",function(){
      var key = t.json._key;
			$.post("/writers/choosePostType.php", { key: key }, function (d) {
				$(".editor-shadow").last().trigger("click");
						$("#sort").append(`<post-bubble key=${key}>${JSON.stringify(t.json)}</post-bubble>`);
					var key = document.querySelector("editor-iframe").type;
					document.querySelector("post-bubble[key='" + key + "']").smoothSelect();
				});
    });
	}
	connectedCallback() {
		this.json=JSON.parse($(this).text());
		var eload=(is_url(this.json['edit']))?this.json['edit']:"/post-types/"+this.json['_key']+"/"+this.json['edit'];
		this.innerHTML = `<div class='top'><p class="name-holder">
			<a href=${this.json['profile']['link']}><img src=${(this.json['profile']['picture']!=undefined&&this.json['profile']['picture']!="")?this.json['profile']['picture']:"/icon/profile_picture.png"} /></a>
			<span>Created by: <a href=${this.json['profile']['link']} class="name">${this.json['profile']['name']}</a></span>
			<span style="margin-left:10px">Published: ${(def(this.json['published'])!="")?timeSince(this.json['published']):""}</span>
			</p><button class="adder">add</button></div><div class="description">${(this.json.description!=undefined)?this.json.description:"no description."}</div><post-editor type=${this.json['_key']} direct=${eload}></post-editor>`;
	}
	post(d){
		$(this).find("post-editor").css("display","none");
		$(this).append("<dummy-post>"+d+"</dummy-post>");
	}
}
window.customElements.define("type-display", TypeDisplay);
class DummyPost extends HTMLElement {
	constructor() {
		super();
	}
	connectedCallback() {
		var json=JSON.parse($(this).text());
		var pload=(is_url(json['frame']['process']))?json['frame']['process']+"?key="+json['_key']:"/post-types/"+json['type']+"/"+json['frame']['process']+"?key="+json['_key'];
		var scroll=(json['frame']['scroll'])?"yes":"no";
			var size=(json['frame']['size']==0)?"":"height='"+json['frame']['size']+"'";
		this.innerHTML =`<iframe onload='this.contentWindow.postMessage(${$(this).text()},"*");' key=${this.key} type=${json['type']} scrolling=${scroll} ${size} src=${pload}
		allowfullscreen="true" webkitallowfullscreen="true" mozallowfullscreen="true"></iframe>`;
	}
}
window.customElements.define("dummy-post", DummyPost);
