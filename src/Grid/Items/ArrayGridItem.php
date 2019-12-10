<?php


namespace Pfilsx\DataGrid\Grid\Items;


use Pfilsx\DataGrid\DataGridException;

class ArrayGridItem extends DataGridItem
{
    public function has(string $attribute): bool
    {
        return array_key_exists($attribute, $this->data);
    }

    public function get(string $attribute)
    {
        if ($this->has($attribute)){
            return $this->data[$attribute];
        }
        throw new DataGridException('Unknown property ' . $attribute);
    }

    public function __call(string $name, array $arguments)
    {
        throw new DataGridException('ArrayGridItem does not support methods invoke');
    }
}