<?php


namespace Pfilsx\DataGrid\Grid;


use Doctrine\Common\Collections\Criteria;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DataGridFiltersBuilder implements DataGridFiltersBuilderInterface
{
    //TODO check column exists
    /**
     * @var ContainerInterface
     */
    protected $container;
    /**
     * @var Criteria
     */
    protected $criteria;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->criteria = Criteria::create();
    }

    public function addEqualFilter(string $attribute, array $params): DataGridFiltersBuilderInterface
    {
        if (array_key_exists($attribute, $params)){
            if ($params[$attribute] == null){
                $this->criteria->andWhere(Criteria::expr()->isNull($attribute));
            } else {
                $this->criteria->andWhere(Criteria::expr()->eq($attribute, $params[$attribute]));
            }
        }
        return $this;
    }

    public function addLikeFilter(string $attribute, array $params): DataGridFiltersBuilderInterface
    {
        if (array_key_exists($attribute, $params)) {
            $this->criteria->andWhere(Criteria::expr()->contains($attribute, $params[$attribute]));
        }
        return $this;
    }

    public function addRelationFilter(string $attribute, string $relationClass, array $params): DataGridFiltersBuilderInterface
    {
        if (array_key_exists($attribute, $params)) {
            $repository = $this->container->get('doctrine')->getRepository($relationClass);
            $entity = $repository->findOneBy(['id' => $params[$attribute]]);
            $this->criteria->andWhere(Criteria::expr()->eq($attribute, $entity));
        }
        return $this;
    }

    public function addCustomFilter(string $attribute, callable $callback, array $params): DataGridFiltersBuilderInterface
    {
        if (array_key_exists($attribute, $params)){
            call_user_func_array($callback, [&$this->criteria, $attribute, $params[$attribute]]);
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
}