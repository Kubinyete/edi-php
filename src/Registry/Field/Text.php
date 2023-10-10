<?php

namespace Kubinyete\Edi\Registry\Field;

use Attribute;

#[Attribute]
class Text extends Field
{
    public function __construct(public int $size, ...$args)
    {
        parent::__construct($size, ...$args, padDirection: STR_PAD_RIGHT, padChar: ' ');
    }
}
