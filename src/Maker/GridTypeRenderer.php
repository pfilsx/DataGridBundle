<?php


namespace Pfilsx\DataGrid\Maker;

use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\Util\ClassNameDetails;

/**
 * @internal
 */
class GridTypeRenderer
{
    private $generator;

    public function __construct(Generator $generator)
    {
        $this->generator = $generator;
    }

    public function render(ClassNameDetails $gridClassDetails, array $gridFields, ClassNameDetails $boundClassDetails = null)
    {
        $this->generator->generateClass(
            $gridClassDetails->getFullName(),
            __DIR__.'/../Resources/skeleton/grid/Type.tpl.php',
            [
                'bounded_full_class_name' => $boundClassDetails ? $boundClassDetails->getFullName() : null,
                'bounded_class_name' => $boundClassDetails ? $boundClassDetails->getShortName() : null,
                'grid_fields' => $gridFields
            ]
        );
    }
}