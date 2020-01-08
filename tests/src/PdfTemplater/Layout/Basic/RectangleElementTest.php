<?php

namespace Layout\Basic;

use PdfTemplater\Layout\Basic\RectangleElement;
use PdfTemplater\Layout\Basic\RgbColor;
use PdfTemplater\Layout\Color;
use PdfTemplater\Layout\LayoutArgumentException;
use PHPUnit\Framework\TestCase;

class RectangleElementTest extends TestCase
{

    public function testGetStrokeWidth()
    {
        $test = new RectangleElement('test');

        $this->assertNull($test->getStrokeWidth());

        $test->setStrokeWidth(10.0);

        $this->assertSame(10.0, $test->getStrokeWidth());
    }

    public function testGetFill()
    {
        $test = new RectangleElement('test');

        $this->assertNull($test->getFill());

        $test->setFill(new RgbColor(0.5, 0.5, 0.5));

        $clr = $test->getFill();

        $this->assertInstanceOf(Color::class, $clr);
        $this->assertSame([0.5, 0.5, 0.5], $clr->getRgb());
    }

    public function testSetStroke()
    {
        $test = new RectangleElement('test');

        $test->setStroke(new RgbColor(0.5, 0.5, 0.5));

        $clr = $test->getStroke();

        $this->assertInstanceOf(Color::class, $clr);
        $this->assertSame([0.5, 0.5, 0.5], $clr->getRgb());

        $test->setStroke(null);

        $this->assertNull($test->getStroke());
    }

    public function testSetFill()
    {
        $test = new RectangleElement('test');

        $test->setFill(new RgbColor(0.5, 0.5, 0.5));

        $clr = $test->getFill();

        $this->assertInstanceOf(Color::class, $clr);
        $this->assertSame([0.5, 0.5, 0.5], $clr->getRgb());

        $test->setFill(null);

        $this->assertNull($test->getFill());
    }

    public function testGetStroke()
    {
        $test = new RectangleElement('test');

        $this->assertNull($test->getStroke());

        $test->setStroke(new RgbColor(0.5, 0.5, 0.5));

        $clr = $test->getStroke();

        $this->assertInstanceOf(Color::class, $clr);
        $this->assertSame([0.5, 0.5, 0.5], $clr->getRgb());
    }

    public function testSetStrokeWidth()
    {
        $test = new RectangleElement('test');

        $test->setStrokeWidth(10.0);

        $this->assertSame(10.0, $test->getStrokeWidth());

        $test->setStrokeWidth(null);

        $this->assertNull($test->getStrokeWidth());
    }

    public function testSetStrokeWidthInvalid()
    {
        $test = new RectangleElement('test');

        $this->expectException(LayoutArgumentException::class);

        $test->setStrokeWidth(-10.0);
    }

    public function testIsValid()
    {
        $test = new RectangleElement('test');

        $this->assertTrue($test->isValid());
    }
}
