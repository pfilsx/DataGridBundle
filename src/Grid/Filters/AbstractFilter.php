<?php


namespace Pfilsx\DataGrid\Grid\Filters;


use Pfilsx\DataGrid\DataGridServiceContainer;
use Twig\TemplateWrapper;

abstract class AbstractFilter
{
    /**
     * @var DataGridServiceContainer
     */
    protected $container;
    /**
     * @var TemplateWrapper|null
     */
    protected $template;
    /**
     * @var array
     */
    protected $options = [];

    public function __construct(DataGridServiceContainer $container, array $config = [])
    {
        $this->container = $container;
        foreach ($config as $key => $value) {
            $setter = 'set' . ucfirst($key);
            if (method_exists($this, $setter)) {
                $this->$setter($value);
            }
        }
    }

    public function getTemplate()
    {
        return $this->template;
    }

    public function setTemplate(?TemplateWrapper $template)
    {
        $this->template = $template;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function setOptions(array $value)
    {
        $this->options = $value;
    }

    public abstract function getBlockName(): ?string;

    protected function getParams(): array
    {
        return [];
    }

    protected function prepareValue(&$value)
    {
    }

    public function render($attribute, $value): string
    {
        $this->prepareValue($value);
        return $this->template->renderBlock('grid_filter', array_merge($this->getParams(), [
            'attribute' => $attribute,
            'value' => $value,
            'options' => $this->options,
            'block_name' => $this->getBlockName()
        ]));
    }
}
