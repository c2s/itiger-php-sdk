<?php

namespace Tiger\SDK\Exceptions;

class NoAvailableWebSocketServerException extends \Exception
{
    protected $message = 'No available websocket server';
}