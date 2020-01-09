<?php


namespace Pfilsx\DataGrid\Grid\Providers;


use DateTime;
use Doctrine\ORM\QueryBuilder;
use Pfilsx\DataGrid\DataGridException;
use Pfilsx\DataGrid\Grid\Hydrators\DataGridHydrator;
use Pfilsx\DataGrid\Grid\Items\ArrayGridItem;
use ReflectionClass;

class QueryBuilderDataProvider extends DataProvider
{
    /**
     * @var QueryBuilder
     */
    protected $builder;

    public function __construct(QueryBuilder $builder)
    {
        $this->builder = $builder;
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
            $item = new ArrayGridItem($row, array_key_exists('id', $row) ? 'id' : null);
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
        $this->builder->andWhere($this->builder->expr()->like('lower(' . $attribute . ')', ':' . $placeholderName));
        $this->builder->setParameter($placeholderName, '%' . mb_strtolower($value) . '%');
        return $this;
    }

    public function addCustomFilter(string $attribute, $value, callable $callback): DataProviderInterface
    {
        $builder = $this->builder;
        call_user_func_array($callback, [&$builder, $attribute, $value]);
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
        $this->builder->setParameter($placeholderName, $date);
        $this->builder->setParameter($placeholderNameNext, $nextDate);
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