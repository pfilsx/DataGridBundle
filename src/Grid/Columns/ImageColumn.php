<?php


namespace Pfilsx\DataGrid\Grid\Columns;


use Pfilsx\DataGrid\Grid\DataGrid;

class ImageColumn extends DataColumn
{

    protected $width = 25;

    protected $height = 25;

    protected $format = 'html';

    protected $alt = '';

    protected $noImageMessage = '-';

    function getCellContent($entity, ?DataGrid $grid)
    {
        $value = (string)$this->getCellValue($entity);
        if (!empty($value)){
            if ($this->format !== 'raw'){
                return $grid->getTemplate()->renderBlock('grid_img', [
                    'src' => $value,
                    'width' => $this->width,
                    'height' => $this->height,
                    'alt' => $this->alt
                ]);
            }
            return htmlspecialchars($value);
        } else {
            return $this->noImageMessage;
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
     * @return string
     */
    public function getAlt(): string
    {
        return $this->alt;
    }

    /**
     * @param string $alt
     */
    protected function setAlt(string $alt): void
    {
        $this->alt = $alt;
    }

    /**
     * @return string
     */
    public function getNoImageMessage(): string
    {
        return $this->noImageMessage;
    }

    /**
     * @param string $noImageMessage
     */
    protected function setNoImageMessage(string $noImageMessage): void
    {
        $this->noImageMessage = $noImageMessage;
    }
}