<?php


namespace Pfilsx\DataGrid\Grid\Columns;


use Pfilsx\DataGrid\Grid\DataGrid;
use Exception;

class ActionColumn extends AbstractColumn
{
    protected $buttonsTemplate = '{show} {edit} {delete}';

    protected $buttons = [];

    protected $urlGenerator;

    protected $pathPrefix;

    protected $buttonsVisibility = [
        'show' => true,
        'edit' => true,
        'delete' => true
    ];

    public function getHeadContent()
    {
        return '';
    }

    public function hasFilter()
    {
        return false;
    }

    public function getFilterContent()
    {
        return null;
    }

    public function getCellContent($entity, DataGrid $grid)
    {
        return preg_replace_callback('/\{(\w+)\}/', function($matches) use ($entity, $grid) {
            if ($this->isButtonVisible($matches[1])){
                if (array_key_exists($matches[1], $this->buttons) && is_callable($this->buttons[$matches[1]])){
                    return call_user_func_array($this->buttons[$matches[1]], [
                        $entity,
                        $this->generateUrl($entity, $matches[1], $grid)
                    ]);
                }
                return $grid->getTemplate()->renderBlock('action_button', [
                    'url' => $this->generateUrl($entity, $matches[1], $grid),
                    'action' => $matches[1],
                    'entity' => $entity
                ]);
            }
            return '';
        }, $this->buttonsTemplate);
    }

    /**
     * @param $entity
     * @param string $action
     * @param DataGrid $grid
     * @return mixed|string
     * @throws Exception
     */
    protected function generateUrl($entity, $action, $grid){
        if ($this->urlGenerator != null && is_callable($this->urlGenerator)){
            return call_user_func_array($this->urlGenerator, [$entity, $action, $grid->getRouter()]);
        } elseif (method_exists($entity, 'getId')) {
            return $grid->getRouter()->generate($this->pathPrefix.$action, ['id' => $entity->getId()]);
        } else {
            throw new Exception('Could not generate url for action: '.$action);
        }
    }

    /**
     * @return string
     */
    public function getButtonsTemplate(): string
    {
        return $this->buttonsTemplate;
    }

    /**
     * @param string $buttonsTemplate
     */
    protected function setButtonsTemplate(string $buttonsTemplate): void
    {
        $this->buttonsTemplate = $buttonsTemplate;
    }

    /**
     * @return array
     */
    public function getButtons(): array
    {
        return $this->buttons;
    }

    /**
     * @param array $buttons
     */
    protected function setButtons(array $buttons): void
    {
        $this->buttons = $buttons;
    }

    /**
     * @return mixed
     */
    public function getUrlGenerator()
    {
        return $this->urlGenerator;
    }

    /**
     * @param mixed $urlGenerator
     */
    protected function setUrlGenerator(callable $urlGenerator): void
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @return mixed
     */
    public function getPathPrefix()
    {
        return $this->pathPrefix;
    }

    /**
     * @param mixed $pathPrefix
     */
    protected function setPathPrefix($pathPrefix): void
    {
        $this->pathPrefix = $pathPrefix;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function isButtonVisible(string $name): bool
    {
        return !array_key_exists($name, $this->buttonsVisibility) || $this->buttonsVisibility[$name];
    }

    /**
     * @param array $buttonsVisibility
     */
    public function setButtonsVisibility(array $buttonsVisibility): void
    {
        $this->buttonsVisibility = array_merge($this->buttonsVisibility, $buttonsVisibility);
    }
}
