const template = document.createElement('template');
template.innerHTML = `
<style>
	select{
		font-size:9pt;height: 25px; border-radius:2px;color:rgb(15,75,169);font-weight:bold;text-align:center;border:1px solid rgb(15,75,169);
	}
	option{
		font-size:9pt;color:rgb(15,75,169);
	}
	img{height:24px;background:white; margin-left:2px;margin-bottom:-7.25px;border-radius:2px;cursor:pointer}
	img:hover{background:rgb(228.33,235,245.44)}
	img:active{background:rgb(195,210,233.5)}
</style>
<select>
<option value="hot">HOT</option>
<option value="time">Newest</option>
<option value="points">Top</option>
<option value="upvotes">Most Liked</option>
<option value="downvotes">Most Disliked</option>
<option value="comments">Most Commented</option>
</select><img src='/icon/DESC.png'/>`;
class OrderSelect extends HTMLElement{
	constructor(){
		super();
		this.attachShadow({mode: 'open'});
		var th=$(this);
	    const shadow=this.shadowRoot;
	    this.s=shadow;
	    shadow.appendChild(template.content.cloneNode(true));
	    shadow.querySelector("select").onchange=function(){
	    	th.attr("order",shadow.querySelector("select").value);
	    	th.trigger("change");
	    };
	    shadow.querySelector("img").onclick=function(){
	    	if(th.attr("flipped")==0){
				shadow.querySelector("option[value=time]").innerHTML="Oldest";
				shadow.querySelector("option[value=points]").innerHTML="Bottom";
				shadow.querySelector("option[value=upvotes]").innerHTML="Least Liked";
				shadow.querySelector("option[value=downvotes]").innerHTML="Least Disliked";
				shadow.querySelector("option[value=comments]").innerHTML="Least Commented";
				shadow.querySelector("option[value=hot]").innerHTML="Dull";
				th.attr("flipped","1");
			}else{
				shadow.querySelector("option[value=time]").innerHTML="Newest";
				shadow.querySelector("option[value=points]").innerHTML="Top";
				shadow.querySelector("option[value=upvotes]").innerHTML="Most Liked";
				shadow.querySelector("option[value=downvotes]").innerHTML="Most Disliked";
				shadow.querySelector("option[value=comments]").innerHTML="Most Commented";
				shadow.querySelector("option[value=hot]").innerHTML="HOT";
				th.attr("flipped","0");
			}
			th.trigger("change");
	    };
	}
	connectedCallback() {
		if($(this).attr("flipped")==1){
			this.s.querySelector("option[value=time]").innerHTML="Oldest";
			this.s.querySelector("option[value=points]").innerHTML="Bottom";
			this.s.querySelector("option[value=upvotes]").innerHTML="Least Liked";
			this.s.querySelector("option[value=downvotes]").innerHTML="Least Disliked";
			this.s.querySelector("option[value=comments]").innerHTML="Least Commented";
			this.s.querySelector("option[value=hot]").innerHTML="Dull";
		}else
			$(this).attr("flipped",0);
		if($(this).attr("order")=="time"||$(this).attr("order")=="points"||$(this).attr("order")=="upvotes"||$(this).attr("order")=="downvotes"||$(this).attr("order")=="comments")
			this.s.querySelector("select").value=$(this).attr("order");
		else
			$(this).attr("order","hot");
	}
}
window.customElements.define("order-select",OrderSelect);
