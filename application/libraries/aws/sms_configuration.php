<?php



//require 'vendor/autoload.php';

require_once __DIR__ . '/vendor/autoload.php';

$s3Client = new Aws\Sns\SnsClient([
    'region'  => 'us-east-1',
    'version' => 'latest',
    'credentials' => ['key' => '', 'secret' => '']
  ]);



?>
