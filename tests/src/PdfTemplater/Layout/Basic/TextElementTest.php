<?php

namespace Layout\Basic;

use PdfTemplater\Layout\Basic\RgbColor;
use PdfTemplater\Layout\Basic\TextElement;
use PdfTemplater\Layout\LayoutArgumentException;
use PHPUnit\Framework\TestCase;

class TextElementTest extends TestCase
{

    public function testGetVerticalAlignMode()
    {
        $test = new TextElement(
            'test',
            0.0,
            0.0,
            1.0,
            1.0,
            null,
            null,
            null,
            'test2',
            'Times',
            12.0,
            12.0,
            TextElement::WRAP_NONE,
            TextElement::ALIGN_LEFT,
            TextElement::VERTICAL_ALIGN_TOP
        );

        $this->assertSame(TextElement::VERTICAL_ALIGN_TOP, $test->getVerticalAlignMode());

        $test->setVerticalAlignMode(TextElement::VERTICAL_ALIGN_BOTTOM);

        $this->assertSame(TextElement::VERTICAL_ALIGN_BOTTOM, $test->getVerticalAlignMode());
    }

    public function testGetLineSize()
    {
        $test = new TextElement(
            'test',
            0.0,
            0.0,
            1.0,
            1.0,
            null,
            null,
            null,
            'test2',
            'Times',
            12.0,
            12.0,
            TextElement::WRAP_NONE,
            TextElement::ALIGN_LEFT,
            TextElement::VERTICAL_ALIGN_TOP
        );

        $this->assertSame(12.0, $test->getLineSize());

        $test->setLineSize(10.0);

        $this->assertSame(10.0, $test->getLineSize());
    }

    public function testSetColor()
    {
        $test = new TextElement(
            'test',
            0.0,
            0.0,
            1.0,
            1.0,
            null,
            null,
            null,
            'test2',
            'Times',
            12.0,
            12.0,
            TextElement::WRAP_NONE,
            TextElement::ALIGN_LEFT,
            TextElement::VERTICAL_ALIGN_TOP
        );

        $test->setColor(new RgbColor(0.5, 0.5, 0.5));

        $clr = $test->getColor();

        $this->assertSame([0.5, 0.5, 0.5], $clr->getRgb());
    }

    public function testGetText()
    {
        $test = new TextElement(
            'test',
            0.0,
            0.0,
            1.0,
            1.0,
            null,
            null,
            null,
            'test2',
            'Times',
            12.0,
            12.0,
            TextElement::WRAP_NONE,
            TextElement::ALIGN_LEFT,
            TextElement::VERTICAL_ALIGN_TOP
        );

        $this->assertSame('test2', $test->getText());

        $test->setText('test3');

        $this->assertSame('test3', $test->getText());
    }

    public function testGetWrapMode()
    {
        $test = new TextElement(
            'test',
            0.0,
            0.0,
            1.0,
            1.0,
            null,
            null,
            null,
            'test2',
            'Times',
            12.0,
            12.0,
            TextElement::WRAP_NONE,
            TextElement::ALIGN_LEFT,
            TextElement::VERTICAL_ALIGN_TOP
        );

        $this->assertSame(TextElement::WRAP_NONE, $test->getWrapMode());

        $test->setWrapMode(TextElement::WRAP_HARD);

        $this->assertSame(TextElement::WRAP_HARD, $test->getWrapMode());
    }

    public function testSetAlignMode()
    {
        $test = new TextElement(
            'test',
            0.0,
            0.0,
            1.0,
            1.0,
            null,
            null,
            null,
            'test2',
            'Times',
            12.0,
            12.0,
            TextElement::WRAP_NONE,
            TextElement::ALIGN_LEFT,
            TextElement::VERTICAL_ALIGN_TOP
        );

        $test->setAlignMode(TextElement::ALIGN_RIGHT);

        $this->assertSame(TextElement::ALIGN_RIGHT, $test->getAlignMode());
    }

    public function testSetAlignModeInvalid()
    {
        $test = new TextElement(
            'test',
            0.0,
            0.0,
            1.0,
            1.0,
            null,
            null,
            null,
            'test2',
            'Times',
            12.0,
            12.0,
            TextElement::WRAP_NONE,
            TextElement::ALIGN_LEFT,
            TextElement::VERTICAL_ALIGN_TOP
        );

        $this->expectException(LayoutArgumentException::class);

        $test->setAlignMode(\PHP_INT_MAX);
    }

