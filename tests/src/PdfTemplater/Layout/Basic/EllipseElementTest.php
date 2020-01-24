<?php
declare(strict_types=1);

namespace Layout\Basic;

use PdfTemplater\Layout\Basic\EllipseElement;
use PdfTemplater\Layout\Basic\RgbColor;
use PdfTemplater\Layout\Color;
use PdfTemplater\Layout\LayoutArgumentException;
use PHPUnit\Framework\TestCase;

class EllipseElementTest extends TestCase
{

    public function testGetStrokeWidth()
    {
        $test = new EllipseElement('test', 0.0, 0.0, 1.0, 1.0, null, null, null);

        $this->assertNull($test->getStrokeWidth());

        $test->setStrokeWidth(10.0);

        $this->assertSame(10.0, $test->getStrokeWidth());
    }

    public function testSetStroke()
    {
        $test = new EllipseElement('test', 0.0, 0.0, 1.0, 1.0, null, null, null);

        $test->setStroke(new RgbColor(0.5, 0.5, 0.5));

        $clr = $test->getStroke();

        $this->assertInstanceOf(Color::class, $clr);
        $this->assertSame([0.5, 0.5, 0.5], $clr->getRgb());

        $test->setStroke(null);

        $this->assertNull($test->getStroke());
    }

    public function testSetStrokeWidth()
    {
        $test = new EllipseElement('test', 0.0, 0.0, 1.0, 1.0, null, null, null);

        $test->setStrokeWidth(10.0);

        $this->assertSame(10.0, $test->getStrokeWidth());
    }

    public function testSetStrokeWidthInvalid()
    {
        $test = new EllipseElement('test', 0.0, 0.0, 1.0, 1.0, null, null, null);

        $this->expectException(LayoutArgumentException::class);

        $test->setStrokeWidth(-10.0);
    }

    public function testGetFill()
    {
        $test = new EllipseElement('test', 0.0, 0.0, 1.0, 1.0, null, null, null);

        $this->assertNull($test->getFill());

        $test->setFill(new RgbColor(0.5, 0.5, 0.5));

        $clr = $test->getFill();

        $this->assertInstanceOf(Color::class, $clr);
        $this->assertSame([0.5, 0.5, 0.5], $clr->getRgb());
    }

    public function testSetFill()
    {
        $test = new EllipseElement('test', 0.0, 0.0, 1.0, 1.0, null, null, null);

        $test->setFill(new RgbColor(0.5, 0.5, 0.5));

        $clr = $test->getFill();

        $this->assertInstanceOf(Color::class, $clr);
        $this->assertSame([0.5, 0.5, 0.5], $clr->getRgb());

        $test->setFill(null);

        $this->assertNull($test->getFill());
    }

    public function testGetStroke()
    {
        $test = new EllipseElement('test', 0.0, 0.0, 1.0, 1.0, null, null, null);

        $this->assertNull($test->getStroke());

        $test->setStroke(new RgbColor(0.5, 0.5, 0.5));

        $clr = $test->getStroke();

        $this->assertInstanceOf(Color::class, $clr);
        $this->assertSame([0.5, 0.5, 0.5], $clr->getRgb());
    }
}
