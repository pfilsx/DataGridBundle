<?php


namespace Pfilsx\DataGrid\Grid\Items;


use Pfilsx\DataGrid\DataGridException;

abstract class DataGridItem implements DataGridItemInterface
{

    protected $data;
    protected $identifier;

    public function __construct($data, $identifier = null)
    {
        $this->data = $data;
        $this->identifier = $identifier;
    }

    public final function getData()
    {
        return $this->data;
    }

    public final function setData($data)
    {
        $this->data = $data;
    }

    public final function hasIdentifier(): bool
    {
        return $this->identifier !== null && $this->has($this->identifier);
    }

    public final function getIdentifier()
    {
        return $this->identifier;
    }

    public final function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * Whether a offset exists
     * @param string $offset - An offset to check for.
     * @return boolean true on success or false on failure.
     * The return value will be casted to boolean if non-boolean was returned.
     */
    public final function offsetExists($offset)
    {
        return $this->has($offset);
    }

    /**
     * Offset to retrieve
     * @param string $offset - The offset to retrieve.
     * @return mixed Can return all value types.
     */
    public final function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * Offset to set
     * @param string $offset - The offset to assign the value to.
     * @param mixed $value - The value to set.
     * @return void
     * @throws DataGridException
     */
    public final function offsetSet($offset, $value)
    {
        throw new DataGridException("Trying to set read-only property: $offset");
    }

    /**
     * Offset to unset
     * @param string $offset - The offset to unset.
     * @return void
     * @throws DataGridException
     */
    public final function offsetUnset($offset)
    {
        throw new DataGridException("Trying to unset read-only property: $offset");
    }

    /**
     * Magic getter
     * @param string $attribute
     * @return mixed
     */
    public final function __get(string $attribute)
    {
        return $this->get($attribute);
    }

    /**
     * Magic setter
     * @param string $attribute
     * @param $value
     * @throws DataGridException
     */
    public final function __set(string $attribute, $value)
    {
        throw new DataGridException("Trying to set read-only property: $attribute");
    }

    /**
     * Magic unset
     * @param string $attribute
     * @throws DataGridException
     */
    public final function __unset(string $attribute)
    {
        throw new DataGridException("Trying to unset read-only property: $attribute");
    }

    /**
     * Magic isset
     * @param string $attribute
     * @return bool
     */
    public function __isset(string $attribute)
    {
        return $this->has($attribute);
    }
}