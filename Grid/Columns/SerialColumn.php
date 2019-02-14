<?php


namespace Pfilsx\DataGrid\Grid\Columns;


use Pfilsx\DataGrid\Grid\DataGrid;

class SerialColumn extends AbstractColumn
{
    protected static $counter = 1;
    function getHeadContent()
    {
        return '#';
    }

    function hasFilter()
    {
        return false;
    }

    function getFilterContent()
    {
        return '';
    }

    function getCellContent($entity, DataGrid $grid)
    {
        return static::$counter++;
    }
}