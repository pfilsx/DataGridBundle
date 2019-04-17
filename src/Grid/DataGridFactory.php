<?php


namespace Pfilsx\DataGrid\Grid;


use Pfilsx\DataGrid\Config\DataGridConfigurationInterface;
use InvalidArgumentException;
use Pfilsx\DataGrid\Grid\Providers\DataProvider;
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
    /**
     * @var DataGridFiltersBuilder
     */
    protected $filterBuilder;
    /**
     * @var AbstractGridType
     */
    protected $gridType;
    /**
     * @var array
     */
    protected $defaultOptions = [];
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
        $this->defaultOptions['twig'] = $this->container['twig'] = $twig;
        $this->defaultOptions['router'] = $this->container['router'] = $router;
        $this->container['doctrine'] = $doctrine;
        $this->gridBuilder = new DataGridBuilder($this->container);
        $this->filterBuilder = new DataGridFiltersBuilder();

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
        $provider = DataProvider::create($dataProvider, $this->container['doctrine']);
        $this->gridBuilder->setProvider($provider);
        $this->filterBuilder->setProvider($provider);

        /** @var AbstractGridType $type */
        $this->gridType = new $gridType($this->container);
        $this->gridType->buildGrid($this->gridBuilder);
        $this->handleRequest();
        if ($this->gridBuilder->hasPagination()) {
            $this->gridBuilder->getPager()->setTotalCount($provider->getTotalCount());
        }

        return new DataGrid($this->gridBuilder, $this->defaultOptions);
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
        if ($this->gridBuilder->hasPagination()) {
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
