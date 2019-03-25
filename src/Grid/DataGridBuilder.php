<?php


namespace Pfilsx\DataGrid\Grid;


use Pfilsx\DataGrid\Grid\Columns\AbstractColumn;
use Pfilsx\DataGrid\Grid\Columns\DataColumn;
use InvalidArgumentException;

class DataGridBuilder implements DataGridBuilderInterface
{
    /**
     * @var array
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
     * @param array $container
     */
    public function __construct(array $container)
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
        return $this->addColumn(DataColumn::class, array_merge(['label' => $attribute], $config, ['attribute' => $attribute]));
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

    public function enablePagination($options = []) : DataGridBuilderInterface
    {
        if (is_array($options) && !empty($options)){
            $this->options['pagination'] = true;
            $this->options['paginationOptions'] = $options;
        } else {
            $this->options['pagination'] = false;
            $this->options['paginationOptions'] = [];
        }
        return $this;
    }
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
