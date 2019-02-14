<?php


namespace Pfilsx\DataGrid\Grid\Filters;


class BooleanFilter extends AbstractFilter
{
    protected $trueChoice = 'Yes';

    protected $falseChoice = 'No';

    protected function prepareValue(&$value)
    {
        $value = $value == null ? null : (int)(bool)$value;
    }

    protected function getBlockName(): string
    {
        return 'boolean_filter';
    }

    protected function getParams(): array
    {
        return [
            'choices' => [1 => $this->trueChoice, 0 => $this->falseChoice]
        ];
    }

    /**
     * @return string
     */
    public function getTrueChoice(): string
    {
        return $this->trueChoice;
    }

    /**
     * @param string $trueChoice
     */
    protected function setTrueChoice(string $trueChoice): void
    {
        $this->trueChoice = $trueChoice;
    }

    /**
     * @return string
     */
    public function getFalseChoice(): string
    {
        return $this->falseChoice;
    }

    /**
     * @param string $falseChoice
     */
    protected function setFalseChoice(string $falseChoice): void
    {
        $this->falseChoice = $falseChoice;
    }
}