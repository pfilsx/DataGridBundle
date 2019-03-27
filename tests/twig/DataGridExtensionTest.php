<?php

namespace Pfilsx\DataGrid\tests\twig;

use Pfilsx\DataGrid\Grid\DataGrid;
use Pfilsx\DataGrid\tests\BaseCase;
use Pfilsx\DataGrid\Twig\DataGridExtension;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\Template;

class DataGridExtensionTest extends BaseCase
{
    /**
     * @var DataGridExtension
     */
    private $extension;

    protected function setUp(): void
    {
        parent::setUp();
        $this->extension = new DataGridExtension($this->container->get('request_stack'));
    }

    public function testClass(): void
    {
        $this->assertInstanceOf(AbstractExtension::class, $this->extension);
    }

    public function testGetFunctions(): void
    {
        $this->assertIsArray($this->extension->getFunctions());
    }

    /**
     * @dataProvider gridProvider
     * @param Environment $env
     * @param DataGrid $grid
     */
    public function testGenerateGrid($env, $grid): void
    {
        $this->assertEquals('', $this->extension->generateGrid($env, $grid));
    }

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
