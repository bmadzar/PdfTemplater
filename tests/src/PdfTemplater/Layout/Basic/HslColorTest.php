<?php
declare(strict_types=1);

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

        $this->assertSame(0x5C503D, $test->getHexAsInt());

        $test = new HslColor(0.1, 0.2, 0.3, 0.4);

        $this->assertSame(0x5C503D, $test->getHexAsInt());
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
        $fgColor = new HslColor(0.1, 0.2, 0.3, 0.4);
        $bgColor = new HslColor(0.4, 0.3, 0.2, 0.1);

        $mixed = $fgColor->getMixed($bgColor);

        $hsl = $mixed->getHsl();

        $this->assertEqualsWithDelta(0.12, $hsl[0], 0.01);
        $this->assertEqualsWithDelta(0.18, $hsl[1], 0.01);
        $this->assertEqualsWithDelta(0.28, $hsl[2], 0.01);

        $this->assertSame(0.46, $mixed->getAlpha());
    }

    public function testGetMixedTransparent()
    {
        $fgColor = new HslColor(0.1, 0.2, 0.3, 0.0);
        $bgColor = new HslColor(0.4, 0.3, 0.2, 0.0);

        $mixed = $fgColor->getMixed($bgColor);

        $this->assertSame(0.0, $mixed->getAlpha());
    }

    public function testGetMixedOpaque()
    {
        $fgColor = new HslColor(0.1, 0.2, 0.3, 1.0);
        $bgColor = new HslColor(0.4, 0.3, 0.2, 0.1);

        $mixed = $fgColor->getMixed($bgColor);

        $hsl = $mixed->getHsl();

        $this->assertSame(0.1, $hsl[0]);
        $this->assertSame(0.2, $hsl[1]);
        $this->assertSame(0.3, $hsl[2]);

        $this->assertSame(1.0, $mixed->getAlpha());
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

        $this->assertSame('5C503D', $test->getHex());

        $test = new HslColor(0.1, 0.2, 0.3, 0.4);

        $this->assertSame('5C503D', $test->getHex());
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
        $test = new HslColor(0.1, 0.2, 0.3);

        $this->assertSame(0.0, $test->getCyan());

        $test = new HslColor(0.1, 0.2, 0.3, 0.4);

        $this->assertSame(0.0, $test->getCyan());
        $this->assertSame(0.0, $test->getCyan(0, 100));
    }

    public function testGetAlpha()
    {
        $test = new HslColor(0.1, 0.2, 0.3);

        $this->assertSame(1.0, $test->getAlpha());

        $test = new HslColor(0.1, 0.2, 0.3, 0.4);

        $this->assertSame(0.4, $test->getAlpha());
        $this->assertSame(40.0, $test->getAlpha(0, 100));
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

        $this->assertSame($test->getRgb(), [0.36, 0.312, 0.24]);

        $test = new HslColor(0.1, 0.2, 0.3, 0.4);

        $this->assertSame($test->getRgb(), [0.36, 0.312, 0.24]);
    }

    public function testGetBlack()
    {
        $test = new HslColor(0.1, 0.2, 0.3);

        $this->assertSame(0.64, $test->getBlack());

        $test = new HslColor(0.1, 0.2, 0.3, 0.4);

        $this->assertSame(0.64, $test->getBlack());
        $this->assertSame(64.0, $test->getBlack(0, 100));
    }

    public function testGetCmyk()
    {
        $test = new HslColor(0.1, 0.2, 0.3);

        $cmyk = $test->getCmyk();

        $this->assertEqualsWithDelta(0.0, $cmyk[0], 0.00001);
        $this->assertEqualsWithDelta(0.13333, $cmyk[1], 0.00001);
        $this->assertEqualsWithDelta(0.33333, $cmyk[2], 0.00001);
        $this->assertEqualsWithDelta(0.64, $cmyk[3], 0.00001);

        $test = new HslColor(0.1, 0.2, 0.3, 0.4);

        $cmyk = $test->getCmyk();

        $this->assertEqualsWithDelta(0.0, $cmyk[0], 0.00001);
        $this->assertEqualsWithDelta(0.13333, $cmyk[1], 0.00001);
        $this->assertEqualsWithDelta(0.33333, $cmyk[2], 0.00001);
        $this->assertEqualsWithDelta(0.64, $cmyk[3], 0.00001);
    }

    public function testGetBlue()
    {
        $test = new HslColor(0.1, 0.2, 0.3);

        $this->assertSame(0.24, $test->getBlue());

        $test = new HslColor(0.1, 0.2, 0.3, 0.4);

        $this->assertSame(0.24, $test->getBlue());
        $this->assertSame(24.0, $test->getBlue(0, 100));
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
        $test = new HslColor(0.1, 0.2, 0.3);

        $this->assertEqualsWithDelta(0.3333, $test->getYellow(), 0.0001);

        $test = new HslColor(0.1, 0.2, 0.3, 0.4);

        $this->assertEqualsWithDelta(0.3333, $test->getYellow(), 0.0001);
        $this->assertEqualsWithDelta(33.3333, $test->getYellow(0, 100), 0.0001);
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

        $this->assertSame(0.312, $test->getGreen());

        $test = new HslColor(0.1, 0.2, 0.3, 0.4);

        $this->assertSame(0.312, $test->getGreen());
        $this->assertSame(31.2, $test->getGreen(0, 100));
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

        $this->assertSame(0.36, $test->getRed());

        $test = new HslColor(0.1, 0.2, 0.3, 0.4);

        $this->assertSame(0.36, $test->getRed());
        $this->assertSame(36.0, $test->getRed(0, 100));
    }

    public function testGetMagenta()
    {
        $test = new HslColor(0.1, 0.2, 0.3);

        $this->assertEqualsWithDelta(0.1333, $test->getMagenta(), 0.0001);

        $test = new HslColor(0.1, 0.2, 0.3, 0.4);

        $this->assertEqualsWithDelta(0.1333, $test->getMagenta(), 0.0001);
        $this->assertEqualsWithDelta(13.3333, $test->getMagenta(0, 100), 0.0001);
    }
}
