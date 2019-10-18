<?php


namespace Pfilsx\DataGrid\Grid\Columns;



class RelationColumn extends DataColumn
{
    protected $labelAttribute;

    protected function checkConfiguration()
    {
        parent::checkConfiguration();
        if (!is_string($this->labelAttribute) || empty($this->labelAttribute)) {
            $this->labelAttribute = 'id';
        }
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return !empty($this->label) ? $this->label : "{$this->attribute}.{$this->labelAttribute}";
    }
    

    public function getCellContent($entity)
    {
        $obj = $this->getCellValue($entity);
        if (is_object($obj)) {
            $result = $obj->{'get' . ucfirst($this->labelAttribute)}();
            return $this->format === 'html'
                ? $result
                : htmlspecialchars($result);
        }
        return is_string($obj) ? $obj : '';
    }

    /**
     * @return mixed
     */
    public function getLabelAttribute()
    {
        return $this->labelAttribute;
    }

    /**
     * @param string $labelAttribute
     */
    protected function setLabelAttribute(string $labelAttribute): void
    {
        $this->labelAttribute = $labelAttribute;
    }
}
