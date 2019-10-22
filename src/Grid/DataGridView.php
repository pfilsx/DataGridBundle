<?php


namespace Pfilsx\DataGrid\Grid;


use Pfilsx\DataGrid\DataGridServiceContainer;

class DataGridView
{
    /**
     * @var DataGrid
     */
    private $grid;
    /**
     * @var DataGridServiceContainer
     */
    private $container;

    private $renderStarted = false;
    private $headRendered = false;
    private $filtersRendered = false;
    private $bodyRendered = false;
    private $renderEnded = false;


    public function __construct(DataGrid $grid, DataGridServiceContainer $container)
    {
        $this->grid = $grid;
        $this->container = $container;
    }

    public function renderGridStart($attributes = []): ?string
    {
        if (!$this->renderStarted) {
            $this->renderStarted = true;
            return $this->grid->getTemplate()->renderBlock('grid_start', [
                'attr' => $attributes,
                'data_grid' => $this->grid,
                'request' => $this->container->getRequest()->getCurrentRequest()
            ]);
        }
        return null;
    }

    public function renderGridHead(): ?string
    {
        if (!$this->headRendered) {
            $this->headRendered = true;
            return $this->grid->getTemplate()->renderBlock('grid_head', [
                'data_grid' => $this->grid,
                'request' => $this->container->getRequest()->getCurrentRequest()
            ]);
        }
        return null;
    }

    public function renderGridFilters(): ?string
    {
        if (!$this->filtersRendered) {
            $this->filtersRendered = true;
            return $this->grid->getTemplate()->renderBlock('grid_filters', [
                'data_grid' => $this->grid,
                'request' => $this->container->getRequest()->getCurrentRequest()
            ]);
        }
        return null;
    }

    public function renderGridBody(): ?string
    {
        if (!$this->bodyRendered) {
            $this->bodyRendered = true;
            return $this->grid->getTemplate()->renderBlock('grid_body', [
                'data_grid' => $this->grid,
                'request' => $this->container->getRequest()->getCurrentRequest()
            ]);
        }
        return null;
    }

    public function renderGridEnd(): ?string
    {
        if ($this->renderStarted && !$this->renderEnded) {
            $this->renderEnded = true;
            return $this->grid->getTemplate()->renderBlock('grid_end', [
                'data_grid' => $this->grid,
                'request' => $this->container->getRequest()->getCurrentRequest()
            ]);
        }
        return null;
    }

    public function renderGridPagination(): ?string
    {
        return $this->grid->getTemplate()->renderBlock('grid_pagination', [
            'data_grid' => $this->grid,
            'request' => $this->container->getRequest()->getCurrentRequest()
        ]);
    }

    public function renderGridWidget(): ?string
    {
        return implode("\n", [
            '<thead>',
            $this->renderGridHead(),
            $this->renderGridFilters(),
            '</thead>',
            '<tbody>',
            $this->renderGridBody(),
            '</tbody>'
        ]);
    }

    public function renderGridView($attributes = []): ?string
    {
        return implode("\n", [
            $this->renderGridStart($attributes),
            $this->renderGridWidget(),
            $this->renderGridEnd(),
            $this->renderGridPagination()
        ]);
    }
}