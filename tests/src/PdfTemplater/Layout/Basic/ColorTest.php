<?php

declare(strict_types=1);

namespace PdfTemplater\Layout\Basic;

use PdfTemplater\Layout\Color;
use PdfTemplater\Layout\ColorConverter;
use PdfTemplater\Layout\LayoutArgumentException;
use PdfTemplater\Test\DataFile;
use PHPUnit\Framework\TestCase;

abstract class ColorTest extends TestCase
{
    protected const DATA_FILE_PATH =
        __DIR__ . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR .
        'data' . \DIRECTORY_SEPARATOR . 'color_data';

    protected const DELTA = 0.03;

    abstract protected function getInstance(array $inputData, ?ColorConverter $converter = null): Color;

    abstract protected function getBasicInstance(): Color;

    protected static ?ColorConverter $converter = null;

    public static function setUpBeforeClass(): void
    {
        self::$converter = new StubConverter();
    }

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
        try {
            $fh = new DataFile(self::DATA_FILE_PATH . \DIRECTORY_SEPARATOR . 'conversion.csv');
        } catch (\RuntimeException $ex) {
            $this->markTestSkipped($ex->getMessage());
        }

        /** @noinspection PhpUndefinedVariableInspection */
        $header = $fh->getParsedLine() ?: [];

        if (\array_diff(
            [
                'red',
                'green',
                'blue',
                'cyan',
                'magenta',
                'yellow',
                'black',
                'cyan-naive',
                'magenta-naive',
                'yellow-naive',
                'black-naive',
                'hue',
                'saturation',
                'lightness',
                'alpha',
                'source',
            ],
            $header
        )) {
            $this->markTestSkipped('Missing fields in conversion.csv');
        }

        while ($line = $fh->getParsedLine()) {
            yield [['source' => $line[15]] + \array_combine($header, \array_map('\floatval', $line))];
        }
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
        if ($inputData['black-naive'] <= 0.99) {
            $this->assertEqualsWithDelta($inputData['cyan-naive'], $test->getCyan(), self::DELTA);
            $this->assertEqualsWithDelta($inputData['magenta-naive'], $test->getMagenta(), self::DELTA);
            $this->assertEqualsWithDelta($inputData['yellow-naive'], $test->getYellow(), self::DELTA);
        }
        $this->assertEqualsWithDelta($inputData['black-naive'], $test->getBlack(), self::DELTA);

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
        [$c, $m, $y, $k] = $test->getCmyk();
        [$h, $s, $l] = $test->getHsl();

        $this->assertEqualsWithDelta($inputData['red'], $r, self::DELTA);
        $this->assertEqualsWithDelta($inputData['green'], $g, self::DELTA);
        $this->assertEqualsWithDelta($inputData['blue'], $b, self::DELTA);
        if ($inputData['black-naive'] <= 0.99) {
            $this->assertEqualsWithDelta($inputData['cyan-naive'], $c, self::DELTA);
            $this->assertEqualsWithDelta($inputData['magenta-naive'], $m, self::DELTA);
            $this->assertEqualsWithDelta($inputData['yellow-naive'], $y, self::DELTA);
        }
        $this->assertEqualsWithDelta($inputData['black-naive'], $k, self::DELTA);

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
        try {
            $fh = new DataFile(self::DATA_FILE_PATH . \DIRECTORY_SEPARATOR . 'mixing.csv');
        } catch (\RuntimeException $ex) {
            $this->markTestSkipped($ex->getMessage());
        }

        /** @noinspection PhpUndefinedVariableInspection */
        $header = $fh->getParsedLine() ?: [];

        if (\array_diff(
            [
                'idx',
                'red',
                'green',
                'blue',
                'cyan',
                'magenta',
                'yellow',
                'black',
                'cyan-naive',
                'magenta-naive',
                'yellow-naive',
                'black-naive',
                'hue',
                'saturation',
                'lightness',
                'alpha',
                'source',
            ],
            $header
        )) {
            $this->markTestSkipped('Missing fields in mixing.csv');
        }

        $sourceData = [];

        while ($line = $fh->getParsedLine()) {
            $line                          = \array_combine($header, $line);
            $sourceData[(int)$line['idx']] = $line;
        }
        unset($line, $fh);

        try {
            $fh = new DataFile(self::DATA_FILE_PATH . \DIRECTORY_SEPARATOR . 'mixing-combined.csv');
        } catch (\RuntimeException $ex) {
            $this->markTestSkipped($ex->getMessage());
        }

        /** @noinspection PhpUndefinedVariableInspection */
        $header = $fh->getParsedLine() ?: [];

        if (\array_diff(
            [
                'row',
                'col',
                'red',
                'green',
                'blue',
                'cyan',
                'magenta',
                'yellow',
                'black',
                'cyan-naive',
                'magenta-naive',
                'yellow-naive',
                'black-naive',
                'hue',
                'saturation',
                'lightness',
                'alpha',
                'source',
            ],
            $header
        )) {
            $this->markTestSkipped('Missing fields in mixing-combined.csv');
        }

        while ($line = $fh->getParsedLine()) {
            $line = \array_combine($header, $line);

            if (!isset($sourceData[(int)$line['row']], $sourceData[(int)$line['col']])) {
                $this->markTestSkipped('Data mismatch in mixing files.');
            }

            $inputData1 = $sourceData[(int)$line['col']];
            $inputData2 = $sourceData[(int)$line['row']];
            $outputData = $line;

            unset($inputData1['idx'], $inputData2['idx'], $outputData['row'], $outputData['col']);

            yield [
                \array_map('floatval', $inputData1),
                \array_map('floatval', $inputData2),
                \array_map('floatval', $outputData),
            ];
        }
        unset($line, $fh);
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
        if ($outputData['black-naive'] <= 0.99) {
            $this->assertEqualsWithDelta($outputData['cyan-naive'], $test->getCyan(), self::DELTA);
            $this->assertEqualsWithDelta($outputData['magenta-naive'], $test->getMagenta(), self::DELTA);
            $this->assertEqualsWithDelta($outputData['yellow-naive'], $test->getYellow(), self::DELTA);
        }
        $this->assertEqualsWithDelta($outputData['black-naive'], $test->getBlack(), self::DELTA);

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
