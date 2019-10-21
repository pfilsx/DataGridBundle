<?php


namespace Pfilsx\DataGrid\Grid;


class Pager
{
    protected $page = 1;

    protected $limit = null;

    protected $totalCount;

    protected $maxPage;

    protected $pages = [];

    protected $isEnabled;


    public function isEnabled()
    {
        return $this->isEnabled;
    }

    public function enable()
    {
        $this->isEnabled = true;
    }

    public function disable()
    {
        $this->isEnabled = false;
    }

    public function setOptions(array $options)
    {
        foreach ($options as $key => $value) {
            $setter = 'set' . ucfirst($key);
            if (method_exists($this, $setter)) {
                $this->$setter($value);
            }
        }
    }

    /**
     * @return int
     */
    public function getFirst()
    {
        return ($this->page - 1) * $this->limit;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }
    /**
     * @param int $page
     */
    public function setPage(int $page): void
    {
        $this->page = $page;
    }

    /**
     * @return mixed
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param $limit
     */
    public function setLimit($limit): void
    {
        $this->limit = $limit;
    }

    /**
     * @param int $totalCount
     */
    public function setTotalCount(int $totalCount): void
    {
        $this->totalCount = $totalCount;
    }

    public function getPaginationOptions()
    {
        $this->rebuildPaginationOptions();
        return [
            'currentPage' => $this->page,
            'pages' => $this->pages
        ];
    }

    /**
     * @internal
     */
    public function rebuildPaginationOptions()
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
