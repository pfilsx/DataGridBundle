<?php


namespace Pfilsx\DataGrid\tests;


use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use InvalidArgumentException;
use Pfilsx\DataGrid\Config\DataGridConfiguration;
use Pfilsx\DataGrid\Grid\AbstractGridType;
use Pfilsx\DataGrid\Grid\DataGridFactory;

class DataGridFactoryTest extends BaseCase
{
    /**
     * @var DataGridFactory
     */
    private $factory;

    protected function setUp(): void
    {
        parent::setUp();
        $configuration = new DataGridConfiguration([
            'template' => 'test_template',
            'noDataMessage' => 'empty',
            'pagination' => ['limit' => 10]
        ]);
        $this->factory = new DataGridFactory($this->container, $this->container->get('request_stack'), $configuration);
    }

    public function testWrongGridTypeException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->factory->createGrid('testClass', $this->createMock(ServiceEntityRepository::class));
    }
}