<?php


namespace Pfilsx\DataGrid\Grid\Columns;


use Pfilsx\DataGrid\Grid\DataGrid;
use Exception;

class DataColumn extends AbstractColumn
{
    protected $attribute;
    protected $format = 'raw';
    protected $sort = true;

    /**
     * @param mixed $attribute
     */
    protected function setAttribute($attribute): void
    {
        $this->attribute = $attribute;
    }

    /**
     * @return string
     */
    public function getFormat(): string
    {
        return $this->format;
    }

    /**
     * @param string $format
     */
    protected function setFormat(string $format): void
    {
        $this->format = $format;
    }

    protected function checkConfiguration()
    {
        if (!is_string($this->attribute) && $this->value === null){
            throw new Exception('attribute or value property must be set for DataColumn');
        }
    }

    public function hasFilter()
    {
        return parent::hasFilter() && !empty($this->attribute);
    }

    public function getHeadContent()
    {
        return !empty($this->label) ? ucfirst($this->label) : (!empty($this->attribute) ? ucfirst($this->attribute) : '');
    }

    public function getFilterContent()
    {
        if ($this->hasFilter()){
            return $this->filter->render($this->attribute, $this->filterValue);
        }
        return '';
    }

    public function getCellContent($entity, DataGrid $grid)
    {
        return $this->format == 'html'
            ? $this->getCellValue($entity)
            : htmlspecialchars($this->getCellValue($entity));
    }

    public function getAttribute(){
        return $this->attribute;
    }

    protected function getCellValue($entity){
        if (is_callable($this->value)){
            return call_user_func_array($this->value, [$entity]);
        }
        if ($this->value !== null){
            return $this->value;
        }
        if (is_string($this->attribute)){
            return $this->getEntityAttribute($entity, $this->attribute);
        }
        throw new Exception('attribute or value property must be set for '.self::class);
    }

    protected function getEntityAttribute($entity, $attribute){
        $attribute = preg_replace_callback('/_([A-z]?)/', function($matches){
            return isset($matches[1]) ? strtoupper($matches[1]) : '';
        }, $attribute);
        $getter = 'get'.ucfirst($attribute);
        if (method_exists($entity, $getter)){
            return $entity->$getter();
        }
        throw new Exception('Unknown property '.$attribute.' in '.get_class($entity));
    }
}