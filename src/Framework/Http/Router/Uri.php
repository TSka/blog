<?php

namespace Framework\Http\Router;

use Psr\Http\Message\UriInterface;

class Uri implements UriInterface
{
    public $path = '/';

    public function getScheme() {}

    public function getAuthority() {}

    public function getUserInfo() {}

    public function getHost() {}

    public function getPort() {}

    public function getPath() {
        return $this->path;
    }

    public function getQuery(){}

    public function getFragment(){}

    public function withScheme($scheme){}

    public function withUserInfo($user, $password = null){}

    public function withHost($host){}

    public function withPort($port){}

    public function withPath($path)
    {
        $new = clone $this;
        $new->path = $path;

        return $new;
    }

    public function withQuery($query){}

    public function withFragment($fragment){}

    public function __toString(){}
}