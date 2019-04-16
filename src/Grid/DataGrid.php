<?php


namespace Pfilsx\DataGrid\Grid;


use Doctrine\Common\Collections\Criteria;
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
     * @var bool
     */
    protected $showTitles = true;

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
     * @var DataGridBuilderInterface
     */
    protected $builder;

    /**
     * DataGrid constructor.
     * @param DataGridBuilderInterface $builder
     * @param array $defaultOptions
     * @internal
     */
    public function __construct(DataGridBuilderInterface $builder, array $defaultOptions = [])
    {
        $this->builder = $builder;
        $this->twig = $defaultOptions['twig'];
        $this->setConfigurationOptions(array_merge($defaultOptions, $builder->getOptions()));

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
        return $this->builder->getProvider();
    }

    public function getColumns()
    {
        return $this->builder->getColumns();
    }

    public function hasFilters()
    {
        return $this->builder->hasFilters();
    }

    public function getData()
    {
        return $this->getProvider()->getItems();
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
        return $this->getProvider()->getPager()->getPaginationOptions();
    }
    /**
     * @internal
     */
    protected function rebuildPaginationOptions()
    {
        $this->getProvider()->setPagerConfiguration([
            'page' => $this->page,
            'limit' => $this->paginationOptions['limit']
        ]);
    }
}
