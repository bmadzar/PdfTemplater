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
        $test = new LineElement('test', 0.0, 0.0, 1.0, 1.0, 2.0, new RgbColor(0.0, 0.0, 0.0));

        $this->assertSame(2.0, $test->getLineWidth());

        $test->setLineWidth(10.0);

        $this->assertSame(10.0, $test->getLineWidth());
    }


    public function testGetLineColor()
    {
        $test = new LineElement('test', 0.0, 0.0, 1.0, 1.0, 2.0, new RgbColor(0.0, 0.0, 0.0));

        $clr = $test->getLineColor();

        $this->assertInstanceOf(Color::class, $clr);
        $this->assertSame([0.0, 0.0, 0.0], $clr->getRgb());

        $test->setLineColor(new RgbColor(0.5, 0.5, 0.5));

        $clr = $test->getLineColor();

        $this->assertInstanceOf(Color::class, $clr);
        $this->assertSame([0.5, 0.5, 0.5], $clr->getRgb());
    }

    public function testSetLineWidth()
    {
        $test = new LineElement('test', 0.0, 0.0, 1.0, 1.0, 2.0, new RgbColor(0.0, 0.0, 0.0));

        $test->setLineWidth(10.0);

        $this->assertSame(10.0, $test->getLineWidth());
    }

    public function testSetLineWidthInvalid()
    {
        $test = new LineElement('test', 0.0, 0.0, 1.0, 1.0, 2.0, new RgbColor(0.0, 0.0, 0.0));

        $this->expectException(LayoutArgumentException::class);

        $test->setLineWidth(-10.0);
    }

    public function testSetLineColor()
    {
        $test = new LineElement('test', 0.0, 0.0, 1.0, 1.0, 2.0, new RgbColor(0.0, 0.0, 0.0));

        $test->setLineColor(new RgbColor(0.5, 0.5, 0.5));

        $clr = $test->getLineColor();

        $this->assertInstanceOf(Color::class, $clr);
        $this->assertSame([0.5, 0.5, 0.5], $clr->getRgb());
    }
}
