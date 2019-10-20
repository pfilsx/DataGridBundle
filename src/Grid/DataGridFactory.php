<?php


namespace Pfilsx\DataGrid\Grid;


use Pfilsx\DataGrid\Config\ConfigurationContainerInterface;
use Pfilsx\DataGrid\Config\ConfigurationInterface;
use InvalidArgumentException;
use Pfilsx\DataGrid\DataGridServiceContainer;
use Pfilsx\DataGrid\Grid\Providers\DataProvider;
use Symfony\Component\HttpFoundation\Request;

class DataGridFactory implements DataGridFactoryInterface
{
    /**
     * @var DataGridServiceContainer
     */
    protected $container;
    /**
     * @var Request
     */
    protected $request;
    /**
     * @var DataGridBuilder
     */
    protected $gridBuilder;
    /**
     * @var DataGridFiltersBuilder
     */
    protected $filterBuilder;
    /**
     * @var AbstractGridType
     */
    protected $gridType;
    /**
     * @var ConfigurationContainerInterface
     */
    protected $defaultConfiguration;
    /**
     * @var array
     */
    protected $queryParams;

    public function __construct(DataGridServiceContainer $container, ConfigurationContainerInterface $configs)
    {
        $this->container = $container;
        $this->request = $container->getRequest()->getCurrentRequest();
        $this->defaultConfiguration = $configs;
        $this->gridBuilder = new DataGridBuilder($this->container);
        $this->filterBuilder = new DataGridFiltersBuilder();
    }


    public function createGrid(string $gridType, $dataProvider): DataGrid
    {
        if (!is_subclass_of($gridType, AbstractGridType::class)) {
            throw new InvalidArgumentException('Expected subclass of ' . AbstractGridType::class);
        }
        $provider = DataProvider::create($dataProvider, $this->container->getDoctrine());
        $this->gridBuilder->setProvider($provider);
        $this->filterBuilder->setProvider($provider);

        /** @var AbstractGridType $type */
        $this->gridType = new $gridType($this->container);
        $this->gridType->buildGrid($this->gridBuilder);
        //TODO move handle request to grid init
        $this->handleRequest();

        return new DataGrid($this->gridBuilder, $this->defaultConfiguration, $this->container);
    }

    protected function handleRequest(): void
    {
        $this->queryParams = $this->request !== null && $this->request->query->has('data_grid') ? $this->request->query->get('data_grid') : [];

        $this->handleSorting();

        $this->handlePagination();

        $this->handleFilters();
    }

    protected function handleSorting()
    {
        if (array_key_exists('sortBy', $this->queryParams)) {
            $this->setSort($this->queryParams['sortBy']);
            unset($this->queryParams['sortBy']);
        }
    }

    protected function handlePagination()
    {
        if (array_key_exists('page', $this->queryParams)) {
            $this->setPage($this->queryParams['page']);
            unset($this->queryParams['page']);
        } else {
            $this->setPage(1);
        }
    }

    protected function handleFilters()
    {
        $this->gridBuilder->setFiltersValues($this->queryParams);
        $this->filterBuilder->setParams($this->queryParams);
        $this->gridType->handleFilters($this->filterBuilder, $this->queryParams);
    }

    protected function setSort($attribute)
    {
        $first = substr($attribute, 0, 1);
        if ($first == '-') {
            $this->gridBuilder->setSort(substr($attribute, 1), 'DESC');
        } else {
            $this->gridBuilder->setSort($attribute, 'ASC');
        }
    }

    protected function setPage($page)
    {
        $this->gridBuilder->getPager()->setPage(is_numeric($page) ? (int)$page : 1);
    }
}
