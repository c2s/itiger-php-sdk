<?php

namespace Tiger\SDK;

interface IAuth
{
    public function signature($params, $bizContent, $timestamp, $version);

    public function getBody(array $params);
}