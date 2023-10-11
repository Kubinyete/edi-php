<?php

namespace Kubinyete\Edi\IO;

use RuntimeException;
use InvalidArgumentException;

class Stream implements StreamInterface
{
    private $resource;

    public function __construct($resource)
    {
        if (!is_resource($resource)) {
            throw new InvalidArgumentException("A stream object expects a resource to be given");
        }

        $this->resource = $resource;
    }

    public function __destruct()
    {
        $this->close();
    }

    //

    public function read(int $length): string
    {
        $buffer = fread($this->resource, $length);
        $this->assert($buffer !== false, "Read operation on resource stream failed");
        return $buffer;
    }

    public function write(string $data, ?int $length = 0): int
    {
        $bytes = fwrite($this->resource, $data, $length);
        $this->assert($bytes !== false, "Write operation on resource stream failed");
        return $bytes;
    }

    public function tell(): int
    {
        $offset = ftell($this->resource);
        $this->assert($offset !== false, "Tell operation on resource stream failed");
        return $offset;
    }

    public function seek(int $offset, int $from = SEEK_SET): void
    {
        $result = fseek($this->resource, $offset, $from);
        $this->assert($result === 0, "Seek operation on resource stream failed");
    }

    public function eof(): bool
    {
        return feof($this->resource);
    }

    public function flush(): void
    {
        $this->assert(fflush($this->resource), "Flush operation on resource stream failed");
    }

    public function close(): void
    {
        if ($this->resource) {
            $this->assert(fclose($this->resource), "Close operation on resource stream failed");
            $this->resource = null;
        }
    }

    private function assert($expression, string $message): void
    {
        if (!$expression) {
            throw new RuntimeException($message);
        }
    }


    //

    public static function create($resource): self
    {
        return new self($resource);
    }

    public static function file(string $path, string $mode, $context = null): self
    {
        return self::create(fopen($path, $mode, false, $context));
    }
}
