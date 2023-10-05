<?php

namespace Kubinyete\Edi\Reader;

use Traversable;
use RuntimeException;
use IteratorAggregate;
use Kubinyete\Edi\IO\StreamInterface;

class LineReader implements IteratorAggregate
{
    private const CR = "\x0D";
    private const LF = "\x0A";

    protected int $lineNumber;
    protected ?string $line;

    public function __construct(protected StreamInterface $stream)
    {
        $this->rollback();
    }

    public function getLine(): ?string
    {
        return $this->line;
    }

    public function getLineNumber(): int
    {
        return $this->lineNumber;
    }

    public function next(): ?string
    {
        $this->line = $this->fetch();
        $this->lineNumber++;
        return $this->line;
    }

    public function rollback(): void
    {
        $this->lineNumber = 0;
        $this->line = null;

        try {
            $this->stream->seek(0);
        } catch (RuntimeException $e) {
            // Some streams are unable to rollback (Ex: stdin)
        }
    }

    public function eof(): bool
    {
        return $this->stream->eof();
    }

    public function getIterator(): Traversable
    {
        while (!$this->eof()) {
            yield $this->next();
        }
    }

    //

    protected function fetch(): ?string
    {
        $line = null;
        $next = null;
        while (!$this->stream->eof() && $next != self::LF) {
            $next = $this->stream->read(1);
            $line .= rtrim($next, self::CR . self::LF);
        }
        return $line;
    }
}
