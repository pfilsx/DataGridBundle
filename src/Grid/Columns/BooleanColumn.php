<?php


namespace Pfilsx\DataGrid\Grid\Columns;


use Pfilsx\DataGrid\Grid\DataGrid;

class BooleanColumn extends DataColumn
{
    protected $trueValue = 'Yes';
    protected $falseValue = 'No';

    function getCellContent($entity, DataGrid $grid)
    {
        if (is_callable($this->value)){
            $result = call_user_func_array($this->value, [$entity]);
        } elseif ($this->value !== null){
            $result = $this->value == true ? $this->trueValue : $this->falseValue;
        } else {
            $result = $this->getEntityAttribute($entity, $this->attribute) == true ? $this->trueValue : $this->falseValue;
        }
        return $this->format == 'html' ? $result : htmlspecialchars($result);
    }

    /**
     * @return string
     */
    public function getTrueValue(): string
    {
        return $this->trueValue;
    }

    /**
     * @param string $trueValue
     */
    protected function setTrueValue(string $trueValue): void
    {
        $this->trueValue = $trueValue;
    }

    /**
     * @return string
     */
    public function getFalseValue(): string
    {
        return $this->falseValue;
    }

    /**
     * @param string $falseValue
     */
    protected function setFalseValue(string $falseValue): void
    {
        $this->falseValue = $falseValue;
    }
}