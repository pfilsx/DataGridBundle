<?php


namespace Pfilsx\DataGrid\Grid;

use Pfilsx\DataGrid\DataGridServiceContainer;

abstract class AbstractGridType implements GridTypeInterface
{
    const ACTION_COLUMN = 'Pfilsx\DataGrid\Grid\Columns\ActionColumn';
    const BOOLEAN_COLUMN = 'Pfilsx\DataGrid\Grid\Columns\BooleanColumn';
    const IMAGE_COLUMN = 'Pfilsx\DataGrid\Grid\Columns\ImageColumn';
    const DATA_COLUMN = 'Pfilsx\DataGrid\Grid\Columns\DataColumn';
    const SERIAL_COLUMN = 'Pfilsx\DataGrid\Grid\Columns\SerialColumn';
    const DATE_COLUMN = 'Pfilsx\DataGrid\Grid\Columns\DateColumn';
    const RELATION_COLUMN = 'Pfilsx\DataGrid\Grid\Columns\RelationColumn';

    const FILTER_TEXT = 'Pfilsx\DataGrid\Grid\Filters\TextFilter';
    const FILTER_BOOLEAN = 'Pfilsx\DataGrid\Grid\Filters\BooleanFilter';
    const FILTER_ENTITY = 'Pfilsx\DataGrid\Grid\Filters\EntityFilter';
    const FILTER_CHOICE = 'Pfilsx\DataGrid\Grid\Filters\ChoiceFilter';
    const FILTER_DATE = 'Pfilsx\DataGrid\Grid\Filters\DateFilter';
    const FILTER_CUSTOM = 'Pfilsx\DataGrid\Grid\Filters\CustomFilter';

    /**
     * @var array
     */
    protected $params;

    public function setParams(array $params)
    {
        $this->params = $params;
    }
}
