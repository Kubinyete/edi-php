<?php

namespace Kubinyete\Edi\Registry\Field;

use Attribute;

#[Attribute]
class TextField extends Field
{
    public function parse($value)
    {
        return trim(strval($value));
    }
}
