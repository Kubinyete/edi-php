<?php

namespace Kubinyete\Edi\Registry\Field;

use Attribute;
use Kubinyete\Edi\Registry\Exception\FieldException;

#[Attribute]
class Numeric extends Field
{
    public function parse(string $value)
    {
        if (!is_numeric($value)) {
            throw new FieldException("Failed to parse field as a number literal");
        }

        return $value;
    }
}