    public function testSetFont()
    {
        $test = new TextElement(
            'test',
            0.0,
            0.0,
            1.0,
            1.0,
            null,
            null,
            null,
            'test2',
            'Times',
            12.0,
            12.0,
            TextElement::WRAP_NONE,
            TextElement::ALIGN_LEFT,
            TextElement::VERTICAL_ALIGN_TOP
        );

        $test->setFont('Arial');

        $this->assertSame('Arial', $test->getFont());
    }

    public function testGetFontSize()
    {
        $test = new TextElement(
            'test',
            0.0,
            0.0,
            1.0,
            1.0,
            null,
            null,
            null,
            'test2',
            'Times',
            12.0,
            12.0,
            TextElement::WRAP_NONE,
            TextElement::ALIGN_LEFT,
            TextElement::VERTICAL_ALIGN_TOP
        );

        $this->assertSame(12.0, $test->getFontSize());

        $test->setFontSize(11.0);

        $this->assertSame(11.0, $test->getFontSize());
    }

    public function testSetVerticalAlignMode()
    {
        $test = new TextElement(
            'test',
            0.0,
            0.0,
            1.0,
            1.0,
            null,
            null,
            null,
            'test2',
            'Times',
            12.0,
            12.0,
            TextElement::WRAP_NONE,
            TextElement::ALIGN_LEFT,
            TextElement::VERTICAL_ALIGN_TOP
        );

        $test->setVerticalAlignMode(TextElement::VERTICAL_ALIGN_BOTTOM);

        $this->assertSame(TextElement::VERTICAL_ALIGN_BOTTOM, $test->getVerticalAlignMode());
    }

    public function testSetVerticalAlignModeInvalid()
    {
        $test = new TextElement(
            'test',
            0.0,
            0.0,
            1.0,
            1.0,
            null,
            null,
            null,
            'test2',
            'Times',
            12.0,
            12.0,
            TextElement::WRAP_NONE,
            TextElement::ALIGN_LEFT,
            TextElement::VERTICAL_ALIGN_TOP
        );

        $this->expectException(LayoutArgumentException::class);

        $test->setVerticalAlignMode(\PHP_INT_MAX);
    }

    public function testSetLineSize()
    {
        $test = new TextElement(
            'test',
            0.0,
            0.0,
            1.0,
            1.0,
            null,
            null,
            null,
            'test2',
            'Times',
            12.0,
            12.0,
            TextElement::WRAP_NONE,
            TextElement::ALIGN_LEFT,
            TextElement::VERTICAL_ALIGN_TOP
        );

        $test->setLineSize(10.0);

        $this->assertSame(10.0, $test->getLineSize());
    }

    public function testSetLineSizeInvalid1()
    {
        $test = new TextElement(
            'test',
            0.0,
            0.0,
            1.0,
            1.0,
            null,
            null,
            null,
            'test2',
            'Times',
            12.0,
            12.0,
            TextElement::WRAP_NONE,
            TextElement::ALIGN_LEFT,
            TextElement::VERTICAL_ALIGN_TOP
        );

        $this->expectException(LayoutArgumentException::class);

        $test->setLineSize(-10.0);
    }

    public function testSetLineSizeInvalid2()
    {
        $test = new TextElement(
            'test',
            0.0,
            0.0,
            1.0,
            1.0,
            null,
            null,
            null,
            'test2',
            'Times',
            12.0,
            12.0,
            TextElement::WRAP_NONE,
            TextElement::ALIGN_LEFT,
            TextElement::VERTICAL_ALIGN_TOP
        );

        $this->expectException(LayoutArgumentException::class);

        $test->setLineSize(0.0);
    }

    public function testGetAlignMode()
    {
        $test = new TextElement(
            'test',
            0.0,
            0.0,
            1.0,
            1.0,
            null,
            null,
            null,
            'test2',
            'Times',
            12.0,
            12.0,
            TextElement::WRAP_NONE,
            TextElement::ALIGN_LEFT,
            TextElement::VERTICAL_ALIGN_TOP
        );

        $this->assertSame(TextElement::ALIGN_LEFT, $test->getAlignMode());

        $test->setAlignMode(TextElement::ALIGN_RIGHT);

        $this->assertSame(TextElement::ALIGN_RIGHT, $test->getAlignMode());
    }

