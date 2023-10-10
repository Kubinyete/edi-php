<?php

namespace Kubinyete\Edi\Registry\Field;

use Attribute;

#[Attribute]
class Field
{
    public function __construct(public int $size, public ?int $index = null, private int $padDirection = STR_PAD_LEFT, private ?string $padChar = null)
    {
    }

    public function parse(string $value)
    {
        $value = $this->removePadding($value);
        return $value;
    }

    public function serialize($value): string
    {
        $value = $this->addPadding($value);
        return $value;
    }

    //

    private function removePadding(string $value): string
    {
        if (is_null($this->padChar)) return $value;
        return match ($this->padDirection) {
            STR_PAD_BOTH => trim($value, $this->padChar),
            STR_PAD_LEFT => ltrim($value, $this->padChar),
            STR_PAD_RIGHT => rtrim($value, $this->padChar),
            default => $value,
        };
    }

    private function addPadding(string $value): string
    {
        if (is_null($this->padChar)) return $value;
        return str_pad($value, $this->size, $this->padChar, $this->padDirection);
    }
}
