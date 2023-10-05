<?php

namespace Kubinyete\Edi\IO;

class Buffer implements StreamInterface
{
    private int $pointer;
    private int $length;
    private string $data;

    public function __construct(string $content)
    {
        $this->setBuffer($content);
    }

    public function setBuffer(string $data, ?int $size = null): void
    {
        $this->data = $data;
        $this->length = $size ?? strlen($data);
        $this->pointer = 0;
    }

    public function read(int $length): string
    {
        $buffer = '';
        while ($this->pointer < $this->length && $length-- > 0) {
            $buffer .= $this->data[$this->pointer++];
        }
        return $buffer;
    }

    public function write(string $data, ?int $length = 0): int
    {
        $this->setBuffer(substr_replace($this->data, $data, $this->pointer, $length));
        return $length;
    }

    public function tell(): int
    {
        return $this->pointer;
    }

    public function seek(int $offset, int $from = SEEK_SET): void
    {
        if ($from == SEEK_SET) {
            $this->pointer = min($offset, $this->length - 1);
        } else if ($from == SEEK_CUR) {
            $this->pointer = max(min($this->pointer + $offset, $this->length - 1), 0);
        } else if ($from == SEEK_END) {
            $this->pointer = max(($this->length - 1) - $offset, 0);
        }
    }

    public function eof(): bool
    {
        return $this->pointer >= $this->length;
    }

    public function flush(): void
    {
    }

    public function close(): void
    {
    }

    //

    public static function from(string $content): static
    {
        return new static($content);
    }
}
