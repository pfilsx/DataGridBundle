<?php


namespace Pfilsx\tests\providers;


use Pfilsx\DataGrid\Grid\Pager;
use Pfilsx\DataGrid\Grid\Providers\RepositoryDataProvider;
use Pfilsx\tests\app\Entity\Node;
use Pfilsx\tests\OrmTestCase;

class RepositoryDataProviderTest extends OrmTestCase
{
    /**
     * @var RepositoryDataProvider
     */
    private $provider;

    public function setUp(): void
    {
        parent::setUp();
        $this->provider = new RepositoryDataProvider($this->getEntityManager()->getRepository(Node::class), $this->serviceContainer->getDoctrine());
        $this->provider->setPager(new Pager());
    }

    public function testGetItems(): void
    {
        $items = $this->provider->getItems();
        $this->assertIsArray($items);
        $this->assertNotEmpty($items);
        $this->assertCount(11, $items);
    }

    public function testEmpty(): void
    {
        $this->provider->addEqualFilter('id', 12);
        $items = $this->provider->getItems();
        $this->assertIsArray($items);
        $this->assertEmpty($items);
    }
}