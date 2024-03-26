<?php

namespace App\Core\Http\Message;

use Psr\Http\Message\StreamInterface;

class Stream implements StreamInterface
{
    private $stream;


    public function __construct($stream)
    {
        if (!is_resource($stream) || get_resource_type($stream) !== 'stream') {
            throw new \InvalidArgumentException('Invalid stream provided.');
        }

        $this->stream = $stream;
    }

    public function __toString(): string
    {
        if (!$this->isReadable()) {
            return '';
        }

        try {
            rewind($this->stream);

            return stream_get_contents($this->stream);
        } catch (\Exception $e) {
            return '';
        }
    }

    public function close(): void
    {
        if (isset($this->stream)) {
            fclose($this->stream);
        }
    }

    public function detach()
    {
        if (!isset($this->stream)) {
            return null;
        }

        $stream = $this->stream;
        unset($this->stream);

        return $stream;
    }

    public function getSize(): ?int
    {
        if (!isset($this->stream)) {
            return null;
        }

        $stats = fstat($this->stream);

        return $stats['size'];
    }

    public function tell(): int
    {
        if (!isset($this->stream)) {
            throw new \RuntimeException('Stream is detached.');
        }

        $result = ftell($this->stream);
        if ($result === false) {
            throw new \RuntimeException('Unable to determine stream position.');
        }

        return $result;
    }

    public function eof(): bool
    {
        if (!isset($this->stream)) {
            throw new \RuntimeException('Stream is detached.');
        }

        return feof($this->stream);
    }

    public function isSeekable(): bool
    {
        if (!isset($this->stream)) {
            return false;
        }

        $meta = stream_get_meta_data($this->stream);

        return $meta['seekable'];
    }

    public function seek(int $offset, int $whence = SEEK_SET): void
    {
        if (!isset($this->stream)) {
            throw new \RuntimeException('Stream is detached.');
        }

        if (!$this->isSeekable()) {
            throw new \RuntimeException('Stream is not seekable.');
        }

        if (fseek($this->stream, $offset, $whence) === - 1) {
            throw new \RuntimeException('Unable to seek to stream position ' . $offset);
        }
    }

    public function rewind(): void
    {
        $this->seek(0);
    }

    public function isWritable(): bool
    {
        if (!isset($this->stream)) {
            return false;
        }

        $meta = stream_get_meta_data($this->stream);

        return !$meta['eof'] && $meta['mode'][0] === 'w';
    }

    public function write(string $string): int
    {
        if (!isset($this->stream)) {
            throw new \RuntimeException('Stream is detached.');
        }

        if (!$this->isWritable()) {
            throw new \RuntimeException('Stream is not writable.');
        }

        $result = fwrite($this->stream, $string);

        if ($result === false) {
            throw new \RuntimeException('Unable to write to stream.');
        }

        return $result;
    }

    public function isReadable(): bool
    {
        if (!isset($this->stream)) {
            return false;
        }

        $meta = stream_get_meta_data($this->stream);

        return !$meta['eof'] && $meta['mode'][0] === 'r';
    }

    public function read(int $length): string
    {
        if (!isset($this->stream)) {
            throw new \RuntimeException('Stream is detached.');
        }

        if (!$this->isReadable()) {
            throw new \RuntimeException('Stream is not readable.');
        }

        $result = fread($this->stream, $length);

        if ($result === false) {
            throw new \RuntimeException('Unable to read from stream.');
        }

        return $result;
    }

    public function getContents(): string
    {
        if (!isset($this->stream)) {
            throw new \RuntimeException('Stream is detached.');
        }

        $result = stream_get_contents($this->stream);

        if ($result === false) {
            throw new \RuntimeException('Unable to read stream contents.');
        }

        return $result;
    }

    public function getMetadata(?string $key = null)
    {
        if (!isset($this->stream)) {
            return $key ? null : [];
        }

        $meta = stream_get_meta_data($this->stream);

        if ($key === null) {
            return $meta;
        }

        return $meta[$key] ?? null;
    }
}
