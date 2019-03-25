<?php


namespace Pfilsx\DataGrid\Grid\Columns;


use Twig\Template;

class ImageColumn extends DataColumn
{

    protected $width = 25;

    protected $height = 25;

    protected $format = 'html';

    protected $alt;

    protected $noImageMessage = '-';

    function getCellContent($entity)
    {
        $value = (string)$this->getCellValue($entity);
        if (!empty($value)) {
            if ($this->format === 'html') {
                /**
                 * @var Template $template
                 */
                $template = $this->container['twig']->loadTemplate($this->template);
                return $template->renderBlock('grid_img', [
                    'src' => $value,
                    'width' => $this->width,
                    'height' => $this->height,
                    'alt' => $this->getAlt($entity)
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
