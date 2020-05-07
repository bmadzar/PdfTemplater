<?php
declare(strict_types=1);

namespace PdfTemplater\Layout\Basic;

use PdfTemplater\Layout\Color;
use PdfTemplater\Layout\LayoutArgumentException;
use PHPUnit\Framework\TestCase;

abstract class ColorTest extends TestCase
{
    private const DATA_FILE_PATH =
        __DIR__ . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR .
        'data' . \DIRECTORY_SEPARATOR . 'color_data';

    private const DELTA = 0.025;

    abstract protected function getInstance(array $inputData): Color;

    abstract protected function getBasicInstance(): Color;

    public function getGettersForLimitTests(): array
    {
        return [
            ['Red'],
            ['Green'],
            ['Blue'],
            ['Cyan'],
            ['Magenta'],
            ['Yellow'],
            ['Black'],
            ['Hue'],
            ['Saturation'],
            ['Lightness'],
            ['Alpha'],
        ];
    }

    abstract public function getSettersForLimitTests(): array;

    /**
     * @dataProvider getGettersForLimitTests
     * @param string $getter
     */
    public function testInvalidGetterLimit1(string $getter)
    {
        $test = $this->getBasicInstance();

        $this->expectException(LayoutArgumentException::class);

        $test->{'get' . $getter}(100, 0);
    }

    /**
     * @dataProvider getGettersForLimitTests
     * @param string $getter
     */
    public function testInvalidGetterLimit2(string $getter)
    {
        $test = $this->getBasicInstance();

        $this->expectException(LayoutArgumentException::class);

        $test->{'get' . $getter}(0, 0);
    }

    /**
     * @dataProvider getGettersForLimitTests
     * @param string $getter
     */
    public function testInvalidGetterLimit3(string $getter)
    {
        $test = $this->getBasicInstance();

        $this->expectException(LayoutArgumentException::class);

        $test->{'get' . $getter}(1, 1);
    }

    /**
     * @dataProvider getSettersForLimitTests
     * @param string $setter
     */
    public function testSetter1(string $setter)
    {
        $test = $this->getBasicInstance();

        $test->{'set' . $setter}(0.5);

        $this->assertSame(0.5, $test->{'get' . $setter}());
    }

    /**
     * @dataProvider getSettersForLimitTests
     * @param string $setter
     */
    public function testSetter2(string $setter)
    {
        $test = $this->getBasicInstance();

        $test->{'set' . $setter}(0.0);

        $this->assertSame(0.0, $test->{'get' . $setter}());
    }

    /**
     * @dataProvider getSettersForLimitTests
     * @param string $setter
     */
    public function testSetter3(string $setter)
    {
        $test = $this->getBasicInstance();

        $test->{'set' . $setter}(1.0);

        $this->assertSame(1.0, $test->{'get' . $setter}());
    }

    /**
     * @dataProvider getSettersForLimitTests
     * @param string $setter
     */
    public function testSetterInvalid1(string $setter)
    {
        $test = $this->getBasicInstance();

        $this->expectException(LayoutArgumentException::class);

        $test->{'set' . $setter}(0.0 - self::DELTA);
    }

    /**
     * @dataProvider getSettersForLimitTests
     * @param string $setter
     */
    public function testSetterInvalid2(string $setter)
    {
        $test = $this->getBasicInstance();

        $this->expectException(LayoutArgumentException::class);

        $test->{'set' . $setter}(1.0 + self::DELTA);
    }

    public function generateColorConversionData(): \Generator
    {
        $fh = \fopen(self::DATA_FILE_PATH . \DIRECTORY_SEPARATOR . 'conversion.csv', 'r');

        if ($fh === false) {
            $this->markTestSkipped('Cannot read data file: conversion.csv');
        }

        $header = \fgetcsv($fh) ?: [];

        if (\array_diff(['red', 'green', 'blue', 'cyan', 'magenta', 'yellow', 'black', 'hue', 'saturation', 'lightness', 'alpha'], $header)) {
            \fclose($fh);
            $this->markTestSkipped('Missing fields in conversion.csv');
        }

        while ($line = \fgetcsv($fh)) {
            yield [\array_combine($header, \array_map('floatval', $line))];
        }

        \fclose($fh);
    }

