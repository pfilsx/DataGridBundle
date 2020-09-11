<?php


namespace Pfilsx\tests;



use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

class DataGridServiceContainerTest extends KernelTestCase
{
    public function testContainer(){
        $this->assertInstanceOf(ManagerRegistry::class, $this->serviceContainer->getDoctrine());
        $this->assertEquals($this->serviceContainer->getDoctrine(), $this->serviceContainer->get('doctrine'));

        $this->assertInstanceOf(RouterInterface::class, $this->serviceContainer->getRouter());
        $this->assertEquals($this->serviceContainer->getRouter(), $this->serviceContainer->get('router'));

        $this->assertInstanceOf(Environment::class, $this->serviceContainer->getTwig());
        $this->assertEquals($this->serviceContainer->getTwig(), $this->serviceContainer->get('twig'));

        $this->assertInstanceOf(RequestStack::class, $this->serviceContainer->getRequest());
        $this->assertEquals($this->serviceContainer->getRequest(), $this->serviceContainer->get('request'));

        $this->assertInstanceOf(TranslatorInterface::class, $this->serviceContainer->getTranslator());
        $this->assertEquals($this->serviceContainer->getTranslator(), $this->serviceContainer->get('translator'));
    }
}