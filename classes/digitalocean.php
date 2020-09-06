<?php
    include $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';

use Aws\S3\S3Client;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\Filesystem;
    $client = S3Client::factory([
	    'credentials' => [
	        'key' => 'F5LOD6FLQ2OTJY7452HN',
	        'secret' => 'pFn8X0OYO/rqyMNAguzORmc4wQ7YHyc5gDyiybKp+ww'
	    ],
	    'region' => 'nyc3', // Region you selected on time of space creation
	    'endpoint' => 'https://nyc3.digitaloceanspaces.com',
	    'version' => 'latest'
	]);

	// Upload image file to server
  $space='shareon';
	$adapter = new AwsS3Adapter($client, $space);
	$filesystem = new Filesystem($adapter);
	class digitalO{
		public function delete($path){
			global $filesystem;
			return $filesystem->delete($path);
		}
    public function deleteDir($path){
			global $filesystem;
			return $filesystem->deleteDir($path);
		}
    public function has($path){
      global $filesystem;
			return $filesystem->has($path);
    }
    public function listContents($path, $b){
      global $filesystem;
      return $filesystem->listContents($path, $b);
    }
    public function uploadPublic($filename,$tmp_name){
      global $filesystem;
	     global $client;
	  $stream = fopen($tmp_name, 'r+');
      $filesystem->writeStream($filename, $stream, ['visibility' => 'public']);
      $cmd = $client->getCommand('GetObject', [
          'Bucket' => 'shareon',
          'Key'    => $filename
      ]);
      $request = $client->createPresignedRequest($cmd, '+1 minutes');
      $presignedUrl = (string) $request->getUri();
      $l=explode("?X-Amz-Content-Sha256=", $presignedUrl);
      return $l[0];
    }
	}
