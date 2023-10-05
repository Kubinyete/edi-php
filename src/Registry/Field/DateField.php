<?php

namespace Kubinyete\Edi\Registry\Field;

use Attribute;
use Throwable;
use DateTimeZone;
use DateTimeImmutable;
use Kubinyete\Edi\Registry\Exception\FieldException;

#[Attribute]
class DateField extends Field
{
    public function __construct(int $size, public string $format = 'Y-m-d', public ?string $tz = null, ?int $index = null)
    {
        parent::__construct($size, $index);
    }

    public function parse($value)
    {
        try {
            $tz = $this->tz ? new DateTimeZone($this->tz) : null;
        } catch (Throwable $e) {
            throw new FieldException("Failed to parse field as a date, provided timezone of '$this->tz' is invalid.");
        }

        $date = DateTimeImmutable::createFromFormat($this->format, $value, $tz);

        if (!$date) {
            throw new FieldException("Failed to parse field as a date '{$value}' with format '{$this->format}'");
        }

        return $date;
    }
}
