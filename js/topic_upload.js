var file=$("file input here").prop("files")[0];
var fd = new FormData();
fd.append("file", file);
fd.append("key",post key here);
var xhr = new XMLHttpRequest();
xhr.open('POST', '/writers/uploadtopicpicture.php', true);
xhr.upload.onprogress = function(e) {
if (e.lengthComputable) {
var percentComplete = (e.loaded / e.total) * 100;
console.log(percentComplete + '% uploaded');
}
};
xhr.onload = function() {
if (this.status == 200) {
	this.reponse//the data from the file
	//handle stuff here
}
};
xhr.send(fd);
