<?php


namespace Pfilsx\DataGrid\tests\columns;


use Exception;
use Pfilsx\DataGrid\DataGridException;
use Pfilsx\DataGrid\Grid\Columns\ActionColumn;
use Pfilsx\DataGrid\Grid\DataGridItem;
use Pfilsx\tests\OrmTestCase;
use Pfilsx\tests\TestEntities\Node;

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
        $this->testColumn = new ActionColumn($this->containerArray, [
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
            'template' => 'test_template.html.twig'
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
        $item = new DataGridItem();
        $item->setEntity($entity);
        $item->setEntityManager($this->getEntityManager());
        $this->assertEquals('_show ', $this->testColumn->getCellContent($item));

        $column = new ActionColumn($this->containerArray, [
            'pathPrefix' => 'test_prefix_',
            'identifier' => 'id',
            'template' => 'test_template.html.twig',
            'buttonsTemplate' => '{show} {edit} {delete}'
        ]);
        $buttons = explode(' ', $column->getCellContent($item));
        $this->assertCount(3, $buttons);
        $this->assertEquals(['show', 'edit', 'delete'], $buttons);

        $column = new ActionColumn($this->containerArray, [
            'pathPrefix' => 'test_prefix_',
            'template' => 'test_template.html.twig',
            'buttonsTemplate' => '{show} {edit} {delete}'
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
        $item = new DataGridItem();
        $item->setEntity($entity);
        $column = new ActionColumn($this->containerArray, [
            'pathPrefix' => 'test_prefix_',
            'template' => 'test_template.html.twig',
        ]);
        $column->getCellContent($item);
    }
}
