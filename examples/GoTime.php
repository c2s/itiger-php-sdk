<?php
include '../vendor/autoload.php';

use Tiger\SDK\Http\SwooleHttp;
use Tiger\SDK\TigerApi;
use Tiger\SDK\PublicApi\Time;

// Set the base uri, default "https://api.Tiger.com" for production environment.
// TigerApi::setBaseUri('https://api.Tiger.com');

// Require PHP 7.1+ and Swoole 2.1.2+
// Require running in cli mode
go(function () {
    $api = new Time(null, new SwooleHttp);
    $timestamp = $api->timestamp();
    var_dump($timestamp);
});