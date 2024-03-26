<?php

declare(strict_types=1);

namespace App\Core\Http\Message;

use Psr\Http\Message\UriInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\MessageInterface;

class Request implements RequestInterface
{
    private string $method;
    private UriInterface $uri;
    private array $headers;
    private StreamInterface $body;
    private string $protocolVersion = '1.1';

    public function __construct(string $method, UriInterface $uri, array $headers, StreamInterface $body)
    {
        $this->method = $method;
        $this->uri = $uri;
        $this->headers = $headers;
        $this->body = $body;
    }

    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    public function withProtocolVersion(string $version): MessageInterface
    {
        $new = clone $this;
        $new->protocolVersion = $version;

        return $new;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function hasHeader(string $name): bool
    {
        return isset($this->headers[$name]);
    }

    public function getHeader(string $name): array
    {
        return $this->headers[$name] ?? [];
    }

    public function getHeaderLine(string $name): string
    {
        return implode(', ', $this->getHeader($name));
    }

    public function withHeader(string $name, $value): MessageInterface
    {
        $new = clone $this;
        $new->headers[$name] = (array) $value;

        return $new;
    }

    public function withAddedHeader(string $name, $value): MessageInterface
    {
        $new = clone $this;
        $new->headers[$name] = array_merge($this->getHeader($name), (array) $value);

        return $new;
    }

    public function withoutHeader(string $name): MessageInterface
    {
        $new = clone $this;
        unset($new->headers[$name]);

        return $new;
    }

    public function getBody(): StreamInterface
    {
        return $this->body;
    }

    public function withBody(StreamInterface $body): MessageInterface
    {
        $new = clone $this;
        $new->body = $body;

        return $new;
    }

    public function getRequestTarget(): string
    {
        $target = $this->uri->getPath();
        $query = $this->uri->getQuery();

        if ($query) {
            $target .= '?' . $query;
        }

        return $target;
    }

    public function withRequestTarget(string $requestTarget): RequestInterface
    {
        $new = clone $this;
        $new->uri = $this->uri->withPath($requestTarget);

        return $new;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function withMethod(string $method): RequestInterface
    {
        $new = clone $this;
        $new->method = $method;

        return $new;
    }

    public function getUri(): UriInterface
    {
        return $this->uri;
    }

    public function withUri(UriInterface $uri, bool $preserveHost = false): RequestInterface
    {
        $new = clone $this;
        $new->uri = $uri;

        if (!$preserveHost || !isset($this->headers['Host'])) {
            $new->updateHostFromUri();
        }

        return $new;
    }

    private function updateHostFromUri(): void
    {
        if ($this->uri->getHost()) {
            $port = $this->uri->getPort();
            $host = $this->uri->getHost() . ($port ? ':' . $port : '');
            $this->headers['Host'] = [$host];
        }
    }
}
