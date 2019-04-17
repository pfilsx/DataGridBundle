<?php


namespace Pfilsx\DataGrid\tests;


use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use InvalidArgumentException;
use Pfilsx\DataGrid\Grid\Columns\AbstractColumn;
use Pfilsx\DataGrid\Grid\Columns\ActionColumn;
use Pfilsx\DataGrid\Grid\Columns\BooleanColumn;
use Pfilsx\DataGrid\Grid\Columns\DataColumn;
use Pfilsx\DataGrid\Grid\Columns\DateColumn;
use Pfilsx\DataGrid\Grid\Columns\ImageColumn;
use Pfilsx\DataGrid\Grid\Columns\SerialColumn;
use Pfilsx\DataGrid\Grid\DataGridBuilder;
use Pfilsx\DataGrid\Grid\Providers\DataProvider;

class DataGridBuilderTest extends BaseCase
{
    /**
     * @var DataGridBuilder
     */
    private $builder;

    const ACTION_COLUMN = 'Pfilsx\DataGrid\Grid\Columns\ActionColumn';
    const BOOLEAN_COLUMN = 'Pfilsx\DataGrid\Grid\Columns\BooleanColumn';
    const IMAGE_COLUMN = 'Pfilsx\DataGrid\Grid\Columns\ImageColumn';
    const DATA_COLUMN = 'Pfilsx\DataGrid\Grid\Columns\DataColumn';
    const SERIAL_COLUMN = 'Pfilsx\DataGrid\Grid\Columns\SerialColumn';
    const DATE_COLUMN = 'Pfilsx\DataGrid\Grid\Columns\DateColumn';

    protected function setUp(): void
    {
        parent::setUp();
        $this->builder = new DataGridBuilder($this->containerArray);
        $provider = DataProvider::create($this->createMock(ServiceEntityRepository::class), $this->containerArray['doctrine']);
        $this->builder->setProvider($provider);
    }

    public function testWrongColumnClass(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->builder->addColumn('wrongClass', []);
    }

    public function testAddColumn()
    {
        $this->builder
            ->addColumn(self::SERIAL_COLUMN);
        $this->assertCount(1, $this->builder->getColumns());
        $this->assertInstanceOf(SerialColumn::class, $this->builder->getColumns()[0]);

        $this->builder->addDataColumn('id');
        $this->assertCount(2, $this->builder->getColumns());
        $this->assertInstanceOf(DataColumn::class, $this->builder->getColumns()[1]);

        $this->builder->addColumn(self::BOOLEAN_COLUMN, [
            'attribute' => 'isEnabled'
        ]);
        $this->assertCount(3, $this->builder->getColumns());
        $this->assertInstanceOf(BooleanColumn::class, $this->builder->getColumns()[2]);

        $this->builder->addColumn(self::IMAGE_COLUMN, [
            'attribute' => 'logo'
        ]);
        $this->assertCount(4, $this->builder->getColumns());
        $this->assertInstanceOf(ImageColumn::class, $this->builder->getColumns()[3]);

        $this->builder->addColumn(self::DATE_COLUMN, [
            'attribute' => 'creationDate',
            'filter' => [
                'class' => 'Pfilsx\DataGrid\Grid\Filters\DateFilter'
            ]
        ]);
        $this->assertCount(5, $this->builder->getColumns());
        $this->assertInstanceOf(DateColumn::class, $this->builder->getColumns()[4]);
        $this->assertTrue($this->builder->hasFilters());

        $this->builder->addColumn(self::ACTION_COLUMN, [
            'pathPrefix' => 'category_'
        ]);
        $this->assertCount(6, $this->builder->getColumns());
        $this->assertInstanceOf(ActionColumn::class, $this->builder->getColumns()[5]);

        return $this->builder;
    }

    /**
     * @depends testAddColumn
     * @param DataGridBuilder $builder
     */
    public function testSetTemplate($builder): void
    {
        $builder->setTemplate('test_template.html.twig');
        $this->assertEquals('test_template.html.twig', $builder->getOptions()['template']);
    }

    public function testSetPagination(): void
    {
        $this->builder->enablePagination(['limit' => 10]);
        $this->assertTrue($this->builder->hasPagination());
        $this->assertEquals(10, $this->builder->getPager()->getLimit());

        $this->builder->enablePagination(false);
        $this->assertFalse($this->builder->hasPagination());
    }

    /**
     * @depends testAddColumn
     * @param DataGridBuilder $builder
     */
    public function testSetSort($builder): void
    {
        $builder->setSort('creationDate', 'DESC');
        foreach ($builder->getColumns() as $column) {
            /**
             * @var $column AbstractColumn
             */
            if ($column->hasSort()) {
                if ($column->getAttribute() == 'creationDate') {
                    $this->assertEquals('DESC', $column->getSort());
                } else {
                    $this->assertTrue($column->getSort());
                }
            } else {
                $this->assertFalse($column->getSort());
            }
        }
    }

    public function testSetCountFieldName(): void
    {
        $this->builder->setCountFieldName('id');
        $this->assertEquals('id', $this->builder->getProvider()->getCountFieldName());
    }

    /**
     * @depends testAddColumn
     * @param DataGridBuilder $builder
     */
    public function testSetFiltersValues($builder): void
    {
        $builder->setFiltersValues(['creationDate' => '01.01.1990']);
        foreach ($builder->getColumns() as $column) {
            /**
             * @var $column AbstractColumn
             */
            if ($column->hasFilter()) {
                $this->assertEquals('01.01.1990', $column->getFilterValue());
            } else {
                $this->assertNull($column->getFilterValue());
            }
        }
    }
}
