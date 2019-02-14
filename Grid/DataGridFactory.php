<?php


namespace Pfilsx\DataGrid\Grid;


use Pfilsx\DataGrid\Grid\Columns\AbstractColumn;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use InvalidArgumentException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class DataGridFactory implements DataGridFactoryInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;
    /**
     * @var DataGridBuilder
     */
    protected $gridBuilder;

    protected $filterBuilder;

    /**
     * @var ServiceEntityRepository
     */
    protected $repository;
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

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->options['twig'] = $container->get('twig');
        $this->options['router'] = $container->get('router');
        $this->gridBuilder = new DataGridBuilder($container);
        $this->filterBuilder = new DataGridFiltersBuilder($container);
        $this->options['filtersCriteria'] = $this->filterBuilder->getCriteria();
    }


    public function createGrid(string $gridType, ServiceEntityRepository $repository): DataGridFactoryInterface
    {
        if (!is_subclass_of($gridType, AbstractGridType::class)) {
            throw new InvalidArgumentException('Expected subclass of' . AbstractGridType::class);
        }
        $this->repository = $repository;
        /** @var AbstractGridType $type */
        $this->gridType = new $gridType($this->container);
        $this->gridType->buildGrid($this->gridBuilder);
        $this->columns = $this->gridBuilder->getColumns();
        $this->options = array_merge($this->options, $this->gridBuilder->getOptions());
        return $this;
    }

    public function handleRequest(Request $request): DataGridFactoryInterface
    {
        $params = array_key_exists('data_grid', $request->query->all()) ? $request->query->get('data_grid') : [];
        if (array_key_exists('sortBy', $params)){
            $this->setSort($params['sortBy']);
            unset($params['sortBy']);
        }
        if (array_key_exists('pagination',$this->options) && $this->options['pagination']){
            if (array_key_exists('page', $params)){
                $this->setPage($params['page']);
                unset($params['page']);
            } else {
                $this->setPage(1);
            }
        }
        foreach ($this->columns as $column){
            if ($column->hasFilter() && array_key_exists($column->getAttribute(), $params)){
                $column->setFilterValue($params[$column->getAttribute()]);
            }
        }
        $this->gridType->handleFilters($this->filterBuilder, $params);
        $this->options['filtersCriteria'] = $this->filterBuilder->getCriteria();
        return $this;
    }

    public function getGrid(): DataGrid
    {
        return new DataGrid($this->repository, $this->columns, $this->options);
    }

    protected function setSort($attribute){
        $first = substr($attribute, 0, 1);
        if ($first == '-'){
            $this->options['sort'] = [substr($attribute,1), 'DESC'];
        } else {
            $this->options['sort'] = [$attribute, 'ASC'];
        }
    }

    protected function setPage($page){
        if (is_numeric($page))
            $this->options['page'] = (int)$page;
        else
            $this->options['page'] = 1;
    }
}