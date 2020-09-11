<?php


namespace Pfilsx\DataGrid\Grid\Providers;


use DateTime;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;
use Pfilsx\DataGrid\Grid\Items\EntityGridItem;
use Symfony\Bridge\Doctrine\ManagerRegistry;

class RepositoryDataProvider extends DataProvider
{

    /**
     * @var EntityRepository
     */
    protected $repository;

    /**
     * @var Criteria
     */
    protected $criteria;


    public function __construct(EntityRepository $repository, ManagerRegistry $manager)
    {
        $this->repository = $repository;
        parent::__construct($manager);
    }


    public function getItems(): array
    {
        $this->getCriteria()
            ->setMaxResults($this->getPager()->getLimit())
            ->setFirstResult($this->getPager()->getFirst());
        $result = $this->repository->matching($this->getCriteria())->toArray();
        if (!empty($result)){
            $identifier = $this->getEntityIdentifier(get_class($result[0]));
            return array_map(function ($entity) use ($identifier) {
                $item = new EntityGridItem($entity, $identifier);
                return $item;
            }, $result);
        }
        return $result;
    }

    public function getTotalCount(): int
    {
        return $this->repository->matching($this->getCriteria())->count();
    }

    /**
     * @return Criteria
     */
    public function getCriteria()
    {
        return $this->criteria ?? ($this->criteria = Criteria::create());
    }

    public function setSort(array $sort): DataProviderInterface
    {
        $this->getCriteria()->orderBy($sort);
        return $this;
    }

    public function addEqualFilter(string $attribute, $value): DataProviderInterface
    {
        if ($value === null) {
            $this->getCriteria()->andWhere(Criteria::expr()->isNull($attribute));
        } else {
            $this->getCriteria()->andWhere(Criteria::expr()->eq($attribute, $value));
        }
        return $this;
    }

    public function addLikeFilter(string $attribute, $value): DataProviderInterface
    {
        $this->getCriteria()->andWhere(Criteria::expr()->contains($attribute, $value));
        return $this;
    }

    public function addRelationFilter(string $attribute, $value, string $relationClass): DataProviderInterface
    {
        $identifier = $this->getEntityIdentifier($relationClass);
        if ($identifier !== null) {
            $repository = $this->entityManager->getRepository($relationClass);
            $entity = $repository->findOneBy([$identifier => $value]);
            $this->getCriteria()->andWhere(Criteria::expr()->eq($attribute, $entity));
        }

        return $this;
    }

    public function addCustomFilter(string $attribute, $value, callable $callback): DataProviderInterface
    {
        $criteria = $this->getCriteria();
        call_user_func_array($callback, [&$criteria, $attribute, $value]);
        return $this;
    }

    protected function equalDate($attribute, $value): void
    {
        $date = new DateTime($value);
        $nextDate = (clone $date)->modify('+1 day');
        $this->getCriteria()
            ->andWhere(Criteria::expr()->gte($attribute, $date))
            ->andWhere(Criteria::expr()->lt($attribute, $nextDate));
    }

    protected function ltDate($attribute, $value): void
    {
        $date = new DateTime($value);
        $this->getCriteria()
            ->andWhere(Criteria::expr()->lt($attribute, $date));
    }

    protected function lteDate($attribute, $value): void
    {
        $date = (new DateTime($value))->modify('+1 day');
        $this->getCriteria()
            ->andWhere(Criteria::expr()->lt($attribute, $date));
    }

    protected function gtDate($attribute, $value): void
    {
        $date = (new DateTime($value))->modify('+1 day');
        $this->getCriteria()
            ->andWhere(Criteria::expr()->gte($attribute, $date));
    }

    protected function gteDate($attribute, $value): void
    {
        $date = new DateTime($value);
        $this->getCriteria()
            ->andWhere(Criteria::expr()->gte($attribute, $date));
    }
}
