<?php


namespace Pfilsx\DataGrid\Grid;


use Pfilsx\DataGrid\Config\Configuration;
use Pfilsx\DataGrid\Config\ConfigurationInterface;
use Pfilsx\DataGrid\DataGridServiceContainer;
use Pfilsx\DataGrid\Grid\Columns\AbstractColumn;
use Pfilsx\DataGrid\Grid\Columns\ActionColumn;
use Pfilsx\DataGrid\Grid\Columns\DataColumn;
use InvalidArgumentException;
use Pfilsx\DataGrid\Grid\Columns\SerialColumn;
use Pfilsx\DataGrid\Grid\Providers\DataProviderInterface;

class DataGridBuilder implements DataGridBuilderInterface
{
    /**
     * @var Pager
     */
    protected $pager;
    /**
     * @var DataGridServiceContainer
     */
    protected $container;
    /**
     * @var DataProviderInterface
     */
    protected $provider;

    /**
     * @var AbstractColumn[]
     */
    protected $columns = [];

    protected $hasFilters = false;

    /**
     * @var Configuration
     */
    protected $configuration;

    protected $instance = 'default';

    protected $sort = null;

    /**
     * DataGridBuilder constructor.
     * @param DataGridServiceContainer $container
     */
    public function __construct(DataGridServiceContainer $container)
    {
        $this->container = $container;
        $this->configuration = new Configuration();
    }

    /**
     * @param string $attribute
     * @param string $columnClass
     * @param array $options
     * @return $this
     */
    public function addColumn(string $attribute, string $columnClass = DataColumn::class, array $options = []): DataGridBuilderInterface
    {
        if (!is_subclass_of($columnClass, AbstractColumn::class)) {
            throw new InvalidArgumentException('Expected subclass of' . AbstractColumn::class);
        }
        /**
         * @var AbstractColumn $column
         */
        $column = new $columnClass($this->container, array_merge($options, ['attribute' => $attribute]));
        $this->columns[] = $column;
        if ($column->hasFilter() && $column->isVisible()) {
            $this->hasFilters = true;
        }
        return $this;
    }

    /**
     * @param string $attribute
     * @param array $options
     * @return $this
     */
    public function addDataColumn(string $attribute, array $options = []): DataGridBuilderInterface
    {
        return $this->addColumn($attribute, DataColumn::class, $options);
    }

    /**
     * @param array $options
     * @return $this
     */
    public function addActionColumn(array $options = []): DataGridBuilderInterface
    {
        return $this->addColumn('id', ActionColumn::class, $options);
    }

    /**
     * @param array $options
     * @return $this
     */
    public function addSerialColumn(array $options = []): DataGridBuilderInterface
    {
        return $this->addColumn('', SerialColumn::class, $options);
    }

    /**
     * @param string $path
     * @return $this
     */
    public function setTemplate(string $path): DataGridBuilderInterface
    {
        $this->configuration->setTemplate($path);
        return $this;
    }
    /**
     * @param string $message
     * @return $this
     */
    public function setNoDataMessage(string $message): DataGridBuilderInterface
    {
        $this->configuration->setNoDataMessage($message);
        return $this;
    }

    public function enablePagination(bool $enabled = true, ?int $limit = null): DataGridBuilderInterface
    {
        $this->configuration->setPaginationEnabled($enabled);
        if ($limit !== null){
            $this->configuration->setPaginationLimit($limit);
        }
        return $this;
    }

    public function setCountFieldName(string $name): DataGridBuilderInterface
    {
        $this->provider->setCountFieldName($name);
        return $this;
    }

    /**
     * @internal
     * @return AbstractColumn[]
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    public function getProvider(): DataProviderInterface
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

    /**
     * @param string $attribute
     * @param string $direction
     */
    public function setSort(string $attribute, string $direction)
    {
        $this->sort = [$attribute, $direction];
    }

    /**
     * @internal
     */
    public function acquireSort(): void
    {
        if (is_array($this->sort) && count($this->sort) === 2) {
            $attribute = $this->sort[0];
            $direction = $this->sort[1];
            foreach ($this->columns as $column) {
                if ($column instanceof DataColumn && $column->hasSort() && $column->getAttribute() == $attribute) {
                    $column->setSort($direction);
                    $this->provider->setSort([$attribute => $direction]);
                    break;
                }
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
            if ($column instanceof DataColumn && $column->hasFilter() && array_key_exists($column->getAttribute(), $filters)) {
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

    /**
     * @return Configuration
     * @internal
     */
    public function getConfiguration(): ConfigurationInterface
    {
        return $this->configuration;
    }

    /**
     * @internal
     * @return string
     */
    public function getInstance(): string {
        return $this->instance;
    }

    /**
     * @param string $name
     * @return DataGridBuilderInterface
     */
    public function setInstance(string $name): DataGridBuilderInterface {
        $this->instance = $name;
        return $this;
    }

    public function setTranslationDomain(string $domain): DataGridBuilderInterface
    {
        $this->configuration->setTranslationDomain($domain);
        return $this;
    }
}
