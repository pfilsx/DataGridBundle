<?php


namespace Pfilsx\DataGrid\Config;


class ConfigurationContainer implements ConfigurationContainerInterface
{
    /**
     * @var ConfigurationInterface[]
     */
    private $instances = [];

    public function __construct(array $config)
    {
        $this->instances['default'] = new Configuration([
            'template' => '@DataGrid/grid.blocks.html.twig',
            'no_data_message' => 'No data found',
            'show_titles' => true,
            'pagination_enabled' => true,
            'pagination_limit' => 10,
            'translation_domain' => null
        ]);
        if (!empty($config['instances'])){
            foreach ($config['instances'] as $key => $configuration) {
                $this->instances[$key] = new Configuration($configuration);
            }
        }
    }

    public function getInstance(string $name): ConfigurationInterface
    {
        return array_key_exists($name, $this->instances) ? $this->instances[$name] : null;
    }
}