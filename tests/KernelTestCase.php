<?php


namespace Pfilsx\tests;


use Pfilsx\DataGrid\DataGridServiceContainer;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;

class KernelTestCase extends \Symfony\Bundle\FrameworkBundle\Test\KernelTestCase
{
    /**
     * @var DataGridServiceContainer
     */
    protected $serviceContainer;

    /**
     * @var Application
     */
    protected $application;

    protected function setUp(): void
    {

        $kernel = self::bootKernel();
        $this->application = new Application($kernel);
        $this->application->setAutoExit(false);
        $this->application->run(new ArrayInput(array(
            'doctrine:schema:drop',
            '--force' => true
        )));
        $this->application->run(new ArrayInput(array(
            'doctrine:schema:create'
        )));
        /** @noinspection PhpParamsInspection */
        $this->serviceContainer = new DataGridServiceContainer(
            $kernel->getContainer()->get('doctrine'),
            $kernel->getContainer()->get('router'),
            $kernel->getContainer()->get('twig'),
            $kernel->getContainer()->get('request_stack'),
            // $kernel->getContainer()->get('translator')
        );
    }
}
