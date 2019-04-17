<?php


namespace Pfilsx\DataGrid\tests\columns;


use Exception;
use Pfilsx\DataGrid\Grid\Columns\ActionColumn;
use Pfilsx\DataGrid\Grid\DataGridItem;

/**
 * Class ActionColumnTest
 * @package Pfilsx\DataGrid\tests\columns
 *
 * @property ActionColumn $testColumn
 */
class ActionColumnTest extends ColumnCase
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
            ]
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
        $entity = new class()
        {
            public function getId()
            {
                return 1;
            }
        };
        $item = new DataGridItem();
        $item->setEntity($entity);
        $this->assertEquals('_show ', $this->testColumn->getCellContent($item));

        $column = new ActionColumn($this->containerArray, [
            'pathPrefix' => 'test_prefix',
            'identifier' => 'id'
        ]);
        $buttons = explode(' ', $column->getCellContent($item));
        $this->assertEquals(3, count($buttons));
        foreach ($buttons as $buttonJson) {
            $button = json_decode($buttonJson, true);
            $this->assertEquals('action_button', $button[0]);
        }

        $column = new ActionColumn($this->containerArray, [
            'pathPrefix' => 'test_prefix'
        ]);
        $buttons = explode(' ', $column->getCellContent($item));
        $this->assertEquals(3, count($buttons));
        foreach ($buttons as $buttonJson) {
            $button = json_decode($buttonJson, true);
            $this->assertEquals('action_button', $button[0]);
        }
    }

    public function testUrlGeneratorException(): void
    {
        $this->expectException(Exception::class);
        $entity = new class()
        {
        };
        $item = new DataGridItem();
        $item->setEntity($entity);
        $column = new ActionColumn($this->containerArray, [
            'pathPrefix' => 'test_prefix'
        ]);
        $column->getCellContent($item);
    }
}
