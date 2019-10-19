<?php

namespace Pfilsx\DataGrid\tests\config;

use Pfilsx\DataGrid\Config\Configuration;
use PHPUnit\Framework\TestCase;

class DataGridConfigurationTest extends TestCase
{
    /**
     * @param $config
     * @param $result
     *
     * @dataProvider configsProvider
     */
    public function testConfiguration($config, $result): void
    {
        $configuration = new Configuration($config);

        $this->assertEquals($result, $configuration->getConfigs());
    }

    public function configsProvider()
    {
        return [
            [
                [
                    'template' => 'test_template',
                    'noDataMessage' => 'empty',
                    'pagination' => []
                ],
                [
                    'template' => 'test_template',
                    'noDataMessage' => 'empty',
                    'pagination' => [
                        'enabled' => false,
                        'options' => null
                    ],
                    'showTitles' => null
                ]
            ],
            [
                [
                    'template' => 'test_template',
                    'noDataMessage' => 'empty',
                    'pagination' => [
                        'limit' => 10
                    ],
                    'showTitles' => true
                ],
                [
                    'template' => 'test_template',
                    'noDataMessage' => 'empty',
                    'pagination' => [
                        'enabled' => true,
                        'options' => [
                            'limit' => 10
                        ]
                    ],
                    'showTitles' => true
                ]
            ]
        ];
    }
}