    public function testSetText()
    {
        $test = new TextElement(
            'test',
            0.0,
            0.0,
            1.0,
            1.0,
            null,
            null,
            null,
            'test2',
            'Times',
            12.0,
            12.0,
            TextElement::WRAP_NONE,
            TextElement::ALIGN_LEFT,
            TextElement::VERTICAL_ALIGN_TOP
        );

        $test->setText('test3');

        $this->assertSame('test3', $test->getText());

        $test->setText('');

        $this->assertSame('', $test->getText());
    }

    public function testGetColor()
    {
        $test = new TextElement(
            'test',
            0.0,
            0.0,
            1.0,
            1.0,
            null,
            null,
            null,
            'test2',
            'Times',
            12.0,
            12.0,
            TextElement::WRAP_NONE,
            TextElement::ALIGN_LEFT,
            TextElement::VERTICAL_ALIGN_TOP
        );

        $this->assertNull($test->getColor());

        $test->setColor(new RgbColor(0.5, 0.5, 0.5));

        $clr = $test->getColor();

        $this->assertSame([0.5, 0.5, 0.5], $clr->getRgb());
    }

    public function testSetFontSize()
    {
        $test = new TextElement(
            'test',
            0.0,
            0.0,
            1.0,
            1.0,
            null,
            null,
            null,
            'test2',
            'Times',
            12.0,
            12.0,
            TextElement::WRAP_NONE,
            TextElement::ALIGN_LEFT,
            TextElement::VERTICAL_ALIGN_TOP
        );

        $test->setFontSize(11.0);

        $this->assertSame(11.0, $test->getFontSize());
    }

    public function testSetFontSizeInvalid1()
    {
        $test = new TextElement(
            'test',
            0.0,
            0.0,
            1.0,
            1.0,
            null,
            null,
            null,
            'test2',
            'Times',
            12.0,
            12.0,
            TextElement::WRAP_NONE,
            TextElement::ALIGN_LEFT,
            TextElement::VERTICAL_ALIGN_TOP
        );

        $this->expectException(LayoutArgumentException::class);

        $test->setFontSize(-12.0);
    }

    public function testSetFontSizeInvalid2()
    {
        $test = new TextElement(
            'test',
            0.0,
            0.0,
            1.0,
            1.0,
            null,
            null,
            null,
            'test2',
            'Times',
            12.0,
            12.0,
            TextElement::WRAP_NONE,
            TextElement::ALIGN_LEFT,
            TextElement::VERTICAL_ALIGN_TOP
        );

        $this->expectException(LayoutArgumentException::class);

        $test->setFontSize(0.0);
    }

    public function testSetWrapMode()
    {
        $test = new TextElement(
            'test',
            0.0,
            0.0,
            1.0,
            1.0,
            null,
            null,
            null,
            'test2',
            'Times',
            12.0,
            12.0,
            TextElement::WRAP_NONE,
            TextElement::ALIGN_LEFT,
            TextElement::VERTICAL_ALIGN_TOP
        );

        $test->setWrapMode(TextElement::WRAP_HARD);

        $this->assertSame(TextElement::WRAP_HARD, $test->getWrapMode());
    }

    public function testSetWrapModeInvalid()
    {
        $test = new TextElement(
            'test',
            0.0,
            0.0,
            1.0,
            1.0,
            null,
            null,
            null,
            'test2',
            'Times',
            12.0,
            12.0,
            TextElement::WRAP_NONE,
            TextElement::ALIGN_LEFT,
            TextElement::VERTICAL_ALIGN_TOP
        );

        $this->expectException(LayoutArgumentException::class);

        $test->setWrapMode(\PHP_INT_MAX);
    }

    public function testGetFont()
    {
        $test = new TextElement(
            'test',
            0.0,
            0.0,
            1.0,
            1.0,
            null,
            null,
            null,
            'test2',
            'Times',
            12.0,
            12.0,
            TextElement::WRAP_NONE,
            TextElement::ALIGN_LEFT,
            TextElement::VERTICAL_ALIGN_TOP
        );

        $this->assertSame('Times', $test->getFont());

        $test->setFont('Arial');

        $this->assertSame('Arial', $test->getFont());
    }
}
