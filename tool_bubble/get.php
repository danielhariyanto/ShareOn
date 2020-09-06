<?php
session_start();
require '../classes/digitalocean.php';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://$space.nyc3.digitaloceanspaces.com/".$_GET['key']."/".$_GET['file']);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$c = curl_exec($ch);
$the_content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
curl_close($ch);
header("Content-Type: $the_content_type;");
if($the_content_type=="text/html"){
$c=explode("</body>",$c);
$c=$c[0]."<script>
var ShareOn_key='';
window.addEventListener('message', (e)=>{
  ShareOn_key=e.data._key;
  var body = document.body,
    html = document.documentElement;

var height = Math.max( body.scrollHeight, body.offsetHeight,
                       html.clientHeight, html.scrollHeight, html.offsetHeight );
  window.top.postMessage({key:ShareOn_key,height:height}, '*');
}, true);
const myObserver = new ResizeObserver(entry => {
    if(ShareOn_key!=''){
    window.top.postMessage({key:ShareOn_key,height:entry[0].contentRect.height}, '*');
  }
});
myObserver.observe(document.querySelector('html'));
</script></body>".$c[1];
}
echo $c;
?>
