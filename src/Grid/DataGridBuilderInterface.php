<?php


namespace Pfilsx\DataGrid\Grid;


use Pfilsx\DataGrid\Grid\Columns\DataColumn;
use Pfilsx\DataGrid\Grid\Providers\DataProviderInterface;

interface DataGridBuilderInterface
{

    public function addColumn(string $attribute, string $columnClass = DataColumn::class, array $config = []): self;

    public function addDataColumn(string $attribute, array $config = []): self;

    public function addActionColumn(array $config = []): self;

    public function setTemplate(string $path): self;

    public function setNoDataMessage(string $message): self;

    public function setShowTitles(bool $flag): self;

    public function enablePagination($options = []): self;

    public function setCountFieldName(string $name): self;

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

    public function getProvider(): DataProviderInterface;

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
