<?php


namespace Pfilsx\DataGrid\Grid\Filters;


class TextFilter extends AbstractFilter
{

    protected function getBlockName(): string
    {
        return 'text_filter';
    }
}