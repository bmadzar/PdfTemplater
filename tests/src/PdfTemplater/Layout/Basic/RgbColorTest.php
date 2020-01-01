<?php

namespace PdfTemplater\Layout\Basic;

use PdfTemplater\Layout\LayoutArgumentException;
use PHPUnit\Framework\TestCase;

class RgbColorTest extends TestCase
{

    public function testGetSaturation()
    {
        $test = new RgbColor(0.1, 0.2, 0.3, 0.4);

        $this->assertSame(0.5, $test->getSaturation());
        $this->assertSame(127.5, $test->getSaturation(0, 255));

        $this->expectException(LayoutArgumentException::class);

        $test->getSaturation(255, 0);
    }

    public function testGetHsl()
    {
        $test = new RgbColor(0.1, 0.2, 0.3, 0.4);

        $hsl = $test->getHsl();

        $this->assertEqualsWithDelta(0.58333, $hsl[0], 0.00001);
        $this->assertEqualsWithDelta(0.5, $hsl[1], 0.00001);
        $this->assertEqualsWithDelta(0.2, $hsl[2], 0.00001);
    }

    public function testGetCmyk()
    {
        $test = new RgbColor(0.1, 0.2, 0.3, 0.4);

        $cmyk = $test->getCmyk();

        $this->assertEqualsWithDelta(0.66667, $cmyk[0], 0.00001);
        $this->assertEqualsWithDelta(0.33333, $cmyk[1], 0.00001);
        $this->assertEqualsWithDelta(0.0, $cmyk[2], 0.00001);
        $this->assertEqualsWithDelta(0.7, $cmyk[3], 0.00001);
    }

    public function testRgb()
    {
        $test = new RgbColor(0.1, 0.2, 0.3, 0.4);

        $this->assertSame(0.1, $test->getRed());
        $this->assertSame(0.2, $test->getGreen());
        $this->assertSame(0.3, $test->getBlue());
        $this->assertSame(0.4, $test->getAlpha());
    }

    public function testGetHexAsInt()
    {
        $test = new RgbColor(0.1, 0.2, 0.3, 0.4);

        $this->assertSame(0x1A334D, $test->getHexAsInt());
    }

    public function testSetGreen()
    {
        $test = new RgbColor(0.1, 0.2, 0.3, 0.4);

        $test->setGreen(0.5);

        $this->assertSame(0.5, $test->getGreen());
    }

    public function testGetRgb()
    {
        $test = new RgbColor(0.1, 0.2, 0.3, 0.4);

        $this->assertSame([0.1, 0.2, 0.3], $test->getRgb());
    }

    public function testGetCyan()
    {
        $test = new RgbColor(0.1, 0.2, 0.3, 0.4);

        $this->assertEqualsWithDelta(0.6667, $test->getCyan(), 0.00005);
        $this->assertSame(170.0, $test->getCyan(0, 255));

        $this->expectException(LayoutArgumentException::class);

        $test->getCyan(255, 0);
    }

    public function testGetGreen()
    {
        $test = new RgbColor(0.1, 0.2, 0.3, 0.4);

        $this->assertSame(0.2, $test->getGreen());
        $this->assertSame(51.0, $test->getGreen(0, 255));

        $this->expectException(LayoutArgumentException::class);

        $test->getGreen(255, 0);
    }

    public function testCreateFromHex()
    {
        $test = RgbColor::createFromHex('1A334D');

        $this->assertInstanceOf(RgbColor::class, $test);
        $this->assertEqualsWithDelta(0.1, $test->getRed(), 0.01);
        $this->assertEqualsWithDelta(0.2, $test->getGreen(), 0.01);
        $this->assertEqualsWithDelta(0.3, $test->getBlue(), 0.01);

    }

    public function testCreateFromHexInvalid1()
    {
        $this->expectException(LayoutArgumentException::class);

        RgbColor::createFromHex('1A344Q');
    }

    public function testCreateFromHexInvalid2()
    {
        $this->expectException(LayoutArgumentException::class);

        RgbColor::createFromHex('1A344');
    }

    public function testCreateFromHexInvalid3()
    {
        $this->expectException(LayoutArgumentException::class);

        RgbColor::createFromHex('1A334DE');
    }

