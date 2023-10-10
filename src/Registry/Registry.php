<?php

namespace Kubinyete\Edi\Registry;

use Closure;
use ReflectionObject;
use ReflectionProperty;
use ReflectionAttribute;
use Kubinyete\Edi\Registry\Field\Field;
use Kubinyete\Edi\Registry\Exception\FieldException;

abstract class Registry
{
    private function onEachField(Closure $callback): void
    {
        $reflection = new ReflectionObject($this);

        foreach ($reflection->getProperties() as $property) {
            foreach ($property->getAttributes(Field::class, ReflectionAttribute::IS_INSTANCEOF) as $attribute) {
                $callback($property, $attribute->newInstance());
            }
        }
    }

    public function hydrate(string $contents): void
    {
        $globalPointer = 0;

        $this->onEachField(function (ReflectionProperty $property, Field $instance) use (&$globalPointer, $contents) {
            $pointer = $instance->index ?? $globalPointer;
            $value = substr($contents, $pointer, $instance->size);

            try {
                $property->setValue($this, $instance->parse($value));
            } catch (FieldException $e) {
                $e->setCursor($pointer);
                $e->setContents($value);
                $e->setName("{$property->getDeclaringClass()->getShortName()}.{$property->getName()}");
                throw $e;
            }

            $pointer += $instance->size;
            $globalPointer = $pointer;
        });
    }

    public function serialize(): string
    {
        $contents = '';
        $index = 0;
        $this->onEachField(function (ReflectionProperty $property, Field $instance) use (&$index, &$contents) {
            if ($instance->index > $index) {
                $leftover = $instance->index - $index;
                $contents .= str_repeat(' ', $leftover);
                $index += $leftover;
            }

            $contents .= $instance->serialize($property->getValue($this));
            $index += $instance->size;
        });
        return $contents;
    }

    public static function from(string $contents): static
    {
        $instance = new static();
        $instance->hydrate($contents);
        return $instance;
    }
}
