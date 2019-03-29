<?php


namespace Pfilsx\DataGrid\Grid;


use Pfilsx\DataGrid\DataGridException;

class DataGridItem
{
    protected $entity;

    protected $row;

    /**
     * @return mixed
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param $entity
     */
    public function setEntity($entity): void
    {
        $this->entity = $entity;
    }

    /**
     * @return array
     */
    public function getRow()
    {
        return $this->row;
    }

    /**
     * @param array $row
     */
    public function setRow(array $row): void
    {
        $this->row = $row;
    }

    public function __get($name)
    {
        if ($this->entity !== null) {
            return $this->getEntityAttribute($name);
        }
        return null;
        //TODO
    }

    protected function getEntityAttribute($name)
    {
        $attribute = preg_replace_callback('/_([A-z]?)/', function ($matches) {
            return isset($matches[1]) ? strtoupper($matches[1]) : '';
        }, $name);
        $getter = 'get' . ucfirst($attribute);
        if (method_exists($this->entity, $getter)) {
            return $this->entity->$getter();
        }
        throw new DataGridException('Unknown property ' . $attribute . ' in ' . get_class($this->entity));
    }
}