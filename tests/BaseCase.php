<?php

namespace Pfilsx\DataGrid\tests;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\QueryBuilder;
use Pfilsx\DataGrid\Grid\Columns\AbstractColumn;
use Pfilsx\DataGrid\Grid\Filters\AbstractFilter;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;
use Twig\Template;

abstract class BaseCase extends TestCase
{
    /**
     * @var Container
     */
    protected $container;

    protected $containerArray;
    /**
     * @var AbstractColumn
     */
    protected $testColumn;
    /**
     * @var AbstractFilter
     */
    protected $testFilter;

    protected function setUp(): void
    {
        $self = $this;

        $this->container = $this->createMock(Container::class);
        $this->container->expects($this->any())
            ->method('get')
            ->will($this->returnCallback(function ($param) use ($self) {
                switch ($param) {
                    case 'router':
                        return $self->createMock(RouterInterface::class);
                    case 'request_stack':
                        return $this->createRequestStackMock();
                    case 'twig':
                        return $this->createTwigMock();
                    case 'doctrine':
                        return $this->createDoctrineMock();
                }
                return null;
            }));

        $this->containerArray = [
            'twig' => $this->container->get('twig'),
            'router' => $this->container->get('router'),
            'doctrine' => $this->container->get('doctrine'),
            'request' => $this->container->get('request_stack')->getCurrentRequest()
        ];
    }

    private function createRequestStackMock()
    {
        $request = new Request([], [], ['key' => 'value']);
        $requestStack = new RequestStack();
        $requestStack->push($request);
        return $requestStack;
    }

    private function createTwigMock()
    {
        $self = $this;
        $mock = $self->createMock(Environment::class);
        $mock->expects($this->any())
            ->method('loadTemplate')
            ->will($this->returnCallback(function ($param) use ($self) {
                $mock = $self->createMock(Template::class);
                $mock->expects($this->any())
                    ->method('renderBlock')
                    ->will($this->returnCallback(function ($param, $options) {
                        return json_encode([$param, $options]);
                    }));
                return $mock;
            }));
        return $mock;
    }

    private function createDoctrineMock()
    {
        $self = $this;
        $mock = $self->createMock(ManagerRegistry::class);
        $mock->expects($this->any())
            ->method('getRepository')
            ->will($this->returnCallback(function ($param) use ($self) {
                $mock = $self->createMock(ServiceEntityRepository::class);
                $mock->expects($this->any())
                    ->method('createQueryBuilder')
                    ->willReturn($self->createQueryBuilderMock());
                return $mock;
            }));
        $mock->expects($this->any())
            ->method('getManager')
            ->will($this->returnCallback(function ($param) use ($self) {
                $mock = $self->createMock(ObjectManager::class);
                $mock->expects($this->any())
                    ->method('getClassMetadata')
                    ->willReturn(new class
                    {
                        public function getIdentifier()
                        {
                            return ['id'];
                        }
                    });
                $mock->expects($this->any())
                    ->method('getRepository')
                    ->will($this->returnCallback(function ($param) use ($self) {
                        $mock = $self->createMock(ServiceEntityRepository::class);
                        $mock->expects($this->any())
                            ->method('createQueryBuilder')
                            ->willReturn($self->createQueryBuilderMock());
                        return $mock;
                    }));
                return $mock;
            }));
        return $mock;
    }

    private function createQueryBuilderMock()
    {
        $self = $this;
        $mock = $self->createMock(QueryBuilder::class);
        $mock->expects($this->any())
            ->method('select')->willReturnSelf();
        $mock->expects($this->any())
            ->method('orderBy')->willReturnSelf();
        $mock->expects($this->any())
            ->method('getQuery')->will($this->returnCallback(function () use ($self) {
                $mock = $self->createMock(AbstractQuery::class);
                $mock->expects($this->any())
                    ->method('getArrayResult')
                    ->willReturn($self->createQueryArrayResult());
                return $mock;
            }));

        return $mock;
    }

    private function createQueryArrayResult()
    {
        return [
            ['id' => 1, 'title' => 'Test1'],
            ['id' => 2, 'title' => 'Test2'],
            ['id' => 3, 'title' => 'Test3'],
            ['id' => 4, 'title' => 'Test4'],
            ['id' => 5, 'title' => 'Test5']
        ];
    }
}
