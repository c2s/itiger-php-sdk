<?php
include '../vendor/autoload.php';

use Tiger\SDK\Auth;
use Tiger\SDK\TigerApi;
use Tiger\SDK\PrivateApi\Account;
use Tiger\SDK\Exceptions\HttpException;
use Tiger\SDK\Exceptions\BusinessException;

// Set the base uri, default "https://api.Tiger.com" for production environment.
// TigerApi::setBaseUri('https://api.Tiger.com');

$auth = new Auth('key', 'secret', 'passphrase');
$api = new Account($auth);

try {
    $result = $api->getOverview();
    var_dump($result);
} catch (HttpException $e) {
    var_dump($e->getMessage());
} catch (BusinessException $e) {
    var_dump($e->getMessage());
}
