<?php
declare(strict_types=1);

namespace PdfTemplater\Layout\Basic;

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

    protected function getInstance(array $inputData): RgbColor
    {
        return new RgbColor(
            $inputData['red'],
            $inputData['green'],
            $inputData['blue'],
            $inputData['alpha'],
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
}
