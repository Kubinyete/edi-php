<?php

namespace Kubinyete\Edi\Registry\Field;

use Attribute;

#[Attribute]
class Number extends Numeric
{
    public function __construct(public int $size, ...$args)
    {
        parent::__construct($size, ...$args);
    }

    public function parse(string $value)
    {
        return intval(parent::parse($value));
    }
}
