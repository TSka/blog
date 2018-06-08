<?php

namespace Framework\Http;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

class Request implements ServerRequestInterface
{
    private $serverParams;
    private $queryParams;
    private $parsedBody;
    private $method;
    private $attributes = [];
    private $uri;

    public function __construct(
        array $serverParams = [],
        $uri = null,
        $method = null,
        array $queryParams = [],
        $parsedBody = null
    ) {
        $this->serverParams  = $serverParams;
        $this->uri = $uri;
        $this->method = $method;
        $this->queryParams = $queryParams;
        $this->parsedBody = $parsedBody;
    }

    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    public function withQueryParams(array $query): self
    {
        $new = clone $this;
        $new->queryParams = $query;
        return $new;
    }

    public function getParsedBody()
    {
        return $this->parsedBody;
    }

    public function withParsedBody($data): self
    {
        $new = clone $this;
        $new->parsedBody = $data;
        return $new;
    }

    public function getProtocolVersion() {}
    public function withProtocolVersion($version) {}
    public function getHeaders() {}
    public function hasHeader($name) {}
    public function getHeader($name) {}
    public function getHeaderLine($name) {}
    public function withHeader($name, $value) {}
    public function withAddedHeader($name, $value) {}
    public function withoutHeader($name) {}
    public function getBody() {}
    public function withBody(StreamInterface $body) {}
    public function getRequestTarget() {}
    public function withRequestTarget($requestTarget) {}
    public function getMethod()
    {
        return $this->method;
    }
    public function withMethod($method) {}
    public function getUri()
    {
        return $this->uri;
    }
    public function withUri(UriInterface $uri, $preserveHost = false) {}
    public function getServerParams() {}
    public function getCookieParams() {}
    public function withCookieParams(array $cookies) {}
    public function getUploadedFiles() {}
    public function withUploadedFiles(array $uploadedFiles) {}

    public function getAttributes() {
        return $this->attributes;
    }

    public function getAttribute($name, $default = null)
    {
        if(!array_key_exists($name, $this->attributes)) {
            return $default;
        }
        return $this->attributes[$name];
    }

    public function withAttribute($name, $value)
    {
        $new = clone $this;
        $new->attributes[$name] = $value;
        return $new;
    }

    public function withoutAttribute($name)
    {
        $new = clone $this;
        unset($new->attributes[$name]);
        return $new;
    }
}