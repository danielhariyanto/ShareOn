function compute(post,points){
	var object={};
	if(post.hasOwnProperty("topics")){
		for(var i=0;i<post['topics'].length;i++)
				object[post['topics'][i]]=points;
	}
	return object;
}
/*SHAREON::TOPTRENDS::COMPUTE*/
function assemble(arr,order){
	var obj={};
	for(var i=0;i<arr.length;i++){
		for(var ind in arr[i]){
		if(obj.hasOwnProperty(ind))
		obj[ind]+=arr[i][ind];
		else
		obj[ind]=arr[i][ind];
		}
	}
	var LL={
		"head":null,
		"add":function(node){
			if(this.head==null){
				this.head=node;
				return ;
			}
			var temp=this.head;
			var prev=null;
			while(temp!=null){
				if(this.compare(node,temp)*this.order==1){
					console.log(999);
					if(prev!=null)
					prev.next=node;
					if(temp==this.head)
					this.head=node;
					node.next=temp;
					return ;
				}else{
					prev=temp;
					temp=temp.next;
				}
			}
				if(prev!=null)
				prev.next=node;
				return ;
		},
		"compare":function(node1,node2){
			if(Object.values(node1.node)[0]>Object.values(node2.node)[0])
			return 1;
			else
			return -1;
		},
		"order":(order.toUpperCase()=="DESC")?-1:1
	};
	for(var ind in obj){
		var o={};
		o[ind]=obj[ind];
		var node={"node":o,"next":null};
		LL.add(node);
	}
	arr=[];
	var n=LL.head;
	var i=0;
	while(n!=null){
		arr[i]=n.node;
		i++;
		n=n.next;
	}
	return arr;
}

/*SHAREON::TOPTRENDS::ASSEMBLE*/