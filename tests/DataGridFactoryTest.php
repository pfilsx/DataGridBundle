<?php


namespace Pfilsx\DataGrid\tests;

use InvalidArgumentException;
use Pfilsx\DataGrid\Config\ConfigurationContainer;
use Pfilsx\DataGrid\DataGridServiceContainer;
use Pfilsx\DataGrid\Grid\AbstractGridType;
use Pfilsx\DataGrid\Grid\DataGridFactory;
use Pfilsx\tests\OrmTestCase;
use Pfilsx\tests\TestEntities\Node;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Template;

class DataGridFactoryTest extends OrmTestCase
{
    /**
     * @var DataGridFactory
     */
    private $factory;

    private $configuration;

    protected function setUp(): void
    {
        parent::setUp();
        $this->configuration = new ConfigurationContainer([
            'instances' => [
                'default' => [
                    'template' => 'test_template.html.twig',
                    'no_data_message' => 'empty',
                    'show_titles' => false,
                    'pagination_enabled' => true,
                    'pagination_limit' => 5
                ]
            ]
        ]);
        $request = new Request();
        $request->query->add([
            'data_grid' => [
                'sortBy' => 'id',
                'page' => 1
            ]
        ]);
        $stack = new RequestStack();
        $stack->push($request);
        /** @noinspection PhpParamsInspection */
        $container = new DataGridServiceContainer(
            static::$kernel->getContainer()->get('doctrine'),
            static::$kernel->getContainer()->get('router'),
            static::$kernel->getContainer()->get('twig'),
            $stack,
            static::$kernel->getContainer()->get('translator')
        );
        $this->factory = new DataGridFactory($container, $this->configuration);
    }

    public function testWrongGridTypeException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->factory->createGrid('testType', $this->getEntityManager()->getRepository(Node::class));
    }

    public function testCreateGrid(): void
    {
        $grid = $this->factory->createGrid(get_class($this->createMock(AbstractGridType::class)), $this->getEntityManager()->getRepository(Node::class));
        $this->assertEquals('empty', $grid->getNoDataMessage());
        $this->assertTrue($grid->hasPagination());
        $this->assertInstanceOf(Template::class, $grid->getTemplate());
        $this->assertIsArray($grid->getData());
        $this->assertNotEmpty($grid->getData());
        $this->assertFalse($grid->hasFilters());
        $this->assertEmpty($grid->getColumns());
        $this->assertFalse($grid->getShowTitles());
        $this->assertEquals([
            'currentPage' => 1,
            'pages' => []
        ], $grid->getPaginationOptions());

        $request = new Request();
        $request->query->add([
            'data_grid' => [
                'sortBy' => '-id'
            ]
        ]);
        $stack = new RequestStack();
        $stack->push($request);
        /** @noinspection PhpParamsInspection */
        $container = new DataGridServiceContainer(
            static::$kernel->getContainer()->get('doctrine'),
            static::$kernel->getContainer()->get('router'),
            static::$kernel->getContainer()->get('twig'),
            $stack,
            static::$kernel->getContainer()->get('translator')
        );
        $factory2 = new DataGridFactory($container, $this->configuration);
        $grid2 = $factory2->createGrid(get_class($this->createMock(AbstractGridType::class)), $this->getEntityManager()->getRepository(Node::class));

        $this->assertEquals([
            'currentPage' => 1,
            'pages' => []
        ], $grid2->getPaginationOptions());
    }
}
