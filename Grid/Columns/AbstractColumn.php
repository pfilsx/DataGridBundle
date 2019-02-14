<?php


namespace Pfilsx\DataGrid\Grid\Columns;


use Pfilsx\DataGrid\Grid\DataGrid;
use Pfilsx\DataGrid\Grid\Filters\AbstractFilter;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class AbstractColumn
{
    protected $attributes;

    protected $value = null;

    protected $label = '';
    /**
     * @var AbstractFilter|null
     */
    protected $filter;

    protected $filterValue;

    protected $sort = false;

    protected $template;
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container,array $config = [])
    {

        $this->container = $container;
        foreach ($config as $key => $value){
            $setter = 'set'.ucfirst($key);
            if (method_exists($this, $setter)){
                $this->$setter($value);
            }
        }
        $this->checkConfiguration();
    }

    protected function checkConfiguration(){

    }

    public function getAttributes(){
        return $this->attributes;
    }

    public function hasAttributes(){
        return !empty($this->attributes);
    }
    /**
     * @param mixed $attributes
     */
    protected function setAttributes($attributes): void
    {
        $this->attributes = $attributes;
    }
    /**
     * @return null
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param null $value
     */
    protected function setValue($value): void
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    protected function setLabel(string $label): void
    {
        $this->label = $label;
    }
    public function hasFilter(){
        return is_subclass_of($this->filter, AbstractFilter::class);
    }

    public function getFilter(){
        return $this->filter;
    }

    public function setFilter(array $filter){
        $filterClass = $filter['class'];
        unset($filter['class']);
        $filter['template'] = $this->template;
        /** @var AbstractFilter $filterObj */
        $this->filter = new $filterClass($this->container, $filter);
    }



    public function hasSort(){
        return $this->sort !== false && !empty($this->attribute);
    }

    public function setSort($direction){
        $this->sort = $direction;
    }

    public function getSort(){
        return $this->sort;
    }

    public function getAttribute(){
        return null;
    }

    public function getFilterValue(){
        return $this->filterValue;
    }
    public function setFilterValue($value){
        $this->filterValue = $value;
    }

    abstract function getHeadContent();

    abstract function getFilterContent();

    abstract function getCellContent($entity, DataGrid $grid);

    /**
     * @return mixed
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param mixed $template
     */
    public function setTemplate($template): void
    {
        $this->template = $template;
        if ($this->hasFilter()){
            $this->filter->setTemplate($template);
        }
    }

}