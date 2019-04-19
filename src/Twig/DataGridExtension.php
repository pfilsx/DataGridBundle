<?php


namespace Pfilsx\DataGrid\Twig;


use Pfilsx\DataGrid\Grid\DataGrid;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class DataGridExtension extends AbstractExtension
{
    const DEFAULT_TEMPLATE = '@DataGrid/grid.blocks.html.twig';
    protected $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('grid_view', [$this, 'generateGrid'], [
                'needs_environment' => true,
                'is_safe' => ['html']
            ]),
            new TwigFunction('grid_function_exists', function (Environment $env, $func) {
                return $env->getFunction($func) !== false;
            }, [
                'needs_environment' => true
            ]),
            new TwigFunction('grid_call_function', function (Environment $env, $func, $params = []) {
                return call_user_func_array($env->getFunction($func)->getCallable(), $params);
            }, [
                'needs_environment' => true
            ])
        ];
    }

    public function generateGrid(Environment $environment, DataGrid $grid, array $attributes = [])
    {
        $template = $grid->getTemplate();
        if (!$template->hasBlock('grid_table', [])) {
            $template = $environment->loadTemplate(self::DEFAULT_TEMPLATE);
            $grid->setTemplate($template);
        }
        return $template->renderBlock('grid_table', [
            'attr' => $attributes,
            'data_grid' => $grid,
            'request' => $this->requestStack->getCurrentRequest()
        ]);
    }
}
