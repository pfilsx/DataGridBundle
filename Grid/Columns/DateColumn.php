<?php


namespace Pfilsx\DataGrid\Grid\Columns;


use DateTime;
use Pfilsx\DataGrid\Grid\DataGrid;

class DateColumn extends DataColumn
{
    protected $dateFormat = 'd.m.Y';

    public function getCellContent($entity, DataGrid $grid)
    {
        $value = $this->getCellValue($entity);
        if ($value instanceof DateTime){
            return $value->format($this->dateFormat);
        } elseif (is_string($value) && $this->dateFormat != null){
            return date($this->dateFormat, strtotime($value));
        }
        return (string)$value;
    }

    /**
     * @return string
     */
    public function getDateFormat(): string
    {
        return $this->dateFormat;
    }

    /**
     * @param string $dateFormat
     */
    protected function setDateFormat(string $dateFormat): void
    {
        $this->dateFormat = $dateFormat;
    }
}