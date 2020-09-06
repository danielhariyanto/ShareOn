class infoContainer extends HTMLElement{
	constructor(){
		super();
		var t=this;
		$(this).on("change","order-select",function(){
			var th=$(this).parent();
			th.find(".listedBlock").remove();
			var order=th.find("order-select").attr("order")+"-"+th.find("order-select").attr("flipped");
			t.loader(order);
		});
	}
	connectedCallback() {
	    this.innerHTML = `
	    <div id="topBar">
            <span>TRENDS</span>
        </div>
        <div id="inside">
            <order-select></order-select>
        </div>`;
        var order=$(this).find("order-select").attr("order")+"-"+$(this).find("order-select").attr("flipped");
        this.loader(order);
	}
	loader(order){
		var o=$(this).find("order-select").attr("order");
		var n=o;
		if(o=="hot")
		n="Ranking";
		else if(o=="time")
		n=""
		$.post( "/geters/topTrends.php",{order:order}, function (data) {
			data=JSON.parse(data);
		    var size=(data.length>6)?6:data.length;
		    for(var i=0; i<size;i++) {
		    	var c=bignumberkiller(Math.round(data[i]["points"]*100)/100);
		    	if(o=="time")
		    	c=timeSince(data[i]["points"]);
		        $("#inside").append("<div class= 'listedBlock'><a href=\"/topics/"+data[i]["name"]+"\"> <img class='floatLeftPicture' src='"+((data[i]['image']!=undefined&&data[i]['image']!="")?data[i]['image']:"/icon/topic.jfif")+"' height='30px' width='30px'/>" +
		            "<span class='title'>" +data[i]["name"]+ "</span>" +
		            "<br><div class= 'lowerContainer'><span class='numbers'>" + c + "</span>" +
		            "<img src='/icon/"+((o.charAt(o.length()-1)=="s")?o.substring(0,o.length()-1):o)+".png' height='10px'/><span class=\"lowerText\"> "+n+"</span></div></a></div>");
		    }
		});
	}
}
window.customElements.define("info-box",infoContainer);
