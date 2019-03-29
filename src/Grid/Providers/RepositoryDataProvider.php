<?php


namespace Pfilsx\DataGrid\Grid\Providers;


use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use Pfilsx\DataGrid\Grid\DataGridItem;
use Pfilsx\DataGrid\Grid\Pager;

class RepositoryDataProvider implements DataProviderInterface
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

    /**
     * @var Pager
     */
    protected $pager;

    protected $pagerConfiguration = [];

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
        }, $this->repository->matching($this->criteria)->toArray());
    }

    /**
     * @internal
     * @return Pager
     */
    public function getPager(): Pager
    {
        return $this->pager ?? ($this->pager = new Pager(array_merge($this->pagerConfiguration, [
                'totalCount' => $this->getTotalCount()
            ])));
    }

    public function getTotalCount(): int
    {
        return $this->repository->matching($this->criteria)->count();
    }

    /**
     * @internal
     * @return Criteria
     */
    public function getCriteria()
    {
        return $this->criteria ?? ($this->criteria = Criteria::create());
    }

    /**
     * @internal
     * @param Criteria $criteria
     * @return DataProviderInterface
     */
    public function setCriteria(Criteria $criteria): DataProviderInterface
    {
        $this->criteria = $criteria;
        return $this;
    }

    /**
     * @internal
     * @param array $pagerConfiguration
     * @return DataProviderInterface
     */
    public function setPagerConfiguration(array $pagerConfiguration): DataProviderInterface
    {
        $this->pagerConfiguration = $pagerConfiguration;
        return $this;
    }

    public function setSort(array $sort): DataProviderInterface
    {
        $this->getCriteria()->orderBy($sort);
        return $this;
    }
}
