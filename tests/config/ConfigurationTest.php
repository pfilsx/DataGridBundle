<?php


namespace Pfilsx\tests\config;


use Pfilsx\DataGrid\Config\Configuration;
use PHPUnit\Framework\TestCase;

class ConfigurationTest extends TestCase
{
    public function testCreate(): void
    {
        $configuration = new Configuration([
            'template' => 'test/grid.blocks.html.twig',
            'pagination_enabled' => true,
            'pagination_limit' => 15,
            'translation_domain' => 'test'
        ]);

        $this->assertEquals('test/grid.blocks.html.twig', $configuration->getTemplate());
        $this->assertTrue($configuration->getPaginationEnabled());
        $this->assertEquals(15, $configuration->getPaginationLimit());
        $this->assertEquals('test', $configuration->getTranslationDomain());
    }

    public function testMerge(): void
    {
        $configuration = new Configuration([
            'template' => 'test/grid.blocks.html.twig',
            'pagination_enabled' => true,
            'pagination_limit' => 15,
            'translation_domain' => null
        ]);

        $configuration2 = new Configuration([
            'translation_domain' => 'test',
            'pagination_limit' => 10
        ]);

        $result = $configuration->merge($configuration2);

        $this->assertEquals(10, $result->getPaginationLimit());
        $this->assertEquals('test', $result->getTranslationDomain());


        $this->assertEquals([
            'template' => 'test/grid.blocks.html.twig',
            'paginationEnabled' => true,
            'paginationLimit' => 10,
            'translationDomain' => 'test',
            'noDataMessage' => null
        ], $result->getConfigsArray());
    }
}