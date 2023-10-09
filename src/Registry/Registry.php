<?php

namespace Kubinyete\Edi\Registry;

use ReflectionObject;
use ReflectionAttribute;
use Kubinyete\Edi\Registry\Field\Field;
use Kubinyete\Edi\Registry\Exception\FieldException;

abstract class Registry
{
    public function hydrate(string $contents): void
    {
        $reflection = new ReflectionObject($this);
        $pointer = 0;

        foreach ($reflection->getProperties() as $property) {
            foreach ($property->getAttributes(Field::class, ReflectionAttribute::IS_INSTANCEOF) as $attribute) {
                /** @var Field $instance */
                $instance = $attribute->newInstance();

                $pointer = $instance->index ?? $pointer;
                $value = substr($contents, $pointer, $instance->size);

                try {
                    $property->setValue($this, $instance->parse($value));
                } catch (FieldException $e) {
                    $e->setCursor($pointer);
                    $e->setContents($value);
                    $e->setName("{$reflection->getShortName()}.{$property->getName()}");
                    throw $e;
                }

                $pointer += $instance->size;
            }
        }
    }

    public static function from(string $contents): static
    {
        $instance = new static();
        $instance->hydrate($contents);
        return $instance;
    }
}
