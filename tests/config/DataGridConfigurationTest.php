<?php

namespace Pfilsx\DataGrid\tests\config;

use Pfilsx\DataGrid\Config\Configuration;
use Pfilsx\DataGrid\Config\ConfigurationContainer;
use PHPUnit\Framework\TestCase;

class DataGridConfigurationTest extends TestCase
{
    /**
     * @param $config
     * @param $instance
     * @param $result
     *
     * @dataProvider configsProvider
     */
    public function testConfiguration($config, $instance, $result): void
    {
        $configurationContainer = new ConfigurationContainer($config);

        $this->assertEquals($result, $configurationContainer->getInstance($instance)->getConfigsArray());
    }

    public function configsProvider()
    {
        return [
            [
                [],
                'default',
                [
                    'template' => '@DataGrid/grid.blocks.html.twig',
                    'noDataMessage' => 'No data found',
                    'showTitles' => true,
                    'paginationEnabled' => true,
                    'paginationLimit' => 10,
                    'translationDomain' => null
                ]
            ],
            [
                [
                    'instances' => [
                        'test' => [
                            'template' => 'test/grid.blocks.html.twig',
                            'show_titles' => false,
                            'pagination_enabled' => true,
                            'pagination_limit' => 15,
                            'translation_domain' => 'test'
                        ]
                    ]
                ],
                'test',
                [
                    'template' => 'test/grid.blocks.html.twig',
                    'noDataMessage' => null,
                    'showTitles' => false,
                    'paginationEnabled' => true,
                    'paginationLimit' => 15,
                    'translationDomain' => 'test'
                ]
            ]
        ];
    }
}
