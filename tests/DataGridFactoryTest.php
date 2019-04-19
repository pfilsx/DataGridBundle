<?php


namespace Pfilsx\DataGrid\tests;


use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use InvalidArgumentException;
use Pfilsx\DataGrid\Config\DataGridConfiguration;
use Pfilsx\DataGrid\Grid\AbstractGridType;
use Pfilsx\DataGrid\Grid\DataGridFactory;
use Pfilsx\tests\OrmTestCase;
use Pfilsx\tests\TestEntities\Node;

class DataGridFactoryTest extends OrmTestCase
{
    /**
     * @var DataGridFactory
     */
    private $factory;

    protected function setUp(): void
    {
        parent::setUp();
        $configuration = new DataGridConfiguration([
            'template' => 'test_template.html.twig',
            'noDataMessage' => 'empty',
            'pagination' => []
        ]);
        $this->factory = new DataGridFactory(
            static::$kernel->getContainer()->get('doctrine'),
            static::$kernel->getContainer()->get('router'),
            static::$kernel->getContainer()->get('twig'),
            static::$kernel->getContainer()->get('request_stack'), $configuration);
    }

    public function testWrongGridTypeException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->factory->createGrid('testType', $this->getEntityManager()->getRepository(Node::class));
    }

    public function testCreateGrid(): void
    {
        $this->factory->createGrid(get_class($this->createMock(AbstractGridType::class)), $this->getEntityManager()->getRepository(Node::class));
        $this->assertTrue(true);
    }
}
