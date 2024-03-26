<?php

declare(strict_types=1);

namespace App\Core\Http\Message;

use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ResponseInterface;

class Response implements ResponseInterface
{
    private string $protocolVersion;
    private array $headers;
    private StreamInterface $body;
    private int $statusCode;
    private string $reasonPhrase;

    public function __construct(
        StreamInterface $body,
        int $status = 200,
        array $headers = [],
        string $protocolVersion = '1.1',
        string $reasonPhrase = ''
    ) {
        $this->body = $body;
        $this->statusCode = $status;
        $this->headers = $headers;
        $this->protocolVersion = $protocolVersion;
        $this->reasonPhrase = $reasonPhrase;
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

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function withStatus(int $code, string $reasonPhrase = ''): ResponseInterface
    {
        $new = clone $this;
        $new->statusCode = $code;
        $new->reasonPhrase = $reasonPhrase;

        return $new;
    }

    public function getReasonPhrase(): string
    {
        return $this->reasonPhrase;
    }
}
