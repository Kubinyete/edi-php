<?php

namespace Kubinyete\Edi\Registry\Field;

use Attribute;

#[Attribute]
class Number extends Numeric
{
    public function __construct(public int $size, ...$args)
    {
        parent::__construct($size, ...$args, padDirection: STR_PAD_LEFT, padChar: '0');
    }

    public function parse(string $value)
    {
        return intval(parent::parse($value));
    }
}
