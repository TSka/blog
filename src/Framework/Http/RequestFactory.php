<?php

namespace Framework\Http;

use Framework\Http\Router\Uri;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;

class RequestFactory
{
    public static function fromGlobals(
        $server = null,
        array $query = null,
        array $body = null
    ): ServerRequestInterface {
        $server = $server ?: $_SERVER;
        return new Request(
            $server,
            self::uriFromServer($server),
            $server['REQUEST_METHOD'] ?? 'GET',
            $query ?: $_GET,
            $body ?: $_POST
        );
    }

    public static function uriFromServer(array $server): UriInterface
    {
        $path = $server['REQUEST_URI'] ?? '/';

        if (($qpos = strpos($path, '?')) !== false) {
            $path = substr($path, 0, $qpos);
        }

        return (new Uri())->withPath($path);
    }
}