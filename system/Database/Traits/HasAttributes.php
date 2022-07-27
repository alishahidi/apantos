<?php

namespace System\Database\Traits;

trait HasAttributes
{
    private function registerAttribute($object, string $attribute, $value)
    {
        $this->inCastAttributes($attribute) ? $object->$attribute = $this->castDecodeValue($attribute, $value) : $object->$attribute = $value;
    }

    protected function arrayToAttributes(array $array, $object = null)
    {
        if (! $object) {
            $className = get_called_class();
            $object = new $className();
        }
        foreach ($array as $attribute => $value) {
            if ($this->inHiddenAttributes($attribute)) {
                continue;
            }
            $this->registerAttribute($object, $attribute, $value);
        }

        return $object;
    }

    protected function arrayToObjects(array $array)
    {
        $collection = [];

        foreach ($array as $value) {
            $object = $this->arrayToAttributes($value);
            array_push($collection, $object);
        }

        $this->collection = $collection;
    }

    protected function inHiddenAttributes(string $attribute)
    {
        return in_array($attribute, $this->hidden);
    }

    protected function inCastAttributes($attribute)
    {
        return $this->casts[$attribute] == 'array' || $this->casts[$attribute] == 'object';
    }

    protected function castEncodeValue($attribute, $value)
    {
        if ($this->inCastAttributes($attribute)) {
            return serialize($value);
        }

        return $value;
    }

    protected function castDecodeValue($attribute, $value)
    {
        if ($this->inCastAttributes($attribute)) {
            return unserialize($value);
        }

        return $value;
    }

    private function arrayToCastEncodeValue(array $values)
    {
        $newArray = [];

        foreach ($values as $attribute => $value) {
            $this->inCastAttributes($attribute) ? $newArray[$attribute] = $this->castEncodeValue($attribute, $value) : $newArray[$attribute] = $value;
        }

        return $newArray;
    }
}
