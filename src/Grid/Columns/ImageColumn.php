<?php


namespace Pfilsx\DataGrid\Grid\Columns;


use Twig\Template;

class ImageColumn extends DataColumn
{

    protected $width = 25;

    protected $height = 25;

    protected $format = 'html';

    protected $alt;

    protected $emptyValue = '-';

    public function getCellContent($entity)
    {
        $value = (string)$this->getCellValue($entity);
        if (!empty($value)) {
            if ($this->format === 'html') {
                return $this->template->renderBlock('grid_img', [
                    'src' => $value,
                    'width' => $this->width,
                    'height' => $this->height,
                    'alt' => $this->getAlt($entity)
                ]);
            }
            return htmlspecialchars($value);
        } else {
            return $this->emptyValue;
        }
    }

    /**
     * @return int
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * @param int $width
     */
    protected function setWidth(int $width): void
    {
        $this->width = $width;
    }

    /**
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * @param int $height
     */
    protected function setHeight(int $height): void
    {
        $this->height = $height;
    }

    /**
     * @param $entity
     * @return string
     */
    public function getAlt($entity): string
    {
        return is_callable($this->alt)
            ? htmlspecialchars(call_user_func_array($this->alt, [$entity]))
            : '';
    }

    /**
     * @param callable $alt
     */
    protected function setAlt(callable $alt): void
    {
        $this->alt = $alt;
    }
}
