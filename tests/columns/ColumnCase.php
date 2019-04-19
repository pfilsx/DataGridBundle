<?php


namespace Pfilsx\DataGrid\tests\columns;


use Pfilsx\DataGrid\Grid\DataGrid;
use Pfilsx\DataGrid\tests\BaseCase;
use Twig\Template;

class ColumnCase extends BaseCase
{
    /**
     * @var DataGrid
     */
    protected $grid;

    protected function setUp(): void
    {
        parent::setUp();
        $self = $this;
        $this->grid = $this->createMock(DataGrid::class);
        $this->grid->expects($this->any())
            ->method('getTemplate')
            ->will($this->returnCallback(function () use ($self) {
                $mock = $self->createMock(Template::class);
                return $mock;
            }));

        $this->grid->expects($this->any())
            ->method('getRouter')
            ->will($this->returnCallback(function () use ($self) {
                return $self->containerMock->get('router');
            }));
    }
}
