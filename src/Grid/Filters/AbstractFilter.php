<?php


namespace Pfilsx\DataGrid\Grid\Filters;


use Twig\Template;

abstract class AbstractFilter
{
    protected $defaultTemplate = '@DataGrid/grid.blocks.html.twig';
    /**
     * @var array
     */
    protected $container;
    /**
     * @var \Twig_Template|null
     */
    protected $template;
    /**
     * @var array
     */
    protected $options = [];

    public function __construct(array $container, array $config = [])
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

    public function setTemplate(?string $template)
    {
        $twig = $this->container['twig'];
        $this->template = is_string($template) ? $twig->loadTemplate($template) : $twig->loadTemplate($this->defaultTemplate);
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
        if (!$this->template instanceof Template || !$this->template->hasBlock('grid_filter', [])) {
            $this->template = $this->container['twig']->loadTemplate($this->defaultTemplate);
        }
        $this->prepareValue($value);
        return $this->template->renderBlock('grid_filter', array_merge($this->getParams(), [
            'attribute' => $attribute,
            'value' => $value,
            'options' => $this->options,
            'block_name' => $this->getBlockName()
        ]));
    }
}
