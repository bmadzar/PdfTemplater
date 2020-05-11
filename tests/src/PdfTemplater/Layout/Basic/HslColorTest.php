<?php

declare(strict_types=1);

namespace PdfTemplater\Layout\Basic;

use PdfTemplater\Layout\ColorConverter;

class HslColorTest extends ColorTest
{

    protected function getInstance(array $inputData, ?ColorConverter $converter = null): HslColor
    {
        return new HslColor(
            $inputData['hue'],
            $inputData['saturation'],
            $inputData['lightness'],
            $inputData['alpha'],
            $converter,
        );
    }

    protected function getBasicInstance(): HslColor
    {
        return new HslColor(0.1, 0.2, 0.3, 0.4);
    }

    public function getSettersForLimitTests(): array
    {
        return [
            ['Hue'],
            ['Saturation'],
            ['Lightness'],
            ['Alpha'],
        ];
    }

    public function generateConverterColorConversionData(): \Generator
    {
        foreach (parent::generateColorConversionData() as $line) {
            if ($line[0]['source'] === 'RGB') {
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

        if ($inputData['black'] <= 0.99) {
            $this->assertEqualsWithDelta($inputData['cyan'], $test->getCyan(), self::DELTA);
            $this->assertEqualsWithDelta($inputData['magenta'], $test->getMagenta(), self::DELTA);
            $this->assertEqualsWithDelta($inputData['yellow'], $test->getYellow(), self::DELTA);
        }
        $this->assertEqualsWithDelta($inputData['black'], $test->getBlack(), self::DELTA);

        [$c, $m, $y, $k] = $test->getCmyk();

        if ($inputData['black'] <= 0.99) {
            $this->assertEqualsWithDelta($inputData['cyan'], $c, self::DELTA);
            $this->assertEqualsWithDelta($inputData['magenta'], $m, self::DELTA);
            $this->assertEqualsWithDelta($inputData['yellow'], $y, self::DELTA);
        }
        $this->assertEqualsWithDelta($inputData['black'], $k, self::DELTA);
    }
}
