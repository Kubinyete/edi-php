<?php

namespace Kubinyete\Edi\IO;

interface StreamInterface
{
    function read(int $length): string;
    function write(string $data, ?int $length = 0): int;
    function tell(): int;
    function seek(int $offset, int $from = SEEK_SET): void;
    function eof(): bool;
    function flush(): void;
    function close(): void;
}
