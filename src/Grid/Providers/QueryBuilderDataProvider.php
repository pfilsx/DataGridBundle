<?php


namespace Pfilsx\DataGrid\Grid\Providers;


use DateTime;
use Doctrine\ORM\QueryBuilder;
use Pfilsx\DataGrid\DataGridException;
use Pfilsx\DataGrid\Grid\DataGridItem;
use Pfilsx\DataGrid\Grid\Hydrators\DataGridHydrator;
use ReflectionClass;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\Lock\Exception\NotSupportedException;

class QueryBuilderDataProvider extends DataProvider
{
    /**
     * @var QueryBuilder
     */
    protected $builder;
    protected $entityManager;

    public function __construct(QueryBuilder $builder, ManagerRegistry $manager)
    {
        $this->builder = $builder;
        $this->entityManager = $manager->getManager();
    }

    public function getItems(): array
    {
        $this->builder
            ->setMaxResults($this->getPager()->getLimit())
            ->setFirstResult($this->getPager()->getFirst());

        $hydrator = new ReflectionClass(DataGridHydrator::class);
        $hydratorName = $hydrator->getShortName();
        $this->builder->getEntityManager()->getConfiguration()
            ->addCustomHydrationMode($hydratorName, DataGridHydrator::class);

        return array_map(function ($row) {
            $item = new DataGridItem();
            $item->setRow($row);
            $item->setEntityManager($this->entityManager);
            return $item;
        }, $this->builder->getQuery()->getResult($hydratorName));
    }

    public function getTotalCount(): int
    {
        if (empty($this->countFieldName)) {
            throw new DataGridException("countableFieldName must be set for " . static::class);
        }
        $countQueryBuilder = clone($this->builder);
        $countQueryBuilder->select("count({$this->countFieldName})");
        $countQueryBuilder->setMaxResults(null);
        $countQueryBuilder->setFirstResult(null);
        $countQueryBuilder->resetDQLPart('groupBy');
        $countQueryBuilder->resetDQLPart('orderBy');

        return $countQueryBuilder->getQuery()->getSingleScalarResult();
    }

    public function setSort(array $sort): DataProviderInterface
    {
        foreach ($sort as $key => $value) {
            $this->builder->addOrderBy($key, $value);
        }
        return $this;
    }

    public function addEqualFilter(string $attribute, $value): DataProviderInterface
    {
        if ($value === null) {
            $this->builder->andWhere($this->builder->expr()->isNull($attribute));
        } else {
            $placeholderName = str_replace('.', '_', $attribute);
            $this->builder->andWhere($this->builder->expr()->eq($attribute, ':' . $placeholderName));
            $this->builder->setParameter($placeholderName, $value);
        }
        return $this;
    }

    public function addLikeFilter(string $attribute, $value): DataProviderInterface
    {
        $placeholderName = str_replace('.', '_', $attribute);
        $this->builder->andWhere($this->builder->expr()->like($attribute, ':' . $placeholderName));
        $this->builder->setParameter($placeholderName, $value);
        return $this;
    }

    public function addRelationFilter(string $attribute, $value, string $relationClass): DataProviderInterface
    {
        throw new NotSupportedException("Method addRelationFilter() is not supported in " . get_called_class());
    }

    public function addCustomFilter(string $attribute, $value, callable $callback): DataProviderInterface
    {
        $builder = $this->builder;
        call_user_func_array($callback, [&$builder, $attribute, $value]);
        return $this;
    }

    public function addDateFilter(string $attribute, $value, string $comparison = 'equal'): DataProviderInterface
    {
        $comparisonFunc = lcfirst($comparison) . 'Date';
        if (method_exists($this, $comparisonFunc)) {
            $this->$comparisonFunc($attribute, $value);
        } else {
            $this->equalDate($attribute, $value);
        }
        return $this;
    }

    protected function equalDate($attribute, $value): void
    {
        $date = new DateTime($value);
        $nextDate = (clone $date)->modify('+1 day');
        $placeholderName = str_replace('.', '_', $attribute);
        $placeholderNameNext = str_replace('.', '_', $attribute) . '_next';
        $this->builder
            ->andWhere($this->builder->expr()->gte($attribute, ':' . $placeholderName))
            ->andWhere($this->builder->expr()->lt($attribute, ':' . $placeholderNameNext));
        $this->builder->setParameters([
            $placeholderName => $date,
            $placeholderNameNext => $nextDate
        ]);
    }

    protected function ltDate($attribute, $value): void
    {
        $date = new DateTime($value);
        $placeholderName = str_replace('.', '_', $attribute);
        $this->builder
            ->andWhere($this->builder->expr()->lt($attribute, ':' . $placeholderName));
        $this->builder->setParameter($placeholderName, $date);
    }

    protected function lteDate($attribute, $value): void
    {
        $date = (new DateTime($value))->modify('+1 day');
        $placeholderName = str_replace('.', '_', $attribute);
        $this->builder
            ->andWhere($this->builder->expr()->lt($attribute, ':' . $placeholderName));
        $this->builder->setParameter($placeholderName, $date);
    }

    protected function gtDate($attribute, $value): void
    {
        $date = (new DateTime($value))->modify('+1 day');
        $placeholderName = str_replace('.', '_', $attribute);
        $this->builder
            ->andWhere($this->builder->expr()->gte($attribute, ':' . $placeholderName));
        $this->builder->setParameter($placeholderName, $date);
    }

    protected function gteDate($attribute, $value): void
    {
        $date = new DateTime($value);
        $placeholderName = str_replace('.', '_', $attribute);
        $this->builder
            ->andWhere($this->builder->expr()->gte($attribute, ':' . $placeholderName));
        $this->builder->setParameter($placeholderName, $date);
    }
}