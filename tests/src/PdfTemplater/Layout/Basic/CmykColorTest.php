<?php

namespace PdfTemplater\Layout\Basic;

use PdfTemplater\Layout\LayoutArgumentException;
use PHPUnit\Framework\TestCase;

class CmykColorTest extends TestCase
{

    public function testGetBlack()
    {
        $test = new CmykColor(0, 0, 0, 0, 1.0);

        $this->assertSame(0.0, $test->getBlack());

        $test->setBlack(0.5);

        $this->assertSame(0.5, $test->getBlack());
        $this->assertSame(5.0, $test->getBlack(0, 10));

        $this->expectException(LayoutArgumentException::class);

        $test->getBlack(10, 0);
    }

    public function testGetLightness()
    {

    }

    public function testGetGreen()
    {
        $test = new CmykColor(0.1, 0.2, 0.3, 0.4, 1.0);

        $this->assertSame(0.47843, $test->getGreen());
        $this->assertSame(122, $test->getGreen(0, 255));

        $this->expectException(LayoutArgumentException::class);

        $test->getGreen(255, 0);
    }

    public function testGetHex()
    {

    }

    public function testGetHue()
    {

    }

    public function testSetCyan()
    {

    }

    public function testSetMagenta()
    {

    }

    public function testSetBlack()
    {

    }

    public function testGetYellow()
    {
        $test = new CmykColor(0, 0, 0, 0, 1.0);

        $this->assertSame(0.0, $test->getYellow());

        $test->setYellow(0.5);

        $this->assertSame(0.5, $test->getYellow());
        $this->assertSame(5.0, $test->getYellow(0, 10));

        $this->expectException(LayoutArgumentException::class);

        $test->getYellow(10, 0);
    }

    public function testGetCmyk()
    {
        $test = new CmykColor(0.1, 0.2, 0.3, 0.4, 1.0);

        $this->assertSame([0.1, 0.2, 0.3, 0.4], $test->getCmyk());

    }

    public function testGetBlue()
    {
        $test = new CmykColor(0.1, 0.2, 0.3, 0.4, 1.0);

        $this->assertSame(0.41961, $test->getBlue());
        $this->assertSame(107, $test->getBlue(0, 255));

        $this->expectException(LayoutArgumentException::class);

        $test->getBlue(255, 0);
    }

    public function testGetRed()
    {
        $test = new CmykColor(0.1, 0.2, 0.3, 0.4, 1.0);

        $this->assertSame(0.54118, $test->getRed());
        $this->assertSame(138, $test->getRed(0, 255));

        $this->expectException(LayoutArgumentException::class);

        $test->getRed(255, 0);
    }

    public function testGetCyan()
    {
        $test = new CmykColor(0, 0, 0, 0, 1.0);

        $this->assertSame(0.0, $test->getCyan());

        $test->setCyan(0.5);

        $this->assertSame(0.5, $test->getCyan());
        $this->assertSame(5.0, $test->getCyan(0, 10));

        $this->expectException(LayoutArgumentException::class);

        $test->getCyan(10, 0);
    }

    public function testGetHsl()
    {

    }

    public function testGetSaturation()
    {

    }

    public function testGetAlpha()
    {
        $test = new CmykColor(0, 0, 0, 0, 1.0);

        $this->assertSame(1.0, $test->getAlpha());

        $test->setAlpha(0.5);

        $this->assertSame(0.5, $test->getAlpha());
        $this->assertSame(5.0, $test->getAlpha(0, 10));

        $this->expectException(LayoutArgumentException::class);

        $test->getAlpha(10, 0);
    }

    public function testGetRgb()
    {
        $test = new CmykColor(0.1, 0.2, 0.3, 0.4, 1.0);

        $this->assertSame([0.54118, 0.47843, 0.41961], $test->getRgb());
    }

    public function testSetYellow()
    {

    }

    public function testGetMagenta()
    {
        $test = new CmykColor(0, 0, 0, 0, 1.0);

        $this->assertSame(0.0, $test->getMagenta());

        $test->setMagenta(0.5);

        $this->assertSame(0.5, $test->getMagenta());
        $this->assertSame(5.0, $test->getMagenta(0, 10));

        $this->expectException(LayoutArgumentException::class);

        $test->getMagenta(10, 0);
    }

    public function testSetAlpha()
    {

    }

    public function testGetHexAsInt()
    {

    }
}
