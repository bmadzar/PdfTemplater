<?php

namespace PdfTemplater\Layout\Basic;

use PdfTemplater\Layout\LayoutArgumentException;
use PHPUnit\Framework\TestCase;

class HslColorTest extends TestCase
{

    public function testHsl()
    {
        $test = new HslColor(0.1, 0.2, 0.3, 0.4);

        $this->assertSame(0.1, $test->getHue());
        $this->assertSame(0.2, $test->getSaturation());
        $this->assertSame(0.3, $test->getLightness());
        $this->assertSame(0.4, $test->getAlpha());
    }

    public function testGetHexAsInt()
    {
        $test = new HslColor(0.1, 0.2, 0.3);

        $this->assertSame($test->getHexAsInt(), 0x5C503D);

        $test = new HslColor(0.1, 0.2, 0.3, 0.4);

        $this->assertSame($test->getHexAsInt(), 0x5C503D);
    }

    public function testGetLightness()
    {
        $test = new HslColor(0.1, 0.2, 0.3);

        $this->assertSame(0.3, $test->getLightness());
        $this->assertSame(30.0, $test->getLightness(0, 100));

        $test = new HslColor(0.1, 0.2, 0.3, 0.4);

        $this->assertSame(0.3, $test->getLightness());
        $this->assertSame(30.0, $test->getLightness(0, 100));
    }

    public function testGetLightnessInvalid1()
    {
        $test = new HslColor(0.1, 0.2, 0.3);

        $this->expectException(LayoutArgumentException::class);

        $test->getLightness(100, 0);
    }

    public function testGetLightnessInvalid2()
    {
        $test = new HslColor(0.1, 0.2, 0.3);

        $this->expectException(LayoutArgumentException::class);

        $test->getLightness(0, 0);
    }

    public function testSetAlpha()
    {
        $test = new HslColor(0.1, 0.2, 0.3, 0.4);

        $test->setAlpha(0.5);

        $this->assertSame(0.5, $test->getAlpha());

        $test->setAlpha(0);

        $this->assertSame(0.0, $test->getAlpha());

        $test->setAlpha(1.0);

        $this->assertSame(1.0, $test->getAlpha());
    }

    public function testSetAlphaInvalid1()
    {
        $test = new HslColor(0.1, 0.2, 0.3, 0.4);

        $this->expectException(LayoutArgumentException::class);

        $test->setAlpha(-1);
    }

    public function testSetAlphaInvalid2()
    {
        $test = new HslColor(0.1, 0.2, 0.3, 0.4);

        $this->expectException(LayoutArgumentException::class);

        $test->setAlpha(1.01);
    }

    public function testGetMixed()
    {

    }

    public function testSetHue()
    {
        $test = new HslColor(0.1, 0.2, 0.3, 0.4);

        $test->setHue(0.5);

        $this->assertSame(0.5, $test->getHue());

        $test->setHue(0);

        $this->assertSame(0.0, $test->getHue());

        $test->setHue(1.0);

        $this->assertSame(1.0, $test->getHue());
    }

    public function testSetHueInvalid1()
    {
        $test = new HslColor(0.1, 0.2, 0.3, 0.4);

        $this->expectException(LayoutArgumentException::class);

        $test->setHue(-1);
    }

    public function testSetHueInvalid2()
    {
        $test = new HslColor(0.1, 0.2, 0.3, 0.4);

        $this->expectException(LayoutArgumentException::class);

        $test->setHue(1.01);
    }

    public function testGetHex()
    {
        $test = new HslColor(0.1, 0.2, 0.3);

        $this->assertSame($test->getHexAsInt(), '5C503D');

        $test = new HslColor(0.1, 0.2, 0.3, 0.4);

        $this->assertSame($test->getHexAsInt(), '5C503D');
    }

    public function testGetHsl()
    {
        $test = new HslColor(0.1, 0.2, 0.3);

        $this->assertSame($test->getHsl(), [0.1, 0.2, 0.3]);

        $test = new HslColor(0.1, 0.2, 0.3, 0.4);

        $this->assertSame($test->getHsl(), [0.1, 0.2, 0.3]);
    }

    public function testGetCyan()
    {

    }

    public function testGetAlpha()
    {
        $test = new HslColor(0.1, 0.2, 0.3);

        $this->assertSame(0.0, $test->getAlpha());

        $test = new HslColor(0.1, 0.2, 0.3, 0.4);

        $this->assertSame(0.4, $test->getAlpha());
        $this->assertSame(40, $test->getAlpha(0, 100));
    }

    public function testGetAlphaInvalid1()
    {
        $test = new HslColor(0.1, 0.2, 0.3, 0.4);

        $this->expectException(LayoutArgumentException::class);

        $test->getAlpha(100, 0);
    }

    public function testGetAlphaInvalid2()
    {
        $test = new HslColor(0.1, 0.2, 0.3, 0.4);

        $this->expectException(LayoutArgumentException::class);

        $test->getAlpha(0, 0);
    }

    public function testGetRgb()
    {
        $test = new HslColor(0.1, 0.2, 0.3);

        $this->assertSame($test->getRgb(), [0x5C / 0xFF, 0x50 / 0xFF, 0x3D / 0xFF]);

        $test = new HslColor(0.1, 0.2, 0.3, 0.4);

        $this->assertSame($test->getRgb(), [0x5C / 0xFF, 0x50 / 0xFF, 0x3D / 0xFF]);
    }

