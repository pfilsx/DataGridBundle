<?php


namespace Pfilsx\DataGrid\Grid;


use Pfilsx\DataGrid\Config\ConfigurationContainerInterface;
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
    }


    public function createGrid(string $gridType, $dataSource, array $params = []): DataGridInterface
    {
        if (!is_subclass_of($gridType, AbstractGridType::class)) {
            throw new InvalidArgumentException('Expected subclass of ' . AbstractGridType::class);
        }
        $provider = DataProvider::create($dataSource, $this->container->getDoctrine());

        /** @var AbstractGridType $type */
        $gridType = new $gridType($this->container, $params);


        return new DataGrid($gridType, $provider, $this->defaultConfiguration, $this->container);
    }
}
