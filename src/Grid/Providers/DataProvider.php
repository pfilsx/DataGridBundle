<?php


namespace Pfilsx\DataGrid\Grid\Providers;


use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\Mapping\ClassMetadata;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ObjectManager;
use Pfilsx\DataGrid\DataGridException;
use Pfilsx\DataGrid\Grid\Pager;
use Symfony\Bridge\Doctrine\ManagerRegistry;

abstract class DataProvider implements DataProviderInterface
{
    /**
     * @var Pager
     */
    protected $pager;

    protected $countFieldName;

    /**
     * @var ManagerRegistry
     */
    protected $registry;

    /**
     * @var ObjectManager
     */
    protected $entityManager;

    public function __construct(ManagerRegistry $registry = null)
    {
        $this->registry = $registry;
    }

    /**
     * @internal
     * @return Pager
     */
    public function getPager(): Pager
    {
        return $this->pager;
    }

    /**
     * @internal
     * @param Pager $pager
     */
    public function setPager(Pager $pager): void
    {
        $this->pager = $pager;
    }

    public function setCountFieldName(string $name): DataProviderInterface
    {
        $this->countFieldName = $name;
        return $this;
    }

    public function getCountFieldName(): string
    {
        return $this->countFieldName;
    }

    public function addEqualFilter(string $attribute, $value): DataProviderInterface
    {
        throw new DataGridException("Method addEqualFilter() is not supported in " . static::class);
    }

    public function addLikeFilter(string $attribute, $value): DataProviderInterface
    {
        throw new DataGridException("Method addLikeFilter() is not supported in " . static::class);
    }

    public function addRelationFilter(string $attribute, $value, string $relationClass): DataProviderInterface
    {
        throw new DataGridException("Method addRelationFilter() is not supported in " . static::class);
    }

    public function addCustomFilter(string $attribute, $value, callable $callback): DataProviderInterface
    {
        throw new DataGridException("Method addCustomFilter() is not supported in " . static::class);
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

    /**
     * @param $attribute
     * @param $value
     * @throws DataGridException
     */
    protected function equalDate($attribute, $value): void
    {
        throw new DataGridException("Method equalDate() is not supported in " . static::class);
    }

    protected function getEntityIdentifier(string $className){
        $metadata = $this->getEntityMetadata($className);
        if ($metadata === null) return null;
        return !empty($metadata->getIdentifier()) ? $metadata->getIdentifier()[0] : null;
    }

    /**
     * @param string $className
     * @return ClassMetadata|null
     */
    protected function getEntityMetadata(string $className)
    {
        $metadata = null;

        /** @var EntityManagerInterface $em */
        foreach ($this->registry->getManagers() as $em) {
            $cmf = $em->getMetadataFactory();

            foreach ($cmf->getAllMetadata() as $m) {
                if ($m->getName() === $className) {
                    $this->entityManager = $em;
                    return $m;
                }
            }
        }
        return $metadata;
    }


    public static function create($data, ManagerRegistry $doctrine = null): DataProviderInterface
    {
        if ($data instanceof EntityRepository && $doctrine !== null) {
            return new RepositoryDataProvider($data, $doctrine);
        }
        if ($data instanceof QueryBuilder) {
            return new QueryBuilderDataProvider($data);
        }
        if (is_array($data)) {
            return new ArrayDataProvider($data, $doctrine);
        }
        throw new DataGridException('Provided data must be one of: ' . implode(', ', [
                ServiceEntityRepository::class,
                QueryBuilder::class,
                'Array'
            ]) . ', ' . (($type = gettype($data)) == 'object' ? get_class($data) : $type) . ' given');
    }
}
