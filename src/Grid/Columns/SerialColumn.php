<?php


namespace Pfilsx\DataGrid\Grid\Columns;


use Pfilsx\DataGrid\Grid\DataGrid;

class SerialColumn extends AbstractColumn
{
    protected static $counter = 1;

    public function getHeadContent()
    {
        return '#';
    }

    public function hasFilter()
    {
        return false;
    }

    public function getFilterContent()
    {
        return '';
    }

    public function getCellContent($entity, DataGrid $grid)
    {
        return static::$counter++;
    }
}