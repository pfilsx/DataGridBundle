<?php


namespace Pfilsx\DataGrid\Grid\Columns;


use Pfilsx\DataGrid\DataGridException;

class DataColumn extends AbstractColumn
{
    protected $attribute;
    protected $format = 'raw';
    protected $sort = true;
    protected $emptyValue = '';

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
    public function getLabel(): string
    {
        return $this->label === false
            ? ''
            : (!empty($this->label) ? $this->label : $this->attribute);
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

    public function getEmptyValue()
    {
        return $this->emptyValue;
    }

    protected function setEmptyValue($value): void
    {
        $this->emptyValue = $value;
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
        $label = $this->getLabel();
        if (($translator = $this->container->getTranslator()) !== null) {
            $label = $translator->trans($label, [], $this->translationDomain);
        }
        return ucfirst($label);
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
        return empty($result)
            ? $this->emptyValue
            : ($this->format === 'html' ? $result : htmlspecialchars($result));
    }

    protected function getCellValue($entity)
    {
        if (is_callable($this->value)) {
            return call_user_func_array($this->value, [$entity]);
        } elseif ($this->value !== null) {
            return $this->value;
        } else {
            return $entity->{$this->attribute};
        }
    }
}
