<?php

namespace Kubinyete\Edi\Registry\Field;

use Attribute;
use Kubinyete\Edi\Registry\Exception\FieldException;

#[Attribute]
class DecimalField extends Field
{
    public function __construct(int $size, public ?int $precision = null, public string $delimiters = '.', ?int $index = null)
    {
        parent::__construct($size, $index);
    }

    public function parse($value)
    {
        if (!is_numeric($value)) {
            throw new FieldException("Failed to parse field as a number literal");
        }

        // 002350
        // 2350
        // 23,50
        // 23.50
        // .

        if ($this->delimiters) {
            $delimiters = str_split($this->delimiters);
            $symbol = $delimiters[0] ?? '.';

            $value = str_replace($delimiters, $symbol, $value);

            if ($this->precision > 0 && strpos($value, $symbol) === false) {
                $value = substr_replace($value, $symbol, strlen($value) - $this->precision, 0);
            }
        }

        $value = ltrim($value, '0 ');
        if (str_starts_with($value, $symbol)) {
            $value = '0' . $value;
        }

        return $value;
    }
}
