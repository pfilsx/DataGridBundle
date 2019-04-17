<?php


namespace Pfilsx\DataGrid\Grid;


use Doctrine\Common\Persistence\ObjectManager;
use Pfilsx\DataGrid\DataGridException;

class DataGridItem
{
    protected $entity;
    /**
     * @var ObjectManager
     */
    protected $entityManager;

    protected $row;

    /**
     * @param $entity
     */
    public function setEntity($entity): void
    {
        $this->entity = $entity;
    }

    public function setEntityManager($manager)
    {
        $this->entityManager = $manager;
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

    public function has($name)
    {
        if ($this->entity !== null) {
            return method_exists($this->entity, 'get' . ucfirst($name));
        }
        if ($this->row !== null) {
            return array_key_exists($name, $this->row);
        }
        return false;
    }

    public function get($name)
    {
        return $this->__get($name);
    }

    public function __get($name)
    {
        if ($this->entity !== null) {
            return $this->getEntityAttribute($name);
        }
        if ($this->row !== null) {
            if (array_key_exists($name, $this->row)) {
                return $this->row[$name];
            }
            throw new DataGridException('Unknown property ' . $name);
        }
        return null;
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

    public function getId()
    {
        if ($this->entity !== null) {
            return $this->getEntityId();
        }
        return null;
    }

    public function getEntityId()
    {
        $metaData = $this->entityManager->getClassMetadata(get_class($this->entity));
        $idAttr = $metaData->getIdentifier()[0];
        $getter = 'get' . ucfirst($idAttr);
        if (method_exists($this->entity, $getter)) {
            return $this->entity->$getter();
        }
        throw new DataGridException('Cannot find identifier in ' . get_class($this->entity));
        //TODO surrogate pk
    }

}