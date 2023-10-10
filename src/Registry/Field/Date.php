<?php

namespace Kubinyete\Edi\Registry\Field;

use Attribute;
use Throwable;
use DateTimeZone;
use DateTimeImmutable;
use Kubinyete\Edi\Registry\Exception\FieldException;

#[Attribute]
class Date extends Field
{
    public function __construct(int $size, private string $format = 'Y-m-d', private ?string $tz = null, ...$args)
    {
        parent::__construct($size, ...$args);
    }

    public function parse(string $value)
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

    public function serialize($value): string
    {
        $format = str_replace('!', '', $this->format);
        return str_pad($value->format($format), $this->size);
    }
}
