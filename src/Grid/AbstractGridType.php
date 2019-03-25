<?php


namespace Pfilsx\DataGrid\Grid;

abstract class AbstractGridType
{
    const ACTION_COLUMN = 'Pfilsx\DataGrid\Grid\Columns\ActionColumn';
    const BOOLEAN_COLUMN = 'Pfilsx\DataGrid\Grid\Columns\BooleanColumn';
    const IMAGE_COLUMN = 'Pfilsx\DataGrid\Grid\Columns\ImageColumn';
    const DATA_COLUMN = 'Pfilsx\DataGrid\Grid\Columns\DataColumn';
    const SERIAL_COLUMN = 'Pfilsx\DataGrid\Grid\Columns\SerialColumn';
    const DATE_COLUMN = 'Pfilsx\DataGrid\Grid\Columns\DateColumn';

    const FILTER_TEXT = 'Pfilsx\DataGrid\Grid\Filters\TextFilter';
    const FILTER_BOOLEAN = 'Pfilsx\DataGrid\Grid\Filters\BooleanFilter';
    const FILTER_ENTITY = 'Pfilsx\DataGrid\Grid\Filters\EntityFilter';
    const FILTER_CHOICE = 'Pfilsx\DataGrid\Grid\Filters\ChoiceFilter';
    const FILTER_DATE = 'Pfilsx\DataGrid\Grid\Filters\DateFilter';
    const FILTER_CUSTOM = 'Pfilsx\DataGrid\Grid\Filters\CustomFilter';

    /**
     * @var array
     */
    protected $container;

    protected $doctrine;

    protected $environment;

    protected $router;

    protected  $request;

    /**
     * AbstractGridType constructor.
     * @param array $container
     *
     * @uses AbstractGridType::setDoctrine()
     * @uses AbstractGridType::setTwig()
     * @uses AbstractGridType::setRouter()
     * @uses AbstractGridType::setRequest()
     */
    public function __construct(array $container)
    {
        $this->container = $container;
        foreach ($this->container as $key => $value){
            $setter = 'set'.ucfirst($key);
            if (method_exists($this, $setter)){
                $this->$setter($value);
            }
        }
    }

    public abstract function buildGrid(DataGridBuilderInterface $builder): void;

    public abstract function handleFilters(DataGridFiltersBuilderInterface $builder, array $filters): void;

    protected function setDoctrine($value){
        $this->doctrine = $value;
    }

    protected function setTwig($value){
        $this->environment = $value;
    }

    protected function setRouter($value){
        $this->router = $value;
    }

    protected function setRequest($value){
        $this->request = $value;
    }
}
