<?php

namespace Kubinyete\Edi\Registry\Exception;

use UnexpectedValueException;

class FieldException extends UnexpectedValueException
{
    private ?int $cursor = null;
    private ?string $contents = null;
    private ?string $name = null;

    public function getCursor(): ?int
    {
        return $this->cursor;
    }

    public function setCursor(int $cursor): void
    {
        $this->cursor = $cursor;
    }

    public function clearCursor(): void
    {
        $this->cursor = null;
    }

    public function getContents(): ?string
    {
        return $this->contents;
    }

    public function setContents(string $value): void
    {
        $this->contents = $value;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $value): void
    {
        $this->name = $value;
    }
}
