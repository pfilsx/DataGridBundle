<?php


namespace Pfilsx\DataGrid\Grid\Items;


use Pfilsx\DataGrid\DataGridException;

class EntityGridItem extends DataGridItem
{

    public function has(string $attribute): bool
    {
        list($camelAttribute, $getter) = $this->getPropertyAccessVariations($attribute);
        return method_exists($this->data, $getter)
            || property_exists($this->data, $attribute)
            || property_exists($this->data, $camelAttribute);
    }

    public function get(string $attribute)
    {
        list($camelAttribute, $getter) = $this->getPropertyAccessVariations($attribute);
        if (method_exists($this->data, $getter)) {
            return $this->data->$getter();
        }
        if (property_exists($this->data, $attribute)) {
            return $this->data->$attribute;
        }
        if (property_exists($this->data, $camelAttribute)) {
            return $this->data->$camelAttribute;
        }
        throw new DataGridException('Unknown property ' . $attribute . ' in ' . get_class($this->data));
    }


    private function getPropertyAccessVariations(string $attribute): array
    {
        $camelAttribute = preg_replace_callback('/_([A-z]?)/', function ($matches) {
            return isset($matches[1]) ? strtoupper($matches[1]) : '';
        }, $attribute);
        $getter = 'get' . $camelAttribute;
        return [$camelAttribute, $getter];
    }
}