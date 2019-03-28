<?php


namespace Pfilsx\DataGrid\Grid\Columns;


use Pfilsx\DataGrid\DataGridException;

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
     * @return null|string
     */
    public function getAttribute()
    {
        return $this->attribute;
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
        $this->format = strtolower($format);
    }

    protected function checkConfiguration()
    {
        if ((!is_string($this->attribute) || empty($this->attribute)) && $this->value === null) {
            throw new DataGridException('attribute or value property must be set for ' . static::class);
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
        if ($this->hasFilter()) {
            return $this->filter->render($this->attribute, $this->filterValue);
        }
        return '';
    }

    public function getCellContent($entity)
    {
        $result = (string)$this->getCellValue($entity);
        return $this->format === 'html'
            ? $result
            : htmlspecialchars($result);
    }

    protected function getCellValue($entity)
    {
        if (is_callable($this->value)) {
            return call_user_func_array($this->value, [$entity]);
        } elseif ($this->value !== null) {
            return $this->value;
        } else {
            return $this->getEntityAttribute($entity, $this->attribute);
        }
    }

    protected function getEntityAttribute($entity, $attribute)
    {
        $attribute = preg_replace_callback('/_([A-z]?)/', function ($matches) {
            return isset($matches[1]) ? strtoupper($matches[1]) : '';
        }, $attribute);
        $getter = 'get' . ucfirst($attribute);
        if (method_exists($entity, $getter)) {
            return $entity->$getter();
        }
        throw new DataGridException('Unknown property ' . $attribute . ' in ' . get_class($entity));
    }
}
