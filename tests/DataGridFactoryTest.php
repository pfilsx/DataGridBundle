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
            'pagination' => []
        ]);
        $this->factory = new DataGridFactory(
            $this->containerMock->get('doctrine'),
            $this->containerMock->get('router'),
            $this->containerMock->get('twig'),
            $this->containerMock->get('request_stack'), $configuration);
    }

    public function testWrongGridTypeException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->factory->createGrid('testType', $this->createMock(ServiceEntityRepository::class));
    }

    public function testCreateGrid(): void
    {
        $this->factory->createGrid(get_class($this->createMock(AbstractGridType::class)), $this->createMock(ServiceEntityRepository::class));
        $this->assertTrue(true);
    }
}