    public function testGetBlue()
    {
        $test = new RgbColor(0.1, 0.2, 0.3, 0.4);

        $this->assertSame(0.3, $test->getBlue());
        $this->assertSame(76.5, $test->getBlue(0, 255));

        $this->expectException(LayoutArgumentException::class);

        $test->getBlue(255, 0);
    }

    public function testSetRed()
    {
        $test = new RgbColor(0.1, 0.2, 0.3, 0.4);

        $test->setRed(0.5);

        $this->assertSame(0.5, $test->getRed());
    }

    public function testGetLightness()
    {
        $test = new RgbColor(0.1, 0.2, 0.3, 0.4);

        $this->assertSame(0.2, $test->getLightness());
        $this->assertSame(51.0, $test->getLightness(0, 255));

        $this->expectException(LayoutArgumentException::class);

        $test->getLightness(255, 0);
    }

    public function testGetHex()
    {
        $test = new RgbColor(0.1, 0.2, 0.3, 0.4);

        $this->assertSame('1A334D', $test->getHex());
    }

    public function testSetAlpha()
    {
        $test = new RgbColor(0.1, 0.2, 0.3, 0.4);

        $test->setAlpha(0.5);

        $this->assertSame(0.5, $test->getAlpha());
    }

    public function testGetYellow()
    {
        $test = new RgbColor(0.1, 0.2, 0.3, 0.4);

        $this->assertSame(0.0, $test->getYellow());
        $this->assertSame(0.0, $test->getYellow(0, 255));

        $this->expectException(LayoutArgumentException::class);

        $test->getYellow(255, 0);
    }

    public function testGetBlack()
    {
        $test = new RgbColor(0.1, 0.2, 0.3, 0.4);

        $this->assertSame(0.70, $test->getBlack());
        $this->assertSame(178.5, $test->getBlack(0, 255));

        $this->expectException(LayoutArgumentException::class);

        $test->getBlack(255, 0);
    }

    public function testGetRed()
    {
        $test = new RgbColor(0.1, 0.2, 0.3, 0.4);

        $this->assertSame(0.1, $test->getRed());
        $this->assertSame(25.5, $test->getRed(0, 255));

        $this->expectException(LayoutArgumentException::class);

        $test->getRed(255, 0);
    }

    public function testGetMagenta()
    {
        $test = new RgbColor(0.1, 0.2, 0.3, 0.4);

        $this->assertEqualsWithDelta(0.3333, $test->getMagenta(), 0.00005);
        $this->assertSame(85.0, $test->getMagenta(0, 255));

        $this->expectException(LayoutArgumentException::class);

        $test->getAlpha(255, 0);
    }

    public function testGetAlpha()
    {
        $test = new RgbColor(0.1, 0.2, 0.3, 0.4);

        $this->assertSame(0.4, $test->getAlpha());
        $this->assertSame(102.0, $test->getAlpha(0, 255));

        $this->expectException(LayoutArgumentException::class);

        $test->getAlpha(255, 0);
    }

    public function testSetBlue()
    {
        $test = new RgbColor(0.1, 0.2, 0.3, 0.4);

        $test->setBlue(0.5);

        $this->assertSame(0.5, $test->getBlue());
    }

    public function testGetHue()
    {
        $test = new RgbColor(0.1, 0.2, 0.3, 0.4);

        $this->assertEqualsWithDelta(0.583333, $test->getHue(), 0.000001);
        $this->assertSame(148.75, $test->getHue(0, 255));

        $this->expectException(LayoutArgumentException::class);

        $test->getHue(255, 0);
    }

    public function testGetMixed()
    {
        $testFg = new RgbColor(0.1, 0.2, 0.3, 0.4);
        $testBg = new RgbColor(0.4, 0.3, 0.2, 0.1);

        $mixed = $testFg->getMixed($testBg);

        $rgb = $mixed->getRgb();

        $this->assertEqualsWithDelta(0.176, $rgb[0], 0.001);
        $this->assertEqualsWithDelta(0.216, $rgb[1], 0.001);
        $this->assertEqualsWithDelta(0.290, $rgb[2], 0.001);

        $this->assertSame(0.46, $mixed->getAlpha());
    }
}
