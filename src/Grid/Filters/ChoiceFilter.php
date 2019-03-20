<?php


namespace Pfilsx\DataGrid\Grid\Filters;


class ChoiceFilter extends AbstractFilter
{
    protected $choices = [];

    protected function getBlockName(): string
    {
        return 'choice_filter';
    }

    protected function getParams(): array
    {
        return [
            'choices' => $this->choices
        ];
    }
    /**
     * @return array
     */
    public function getChoices(): array
    {
        return $this->choices;
    }
    /**
     * @param array $choices
     */
    protected function setChoices(array $choices): void
    {
        $this->choices = $choices;
    }

}