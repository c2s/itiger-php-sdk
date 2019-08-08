<?php
include '../vendor/autoload.php';

use Tiger\SDK\TigerApi;
use Tiger\SDK\PublicApi\Time;

// Set the base uri, default "https://api.Tiger.com" for production environment.
// TigerApi::setBaseUri('https://api.Tiger.com');

$api = new Time();
$timestamp = $api->timestamp();
var_dump($timestamp);
