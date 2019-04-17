<?php


namespace Pfilsx\DataGrid\Grid;


use Pfilsx\DataGrid\Grid\Columns\AbstractColumn;
use Pfilsx\DataGrid\Grid\Columns\DataColumn;
use InvalidArgumentException;
use Pfilsx\DataGrid\Grid\Providers\DataProvider;
use Pfilsx\DataGrid\Grid\Providers\DataProviderInterface;

class DataGridBuilder implements DataGridBuilderInterface
{
    /**
     * @var Pager
     */
    protected $pager;
    /**
     * @var array
     */
    protected $container;
    /**
     * @var DataProvider
     */
    protected $provider;

    /**
     * @var AbstractColumn[]
     */
    protected $columns = [];
    /**
     * @var array
     */
    protected $options = [
        'template' => '@DataGrid/grid.blocks.html.twig'
    ];

    protected $hasFilters = false;

    /**
     * DataGridBuilder constructor.
     * @param array $container
     */
    public function __construct(array $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $columnClass
     * @param array $config
     * @return $this
     */
    public function addColumn(string $columnClass, array $config = []): DataGridBuilderInterface
    {
        if (!is_subclass_of($columnClass, AbstractColumn::class)) {
            throw new InvalidArgumentException('Expected subclass of' . AbstractColumn::class);
        }
        /**
         * @var AbstractColumn $column
         */
        $column = new $columnClass($this->container, array_merge(['template' => $this->options['template']], $config));
        $this->columns[] = $column;
        if ($column->hasFilter() && $column->isVisible()) {
            $this->hasFilters = true;
        }
        return $this;
    }

    /**
     * @param string $attribute
     * @param array $config
     * @return $this
     */
    public function addDataColumn(string $attribute, array $config = []): DataGridBuilderInterface
    {
        return $this->addColumn(DataColumn::class, array_merge(['label' => $attribute], $config, ['attribute' => $attribute]));
    }

    /**
     * @param string $path
     * @return $this
     */
    public function setTemplate(string $path): DataGridBuilderInterface
    {
        $this->options['template'] = $path;
        foreach ($this->columns as $column) {
            $column->setTemplate($path);
        }
        return $this;
    }

    /**
     * @param string $message
     * @return $this
     */
    public function setNoDataMessage(string $message): DataGridBuilderInterface
    {
        $this->options['noDataMessage'] = $message;
        return $this;
    }

    public function enablePagination($options = []): DataGridBuilderInterface
    {
        if (is_array($options) && !empty($options)) {
            $this->getPager()->enable();
            $this->getPager()->setOptions($options);
        } else {
            $this->getPager()->disable();
            $this->getPager()->setLimit(null);
        }
        return $this;
    }

    /**
     * @internal
     * @return array
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * @internal
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    public function getProvider(): DataProvider
    {
        return $this->provider;
    }

    /**
     * @internal
     * @param DataProviderInterface $provider
     */
    public function setProvider(DataProviderInterface $provider): void
    {
        $provider->setPager($this->getPager());
        $this->provider = $provider;
    }

    public function setSort(string $attribute, string $direction)
    {
        foreach ($this->columns as $column) {
            if ($column->hasSort() && $column->getAttribute() == $attribute) {
                $column->setSort($direction);
                $this->provider->setSort([$attribute => $direction]);
                break;
            }
        }
    }

    /**
     * @internal
     * @return bool
     */
    public function hasFilters(): bool
    {
        return $this->hasFilters;
    }

    /**
     * @internal
     * @param array $filters
     */
    public function setFiltersValues(array $filters): void
    {
        foreach ($this->columns as $column) {
            if ($column->hasFilter() && array_key_exists($column->getAttribute(), $filters)) {
                $column->setFilterValue($filters[$column->getAttribute()]);
            }
        }
    }


    /**
     * @internal
     * @return Pager
     */
    public function getPager(): Pager
    {
        return $this->pager ?? ($this->pager = new Pager());
    }

    /**
     * @internal
     * @return bool
     */
    public function hasPagination(): bool
    {
        return $this->getPager()->isEnabled() && is_integer($this->getPager()->getLimit());
    }
}
