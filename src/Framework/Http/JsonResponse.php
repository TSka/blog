<?php

namespace Framework\Http;

class JsonResponse extends Response
{
    public function __construct($data, $status = 200, array $headers = [])
    {
        $body = json_encode($data, JSON_PRETTY_PRINT);
        $headers['content-type'] = 'application/json';

        parent::__construct($body, $status, $headers);
    }
}