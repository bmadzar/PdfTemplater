<?php

namespace PdfTemplater\Layout\Basic;

use PdfTemplater\Layout\LayoutArgumentException;
use PHPUnit\Framework\TestCase;

class CmykColorTest extends TestCase
{
    public function testCmyk()
    {
        $test = new CmykColor(0.1, 0.2, 0.3, 0.4, 1.0);

        $this->assertSame(0.1, $test->getCyan());
        $this->assertSame(0.2, $test->getMagenta());
        $this->assertSame(0.3, $test->getYellow());
        $this->assertSame(0.4, $test->getBlack());
        $this->assertSame(1.0, $test->getAlpha());
    }

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
        $test = new CmykColor(0.1, 0.2, 0.3, 0.4, 1.0);

        $this->assertEqualsWithDelta(0.48, $test->getLightness(), 0.000005);
        $this->assertEqualsWithDelta(122.4, $test->getLightness(0, 255), 0.000005);

        $this->expectException(LayoutArgumentException::class);

        $test->getLightness(255, 0);

    }

    public function testGetGreen()
    {
        $test = new CmykColor(0.1, 0.2, 0.3, 0.4, 1.0);

        $this->assertEqualsWithDelta(0.48, $test->getGreen(), 0.000005);
        $this->assertSame(122.4, $test->getGreen(0, 255));

        $this->expectException(LayoutArgumentException::class);

        $test->getGreen(255, 0);
    }

    public function testGetHex()
    {
        $test = new CmykColor(0.1, 0.2, 0.3, 0.4, 1.0);

        $this->assertSame('8A7A6B', $test->getHex());
    }

    public function testGetHue()
    {
        $test = new CmykColor(0.1, 0.2, 0.3, 0.4, 1.0);

        $this->assertEqualsWithDelta(0.083333, $test->getHue(), 0.00001);
        $this->assertSame(21.25, $test->getHue(0, 255));

        $this->expectException(LayoutArgumentException::class);

        $test->getHue(255, 0);
    }

    public function testSetCyan()
    {
        $test = new CmykColor(0.1, 0.2, 0.3, 0.4, 1.0);

        $this->assertSame(0.1, $test->getCyan());

        $test->setCyan(0.75);

        $this->assertSame(0.75, $test->getCyan());

        $test->setCyan(1.0);
        $test->setCyan(0.0);
    }

    public function testSetCyanLimits()
    {
        $test = new CmykColor(0.1, 0.2, 0.3, 0.4, 1.0);

        $test->setCyan(1.0);
        $test->setCyan(0.0);

        $this->assertTrue(true);
    }

    public function testSetCyanNegative()
    {
        $test = new CmykColor(0.1, 0.2, 0.3, 0.4, 1.0);

        $this->expectException(LayoutArgumentException::class);

        $test->setCyan(-0.1);
    }

    public function testSetCyanTooLarge()
    {
        $test = new CmykColor(0.1, 0.2, 0.3, 0.4, 1.0);

        $this->expectException(LayoutArgumentException::class);

        $test->setCyan(1.1);
    }

    public function testSetMagenta()
    {
        $test = new CmykColor(0.1, 0.2, 0.3, 0.4, 1.0);

        $this->assertSame(0.2, $test->getMagenta());

        $test->setMagenta(0.75);

        $this->assertSame(0.75, $test->getMagenta());

        $test->setMagenta(1.0);
        $test->setMagenta(0.0);
    }

    public function testSetMagentaLimits()
    {
        $test = new CmykColor(0.1, 0.2, 0.3, 0.4, 1.0);

        $test->setMagenta(1.0);
        $test->setMagenta(0.0);

        $this->assertTrue(true);
    }

    public function testSetMagentaNegative()
    {
        $test = new CmykColor(0.1, 0.2, 0.3, 0.4, 1.0);

        $this->expectException(LayoutArgumentException::class);

        $test->setMagenta(-0.1);
    }

    public function testSetMagentaTooLarge()
    {
        $test = new CmykColor(0.1, 0.2, 0.3, 0.4, 1.0);

        $this->expectException(LayoutArgumentException::class);

        $test->setMagenta(1.1);
    }


    public function testSetBlack()
    {
        $test = new CmykColor(0.1, 0.2, 0.3, 0.4, 1.0);

        $this->assertSame(0.4, $test->getBlack());

        $test->setBlack(0.75);

        $this->assertSame(0.75, $test->getBlack());

        $test->setBlack(1.0);
        $test->setBlack(0.0);
    }

    public function testSetBlackLimits()
    {
        $test = new CmykColor(0.1, 0.2, 0.3, 0.4, 1.0);

        $test->setBlack(1.0);
        $test->setBlack(0.0);

        $this->assertTrue(true);
    }

    public function testSetBlackNegative()
    {
        $test = new CmykColor(0.1, 0.2, 0.3, 0.4, 1.0);

        $this->expectException(LayoutArgumentException::class);

        $test->setBlack(-0.1);
    }

    public function testSetBlackTooLarge()
    {
        $test = new CmykColor(0.1, 0.2, 0.3, 0.4, 1.0);

        $this->expectException(LayoutArgumentException::class);

        $test->setBlack(1.1);
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

        $this->assertSame(0.42, $test->getBlue());
        $this->assertSame(107.1, $test->getBlue(0, 255));

        $this->expectException(LayoutArgumentException::class);

        $test->getBlue(255, 0);
    }

    public function testGetRed()
    {
        $test = new CmykColor(0.1, 0.2, 0.3, 0.4, 1.0);

        $this->assertSame(0.54, $test->getRed());
        $this->assertSame(137.7, $test->getRed(0, 255));

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
        $test = new CmykColor(0.1, 0.2, 0.3, 0.4, 1.0);

        $hsl = $test->getHsl();

        $this->assertEqualsWithDelta(0.083333, $hsl[0], 0.00001);
        $this->assertEqualsWithDelta(0.125, $hsl[1], 0.00001);
        $this->assertEqualsWithDelta(0.480, $hsl[2], 0.00001);
    }

    public function testGetSaturation()
    {
        $test = new CmykColor(0.1, 0.2, 0.3, 0.4, 1.0);

        $this->assertEqualsWithDelta(0.125, $test->getSaturation(), 0.000005);
        $this->assertSame(31.875, $test->getSaturation(0, 255));

        $this->expectException(LayoutArgumentException::class);

        $test->getSaturation(255, 0);
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

        $this->assertSame([0.54, 0.48, 0.42], $test->getRgb());
    }


    public function testSetYellow()
    {
        $test = new CmykColor(0.1, 0.2, 0.3, 0.4, 1.0);

        $this->assertSame(0.3, $test->getYellow());

        $test->setYellow(0.75);

        $this->assertSame(0.75, $test->getYellow());

        $test->setYellow(1.0);
        $test->setYellow(0.0);
    }

    public function testSetYellowLimits()
    {
        $test = new CmykColor(0.1, 0.2, 0.3, 0.4, 1.0);

        $test->setYellow(1.0);
        $test->setYellow(0.0);

        $this->assertTrue(true);
    }

    public function testSetYellowNegative()
    {
        $test = new CmykColor(0.1, 0.2, 0.3, 0.4, 1.0);

        $this->expectException(LayoutArgumentException::class);

        $test->setYellow(-0.1);
    }

    public function testSetYellowTooLarge()
    {
        $test = new CmykColor(0.1, 0.2, 0.3, 0.4, 1.0);

        $this->expectException(LayoutArgumentException::class);

        $test->setYellow(1.1);
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
        $test = new CmykColor(0.1, 0.2, 0.3, 0.4, 1.0);

        $this->assertSame(1.0, $test->getAlpha());

        $test->setAlpha(0.75);

        $this->assertSame(0.75, $test->getAlpha());
    }

    public function testSetAlphaLimits()
    {
        $test = new CmykColor(0.1, 0.2, 0.3, 0.4, 1.0);

        $test->setAlpha(1.0);
        $test->setAlpha(0.0);

        $this->assertTrue(true);
    }

    public function testSetAlphaNegative()
    {
        $test = new CmykColor(0.1, 0.2, 0.3, 0.4, 1.0);

        $this->expectException(LayoutArgumentException::class);

        $test->setAlpha(-0.1);
    }

    public function testSetAlphaTooLarge()
    {
        $test = new CmykColor(0.1, 0.2, 0.3, 0.4, 1.0);

        $this->expectException(LayoutArgumentException::class);

        $test->setAlpha(1.1);
    }

    public function testGetHexAsInt()
    {
        $test = new CmykColor(0.1, 0.2, 0.3, 0.4, 1.0);

        $this->assertSame(0x8A7A6B, $test->getHexAsInt());
    }

    public function testGetMixed()
    {
        $testFg = new CmykColor(0.1, 0.2, 0.3, 0.4, 0.5);
        $testBg = new CmykColor(0.5, 0.4, 0.3, 0.2, 0.1);

        $mixed = $testFg->getMixed($testBg);

        $cmyk = $mixed->getCmyk();

        $this->assertEqualsWithDelta(0.14, $cmyk[0], 0.005);
        $this->assertEqualsWithDelta(0.22, $cmyk[1], 0.005);
        $this->assertEqualsWithDelta(0.30, $cmyk[2], 0.005);
        $this->assertEqualsWithDelta(0.38, $cmyk[3], 0.005);

        $this->assertSame(0.55, $mixed->getAlpha());
    }

    public function testGetMixedTransparent()
    {
        $testFg = new CmykColor(0.1, 0.2, 0.3, 0.4, 0.0);
        $testBg = new CmykColor(0.5, 0.4, 0.3, 0.2, 0.0);

        $mixed = $testFg->getMixed($testBg);

        $this->assertSame(0.0, $mixed->getAlpha());
    }

    public function testGetMixedOpaque()
    {
        $testFg = new CmykColor(0.1, 0.2, 0.3, 0.4, 1.0);
        $testBg = new CmykColor(0.5, 0.4, 0.3, 0.2, 0.1);

        $mixed = $testFg->getMixed($testBg);

        $cmyk = $mixed->getCmyk();

        $this->assertSame(0.1, $cmyk[0]);
        $this->assertSame(0.2, $cmyk[1]);
        $this->assertSame(0.3, $cmyk[2]);
        $this->assertSame(0.4, $cmyk[3]);

        $this->assertSame(1.0, $mixed->getAlpha());
    }
}
