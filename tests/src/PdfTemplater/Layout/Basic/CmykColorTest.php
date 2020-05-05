<?php
declare(strict_types=1);

namespace PdfTemplater\Layout\Basic;

class CmykColorTest extends ColorTest
{
    protected function getInstance(array $inputData): CmykColor
    {
        return new CmykColor(
            $inputData['cyan'],
            $inputData['magenta'],
            $inputData['yellow'],
            $inputData['black'],
            $inputData['alpha'],
        );
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
}
