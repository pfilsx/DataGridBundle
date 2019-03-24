<?php


namespace Pfilsx\DataGrid\Grid;


use DateTime;
use Doctrine\Common\Collections\Criteria;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DataGridFiltersBuilder implements DataGridFiltersBuilderInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;
    /**
     * @var Criteria
     */
    protected $criteria;
    /**
     * @var array
     */
    protected $params = [];

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->criteria = Criteria::create();
    }

    public function addEqualFilter(string $attribute): DataGridFiltersBuilderInterface
    {
        if (array_key_exists($attribute, $this->params)) {
            if ($this->params[$attribute] == null) {
                $this->criteria->andWhere(Criteria::expr()->isNull($attribute));
            } else {
                $this->criteria->andWhere(Criteria::expr()->eq($attribute, $this->params[$attribute]));
            }
        }
        return $this;
    }

    public function addLikeFilter(string $attribute): DataGridFiltersBuilderInterface
    {
        if (array_key_exists($attribute, $this->params)) {
            $this->criteria->andWhere(Criteria::expr()->contains($attribute, $this->params[$attribute]));
        }
        return $this;
    }

    public function addRelationFilter(string $attribute, string $relationClass): DataGridFiltersBuilderInterface
    {
        if (array_key_exists($attribute, $this->params)) {
            $repository = $this->container->get('doctrine')->getRepository($relationClass);
            $entity = $repository->findOneBy(['id' => $this->params[$attribute]]);
            $this->criteria->andWhere(Criteria::expr()->eq($attribute, $entity));
        }
        return $this;
    }

    /**
     * @param string $attribute
     * @param callable $callback - callback function
     * @return DataGridFiltersBuilderInterface
     */
    public function addCustomFilter(string $attribute, callable $callback): DataGridFiltersBuilderInterface
    {
        if (array_key_exists($attribute, $this->params)) {
            call_user_func_array($callback, [&$this->criteria, $attribute, $this->params[$attribute]]);
        }
        return $this;
    }

    public function addDateFilter(string $attribute, string $comparison = 'equal'): DataGridFiltersBuilderInterface
    {
        if (array_key_exists($attribute, $this->params)) {
            $comparisonFunc = lcfirst($comparison) . 'Date';
            if (method_exists($this, $comparisonFunc)) {
                $this->$comparisonFunc($attribute);
            } else {
                $this->equalDate($attribute);
            }
        }
        return $this;
    }

    /**
     * @internal
     * @return Criteria
     */
    public function getCriteria(): Criteria
    {
        return $this->criteria;
    }

    /**
     * @internal
     * @param array $params
     */
    public function setParams(array $params): void
    {
        $this->params = $params;
    }

    protected function equalDate($attribute): void
    {
        $date = new DateTime($this->params[$attribute]);
        $nextDate = (clone $date)->modify('+1 day');
        $this->criteria
            ->andWhere(Criteria::expr()->gte($attribute, $date))
            ->andWhere(Criteria::expr()->lt($attribute, $nextDate));
    }

    protected function notEqualDate($attribute): void
    {
        $date = new DateTime($this->params[$attribute]);
        $nextDate = (clone $date)->modify('+1 day');
        $this->criteria
            ->andWhere(Criteria::expr()->lt($attribute, $date))
            ->andWhere(Criteria::expr()->gte($attribute, $nextDate));
    }

    protected function ltDate($attribute): void
    {
        $date = new DateTime($this->params[$attribute]);
        $this->criteria
            ->andWhere(Criteria::expr()->lt($attribute, $date));
    }

    protected function lteDate($attribute): void
    {
        $date = (new DateTime($this->params[$attribute]))->modify('+1 day');
        $this->criteria
            ->andWhere(Criteria::expr()->lt($attribute, $date));
    }

    protected function gtDate($attribute): void
    {
        $date = (new DateTime($this->params[$attribute]))->modify('+1 day');
        $this->criteria
            ->andWhere(Criteria::expr()->gte($attribute, $date));
    }

    protected function gteDate($attribute): void
    {
        $date = new DateTime($this->params[$attribute]);
        $this->criteria
            ->andWhere(Criteria::expr()->gte($attribute, $date));
    }
}
