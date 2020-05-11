<?php

declare(strict_types=1);

namespace PdfTemplater\Layout\Basic;

use PdfTemplater\Layout\ColorConverter;
use PdfTemplater\Layout\LayoutArgumentException;

class RgbColorTest extends ColorTest
{
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

    protected function getInstance(array $inputData, ?ColorConverter $converter = null): RgbColor
    {
        return new RgbColor(
            $inputData['red'],
            $inputData['green'],
            $inputData['blue'],
            $inputData['alpha'],
            $converter,
        );
    }

    protected function getBasicInstance(): RgbColor
    {
        return new RgbColor(0.1, 0.2, 0.3, 0.4);
    }

    public function getSettersForLimitTests(): array
    {
        return [
            ['Red'],
            ['Green'],
            ['Blue'],
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
