<?php

namespace Kubinyete\Edi\Registry\Field;

use Attribute;

#[Attribute]
class DecimalField extends NumericField
{
    public function __construct(int $size, public ?int $precision = null, public string $delimiters = '.', ?int $index = null)
    {
        parent::__construct($size, $index);
    }

    public function parse($value)
    {
        $value = parent::parse($value);

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
