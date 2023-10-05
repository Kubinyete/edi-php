<?php

namespace Kubinyete\Edi\Registry\Field;

use Attribute;

#[Attribute]
class Field
{
    public function __construct(public int $size, public ?int $index = null)
    {
    }

    public function parse($value)
    {
        return $value;
    }
}
