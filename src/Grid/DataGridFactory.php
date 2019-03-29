<?php


namespace Pfilsx\DataGrid\Grid;


use Pfilsx\DataGrid\Config\DataGridConfigurationInterface;
use Pfilsx\DataGrid\Grid\Columns\AbstractColumn;
use InvalidArgumentException;
use Pfilsx\DataGrid\Grid\Providers\DataProvider;
use Pfilsx\DataGrid\Grid\Providers\DataProviderInterface;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

class DataGridFactory implements DataGridFactoryInterface
{
    protected $container = [];
    /**
     * @var Request
     */
    protected $request;
    /**
     * @var DataGridBuilder
     */
    protected $gridBuilder;

    protected $filterBuilder;

    /**
     * @var DataProviderInterface
     */
    protected $provider;
    /**
     * @var AbstractGridType
     */
    protected $gridType;
    /**
     * @var array
     */
    protected $options = [
        'filters' => []
    ];
    /**
     * @var AbstractColumn[]
     */
    protected $columns;
    /**
     * @var array
     */
    protected $queryParams;

    public function __construct(
        ManagerRegistry $doctrine,
        RouterInterface $router,
        Environment $twig,
        RequestStack $requestStack,
        DataGridConfigurationInterface $configs
    )
    {
        $this->request = $this->container['request'] = $requestStack->getCurrentRequest();
        $this->options['twig'] = $this->container['twig'] = $twig;
        $this->options['router'] = $this->container['router'] = $router;
        $this->container['doctrine'] = $doctrine;
        $this->gridBuilder = new DataGridBuilder($this->container);
        $this->filterBuilder = new DataGridFiltersBuilder($this->container);
        $this->options['filtersCriteria'] = $this->filterBuilder->getCriteria();
        foreach ($configs->getConfigs() as $key => $value) {
            $setter = 'setDefault' . ucfirst($key);
            if (method_exists($this, $setter)) {
                $this->$setter($value);
            }
        }
    }


    public function createGrid(string $gridType, $dataProvider): DataGrid
    {
        if (!is_subclass_of($gridType, AbstractGridType::class)) {
            throw new InvalidArgumentException('Expected subclass of ' . AbstractGridType::class);
        }
        $this->provider = DataProvider::create($dataProvider);
        /** @var AbstractGridType $type */
        $this->gridType = new $gridType($this->container);
        $this->gridType->buildGrid($this->gridBuilder);
        $this->columns = $this->gridBuilder->getColumns();
        $this->options = array_merge($this->options, $this->gridBuilder->getOptions());
        $this->handleRequest();
        return new DataGrid($this->provider, $this->columns, $this->options);
    }

    protected function handleRequest(): void
    {
        $this->queryParams = $this->request->query->has('data_grid') ? $this->request->query->get('data_grid') : [];

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
        if (array_key_exists('pagination', $this->options) && $this->options['pagination']) {
            if (array_key_exists('page', $this->queryParams)) {
                $this->setPage($this->queryParams['page']);
                unset($this->queryParams['page']);
            } else {
                $this->setPage(1);
            }
        }
    }

    protected function handleFilters()
    {
        foreach ($this->columns as $column) {
            if ($column->hasFilter() && array_key_exists($column->getAttribute(), $this->queryParams)) {
                $column->setFilterValue($this->queryParams[$column->getAttribute()]);
            }
        }
        $this->filterBuilder->setParams($this->queryParams);
        $this->gridType->handleFilters($this->filterBuilder, $this->queryParams);
        $this->options['filtersCriteria'] = $this->filterBuilder->getCriteria();
    }

    protected function setSort($attribute)
    {
        $first = substr($attribute, 0, 1);
        if ($first == '-') {
            $this->options['sort'] = [substr($attribute, 1), 'DESC'];
        } else {
            $this->options['sort'] = [$attribute, 'ASC'];
        }
    }

    protected function setPage($page)
    {
        $this->options['page'] = is_numeric($page) ? (int)$page : 1;
    }

    protected function setDefaultTemplate($template)
    {
        $this->gridBuilder->setTemplate($template);
    }

    protected function setDefaultNoDataMessage($message)
    {
        $this->gridBuilder->setNoDataMessage($message);
    }

    protected function setDefaultPagination($pagination)
    {
        if ($pagination['enabled']) {
            $this->gridBuilder->enablePagination($pagination['options']);
        }
    }
}
