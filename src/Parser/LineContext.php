<?php

namespace Kubinyete\Edi\Parser;

use Throwable;
use Kubinyete\Edi\Parser\Exception\ParseException;

final class LineContext
{
    public function __construct(private string $contents, private int $lineNumber)
    {
    }

    public function getContents(): string
    {
        return $this->contents;
    }

    public function getLineNumber(): int
    {
        return $this->lineNumber;
    }

    public function unwrap(): array
    {
        return array_values(get_object_vars($this));
    }

    public function raise(string $message, int $cursor = -1, ?Throwable $previous = null): void
    {
        $reference = $cursor >= 0 ? str_repeat('-', $cursor) . '^' : '';
        $verbose = <<<MSG
Line {$this->lineNumber}: {$message}
{$this->contents}
{$reference}

MSG;
        throw new ParseException($verbose, 0, $previous);
    }

    public function assert($condition, string $message): void
    {
        if (!$condition) {
            $this->raise($message);
        }
    }

    public function empty(): bool
    {
        return !$this->contents;
    }
}
