<?php


namespace Pfilsx\DataGrid\tests;

use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Expression;
use Pfilsx\DataGrid\Grid\DataGridFiltersBuilder;

class DataGridFiltersBuilderTest extends BaseCase
{
    /**
     * @var DataGridFiltersBuilder
     */
    private $builder;

    protected function setUp(): void
    {
        parent::setUp();
        $this->builder = new DataGridFiltersBuilder($this->containerArray);
    }

    public function testAddEqualFilterOnEmptyData(): void
    {
        $this->assertNull($this->builder->getCriteria()->getWhereExpression());
        $this->builder->addEqualFilter('id');
        $this->assertNull($this->builder->getCriteria()->getWhereExpression());
    }

    public function testAddEqualFilter(): void
    {
        $this->assertNull($this->builder->getCriteria()->getWhereExpression());
        $this->builder->setParams(['id' => '1']);
        $this->builder->addEqualFilter('id');
        $this->assertInstanceOf(Expression::class, $this->builder->getCriteria()->getWhereExpression());
    }

    public function testAddEqualFilterNull(): void
    {
        $this->assertNull($this->builder->getCriteria()->getWhereExpression());
        $this->builder->setParams(['id' => null]);
        $this->builder->addEqualFilter('id');
        $this->assertInstanceOf(Expression::class, $this->builder->getCriteria()->getWhereExpression());
    }

    public function testAddLikeFilterOnEmptyData(): void
    {
        $this->assertNull($this->builder->getCriteria()->getWhereExpression());
        $this->builder->addLikeFilter('id');
        $this->assertNull($this->builder->getCriteria()->getWhereExpression());
    }

    public function testAddLikeFilter(): void
    {
        $this->assertNull($this->builder->getCriteria()->getWhereExpression());
        $this->builder->setParams(['id' => '1']);
        $this->builder->addLikeFilter('id');
        $this->assertInstanceOf(Expression::class, $this->builder->getCriteria()->getWhereExpression());
    }

    public function testAddRelationFilterOnEmptyData(): void
    {
        $this->assertNull($this->builder->getCriteria()->getWhereExpression());
        $this->builder->addRelationFilter('fid', 'App\Entity\TestEntity');
        $this->assertNull($this->builder->getCriteria()->getWhereExpression());
    }

    public function testAddRelationFilter(): void
    {
        $this->assertNull($this->builder->getCriteria()->getWhereExpression());
        $this->builder->setParams(['fid' => '1']);
        $this->builder->addRelationFilter('fid', 'App\Entity\TestEntity');
        $this->assertInstanceOf(Expression::class, $this->builder->getCriteria()->getWhereExpression());
    }

    public function testAddCustomFilterOnEmptyData(): void
    {
        $this->assertNull($this->builder->getCriteria()->getWhereExpression());
        $this->builder->addCustomFilter('id', function ($criteria, $attr, $value) {
            $criteria->andWhere(Criteria::expr()->eq($attr, $value));
        });
        $this->assertNull($this->builder->getCriteria()->getWhereExpression());
    }

    public function testAddCustomFilter(): void
    {
        $this->assertNull($this->builder->getCriteria()->getWhereExpression());
        $this->builder->setParams(['id' => '1']);
        $this->builder->addCustomFilter('id', function ($criteria, $attr, $value) {
            $criteria->andWhere(Criteria::expr()->eq($attr, $value));
        });
        $this->assertInstanceOf(Expression::class, $this->builder->getCriteria()->getWhereExpression());
    }

    public function testAddDateFilterOnEmptyData(): void
    {
        $this->assertNull($this->builder->getCriteria()->getWhereExpression());
        $this->builder->addDateFilter('id');
        $this->assertNull($this->builder->getCriteria()->getWhereExpression());
    }

    /**
     * @dataProvider comparisonProvider
     * @param string $comparison
     */
    public function testAddDateFilter($comparison): void
    {
        $this->assertNull($this->builder->getCriteria()->getWhereExpression());
        $this->builder->setParams(['date' => '01-01-1990']);
        $this->builder->addDateFilter('date', $comparison);
        $this->assertInstanceOf(Expression::class, $this->builder->getCriteria()->getWhereExpression());
    }


    public function comparisonProvider()
    {
        return [
            ['equal'],
            ['notEqual'],
            ['lt'],
            ['lte'],
            ['gt'],
            ['gte'],
            ['someTest']
        ];
    }
}
