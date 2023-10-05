<?php

namespace Kubinyete\Edi\Parser;

use Traversable;
use IteratorAggregate;
use Kubinyete\Edi\Reader\LineReader;
use Kubinyete\Edi\Registry\Registry;
use Kubinyete\Edi\IO\StreamInterface;

abstract class LineParser implements IteratorAggregate
{
    protected LineReader $reader;

    public function __construct(StreamInterface $stream)
    {
        $this->reader = new LineReader($stream);
    }

    //

    protected abstract function parse(LineContext $context): ?Registry;

    //

    public function next(): ?Registry
    {
        if (!$this->reader->eof()) {
            return $this->parse(new LineContext($this->reader->next(), $this->reader->getLineNumber()));
        }

        return null;
    }

    public function rollback(): void
    {
        $this->reader->rollback();
    }

    public function getIterator(): Traversable
    {
        while ($registry = $this->next()) {
            yield $registry;
        }
    }

    //
}
