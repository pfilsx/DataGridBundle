<?php


namespace Pfilsx\DataGrid\tests;

use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Expression;
use Pfilsx\DataGrid\Grid\DataGridFiltersBuilder;
use Pfilsx\DataGrid\Grid\Providers\DataProvider;
use Pfilsx\tests\OrmTestCase;
use Pfilsx\tests\TestEntities\Node;
use Pfilsx\tests\TestEntities\NodeAssoc;

class DataGridFiltersBuilderTest extends OrmTestCase
{
    /**
     * @var DataGridFiltersBuilder
     */
    private $builder;

    protected function setUp(): void
    {
        parent::setUp();
        $this->builder = new DataGridFiltersBuilder();
        $provider = DataProvider::create($this->getEntityManager()->getRepository(Node::class), $this->serviceContainer->getDoctrine());
        $this->builder->setProvider($provider);
    }

    public function testAddEqualFilterOnEmptyData(): void
    {
        $this->assertNull($this->builder->getProvider()->getCriteria()->getWhereExpression());
        $this->builder->addEqualFilter('id');
        $this->assertNull($this->builder->getProvider()->getCriteria()->getWhereExpression());
    }

    public function testAddEqualFilter(): void
    {
        $this->assertNull($this->builder->getProvider()->getCriteria()->getWhereExpression());
        $this->builder->setParams(['id' => '1']);
        $this->builder->addEqualFilter('id');
        $this->assertInstanceOf(Expression::class, $this->builder->getProvider()->getCriteria()->getWhereExpression());
    }

    public function testAddEqualFilterNull(): void
    {
        $this->assertNull($this->builder->getProvider()->getCriteria()->getWhereExpression());
        $this->builder->setParams(['id' => null]);
        $this->builder->addEqualFilter('id');
        $this->assertInstanceOf(Expression::class, $this->builder->getProvider()->getCriteria()->getWhereExpression());
    }

    public function testAddLikeFilterOnEmptyData(): void
    {
        $this->assertNull($this->builder->getProvider()->getCriteria()->getWhereExpression());
        $this->builder->addLikeFilter('id');
        $this->assertNull($this->builder->getProvider()->getCriteria()->getWhereExpression());
    }

    public function testAddLikeFilter(): void
    {
        $this->assertNull($this->builder->getProvider()->getCriteria()->getWhereExpression());
        $this->builder->setParams(['id' => '1']);
        $this->builder->addLikeFilter('id');
        $this->assertInstanceOf(Expression::class, $this->builder->getProvider()->getCriteria()->getWhereExpression());
    }

    public function testAddRelationFilterOnEmptyData(): void
    {
        $this->assertNull($this->builder->getProvider()->getCriteria()->getWhereExpression());
        $this->builder->addRelationFilter('assoc', NodeAssoc::class);
        $this->assertNull($this->builder->getProvider()->getCriteria()->getWhereExpression());
    }

    public function testAddRelationFilter(): void
    {
        $this->assertNull($this->builder->getProvider()->getCriteria()->getWhereExpression());
        $this->builder->setParams(['assoc' => '1']);
        $this->builder->addRelationFilter('assoc', NodeAssoc::class);
        $this->assertInstanceOf(Expression::class, $this->builder->getProvider()->getCriteria()->getWhereExpression());
    }

    public function testAddCustomFilterOnEmptyData(): void
    {
        $this->assertNull($this->builder->getProvider()->getCriteria()->getWhereExpression());
        $this->builder->addCustomFilter('id', function ($criteria, $attr, $value) {
            $criteria->andWhere(Criteria::expr()->eq($attr, $value));
        });
        $this->assertNull($this->builder->getProvider()->getCriteria()->getWhereExpression());
    }

    public function testAddCustomFilter(): void
    {
        $this->assertNull($this->builder->getProvider()->getCriteria()->getWhereExpression());
        $this->builder->setParams(['id' => '1']);
        $this->builder->addCustomFilter('id', function ($criteria, $attr, $value) {
            $criteria->andWhere(Criteria::expr()->eq($attr, $value));
        });
        $this->assertInstanceOf(Expression::class, $this->builder->getProvider()->getCriteria()->getWhereExpression());
    }

    public function testAddDateFilterOnEmptyData(): void
    {
        $this->assertNull($this->builder->getProvider()->getCriteria()->getWhereExpression());
        $this->builder->addDateFilter('id');
        $this->assertNull($this->builder->getProvider()->getCriteria()->getWhereExpression());
    }

    /**
     * @dataProvider comparisonProvider
     * @param string $comparison
     */
    public function testAddDateFilter($comparison): void
    {
        $this->assertNull($this->builder->getProvider()->getCriteria()->getWhereExpression());
        $this->builder->setParams(['date' => '01-01-1990']);
        $this->builder->addDateFilter('date', $comparison);
        $this->assertInstanceOf(Expression::class, $this->builder->getProvider()->getCriteria()->getWhereExpression());
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
