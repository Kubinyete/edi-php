<?php

namespace Kubinyete\Edi\Registry\Field;

use Attribute;

#[Attribute]
class Text extends Field
{
    public function __construct(public int $size, int $padDirection = STR_PAD_RIGHT, ?string $padChar = ' ', ...$args)
    {
        parent::__construct($size, ...$args, padDirection: $padDirection, padChar: $padChar);
    }
}
