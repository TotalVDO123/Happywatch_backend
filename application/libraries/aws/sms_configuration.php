<?php
/*
require 'vendor/autoload.php';




use Aws\Sns\SnsClient;
use Aws\Exception\AwsException;

$s3Client = new SnsClient([
    'region' => 'us-east-1',
    'version' => 'latest',
    'credentials' => [
        'key'    => 'AKIAXRJJLDYGLQGF7JU3',
        'secret' => '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',
    ],
]);

*/


//require 'vendor/autoload.php';

require_once __DIR__ . '/vendor/autoload.php';

$s3Client = new Aws\Sns\SnsClient([
    'region'  => 'us-east-1',
    'version' => 'latest',
    'credentials' => ['key' => 'AKIAXRJJLDYGLQGF7JU3', 'secret' => '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']
  ]);



?>