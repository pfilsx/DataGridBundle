<?php


namespace Pfilsx\DataGrid\tests\columns;


use Exception;
use Pfilsx\DataGrid\Grid\Columns\ActionColumn;
use Pfilsx\DataGrid\Grid\DataGrid;
use Pfilsx\DataGrid\tests\BaseCase;
use Twig\Template;

/**
 * Class ActionColumnTest
 * @package Pfilsx\DataGrid\tests\columns
 *
 * @property ActionColumn $testColumn
 */
class ActionColumnTest extends BaseCase
{
    private $grid;

    protected function setUp(): void
    {
        parent::setUp();
        $this->testColumn = new ActionColumn($this->container, [
            'buttonsTemplate' => '{show} {delete}',
            'buttons' => [
                'show' => function ($entity, $url) {
                    return $url;
                }
            ],
            'pathPrefix' => 'test_prefix',
            'urlGenerator' => function ($entity, $action, $router) {
                return '_' . $action;
            },
            'buttonsVisibility' => [
                'show' => true,
                'delete' => false
            ]
        ]);
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
                return $self->container->get('router');
            }));
    }

    public function testGetHeadContent(): void
    {
        $this->assertEmpty($this->testColumn->getHeadContent());
    }

    public function testFilter(): void
    {
        $this->assertFalse($this->testColumn->hasFilter());
        $this->assertEmpty($this->testColumn->getFilterContent());
    }

    public function testGetButtonsTemplate(): void
    {
        $this->assertEquals('{show} {delete}', $this->testColumn->getButtonsTemplate());
    }

    public function testButtons(): void
    {
        $this->assertArrayHasKey('show', $this->testColumn->getButtons());
        $this->assertIsCallable($this->testColumn->getButtons()['show']);
    }

    public function testGetUrlGenerator(): void
    {
        $this->assertIsCallable($this->testColumn->getUrlGenerator());
    }

    public function testGetPrefix(): void
    {
        $this->assertEquals('test_prefix', $this->testColumn->getPathPrefix());
    }

    public function testGetButtonVisibility(): void
    {
        $this->assertTrue($this->testColumn->isButtonVisible('show'));
        $this->assertFalse($this->testColumn->isButtonVisible('delete'));
    }

    public function testGetCellContent(): void
    {
        $entity = new class()
        {
            public function getId()
            {
                return 1;
            }
        };
        $this->assertEquals('_show ', $this->testColumn->getCellContent($entity, $this->grid));

        $column = new ActionColumn($this->container, [
            'pathPrefix' => 'test_prefix'
        ]);
        $this->assertEquals('  ', $column->getCellContent($entity, $this->grid));
    }

    public function testUrlGeneratorException(): void
    {
        $this->expectException(Exception::class);
        $entity = new class(){};
        $column = new ActionColumn($this->container, [
            'pathPrefix' => 'test_prefix'
        ]);
        $column->getCellContent($entity, $this->grid);
    }
}