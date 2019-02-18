<?php


namespace Pfilsx\DataGrid\Grid\Columns;


use Pfilsx\DataGrid\Grid\DataGrid;

class ImageColumn extends DataColumn
{

    protected $width = 25;

    protected $height = 25;

    protected $format = 'html';


    function getCellContent($entity, DataGrid $grid)
    {
        if ($this->format !== 'raw')
            return '<img src="'.$this->getImgUrl($entity).'" width="'.$this->width.'" height="'.$this->height.'"/>';
        return htmlspecialchars($this->getImgUrl($entity));
    }

    protected function getImgUrl($entity){
        $manager = $this->container->get('assets.packages');
        return $manager->getUrl($this->getCellValue($entity));
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
}