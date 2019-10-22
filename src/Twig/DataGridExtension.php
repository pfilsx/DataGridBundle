<?php


namespace Pfilsx\DataGrid\Twig;


use Pfilsx\DataGrid\Grid\DataGridView;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class DataGridExtension extends AbstractExtension
{

    public function getFunctions(): array
    {
        return [
            new TwigFunction('grid_start', [$this, 'gridStart'], [
                'is_safe' => ['html']
            ]),
            new TwigFunction('grid_head', [$this, 'gridHead'], [
                'is_safe' => ['html']
            ]),
            new TwigFunction('grid_filters', [$this, 'gridFilters'], [
                'is_safe' => ['html']
            ]),
            new TwigFunction('grid_body', [$this, 'gridBody'], [
                'is_safe' => ['html']
            ]),
            new TwigFunction('grid_end', [$this, 'gridEnd'], [
                'is_safe' => ['html']
            ]),
            new TwigFunction('grid_pagination', [$this, 'gridPagination'], [
                'is_safe' => ['html']
            ]),
            new TwigFunction('grid_widget', [$this, 'gridWidget'], [
                'is_safe' => ['html']
            ]),
            new TwigFunction('grid_view', [$this, 'gridView'], [
                'is_safe' => ['html']
            ]),
        ];
    }

    public function gridStart(DataGridView $gridView, array $attributes = [])
    {
        return $gridView->renderGridStart($attributes);
    }

    public function gridHead(DataGridView $gridView)
    {
        return $gridView->renderGridHead();
    }

    public function gridFilters(DataGridView $gridView)
    {
        return $gridView->renderGridFilters();
    }

    public function gridBody(DataGridView $gridView)
    {
        return $gridView->renderGridBody();
    }

    public function gridEnd(DataGridView $gridView)
    {
        return $gridView->renderGridEnd();
    }

    public function gridPagination(DataGridView $gridView)
    {
        return $gridView->renderGridPagination();
    }

    public function gridWidget(DataGridView $gridView)
    {
        return $gridView->renderGridWidget();
    }

    public function gridView(DataGridView $gridView, array $attributes = [])
    {
        return $gridView->renderGridView($attributes);
    }
}
