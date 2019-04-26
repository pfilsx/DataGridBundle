<?php


namespace Pfilsx\DataGrid\Grid\Columns;


use Pfilsx\DataGrid\DataGridException;

class RelationColumn extends DataColumn
{
    protected $labelAttribute;

    protected function checkConfiguration()
    {
        parent::checkConfiguration();
        if (!is_string($this->labelAttribute) || empty($this->labelAttribute)) {
            throw new DataGridException('labelAttribute property must be set for RelationColumn');
        }
    }

    public function getHeadContent()
    {
        return !empty($this->label)
            ? ucfirst($this->label)
            : (!empty($this->attribute)
                ? ucfirst($this->attribute) . '.' . ucfirst($this->labelAttribute)
                : ucfirst($this->labelAttribute)
            );
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
     * @param string $labelAttribute
     */
    protected function setLabelAttribute(string $labelAttribute): void
    {
        $this->labelAttribute = $labelAttribute;
    }
}
