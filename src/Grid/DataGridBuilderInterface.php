<?php


namespace Pfilsx\DataGrid\Grid;


use Pfilsx\DataGrid\Grid\Providers\DataProvider;
use Pfilsx\DataGrid\Grid\Providers\DataProviderInterface;

interface DataGridBuilderInterface
{

    public function addColumn(string $columnClass, array $config = []): self;

    public function addDataColumn(string $attribute, array $config = []): self;

    public function setTemplate(string $path): self;

    public function setNoDataMessage(string $message): self;

    public function enablePagination($options = []): self;

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

    public function getProvider(): DataProvider;

    /**
     * @internal
     * @param DataProviderInterface $provider
     */
    public function setProvider(DataProviderInterface $provider): void;

    public function hasFilters(): bool;

    /**
     * @internal
     * @param array $filters
     */
    public function setFiltersValues(array $filters): void;

    /**
     * @internal
     * @return Pager
     */
    public function getPager(): Pager;

    /**
     * @internal
     * @return bool
     */
    public function hasPagination(): bool;
}
