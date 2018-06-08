<?php

namespace Framework\Http;

class HtmlResponse extends Response
{
    public function __construct($html, $status = 200, array $headers = [])
    {
        $headers['content-type'] = 'text/html; charset=utf-8';

        parent::__construct($html, $status, $headers);
    }
}