    public function testGetBlack()
    {

    }

    public function testGetCmyk()
    {
        $test = new HslColor(0.1, 0.2, 0.3);

        $this->assertSame($test->getCmyk(), [0.0, 0.02, 0.34, 0.64]);

        $test = new HslColor(0.1, 0.2, 0.3, 0.4);

        $this->assertSame($test->getCmyk(), [0.0, 0.02, 0.34, 0.64]);
    }

    public function testGetBlue()
    {
        $test = new HslColor(0.1, 0.2, 0.3);

        $this->assertSame(0x3D / 0xFF, $test->getRed());

        $test = new HslColor(0.1, 0.2, 0.3, 0.4);

        $this->assertSame(0x3D / 0xFF, $test->getRed());
        $this->assertSame((0x3D * 100) / 0xFF, $test->getRed(0, 100));
    }

    public function testGetHue()
    {
        $test = new HslColor(0.1, 0.2, 0.3);

        $this->assertSame(0.1, $test->getHue());
        $this->assertSame(10.0, $test->getHue(0, 100));

        $test = new HslColor(0.1, 0.2, 0.3, 0.4);

        $this->assertSame(0.1, $test->getHue());
        $this->assertSame(10.0, $test->getHue(0, 100));
    }

    public function testGetHueInvalid1()
    {
        $test = new HslColor(0.1, 0.2, 0.3);

        $this->expectException(LayoutArgumentException::class);

        $test->getHue(100, 0);
    }

    public function testGetHueInvalid2()
    {
        $test = new HslColor(0.1, 0.2, 0.3);

        $this->expectException(LayoutArgumentException::class);

        $test->getHue(0, 0);
    }

    public function testGetYellow()
    {

    }

    public function testGetSaturation()
    {
        $test = new HslColor(0.1, 0.2, 0.3);

        $this->assertSame(0.2, $test->getSaturation());
        $this->assertSame(20.0, $test->getSaturation(0, 100));

        $test = new HslColor(0.1, 0.2, 0.3, 0.4);

        $this->assertSame(0.2, $test->getSaturation());
        $this->assertSame(20.0, $test->getSaturation(0, 100));
    }

    public function testGetSaturationInvalid1()
    {
        $test = new HslColor(0.1, 0.2, 0.3);

        $this->expectException(LayoutArgumentException::class);

        $test->getSaturation(100, 0);
    }

    public function testGetSaturationInvalid2()
    {
        $test = new HslColor(0.1, 0.2, 0.3);

        $this->expectException(LayoutArgumentException::class);

        $test->getSaturation(0, 0);
    }

    public function testGetGreen()
    {
        $test = new HslColor(0.1, 0.2, 0.3);

        $this->assertSame(0x50 / 0xFF, $test->getRed());

        $test = new HslColor(0.1, 0.2, 0.3, 0.4);

        $this->assertSame(0x50 / 0xFF, $test->getRed());
        $this->assertSame((0x50 * 100) / 0xFF, $test->getRed(0, 100));
    }

    public function testSetSaturation()
    {
        $test = new HslColor(0.1, 0.2, 0.3, 0.4);

        $test->setSaturation(0.5);

        $this->assertSame(0.5, $test->getSaturation());

        $test->setSaturation(0);

        $this->assertSame(0.0, $test->getSaturation());

        $test->setSaturation(1.0);

        $this->assertSame(1.0, $test->getSaturation());
    }

    public function testSetSaturationInvalid1()
    {
        $test = new HslColor(0.1, 0.2, 0.3, 0.4);

        $this->expectException(LayoutArgumentException::class);

        $test->setSaturation(-1);
    }

    public function testSetSaturationInvalid2()
    {
        $test = new HslColor(0.1, 0.2, 0.3, 0.4);

        $this->expectException(LayoutArgumentException::class);

        $test->setSaturation(1.01);
    }

    public function testSetLightness()
    {
        $test = new HslColor(0.1, 0.2, 0.3, 0.4);

        $test->setLightness(0.5);

        $this->assertSame(0.5, $test->getLightness());

        $test->setLightness(0);

        $this->assertSame(0.0, $test->getLightness());

        $test->setLightness(1.0);

        $this->assertSame(1.0, $test->getLightness());
    }

    public function testSetLightnessInvalid1()
    {
        $test = new HslColor(0.1, 0.2, 0.3, 0.4);

        $this->expectException(LayoutArgumentException::class);

        $test->setLightness(-1);
    }

    public function testSetLightnessInvalid2()
    {
        $test = new HslColor(0.1, 0.2, 0.3, 0.4);

        $this->expectException(LayoutArgumentException::class);

        $test->setLightness(1.01);
    }

    public function testGetRed()
    {
        $test = new HslColor(0.1, 0.2, 0.3);

        $this->assertSame(0x5C / 0xFF, $test->getRed());

        $test = new HslColor(0.1, 0.2, 0.3, 0.4);

        $this->assertSame(0x5C / 0xFF, $test->getRed());
        $this->assertSame((0x5C * 100) / 0xFF, $test->getRed(0, 100));
    }

    public function testGetMagenta()
    {

    }
}