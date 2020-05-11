<?php

declare(strict_types=1);

namespace PdfTemplater\Layout\Basic;

use PdfTemplater\Layout\ColorConverter;

class CmykColorTest extends ColorTest
{
    protected function getInstance(array $inputData, ?ColorConverter $converter = null): CmykColor
    {
        if ($converter) {
            return new CmykColor(
                $inputData['cyan'],
                $inputData['magenta'],
                $inputData['yellow'],
                $inputData['black'],
                $inputData['alpha'],
                $converter,
            );
        } else {
            return new CmykColor(
                $inputData['cyan-naive'],
                $inputData['magenta-naive'],
                $inputData['yellow-naive'],
                $inputData['black-naive'],
                $inputData['alpha'],
            );
        }
    }

    protected function getBasicInstance(): CmykColor
    {
        return new CmykColor(0.1, 0.2, 0.3, 0.4, 0.5);
    }

    public function getSettersForLimitTests(): array
    {
        return [
            ['Cyan'],
            ['Magenta'],
            ['Yellow'],
            ['Black'],
            ['Alpha'],
        ];
    }

    public function generateConverterColorConversionData(): \Generator
    {
        foreach (parent::generateColorConversionData() as $line) {
            if ($line[0]['source'] === 'CMYK') {
                yield $line;
            }
        }
        unset($line);
    }

    /**
     * @dataProvider generateConverterColorConversionData
     * @param float[] $inputData
     */
    public function testConverterColorConversion(array $inputData)
    {
        $test = $this->getInstance($inputData, self::$converter);

        $this->assertEqualsWithDelta($inputData['red'], $test->getRed(), self::DELTA);
        $this->assertEqualsWithDelta($inputData['green'], $test->getGreen(), self::DELTA);
        $this->assertEqualsWithDelta($inputData['blue'], $test->getBlue(), self::DELTA);

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
        [$h, $s, $l] = $test->getHsl();

        $this->assertEqualsWithDelta($inputData['red'], $r, self::DELTA);
        $this->assertEqualsWithDelta($inputData['green'], $g, self::DELTA);
        $this->assertEqualsWithDelta($inputData['blue'], $b, self::DELTA);

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
    }
}