    /**
     * @dataProvider generateColorConversionData
     * @param float[] $inputData
     */
    public function testColorConversion(array $inputData)
    {
        $test = $this->getInstance($inputData);

        $this->assertEqualsWithDelta($inputData['red'], $test->getRed(), self::DELTA);
        $this->assertEqualsWithDelta($inputData['green'], $test->getGreen(), self::DELTA);
        $this->assertEqualsWithDelta($inputData['blue'], $test->getBlue(), self::DELTA);
        //$this->assertEqualsWithDelta($inputData['cyan'], $test->getCyan(), self::DELTA);
        //$this->assertEqualsWithDelta($inputData['magenta'], $test->getMagenta(), self::DELTA);
        //$this->assertEqualsWithDelta($inputData['yellow'], $test->getYellow(), self::DELTA);
        //$this->assertEqualsWithDelta($inputData['black'], $test->getBlack(), self::DELTA);

        if ($inputData['lightness'] >= 0.01 && $inputData['lightness'] <= 0.99) { // White and black
            if ($inputData['saturation'] >= 0.01) { // Grey
                // Hue wraps around
                $this->assertTrue(
                    \abs($inputData['hue'] - $test->getHue()) <= self::DELTA ||
                    \abs($inputData['hue'] - $test->getHue()) >= (1.0 - self::DELTA)
                );
            }

            $this->assertEqualsWithDelta($inputData['saturation'], $test->getSaturation(), self::DELTA);
        }

        $this->assertEqualsWithDelta($inputData['lightness'], $test->getLightness(), self::DELTA);
        $this->assertEqualsWithDelta($inputData['alpha'], $test->getAlpha(), self::DELTA);

        [$r, $g, $b] = $test->getRgb();
        //[$c, $m, $y, $k] = $test->getCmyk();
        [$h, $s, $l] = $test->getHsl();

        $this->assertEqualsWithDelta($inputData['red'], $r, self::DELTA);
        $this->assertEqualsWithDelta($inputData['green'], $g, self::DELTA);
        $this->assertEqualsWithDelta($inputData['blue'], $b, self::DELTA);
        //$this->assertEqualsWithDelta($inputData['cyan'], $c, self::DELTA);
        //$this->assertEqualsWithDelta($inputData['magenta'], $m, self::DELTA);
        //$this->assertEqualsWithDelta($inputData['yellow'], $y, self::DELTA);
        //$this->assertEqualsWithDelta($inputData['black'], $k, self::DELTA);

        if ($inputData['lightness'] >= 0.01 && $inputData['lightness'] <= 0.99) { // White and black
            if ($inputData['saturation'] >= 0.01) { // Grey
                // Hue wraps around
                $this->assertTrue(
                    \abs($inputData['hue'] - $h) <= self::DELTA ||
                    \abs($inputData['hue'] - $h) >= (1.0 - self::DELTA)
                );
            }

            $this->assertEqualsWithDelta($inputData['saturation'], $s, self::DELTA);
        }

        $this->assertEqualsWithDelta($inputData['lightness'], $l, self::DELTA);

        $testHex = str_split($test->getHex(), 2);

        $this->assertEqualsWithDelta($inputData['red'] * 255, (int)hexdec($testHex[0]), self::DELTA * 255);
        $this->assertEqualsWithDelta($inputData['green'] * 255, (int)hexdec($testHex[1]), self::DELTA * 255);
        $this->assertEqualsWithDelta($inputData['blue'] * 255, (int)hexdec($testHex[2]), self::DELTA * 255);

        $this->assertSame((int)hexdec($test->getHex()), $test->getHexAsInt());
    }

    public function generateColorMixingData(): \Generator
    {
        $fh = \fopen(self::DATA_FILE_PATH . \DIRECTORY_SEPARATOR . 'mixing.csv', 'r');

        if ($fh === false) {
            $this->markTestSkipped('Cannot read data file: mixing.csv');
        }

        $header = \fgetcsv($fh) ?: [];

        if (\array_diff([
            'red1',
            'green1',
            'blue1',
            'cyan1',
            'magenta1',
            'yellow1',
            'black1',
            'hue1',
            'saturation1',
            'lightness1',
            'alpha1',
            'red2',
            'green2',
            'blue2',
            'cyan2',
            'magenta2',
            'yellow2',
            'black2',
            'hue2',
            'saturation2',
            'lightness2',
            'alpha2',
            'red',
            'green',
            'blue',
            'cyan',
            'magenta',
            'yellow',
            'black',
            'hue',
            'saturation',
            'lightness',
            'alpha',
        ], $header)) {
            \fclose($fh);
            $this->markTestSkipped('Missing fields in mixing.csv');
        }

        while ($line = \fgetcsv($fh)) {
            yield [\array_combine($header, $line)];
        }

        \fclose($fh);
    }

    /**
     * @dataProvider generateColorMixingData
     * @param float[] $inputData1
     * @param float[] $inputData2
     * @param float[] $outputData
     */
    public function testColorMixing(array $inputData1, array $inputData2, array $outputData)
    {
        $test1 = $this->getInstance($inputData1);
        $test2 = $this->getInstance($inputData2);

        $test = $test1->getMixed($test2);

        $this->assertInstanceOf(get_class($test1), $test2);

        $this->assertEqualsWithDelta($outputData['red'], $test->getRed(), self::DELTA);
        $this->assertEqualsWithDelta($outputData['green'], $test->getGreen(), self::DELTA);
        $this->assertEqualsWithDelta($outputData['blue'], $test->getBlue(), self::DELTA);
        //$this->assertEqualsWithDelta($outputData['cyan'], $test->getCyan(), self::DELTA);
        //$this->assertEqualsWithDelta($outputData['magenta'], $test->getMagenta(), self::DELTA);
        //$this->assertEqualsWithDelta($outputData['yellow'], $test->getYellow(), self::DELTA);
        //$this->assertEqualsWithDelta($outputData['black'], $test->getBlack(), self::DELTA);

        if ($outputData['lightness'] >= 0.01 && $outputData['lightness'] <= 0.99) { // White and black
            if ($outputData['saturation'] >= 0.01) { // Grey
                // Hue wraps around
                $this->assertTrue(
                    \abs($outputData['hue'] - $test->getHue()) <= self::DELTA ||
                    \abs($outputData['hue'] - $test->getHue()) >= (1.0 - self::DELTA)
                );
            }

            $this->assertEqualsWithDelta($outputData['saturation'], $test->getSaturation(), self::DELTA);
        }

        $this->assertEqualsWithDelta($outputData['lightness'], $test->getLightness(), self::DELTA);
        $this->assertEqualsWithDelta($outputData['alpha'], $test->getAlpha(), self::DELTA);
    }

}
