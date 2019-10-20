<?php


namespace Pfilsx\DataGrid\tests\columns;


use Pfilsx\DataGrid\DataGridException;
use Pfilsx\DataGrid\Grid\Columns\ActionColumn;
use Pfilsx\DataGrid\Grid\Items\EntityGridItem;
use Pfilsx\tests\app\Entity\Node;
use Pfilsx\tests\OrmTestCase;


/**
 * Class ActionColumnTest
 * @package Pfilsx\DataGrid\tests\columns
 *
 * @property ActionColumn $testColumn
 */
class ActionColumnTest extends OrmTestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        $this->testColumn = new ActionColumn($this->serviceContainer, [
            'buttonsTemplate' => '{show} {delete}',
            'buttons' => [
                'show' => function ($entity, $url) {
                    return $url;
                }
            ],
            'pathPrefix' => 'test_prefix',
            'urlGenerator' => function ($entity, $action) {
                return '_' . $action;
            },
            'buttonsVisibility' => [
                'show' => true,
                'delete' => false
            ],
            'template' => $this->template
        ]);
    }

    public function testGetHeadContent(): void
    {
        $this->assertEmpty($this->testColumn->getHeadContent());
    }

    public function testFilter(): void
    {
        $this->assertFalse($this->testColumn->hasFilter());
        $this->assertEmpty($this->testColumn->getFilterContent());
    }

    public function testGetButtonsTemplate(): void
    {
        $this->assertEquals('{show} {delete}', $this->testColumn->getButtonsTemplate());
    }

    public function testButtons(): void
    {
        $this->assertArrayHasKey('show', $this->testColumn->getButtons());
        $this->assertIsCallable($this->testColumn->getButtons()['show']);
    }

    public function testGetUrlGenerator(): void
    {
        $this->assertIsCallable($this->testColumn->getUrlGenerator());
    }

    public function testGetPrefix(): void
    {
        $this->assertEquals('test_prefix', $this->testColumn->getPathPrefix());
    }

    public function testGetButtonVisibility(): void
    {
        $this->assertTrue($this->testColumn->isButtonVisible('show'));
        $this->assertFalse($this->testColumn->isButtonVisible('delete'));
    }

    public function testGetCellContent(): void
    {
        $entity = new Node();
        $entity->setId(1);
        $item = new EntityGridItem($entity, 'id');
        $this->assertEquals('_show ', $this->testColumn->getCellContent($item));

        $column = new ActionColumn($this->serviceContainer, [
            'pathPrefix' => 'test_prefix_',
            'identifier' => 'id',
            'buttonsTemplate' => '{show} {edit} {delete}',
            'template' => $this->template
        ]);
        $buttons = explode(' ', $column->getCellContent($item));
        $this->assertCount(3, $buttons);
        $this->assertEquals(['show', 'edit', 'delete'], $buttons);

        $column = new ActionColumn($this->serviceContainer, [
            'pathPrefix' => 'test_prefix_',
            'buttonsTemplate' => '{show} {edit} {delete}',
            'template' => $this->template
        ]);
        $buttons = explode(' ', $column->getCellContent($item));
        $this->assertCount(3, $buttons);
        $this->assertEquals(['show', 'edit', 'delete'], $buttons);
    }

    public function testUrlGeneratorException(): void
    {
        $this->expectException(DataGridException::class);
        $entity = new class()
        {
        };
        $item = new EntityGridItem($entity);
        $item->setIdentifier('id');
        $column = new ActionColumn($this->serviceContainer, [
            'pathPrefix' => 'test_prefix_',
            'template' => $this->template
        ]);
        $column->getCellContent($item);
    }
}
