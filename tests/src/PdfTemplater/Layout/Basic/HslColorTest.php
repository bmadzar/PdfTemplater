<?php
declare(strict_types=1);

namespace PdfTemplater\Layout\Basic;

class HslColorTest extends ColorTest
{

    protected function getInstance(array $inputData): HslColor
    {
        return new HslColor(
            $inputData['hue'],
            $inputData['saturation'],
            $inputData['lightness'],
            $inputData['alpha'],
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
}
