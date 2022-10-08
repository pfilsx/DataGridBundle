<?php

namespace Pfilsx\tests\Mocks;

use Doctrine\DBAL\Driver\Statement;
use Doctrine\DBAL\Driver\Result;
use IteratorAggregate;
use PDO;
use Traversable;

class HydratorMockStatement implements IteratorAggregate, Statement
{
    /**
     * @var array
     */
    private $_resultSet;

    /**
     * Creates a new mock statement that will serve the provided fake result set to clients.
     *
     * @param array $resultSet The faked SQL result set.
     */
    public function __construct(array $resultSet)
    {
        $this->_resultSet = $resultSet;
    }

    /**
     * Fetches all rows from the result set.
     *
     * @param null $fetchMode
     * @param null $fetchArgument
     * @param array|null $ctorArgs
     *
     * @return array
     */
    public function fetchAll($fetchMode = null, $fetchArgument = null, $ctorArgs = null)
    {
        return $this->_resultSet;
    }

    /**
     * {@inheritdoc}
     */
    public function fetchColumn($columnNumber = 0)
    {
        $row = current($this->_resultSet);
        if (!is_array($row)) return false;
        $val = array_shift($row);
        return $val !== null ? $val : false;
    }

    /**
     * {@inheritdoc}
     */
    public function fetch($fetchMode = null, $cursorOrientation = PDO::FETCH_ORI_NEXT, $cursorOffset = 0)
    {
        $current = current($this->_resultSet);
        next($this->_resultSet);
        return $current;
    }

    /**
     * {@inheritdoc}
     */
    public function closeCursor()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function bindValue($param, $value, $type = null)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function bindParam($column, &$variable, $type = null, $length = null)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function columnCount()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function errorCode()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function errorInfo()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function execute($params = null): Result
    {
        throw new \Exception("Not implemented");
    }

    /**
     * {@inheritdoc}
     */
    public function rowCount()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator(): Traversable
    {
        return $this->_resultSet;
    }

    /**
     * {@inheritdoc}
     */
    public function setFetchMode($fetchStyle, $arg2 = null, $arg3 = null)
    {
    }
}
