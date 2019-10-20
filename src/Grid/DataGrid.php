<?php


namespace Pfilsx\DataGrid\Grid;


use Doctrine\Common\Collections\Criteria;
use Pfilsx\DataGrid\Config\ConfigurationContainerInterface;
use Pfilsx\DataGrid\Config\ConfigurationInterface;
use Pfilsx\DataGrid\DataGridServiceContainer;
use Pfilsx\DataGrid\Grid\Columns\AbstractColumn;
use Pfilsx\DataGrid\Grid\Providers\DataProviderInterface;
use Twig\Template;

/**
 * Class DataGrid
 * @package Pfilsx\DataGrid\Grid
 * @internal
 * TODO translation_domain in columns and builder
 */
class DataGrid
{
    /**
     * @var Template
     */
    protected $template;
    /**
     * @var DataGridServiceContainer
     */
    protected $container;

    /**
     * @var ConfigurationInterface
     */
    protected $configuration;
    /**
     * @var Criteria
     */
    protected $filtersCriteria;
    /**
     * @var DataGridBuilderInterface
     */
    protected $builder;

    /**
     * DataGrid constructor.
     * @param DataGridBuilderInterface $builder
     * @param ConfigurationContainerInterface $defaultConfiguration
     * @param DataGridServiceContainer $container
     * @internal
     */
    public function __construct(
        DataGridBuilderInterface $builder,
        ConfigurationContainerInterface $defaultConfiguration,
        DataGridServiceContainer $container
    )
    {
        $this->builder = $builder;
        $this->container = $container;
        $this->configuration = $defaultConfiguration->getInstance($builder->getInstance())->merge($builder->getConfiguration());
        $this->setTemplate($this->configuration->getTemplate());
        $this->configurePagerOptions();
        if (!empty($this->configuration->getTranslationDomain())){
            foreach ($this->builder->getColumns() as $column){
                $column->setTranslationDomain($this->configuration->getTranslationDomain());
            }
        }
    }

    /**
     * @internal
     */
    protected function configurePagerOptions()
    {
        $pager = $this->builder->getProvider()->getPager();
        $pager->setLimit($this->configuration->getPaginationLimit());
        if ($this->configuration->getPaginationEnabled()){
            $pager->enable();
            $pager->setTotalCount($this->getProvider()->getTotalCount());
        } else {
            $pager->disable();
        }
    }
    /**
     * @return bool
     * @internal
     */
    public function getShowTitles(): bool
    {
        return $this->configuration->getShowTitles();
    }

    /**
     * @return Providers\DataProviderInterface
     * @internal
     */
    public function getProvider(): DataProviderInterface
    {
        return $this->builder->getProvider();
    }

    /**
     * @return AbstractColumn[]
     * @internal
     */
    public function getColumns(): array
    {
        return $this->builder->getColumns();
    }

    /**
     * @return bool
     * @internal
     */
    public function hasFilters()
    {
        return $this->builder->hasFilters();
    }

    /**
     * @return array
     * @internal
     */
    public function getData()
    {
        return $this->getProvider()->getItems();
    }

    /**
     * @return Template
     * @internal
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @internal
     * @param string $path
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function setTemplate(string $path)
    {
        $this->template = $this->container->getTwig()->loadTemplate($path);
        foreach ($this->builder->getColumns() as $column){
            $column->setTemplate($this->template);
        }
    }

    public function getNoDataMessage()
    {
        return $this->container->getTranslator() !== null
            ? $this->container->getTranslator()->trans($this->configuration->getNoDataMessage(), [], $this->configuration->getTranslationDomain())
            : ucfirst($this->configuration->getNoDataMessage());
    }

    public function hasPagination()
    {
        return $this->builder->hasPagination();
    }

    public function getPaginationOptions()
    {
        return $this->builder->getPager()->getPaginationOptions();
    }
}
