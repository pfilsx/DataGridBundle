<?php


namespace Pfilsx\DataGrid\Grid;


use Pfilsx\DataGrid\Grid\Providers\DataProvider;
use Pfilsx\DataGrid\Grid\Providers\DataProviderInterface;

class DataGridFiltersBuilder implements DataGridFiltersBuilderInterface
{
    /**
     * @var array
     */
    protected $params = [];
    /**
     * @var DataProvider
     */
    protected $provider;


    public function addEqualFilter(string $attribute): DataGridFiltersBuilderInterface
    {
        if (array_key_exists($attribute, $this->params)) {
            $this->provider->addEqualFilter($attribute, $this->params[$attribute]);
        }
        return $this;
    }

    public function addLikeFilter(string $attribute): DataGridFiltersBuilderInterface
    {
        if (array_key_exists($attribute, $this->params)) {
            $this->provider->addLikeFilter($attribute, $this->params[$attribute]);
        }
        return $this;
    }

    public function addRelationFilter(string $attribute, string $relationClass): DataGridFiltersBuilderInterface
    {
        if (array_key_exists($attribute, $this->params)) {
            $this->provider->addRelationFilter($attribute, $this->params[$attribute], $relationClass);
        }
        return $this;
    }

    /**
     * @param string $attribute
     * @param callable $callback - callback function
     * @return DataGridFiltersBuilderInterface
     */
    public function addCustomFilter(string $attribute, callable $callback): DataGridFiltersBuilderInterface
    {
        if (array_key_exists($attribute, $this->params)) {
            $this->provider->addCustomFilter($attribute, $this->params[$attribute], $callback);
        }
        return $this;
    }

    public function addDateFilter(string $attribute, string $comparison = 'equal'): DataGridFiltersBuilderInterface
    {
        if (array_key_exists($attribute, $this->params)) {
            $this->provider->addDateFilter($attribute, $this->params[$attribute], $comparison);
        }
        return $this;
    }

    /**
     * @internal
     * @param array $params
     * @return DataGridFiltersBuilderInterface
     */
    public function setParams(array $params): DataGridFiltersBuilderInterface
    {
        $this->params = $params;
        return $this;
    }

    public function getProvider(): DataProviderInterface
    {
        return $this->provider;
    }

    /**
     * @internal
     * @param DataProviderInterface $provider
     */
    public function setProvider(DataProviderInterface $provider): void
    {
        $this->provider = $provider;
    }


}
