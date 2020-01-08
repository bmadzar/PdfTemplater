<?php

namespace Layout\Basic;

use PdfTemplater\Layout\Basic\LineElement;
use PdfTemplater\Layout\Basic\RgbColor;
use PdfTemplater\Layout\Color;
use PdfTemplater\Layout\LayoutArgumentException;
use PHPUnit\Framework\TestCase;

class LineElementTest extends TestCase
{

    public function testGetLineWidth()
    {
        $test = new LineElement('test');

        $test->setLineWidth(10.0);

        $this->assertSame(10.0, $test->getLineWidth());
    }

    public function testGetLineWidthInvalid()
    {
        $test = new LineElement('test');

        $this->expectException(LayoutArgumentException::class);

        $test->getLineWidth();
    }

    public function testGetLineColor()
    {
        $test = new LineElement('test');

        $test->setLineColor(new RgbColor(0.5, 0.5, 0.5));

        $clr = $test->getLineColor();

        $this->assertInstanceOf(Color::class, $clr);
        $this->assertSame([0.5, 0.5, 0.5], $clr->getRgb());
    }

    public function testGetLineColorInvalid()
    {
        $test = new LineElement('test');

        $this->expectException(LayoutArgumentException::class);

        $test->getLineColor();
    }

    public function testSetLineWidth()
    {
        $test = new LineElement('test');

        $test->setLineWidth(10.0);

        $this->assertSame(10.0, $test->getLineWidth());
    }

    public function testSetLineWidthInvalid()
    {
        $test = new LineElement('test');

        $this->expectException(LayoutArgumentException::class);

        $test->setLineWidth(-10.0);
    }

    public function testSetLineColor()
    {
        $test = new LineElement('test');

        $test->setLineColor(new RgbColor(0.5, 0.5, 0.5));

        $clr = $test->getLineColor();

        $this->assertInstanceOf(Color::class, $clr);
        $this->assertSame([0.5, 0.5, 0.5], $clr->getRgb());
    }
}
