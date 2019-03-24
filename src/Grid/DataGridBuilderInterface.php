<?php


namespace Pfilsx\DataGrid\Grid;


interface DataGridBuilderInterface
{

    public function addColumn(string $columnClass, array $config = []): self;

    public function addDataColumn(string $attribute, array $config = []): self;

    public function setTemplate(string $path): self;

    public function setNoDataMessage(string $message): self;

    public function enablePagination($options = []) : self;

    /**
     * @internal
     * @return array
     */
    public function getColumns(): array;

    /**
     * @internal
     * @return array
     */
    public function getOptions(): array;
}
