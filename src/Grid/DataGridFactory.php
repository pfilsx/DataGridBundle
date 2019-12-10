<?php


namespace Pfilsx\DataGrid\Grid;


use Pfilsx\DataGrid\Config\ConfigurationContainerInterface;
use InvalidArgumentException;
use Pfilsx\DataGrid\DataGridServiceContainer;
use Pfilsx\DataGrid\Extension\DependencyInjection\DependencyInjectionExtension;
use Pfilsx\DataGrid\Grid\Providers\DataProvider;
use Symfony\Component\HttpFoundation\Request;

class DataGridFactory implements DataGridFactoryInterface
{
    /**
     * @var DataGridServiceContainer
     */
    protected $container;
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var ConfigurationContainerInterface
     */
    protected $defaultConfiguration;
    /**
     * @var DependencyInjectionExtension
     */
    protected $extension;
    /**
     * @var array
     */
    protected $types;


    public function __construct(DataGridServiceContainer $container, ConfigurationContainerInterface $configs, DependencyInjectionExtension $extension)
    {
        $this->container = $container;
        $this->request = $container->getRequest()->getCurrentRequest();
        $this->defaultConfiguration = $configs;
        $this->extension = $extension;
    }


    public function createGrid(string $gridType, $dataSource, array $params = []): DataGridInterface
    {
        if (!is_subclass_of($gridType, AbstractGridType::class)) {
            throw new InvalidArgumentException('Expected subclass of ' . AbstractGridType::class);
        }
        $provider = DataProvider::create($dataSource, $this->container->getDoctrine());

        /** @var AbstractGridType $type */
        $gridType = $this->getType($gridType);
        $gridType->setParams($params);


        return new DataGrid($gridType, $provider, $this->defaultConfiguration, $this->container);
    }

    /**
     * @param string $name
     * @return AbstractGridType
     */
    private function getType(string $name)
    {
        if (!isset($this->types[$name])) {
            $type = null;

            if ($this->extension->hasType($name)) {
                $type = $this->extension->getType($name);
            }

            if (!$type) {
                // Support fully-qualified class names
                if (!class_exists($name)) {
                    throw new InvalidArgumentException(sprintf('Could not load type "%s": class does not exist.', $name));
                }
                if (!is_subclass_of($name, AbstractGridType::class)) {
                    throw new InvalidArgumentException(sprintf('Could not load type "%s": class does not extend "Pfilsx\DataGrid\AbstractGridType class".', $name));
                }

                $type = new $name();
            }

            $this->types[$name] = $type;
        }

        return $this->types[$name];
    }
}
