<?php


namespace Pfilsx\DataGrid\Grid;


use Pfilsx\DataGrid\Grid\Columns\AbstractColumn;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
     * @var ServiceEntityRepository
     */
    protected $repository;

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

    protected $sort = [];

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
     * @param ServiceEntityRepository $repository
     * @param array $columns
     * @param array $options
     * @internal
     */
    public function __construct(ServiceEntityRepository $repository, array $columns, array $options = [])
    {
        $this->repository = $repository;
        $this->columns = $columns;
        $this->twig = $options['twig'];
        foreach ($options as $key => $value){
            $setter = 'set'.ucfirst($key);
            if (method_exists($this, $setter)){
                $this->$setter($value);
            }
        }
        foreach ($columns as $column){
            if ($column->hasFilter() && $column->isVisible()){
                $this->hasFilters = true;
                break;
            }
        }
        if ($this->hasPagination()){
            $this->rebuildPaginationOptions();
        }
    }

    public function getShowTitles(){
        return $this->showTitles;
    }
    protected function setShowTitles($value){
        $this->showTitles = (bool)$value;
    }

    public function getRepository(){
        return $this->repository;
    }

    public function getColumns(){
        return $this->columns;
    }

    public function hasFilters(){
        return $this->hasFilters;
    }

    public function getData(){
        $this->filtersCriteria
            ->orderBy($this->sort)
            ->setMaxResults($this->hasPagination() ? $this->limit : null)
            ->setFirstResult($this->hasPagination() ? ($this->page-1)*$this->limit : null);
        return $this->repository->matching($this->filtersCriteria);
    }
    /**
     * @return Template
     */
    public function getTemplate(){
        return $this->template;
    }

    /**
     * @internal
     * @param string $path
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function setTemplate(string $path){
        $template = $this->twig->loadTemplate($path);
        $this->template = $template;
    }

    public function getRouter(){
        return $this->router;
    }

    protected function setRouter(RouterInterface $router){
        $this->router = $router;
    }

    public function getNoDataMessage(){
        return $this->noDataMessage;
    }
    protected function setNoDataMessage(string $message){
        $this->noDataMessage = $message;
    }

    protected function setSort(array $sort){
        list($attribute, $direction) = $sort;
        foreach ($this->columns as $column){
            if ($column->hasSort() && $column->getAttribute() == $attribute){
                $column->setSort($direction);
                $this->sort = [$attribute => $direction];
                break;
            }
        }
    }

    protected function setPage(int $page){
        $this->page = $page;
    }

    protected function setPagination(bool $value){
        $this->pagination = $value;
    }

    public function hasPagination(){
        return $this->pagination && is_numeric($this->paginationOptions['limit']);
    }

    protected function setPaginationOptions(array $options){
        $this->paginationOptions = array_merge($this->paginationOptions, $options);
    }

    public function getPaginationOptions(){
        return $this->paginationOptions;
    }

    protected function setFiltersCriteria(Criteria $criteria){
        $this->filtersCriteria = $criteria;
    }

    /**
     * @internal
     */
    protected function rebuildPaginationOptions(){
        $this->limit = $this->paginationOptions['limit'];
        $total = $this->repository->matching($this->filtersCriteria)->count();
        $this->maxPage = (int)ceil($total / $this->limit);
        $this->page  = $this->page != null && $this->page > 0 && $this->page <= $this->maxPage
            ? $this->page : 1;
        if ($this->maxPage == 0){
            $this->paginationOptions['pages'] = [1];
        } elseif ($this->maxPage <= 10){
            $this->paginationOptions['pages'] = range(1,$this->maxPage);
        } elseif ($this->page < 5){
            $this->paginationOptions['pages'] = array_merge(range(1,6), [null,$this->maxPage]);
        } elseif ($this->page  > $this->maxPage - 4){
            $this->paginationOptions['pages'] = array_merge([1, null], range($this->maxPage-5, $this->maxPage));
        } else {
            $this->paginationOptions['pages'] = array_merge([1,null], range($this->page -2,$this->page +2), [null, $this->maxPage]);
        }
        $this->paginationOptions['currentPage'] = $this->page;
    }
}
