<?php
include '../vendor/autoload.php';

use Aws\S3\S3Client;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\Filesystem;
require '../require.php';
if(isset($_SESSION['uid'])){
$tmp= $_FILES['file']['tmp_name'];
	foreach ($_FILES as $k){
		$oname= new SplFileInfo($k['name']);
		break;
    }
	$destination= '../files/';
	$f= new triagens\ArangoDb\files();
	$f=$f->insertFile($_SESSION['uid'], $oname);
// Upload file to Space
$client = S3Client::factory([
    'credentials' => [
        'key' => '5ZAEBYESDMVCPZXCYF7E',
        'secret' => 'XD3oZi6trXXNMi4l8J5sQzqd5N2SN4Rsnnf4idyjwN4'
    ],
    'region' => 'nyc3', // Region you selected on time of space creation
    'endpoint' => 'https://nyc3.digitaloceanspaces.com',
    'version' => 'latest'
]);

// Upload image file to server
$adapter = new AwsS3Adapter($client, 'shareon');
$filesystem = new Filesystem($adapter);
$imageName = "p.pdf";
$oname = '../files/7405655.pdf';
$stream = fopen($oname, 'r+');
$filesystem->writeStream($imageName, $stream, ['visibility' => 'public']);
$cmd = $client->getCommand('GetObject', [
    'Bucket' => 'shareon',
    'Key'    => $imageName
]);

$request = $client->createPresignedRequest($cmd, '+1 minutes');
$presignedUrl = (string) $request->getUri();
$l=explode("?X-Amz-Content-Sha256=", $presignedUrl);
echo $l[0];
	
	echo $web.'files/'.$f[0]['_key'].'.'.$oname->getExtension();
}
