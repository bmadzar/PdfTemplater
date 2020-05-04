<?php
declare(strict_types=1);

namespace Layout\Basic;

use PdfTemplater\Layout\Basic\Element;
use PdfTemplater\Layout\LayoutArgumentException;
use PHPUnit\Framework\TestCase;

class ElementTest extends TestCase
{

    public function testGetTop()
    {
        $test = new Element('test', 0.0, 0.0, 1.0, 1.0);

        $this->assertSame(0.0, $test->getTop());

        $test->setTop(10.0);

        $this->assertSame(10.0, $test->getTop());
    }

    public function testSetId()
    {
        $test = new Element('test', 0.0, 0.0, 1.0, 1.0);

        $this->assertSame('test', $test->getId());

        $test->setId('test2');

        $this->assertSame('test2', $test->getId());
    }

    public function testSetIdInvalid()
    {
        $test = new Element('test', 0.0, 0.0, 1.0, 1.0);

        $this->expectException(LayoutArgumentException::class);

        $test->setId('');
    }

    public function testSetLeft()
    {
        $test = new Element('test', 0.0, 0.0, 1.0, 1.0);

        $test->setLeft(10.0);

        $this->assertSame(10.0, $test->getLeft());

        $test->setLeft(-10.0);

        $this->assertSame(-10.0, $test->getLeft());

        $test->setLeft(0.0);

        $this->assertSame(0.0, $test->getLeft());
    }

    public function testSetWidth()
    {
        $test = new Element('test', 0.0, 0.0, 1.0, 1.0);

        $test->setWidth(10.0);

        $this->assertSame(10.0, $test->getWidth());

        $test->setWidth(0.0);

        $this->assertSame(0.0, $test->getWidth());

        $this->expectException(LayoutArgumentException::class);

        $test->setWidth(-10.0);
    }

    public function testSetHeight()
    {
        $test = new Element('test', 0.0, 0.0, 1.0, 1.0);

        $test->setHeight(10.0);

        $this->assertSame(10.0, $test->getHeight());

        $test->setHeight(0.0);

        $this->assertSame(0.0, $test->getHeight());

        $this->expectException(LayoutArgumentException::class);

        $test->setHeight(-10.0);
    }

    public function testBasic()
    {
        $test = new Element('test', 0.0, 0.0, 1.0, 1.0);

        $this->assertSame('test', $test->getId());
    }

    public function testSetTop()
    {
        $test = new Element('test', 0.0, 0.0, 1.0, 1.0);

        $test->setTop(10.0);

        $this->assertSame(10.0, $test->getTop());

        $test->setTop(-10.0);

        $this->assertSame(-10.0, $test->getTop());

        $test->setTop(0.0);

        $this->assertSame(0.0, $test->getTop());
    }

    public function testGetWidth()
    {
        $test = new Element('test', 0.0, 0.0, 1.0, 1.0);

        $this->assertSame(1.0, $test->getWidth());

        $test->setWidth(10.0);

        $this->assertSame(10.0, $test->getWidth());
    }

    public function testGetHeight()
    {
        $test = new Element('test', 0.0, 0.0, 1.0, 1.0);

        $this->assertSame(1.0, $test->getHeight());

        $test->setHeight(10.0);

        $this->assertSame(10.0, $test->getHeight());
    }

    public function testGetLeft()
    {
        $test = new Element('test', 0.0, 0.0, 1.0, 1.0);

        $this->assertSame(0.0, $test->getLeft());

        $test->setLeft(10.0);

        $this->assertSame(10.0, $test->getLeft());
    }

    public function testGetId()
    {
        $test = new Element('test', 0.0, 0.0, 1.0, 1.0);

        $this->assertSame('test', $test->getId());
    }
}
