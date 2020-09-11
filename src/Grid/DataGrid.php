<?php


namespace Pfilsx\DataGrid\Grid;


use Doctrine\Common\Collections\Criteria;
use Pfilsx\DataGrid\Config\ConfigurationContainerInterface;
use Pfilsx\DataGrid\Config\ConfigurationInterface;
use Pfilsx\DataGrid\DataGridServiceContainer;
use Pfilsx\DataGrid\Grid\Columns\AbstractColumn;
use Pfilsx\DataGrid\Grid\Providers\DataProviderInterface;
use Twig\TemplateWrapper;

/**
 * Class DataGrid
 * @package Pfilsx\DataGrid\Grid
 * @internal
 */
class DataGrid implements DataGridInterface
{
    /**
     * @var TemplateWrapper
     */
    protected $template;
    /**
     * @var DataGridServiceContainer
     */
    protected $container;

    /**
     * @var ConfigurationInterface
     */
    protected $configuration;
    /**
     * @var Criteria
     */
    protected $filtersCriteria;
    /**
     * @var AbstractGridType
     */
    protected $gridType;

    /**
     * @var DataGridBuilderInterface
     */
    protected $builder;
    /**
     * @var DataGridFiltersBuilderInterface
     */
    protected $filterBuilder;

    /**
     * DataGrid constructor.
     * @param AbstractGridType $type
     * @param DataProviderInterface $dataProvider
     * @param ConfigurationContainerInterface $defaultConfiguration
     * @param DataGridServiceContainer $container
     * @internal
     */
    public function __construct(
        AbstractGridType $type,
        DataProviderInterface $dataProvider,
        ConfigurationContainerInterface $defaultConfiguration,
        DataGridServiceContainer $container
    )
    {
        $this->gridType = $type;
        $this->container = $container;

        $this->configureBuilders($dataProvider);

        $this->configuration = $defaultConfiguration->getInstance($this->builder->getInstance())
            ->merge($this->builder->getConfiguration());

        $this->setTemplate($this->configuration->getTemplate());
        $this->setTranslationDomain($this->configuration->getTranslationDomain());

    }

    protected function configureBuilders(DataProviderInterface $dataProvider)
    {
        $this->builder = new DataGridBuilder($this->container);
        $this->builder->setProvider($dataProvider);
        $this->gridType->buildGrid($this->builder);

        $this->filterBuilder = new DataGridFiltersBuilder();
        $this->filterBuilder->setProvider($dataProvider);
    }

    /**
     * @internal
     */
    protected function configurePagerOptions()
    {
        $pager = $this->builder->getProvider()->getPager();
        $pager->setLimit($this->configuration->getPaginationLimit());
        if ($this->configuration->getPaginationEnabled()) {
            $pager->enable();
            $pager->setTotalCount($this->getProvider()->getTotalCount());
            $pager->rebuildPaginationOptions();
        } else {
            $pager->disable();
        }
    }

    /**
     * @return Providers\DataProviderInterface
     * @internal
     */
    public function getProvider(): DataProviderInterface
    {
        return $this->builder->getProvider();
    }

    /**
     * @return AbstractColumn[]
     * @internal
     */
    public function getColumns(): array
    {
        return $this->builder->getColumns();
    }

    /**
     * @return bool
     * @internal
     */
    public function hasFilters()
    {
        return $this->builder->hasFilters();
    }

    /**
     * @return array
     * @internal
     */
    public function getData()
    {
        return $this->getProvider()->getItems();
    }

    /**
     * @return TemplateWrapper
     * @internal
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @return string
     * @internal
     */
    public function getNoDataMessage()
    {
        return $this->container->getTranslator() !== null
            ? $this->container->getTranslator()->trans($this->configuration->getNoDataMessage(), [], $this->configuration->getTranslationDomain())
            : ucfirst($this->configuration->getNoDataMessage());
    }

    /**
     * @return bool
     * @internal
     */
    public function hasPagination()
    {
        return $this->builder->hasPagination();
    }

    /**
     * @return array
     * @internal
     */
    public function getPaginationOptions()
    {
        return $this->builder->getPager()->getPaginationOptions();
    }

    /**
     * @return DataGridView
     */
    public function createView(): DataGridView
    {
        $this->handleRequest();
        $this->configurePagerOptions();
        return new DataGridView($this, $this->container);
    }

    /**
     * @param string $path
     */
    protected function setTemplate(string $path)
    {
        $this->template = $this->container->getTwig()->load($path);
        foreach ($this->builder->getColumns() as $column) {
            $column->setTemplate($this->template);
        }
    }

    /**
     * @param string $domain
     */
    protected function setTranslationDomain(?string $domain)
    {
        foreach ($this->builder->getColumns() as $column) {
            $column->setTranslationDomain($domain);
        }
    }

    protected function handleRequest(): void
    {
        $request = $this->container->getRequest()->getCurrentRequest();
        $queryParams = $request !== null ? $request->query->get('data_grid') ?? [] : [];

        $this->handleSorting($queryParams);

        $this->handlePagination($queryParams);

        $this->handleFilters($queryParams);
    }

    protected function handleSorting(array &$queryParams)
    {
        if (array_key_exists('sortBy', $queryParams)) {
            $this->setSort($queryParams['sortBy']);
            unset($queryParams['sortBy']);
        }
        $this->builder->acquireSort();
    }

    protected function handlePagination(array &$queryParams)
    {
        if (array_key_exists('page', $queryParams)) {
            $this->setPage($queryParams['page']);
            unset($queryParams['page']);
        } else {
            $this->setPage(1);
        }
    }

    protected function handleFilters(array $queryParams)
    {
        $this->builder->setFiltersValues($queryParams);
        $this->filterBuilder->setParams($queryParams);
        $this->gridType->handleFilters($this->filterBuilder, $queryParams);
    }

    protected function setSort($attribute)
    {
        $first = substr($attribute, 0, 1);
        if ($first == '-') {
            $this->builder->setSort(substr($attribute, 1), 'DESC');
        } else {
            $this->builder->setSort($attribute, 'ASC');
        }
    }

    protected function setPage($page)
    {
        $this->builder->getPager()->setPage(is_numeric($page) ? (int)$page : 1);
    }
}
