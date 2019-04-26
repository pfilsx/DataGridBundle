<?php


namespace Pfilsx\DataGrid\tests;


use Pfilsx\DataGrid\DataGridBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DataGridBundleTest extends TestCase
{
    public function testBundle()
    {
        $bundle = new DataGridBundle();
        $container = new ContainerBuilder();
        $bundle->build($container);
        $this->assertTrue(true);
    }
}
