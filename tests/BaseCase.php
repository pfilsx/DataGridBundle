<?php
namespace Pfilsx\DataGrid\tests;

use Pfilsx\DataGrid\Grid\Columns\AbstractColumn;
use PHPUnit\Framework\TestCase;
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
    /**
     * @var AbstractColumn
     */
    protected $testColumn;

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
                        $request = new Request([], [], ['key' => 'value']);
                        $requestStack = new RequestStack();
                        $requestStack->push($request);
                        return $requestStack;
                    case 'twig':
                        $mock = $self->createMock(Environment::class);
                        $mock->expects($this->any())
                            ->method('loadTemplate')
                            ->will($this->returnCallback(function($param) use ($self) {
                                $mock = $self->createMock(Template::class);
                                $mock->expects($this->any())
                                ->method('renderBlock')
                                ->will($this->returnCallback(function($param){return $param;}));
                                return $mock;
                            }));
                        return $mock;
                }
                return null;
            }));
    }
}