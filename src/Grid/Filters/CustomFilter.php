<?php


namespace Pfilsx\DataGrid\Grid\Filters;


class CustomFilter extends AbstractFilter
{
    protected $value;

    public function render($attribute, $value): string
    {
        $this->prepareValue($value);
        return call_user_func_array($this->value, [$attribute, $value, $this->options]);
    }


    public function getBlockName(): ?string
    {
        return null;
    }

    protected function setValue(callable $callback)
    {
        $this->value = $callback;
    }
}