<?php


namespace Pfilsx\DataGrid\Grid;


use Pfilsx\DataGrid\Config\ConfigurationInterface;
use Pfilsx\DataGrid\Grid\Columns\AbstractColumn;
use Pfilsx\DataGrid\Grid\Columns\DataColumn;
use Pfilsx\DataGrid\Grid\Providers\DataProviderInterface;

interface DataGridBuilderInterface
{

    public function addColumn(string $attribute, string $columnClass = DataColumn::class, array $config = []): self;

    public function addDataColumn(string $attribute, array $config = []): self;

    public function addActionColumn(array $config = []): self;

    public function setTemplate(string $path): self;

    public function setNoDataMessage(string $message): self;

    public function enablePagination(bool $enabled = true, int $limit = 10): self;

    public function setCountFieldName(string $name): self;

    public function setInstance(string $name): self;

    public function setTranslationDomain(string $domain): self;

    /**
     * @return AbstractColumn[]
     * @internal
     */
    public function getColumns(): array;


    public function getProvider(): DataProviderInterface;

    /**
     * @param DataProviderInterface $provider
     * @internal
     */
    public function setProvider(DataProviderInterface $provider): void;

    /**
     * @return bool
     * @internal
     */
    public function hasFilters(): bool;

    /**
     * @param array $filters
     * @internal
     */
    public function setFiltersValues(array $filters): void;

    /**
     * @return Pager
     * @internal
     */
    public function getPager(): Pager;

    /**
     * @return bool
     * @internal
     */
    public function hasPagination(): bool;

    /**
     * @return ConfigurationInterface
     * @internal
     */
    public function getConfiguration(): ConfigurationInterface;

    /**
     * @return string
     * @internal
     */
    public function getInstance(): string;

    /**
     * @param string $attribute
     * @param string $direction
     * @internal
     */
    public function setSort(string $attribute, string $direction);
}
