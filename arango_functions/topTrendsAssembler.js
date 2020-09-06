function assembler(arr){
	var obj={};
	for(var i=0;i<arr.length;i++){
		for(var ind in arr[i]){
		if(obj.hasOwnProperty(ind))
		obj[ind]+=arr[i][ind];
		else
		obj[ind]=arr[i][ind];
		}
	}
	return obj;
}
