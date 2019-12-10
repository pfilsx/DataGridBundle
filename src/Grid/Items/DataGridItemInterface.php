<?php


namespace Pfilsx\DataGrid\Grid\Items;


use ArrayAccess;

interface DataGridItemInterface extends ArrayAccess
{
    public function __construct($data, $identifier = null);

    public function has(string $attribute): bool;

    public function get(string $attribute);

    public function __get(string $attribute);
    public function __isset(string $attribute);

    public function __call(string $name, array $arguments);

    public function getData();

    public function hasIdentifier(): bool;

    public function getIdentifier();

}