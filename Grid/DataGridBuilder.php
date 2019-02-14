<?php


namespace Pfilsx\DataGrid\Grid;


use Pfilsx\DataGrid\Grid\Columns\AbstractColumn;
use Pfilsx\DataGrid\Grid\Columns\DataColumn;
use InvalidArgumentException;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DataGridBuilder implements DataGridBuilderInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var AbstractColumn[]
     */
    protected $columns = [];
    /**
     * @var array
     */
    protected $options = [
        'template' => '@DataGrid/grid.blocks.html.twig'
    ];

    /**
     * DataGridBuilder constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $columnClass
     * @param array $config
     * @return $this
     */
    public function addColumn(string $columnClass, array $config = []): DataGridBuilderInterface
    {
        if (!is_subclass_of($columnClass, AbstractColumn::class)) {
            throw new InvalidArgumentException('Expected subclass of' . AbstractColumn::class);
        }
        $this->columns[] = new $columnClass($this->container, array_merge(['template' => $this->options['template']], $config));
        return $this;
    }

    /**
     * @param string $attribute
     * @param array $config
     * @return $this
     */
    public function addDataColumn(string $attribute, array $config = []): DataGridBuilderInterface
    {
        return $this->addColumn(DataColumn::class, array_merge($config, ['attribute' => $attribute, 'label' => $attribute]));
    }
    /**
     * @param string $path
     * @return $this
     */
    public function setTemplate(string $path) : DataGridBuilderInterface
    {
        $this->options['template'] = $path;
        foreach ($this->columns as $column){
            $column->setTemplate($path);
        }
        return $this;
    }
    /**
     * @param string $message
     * @return $this
     */
    public function setNoDataMessage(string $message): DataGridBuilderInterface
    {
        $this->options['noDataMessage'] = $message;
        return $this;
    }

    public function enablePagination(array $options = []) : DataGridBuilderInterface
    {
        $this->options['pagination'] = true;
        $this->options['paginationOptions'] = $options;
        return $this;
    }



    // region filters
    public function addEqualFilter(string $attribute, array $params): DataGridBuilderInterface {
        if (array_key_exists($attribute, $params))
            $this->options['filters'][] = ['equal', $attribute, $params[$attribute]];
        return $this;
    }

    public function addLikeFilter(string $attribute, array $params): DataGridBuilderInterface {
        if (array_key_exists($attribute, $params))
            $this->options['filters'][] = ['like', $attribute, $params[$attribute]];
        return $this;
    }
    //endregion

    /**
     * @internal
     * @return array
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * @internal
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }
}