<?php


namespace Pfilsx\DataGrid\Grid\Columns;

use Exception;
use Pfilsx\DataGrid\DataGridException;
use Pfilsx\DataGrid\Grid\Items\DataGridItemInterface;

class ActionColumn extends AbstractColumn
{
    protected $buttonsTemplate = '{show} {edit} {delete}';

    protected $buttons = [];

    protected $urlGenerator;

    protected $pathPrefix;

    protected $identifier;

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

    public function getCellContent($entity)
    {
        return preg_replace_callback('/\{(\w+)\}/', function ($matches) use ($entity) {
            if ($this->isButtonVisible($matches[1])) {
                if (array_key_exists($matches[1], $this->buttons) && is_callable($this->buttons[$matches[1]])) {
                    return call_user_func_array($this->buttons[$matches[1]], [
                        $entity,
                        $this->generateUrl($entity, $matches[1])
                    ]);
                }
                return $this->template->renderBlock('action_button', [
                    'url' => $this->generateUrl($entity, $matches[1]),
                    'action' => $matches[1],
                    'entity' => $entity,
                    'identifier' => $this->identifier
                ]);
            }
            return '';
        }, $this->buttonsTemplate);
    }

    /**
     * @param DataGridItemInterface $item
     * @param string $action
     * @return mixed|string
     * @throws Exception
     */
    protected function generateUrl($item, $action)
    {
        if (is_callable($this->urlGenerator)) {
            return call_user_func_array($this->urlGenerator, [$item, $action, $this->container->getRouter()]);
        } elseif (!empty($this->identifier) && $item->has($this->identifier)) {
            return $this->container->getRouter()->generate($this->pathPrefix . $action, [
                'id' => $item->get($this->identifier)
            ]);
        } elseif ($item->hasIdentifier()) {
            return $this->container->getRouter()->generate($this->pathPrefix . $action, [
                'id' => $item->get($item->getIdentifier())
            ]);
        } else {
            throw new DataGridException('Could not generate url for action: ' . $action);
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

    protected function setIdentifier(string $identifier)
    {
        $this->identifier = $identifier;
    }
}
