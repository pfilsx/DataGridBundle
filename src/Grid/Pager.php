<?php


namespace Pfilsx\DataGrid\Grid;


class Pager
{
    protected $page = 1;

    protected $limit = null;

    protected $totalCount;

    protected $maxPage;

    protected $pages;

    public function __construct(array $config)
    {
        foreach ($config as $key => $value) {
            $setter = 'set' . ucfirst($key);
            if (method_exists($this, $setter)) {
                $this->$setter($value);
            }
        }
        $this->buildPaginationOptions();
    }

    /**
     * @return mixed
     */
    public function getPage()
    {
        return $this->page;
    }

    public function getFirst()
    {
        return ($this->page - 1) * $this->limit;
    }

    /**
     * @param mixed $page
     */
    protected function setPage($page): void
    {
        $this->page = intval($page);
    }

    /**
     * @return mixed
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param mixed $limit
     */
    protected function setLimit($limit): void
    {
        $this->limit = $limit;
    }

    /**
     * @param mixed $totalCount
     */
    protected function setTotalCount($totalCount): void
    {
        $this->totalCount = $totalCount;
    }

    public function getPaginationOptions()
    {
        return [
            'currentPage' => $this->page,
            'pages' => $this->pages
        ];
    }

    protected function buildPaginationOptions()
    {
        if (is_int($this->limit) && is_int($this->totalCount)) {
            $this->maxPage = (int)ceil($this->totalCount / $this->limit);
            $this->page = $this->page > 0 && $this->page <= $this->maxPage ? $this->page : 1;
            $this->pages = $this->buildPages();
        }
    }

    protected function buildPages()
    {
        if ($this->maxPage === 0) {
            return [1];
        }
        if ($this->maxPage <= 10) {
            return range(1, $this->maxPage);
        }
        if ($this->page < 5) {
            return array_merge(range(1, 6), [null, $this->maxPage]);
        }
        if ($this->page > $this->maxPage - 4) {
            return array_merge([1, null], range($this->maxPage - 5, $this->maxPage));
        }
        return array_merge([1, null], range($this->page - 2, $this->page + 2), [null, $this->maxPage]);
    }

}
