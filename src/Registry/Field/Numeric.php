<?php

namespace Kubinyete\Edi\Registry\Field;

use Attribute;
use Kubinyete\Edi\Registry\Exception\FieldException;

#[Attribute]
class Numeric extends Field
{
    public function __construct(public int $size, int $padDirection = STR_PAD_LEFT, ?string $padChar = '0', ...$args)
    {
        parent::__construct($size, ...$args, padDirection: $padDirection, padChar: $padChar);
    }

    public function parse(string $value)
    {
        if (!is_numeric($value)) {
            throw new FieldException("Failed to parse field as a number literal");
        }

        return parent::parse($value);
    }
}
