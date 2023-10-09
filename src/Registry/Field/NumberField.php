<?php

namespace Kubinyete\Edi\Registry\Field;

use Attribute;

#[Attribute]
class NumberField extends NumericField
{
    public function parse($value)
    {
        return intval(parent::parse($value));
    }
}
