<?php

namespace Pfilsx\DataGrid\tests\twig;

use Pfilsx\DataGrid\Grid\DataGrid;
use Pfilsx\DataGrid\Grid\DataGridView;
use Pfilsx\DataGrid\Twig\DataGridExtension;
use Pfilsx\tests\KernelTestCase;
use ReflectionClass;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\Template;

class DataGridExtensionTest extends KernelTestCase
{
    /**
     * @var DataGridExtension
     */
    private $extension;
    /**
     * @var DataGridView|\PHPUnit\Framework\MockObject\MockObject
     */
    private $gridView;

    protected function setUp(): void
    {
        parent::setUp();
        $this->extension = new DataGridExtension();

        $template = $this->serviceContainer->getTwig()->loadTemplate('test_template.html.twig');
        $grid = $this->createMock(DataGrid::class);
        $grid->expects($this->any())
            ->method('getTemplate')
            ->willReturn($template);
        $grid->expects($this->any())
            ->method('createView')
            ->willReturn(new DataGridView($grid, $this->serviceContainer));

        $this->gridView = $grid->createView();
    }

    public function testClass(): void
    {
        $this->assertInstanceOf(AbstractExtension::class, $this->extension);
    }

    public function testGetFunctions(): void
    {
        $this->assertIsArray($this->extension->getFunctions());
        $this->assertCount(8, $this->extension->getFunctions());
    }

    /**
     * @dataProvider funcProvider
     * @param string $func
     * @param string $result
     * @param bool $checkNull
     */
    public function testMainFunctions($func, $result, $checkNull): void
    {
        if ($func === 'gridEnd') {
            $this->assertNull($this->extension->$func($this->gridView));
            $this->extension->gridStart($this->gridView);
        }
        $this->assertEquals($result, str_replace("\n", ' ', trim($this->extension->$func($this->gridView))));
        if ($checkNull)
            $this->assertNull($this->extension->$func($this->gridView));
    }

    public function testPagination()
    {
        $this->assertEquals('grid_pagination', trim($this->extension->gridPagination($this->gridView)));
        $this->assertEquals('grid_pagination', trim($this->extension->gridPagination($this->gridView)));
    }


    public function funcProvider()
    {
        return [
            ['gridStart', 'grid_start', true],
            ['gridHead', 'grid_head', true],
            ['gridFilters', 'grid_filters', true],
            ['gridBody', 'grid_body', true],
            ['gridEnd', 'grid_end', true],
            ['gridPagination', 'grid_pagination', false],
            ['gridWidget', '<thead> grid_head grid_filters </thead> <tbody> grid_body </tbody>', false],
            ['gridView', 'grid_start <thead> grid_head grid_filters </thead> <tbody> grid_body </tbody> grid_end grid_pagination', false],
        ];
    }

//    /**
//     * @dataProvider gridProvider
//     * @param Environment $env
//     * @param DataGrid $grid
//     */
//    public function testGenerateGrid($env, $grid): void
//    {
//        $this->assertEquals('', $this->extension->generateGrid($env, $grid));
//    }

    public function gridProvider()
    {
        $environment = $this->createMock(Environment::class);
        $environment->expects($this->any())
            ->method('loadTemplate')
            ->willReturn($this->createMock(Template::class));
        $grid = $this->createGridMock();
        $grid2 = $this->createGridMock(false);
        return [
            [$environment, $grid],
            [$environment, $grid2],
        ];
    }

    private function createGridMock($withBlock = true)
    {

        $template = $this->createMock(Template::class);
        $template->expects($this->any())
            ->method('hasBlock')
            ->willReturn($withBlock);

        $grid = $this->createMock(DataGrid::class);
        $grid->expects($this->any())
            ->method('getTemplate')
            ->willReturn($template);
        return $grid;
    }
}
