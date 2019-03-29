<?php


namespace Pfilsx\DataGrid\Grid;


use Pfilsx\DataGrid\Grid\Columns\AbstractColumn;
use Doctrine\Common\Collections\Criteria;
use Pfilsx\DataGrid\Grid\Providers\DataProviderInterface;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;
use Twig\Template;

/**
 * Class DataGrid
 * @package Pfilsx\DataGrid\Grid
 * @internal
 */
class DataGrid
{
    /**
     * @var AbstractColumn[]
     */
    protected $columns = [];
    /**
     * @var bool
     */
    protected $hasFilters = false;
    /**
     * @var bool
     */
    protected $showTitles = true;
    /**
     * @var DataProviderInterface
     */
    protected $provider;

    /**
     * @var Template
     */
    protected $template;
    /**
     * @var RouterInterface
     */
    protected $router;
    /**
     * @var Environment
     */
    protected $twig;

    protected $noDataMessage = 'No data found';

    protected $limit = null;

    protected $page = 1;

    protected $maxPage;

    protected $pagination = false;

    protected $paginationOptions = [
        'limit' => 10
    ];


    /**
     * @var Criteria
     */
    protected $filtersCriteria;

    /**
     * DataGrid constructor.
     * @param DataProviderInterface $provider
     * @param array $columns
     * @param array $options
     * @internal
     */
    public function __construct(DataProviderInterface $provider, array $columns, array $options = [])
    {
        $this->provider = $provider;
        $this->columns = $columns;
        $this->twig = $options['twig'];
        $this->setConfigurationOptions($options);

        foreach ($columns as $column) {
            if ($column->hasFilter() && $column->isVisible()) {
                $this->hasFilters = true;
                break;
            }
        }
        if ($this->hasPagination()) {
            $this->rebuildPaginationOptions();
        }
    }

    /**
     * @internal
     * @param $options
     */
    protected function setConfigurationOptions($options)
    {
        foreach ($options as $key => $value) {
            $setter = 'set' . ucfirst($key);
            if (method_exists($this, $setter)) {
                $this->$setter($value);
            }
        }
    }

    public function getShowTitles()
    {
        return $this->showTitles;
    }

    protected function setShowTitles($value)
    {
        $this->showTitles = (bool)$value;
    }

    public function getProvider()
    {
        return $this->provider;
    }

    public function getColumns()
    {
        return $this->columns;
    }

    public function hasFilters()
    {
        return $this->hasFilters;
    }

    public function getData()
    {
        return $this->provider->getItems();
    }

    /**
     * @return Template
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @internal
     * @param string $path
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function setTemplate(string $path)
    {
        $template = $this->twig->loadTemplate($path);
        $this->template = $template;
    }

    public function getRouter()
    {
        return $this->router;
    }

    protected function setRouter(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function getNoDataMessage()
    {
        return $this->noDataMessage;
    }

    protected function setNoDataMessage(string $message)
    {
        $this->noDataMessage = $message;
    }

    protected function setSort(array $sort)
    {
        list($attribute, $direction) = $sort;
        foreach ($this->columns as $column) {
            if ($column->hasSort() && $column->getAttribute() == $attribute) {
                $column->setSort($direction);
                $this->provider->setSort([$attribute => $direction]);
                break;
            }
        }
    }

    protected function setPage(int $page)
    {
        $this->page = $page;
    }

    protected function setPagination(bool $value)
    {
        $this->pagination = $value;
    }

    public function hasPagination()
    {
        return $this->pagination && is_numeric($this->paginationOptions['limit']);
    }

    protected function setPaginationOptions(array $options)
    {
        $this->paginationOptions = array_merge($this->paginationOptions, $options);
    }

    public function getPaginationOptions()
    {
        return $this->provider->getPager()->getPaginationOptions();
    }

    protected function setFiltersCriteria(Criteria $criteria)
    {
        $this->provider->setCriteria($criteria);
    }
    /**
     * @internal
     */
    protected function rebuildPaginationOptions()
    {
        $this->provider->setPagerConfiguration([
            'page' => $this->page,
            'limit' => $this->paginationOptions['limit']
        ]);
    }
}
