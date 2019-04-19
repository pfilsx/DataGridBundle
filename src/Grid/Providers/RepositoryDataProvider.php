<?php


namespace Pfilsx\DataGrid\Grid\Providers;


use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Persistence\ManagerRegistry;
use Pfilsx\DataGrid\Grid\DataGridItem;

class RepositoryDataProvider extends DataProvider
{

    /**
     * @var ServiceEntityRepository
     */
    protected $repository;

    protected $entityManager;

    /**
     * @var Criteria
     */
    protected $criteria;


    public function __construct(ServiceEntityRepository $repository, ManagerRegistry $manager)
    {
        $this->repository = $repository;
        $this->entityManager = $manager->getManager();
    }


    public function getItems(): array
    {
        $this->getCriteria()
            ->setMaxResults($this->getPager()->getLimit())
            ->setFirstResult($this->getPager()->getFirst());
        return array_map(function ($entity) {
            $item = new DataGridItem();
            $item->setEntity($entity);
            $item->setEntityManager($this->entityManager);
            return $item;
        }, $this->repository->matching($this->getCriteria())->toArray());
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
        $metaData = $this->entityManager->getClassMetadata($relationClass);
        if (!empty($metaData->getIdentifier())) {
            $identifier = $metaData->getIdentifier()[0];
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
