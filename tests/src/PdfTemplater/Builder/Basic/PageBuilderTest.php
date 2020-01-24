<?php
declare(strict_types=1);

namespace Builder\Basic;

use PdfTemplater\Builder\Basic\PageBuilder;
use PdfTemplater\Layout\Element;
use PdfTemplater\Layout\Layer;
use PdfTemplater\Layout\Page;
use PdfTemplater\Node\Basic\Node;
use PHPUnit\Framework\TestCase;

class PageBuilderTest extends TestCase
{

    public function testBuildPage1()
    {
        $test = new PageBuilder();

        $node = new Node('page', null, [
            'number' => '1',
            'width'  => '11.0',
            'height' => '10.0',
        ]);

        $page = $test->buildPage($node);

        $this->assertInstanceOf(Page::class, $page);
        $this->assertSame(1, $page->getNumber());
        $this->assertSame(11.0, $page->getWidth());
        $this->assertSame(10.0, $page->getHeight());
        $this->assertSame([], $page->getLayers());
        $this->assertNull($page->getLayer(1));
    }

    public function testBuildPage2()
    {
        $test = new PageBuilder();

        $node = new Node('page', null, [
            'number' => '1',
            'width'  => '11.0',
            'height' => '10.0',
        ]);

        $element = new Node('element', 'test1', [
            'top'    => '10.0',
            'left'   => '11.0',
            'width'  => '12.0',
            'height' => '13.0',
        ]);

        $node->addChild($element);

        $page = $test->buildPage($node);

        $this->assertInstanceOf(Page::class, $page);
        $this->assertSame(1, $page->getNumber());
        $this->assertSame(11.0, $page->getWidth());
        $this->assertSame(10.0, $page->getHeight());
        $this->assertCount(1, $page->getLayers());

        $layer = $page->getLayer(0);

        $this->assertInstanceOf(Layer::class, $layer);
        $this->assertCount(1, $layer->getElements());
        $this->assertSame(0, $layer->getNumber());

        $element = $layer->getElement('test1');

        $this->assertInstanceOf(Element::class, $element);
    }

    public function testBuildPage3()
    {
        $test = new PageBuilder();

        $node = new Node('page', null, [
            'number' => '1',
            'width'  => '11.0',
            'height' => '10.0',
        ]);

        $element1 = new Node('element', 'test1', [
            'top'    => '10.0',
            'left'   => '11.0',
            'width'  => '12.0',
            'height' => '13.0',
            'layer'  => 1,
        ]);

        $element2 = new Node('element', 'test2', [
            'top'    => '20.0',
            'left'   => '21.0',
            'width'  => '22.0',
            'height' => '23.0',
            'layer'  => 2,
        ]);

        $node->addChild($element1);
        $node->addChild($element2);

        $page = $test->buildPage($node);

        $layer0 = $page->getLayer(0);

        $this->assertNull($layer0);

        $layer1 = $page->getLayer(1);

        $this->assertInstanceOf(Layer::class, $layer1);
        $this->assertCount(1, $layer1->getElements());
        $this->assertSame(1, $layer1->getNumber());

        $element1 = $layer1->getElement('test1');

        $this->assertInstanceOf(Element::class, $element1);

        $layer2 = $page->getLayer(2);

        $this->assertInstanceOf(Layer::class, $layer2);
        $this->assertCount(1, $layer2->getElements());
        $this->assertSame(2, $layer2->getNumber());

        $element2 = $layer2->getElement('test2');

        $this->assertInstanceOf(Element::class, $element2);

        $element1b = $layer2->getElement('test1');

        $this->assertNull($element1b);
    }

    public function testBuildPage4()
    {
        $test = new PageBuilder();

        $node = new Node('page', null, [
            'number' => '1',
            'width'  => '11.0',
            'height' => '10.0',
        ]);

        $element = new Node('element', 'test1', [
            'top'    => '10.0',
            'left'   => '11.0',
            'width'  => '12.0',
            'height' => '13.0',
        ]);

        $node->addChild($element);

        $page = $test->buildPage($node);
        $layer = $page->getLayer(0);

        $this->assertInstanceOf(Layer::class, $layer);
        $this->assertCount(1, $layer->getElements());
        $this->assertSame(0, $layer->getNumber());

        $element = $layer->getElement('test2');

        $this->assertNull($element);
    }

}
