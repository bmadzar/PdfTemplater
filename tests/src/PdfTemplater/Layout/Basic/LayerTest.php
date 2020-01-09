<?php

namespace Layout\Basic;

use PdfTemplater\Layout\Basic\Element;
use PdfTemplater\Layout\Basic\Layer;
use PHPUnit\Framework\TestCase;

class LayerTest extends TestCase
{

    public function testGetNumber()
    {
        $test = new Layer(1);

        $this->assertSame(1, $test->getNumber());

        $test->setNumber(2);

        $this->assertSame(2, $test->getNumber());
    }

    public function testBasic()
    {
        $test = new Layer(1);

        $this->assertSame(1, $test->getNumber());
    }

    public function testSetNumber()
    {
        $test = new Layer(1);

        $test->setNumber(2);

        $this->assertSame(2, $test->getNumber());

        $test->setNumber(0);

        $this->assertSame(0, $test->getNumber());

        $test->setNumber(-2);

        $this->assertSame(-2, $test->getNumber());
    }

    public function testSetElements()
    {
        $test = new Layer(1);

        $el1 = new Element('test1');
        $el2 = new Element('test2');
        $el3 = new Element('test3');

        $test->setElements([$el1, $el2]);

        $this->assertInstanceOf(Element::class, $test->getElement('test1'));
        $this->assertInstanceOf(Element::class, $test->getElement('test2'));

        $test->setElements([$el3]);

        $this->assertInstanceOf(Element::class, $test->getElement('test1'));
        $this->assertInstanceOf(Element::class, $test->getElement('test2'));
        $this->assertInstanceOf(Element::class, $test->getElement('test3'));
    }

    public function testResetElements()
    {
        $test = new Layer(1);

        $el1 = new Element('test1');
        $el2 = new Element('test2');
        $el3 = new Element('test3');

        $test->resetElements([$el1, $el2]);

        $this->assertInstanceOf(Element::class, $test->getElement('test1'));
        $this->assertInstanceOf(Element::class, $test->getElement('test2'));

        $test->resetElements([$el3]);

        $this->assertNull($test->getElement('test1'));
        $this->assertNull($test->getElement('test2'));
        $this->assertInstanceOf(Element::class, $test->getElement('test3'));

        $test->resetElements();

        $this->assertSame([], $test->getElements());
    }

    public function testGetElements()
    {
        $test = new Layer(1);

        $this->assertSame([], $test->getElements());

        $el1 = new Element('test1');
        $el2 = new Element('test2');

        $test->setElements([$el1, $el2]);

        $els = $test->getElements();

        $this->assertContains($el1, $els, '', false, true);
        $this->assertContains($el2, $els, '', false, true);
    }

    public function testGetElement()
    {
        $test = new Layer(1);

        $el1 = new Element('test1');

        $this->assertNull($test->getElement('test1'));

        $test->addElement($el1);

        $this->assertSame($el1, $test->getElement('test1'));
    }

    public function testAddElement()
    {
        $test = new Layer(1);

        $el1 = new Element('test1');
        $el2 = new Element('test1');

        $this->assertNull($test->getElement('test1'));

        $test->addElement($el1);

        $this->assertSame($el1, $test->getElement('test1'));

        $test->addElement($el2);

        $this->assertSame($el2, $test->getElement('test1'));
    }

    public function testRemoveElementById()
    {
        $test = new Layer(1);

        $el1 = new Element('test1');
        $el2 = new Element('test2');

        $test->setElements([$el1, $el2]);

        $this->assertSame($el1, $test->getElement('test1'));

        $test->removeElementById('test1');

        $this->assertNull($test->getElement('test1'));
        $this->assertSame($el2, $test->getElement('test2'));
    }

    public function testRemoveElement()
    {
        $test = new Layer(1);

        $el1 = new Element('test1');
        $el2 = new Element('test2');

        $test->setElements([$el1, $el2]);

        $this->assertSame($el1, $test->getElement('test1'));

        $test->removeElement($el1);

        $this->assertNull($test->getElement('test1'));
        $this->assertSame($el2, $test->getElement('test2'));
    }
}
