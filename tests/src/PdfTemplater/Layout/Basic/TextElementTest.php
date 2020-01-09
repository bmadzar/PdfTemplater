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
        $test = new TextElement('test');

        $test->setVerticalAlignMode(TextElement::VERTICAL_ALIGN_BOTTOM);

        $this->assertSame(TextElement::VERTICAL_ALIGN_BOTTOM, $test->getVerticalAlignMode());
    }

    public function testGetVerticalAlignModeInvalid()
    {
        $test = new TextElement('test');

        $this->expectException(LayoutArgumentException::class);

        $test->getVerticalAlignMode();
    }

    public function testGetLineSize()
    {
        $test = new TextElement('test');

        $test->setLineSize(10.0);

        $this->assertSame(10.0, $test->getLineSize());
    }

    public function testGetLineSizeInvalid()
    {
        $test = new TextElement('test');

        $this->expectException(LayoutArgumentException::class);

        $test->getLineSize();
    }

    public function testSetColor()
    {
        $test = new TextElement('test');

        $test->setColor(new RgbColor(0.5, 0.5, 0.5));

        $clr = $test->getColor();

        $this->assertSame([0.5, 0.5, 0.5], $clr->getRgb());
    }

    public function testGetText()
    {
        $test = new TextElement('test');

        $test->setText('test2');

        $this->assertSame('test2', $test->getText());
    }

    public function testGetTextInvalid()
    {
        $test = new TextElement('test');

        $this->expectException(LayoutArgumentException::class);

        $test->getText();
    }

    public function testGetWrapMode()
    {
        $test = new TextElement('test');

        $test->setWrapMode(TextElement::WRAP_HARD);

        $this->assertSame(TextElement::WRAP_HARD, $test->getWrapMode());
    }

    public function testGetWrapModeInvalid()
    {
        $test = new TextElement('test');

        $this->expectException(LayoutArgumentException::class);

        $test->getWrapMode();
    }

    public function testSetAlignMode()
    {
        $test = new TextElement('test');

        $test->setAlignMode(TextElement::ALIGN_RIGHT);

        $this->assertSame(TextElement::ALIGN_RIGHT, $test->getAlignMode());
    }

    public function testSetAlignModeInvalid()
    {
        $test = new TextElement('test');

        $this->expectException(LayoutArgumentException::class);

        $test->setAlignMode(\PHP_INT_MAX);
    }

    public function testSetFont()
    {
        $test = new TextElement('test');

        $test->setFont('Arial');

        $this->assertSame('Arial', $test->getFont());
    }

    public function testGetFontSize()
    {
        $test = new TextElement('test');

        $test->setFontSize(12.0);

        $this->assertSame(12.0, $test->getFontSize());
    }

    public function testGetFontSizeInvalid()
    {
        $test = new TextElement('test');

        $this->expectException(LayoutArgumentException::class);

        $test->getFontSize();
    }

    public function testSetVerticalAlignMode()
    {
        $test = new TextElement('test');

        $test->setVerticalAlignMode(TextElement::VERTICAL_ALIGN_BOTTOM);

        $this->assertSame(TextElement::VERTICAL_ALIGN_BOTTOM, $test->getVerticalAlignMode());
    }

    public function testSetVerticalAlignModeInvalid()
    {
        $test = new TextElement('test');

        $this->expectException(LayoutArgumentException::class);

        $test->setVerticalAlignMode(\PHP_INT_MAX);
    }

    public function testSetLineSize()
    {
        $test = new TextElement('test');

        $test->setLineSize(10.0);

        $this->assertSame(10.0, $test->getLineSize());
    }

    public function testSetLineSizeInvalid1()
    {
        $test = new TextElement('test');

        $this->expectException(LayoutArgumentException::class);

        $test->setLineSize(-10.0);
    }

    public function testSetLineSizeInvalid2()
    {
        $test = new TextElement('test');

        $this->expectException(LayoutArgumentException::class);

        $test->setLineSize(0.0);
    }

    public function testGetAlignMode()
    {
        $test = new TextElement('test');

        $test->setAlignMode(TextElement::ALIGN_RIGHT);

        $this->assertSame(TextElement::ALIGN_RIGHT, $test->getAlignMode());
    }

    public function testGetAlignModeInvalid()
    {
        $test = new TextElement('test');

        $this->expectException(LayoutArgumentException::class);

        $test->getAlignMode();
    }

    public function testSetText()
    {
        $test = new TextElement('test');

        $test->setText('test2');

        $this->assertSame('test2', $test->getText());

        $test->setText('');

        $this->assertSame('', $test->getText());
    }

    public function testGetColor()
    {
        $test = new TextElement('test');

        $test->setColor(new RgbColor(0.5, 0.5, 0.5));

        $clr = $test->getColor();

        $this->assertSame([0.5, 0.5, 0.5], $clr->getRgb());
    }

    public function testGetColorInvalid()
    {
        $test = new TextElement('test');

        $this->expectException(LayoutArgumentException::class);

        $test->getColor();
    }

    public function testSetFontSize()
    {
        $test = new TextElement('test');

        $test->setFontSize(12.0);

        $this->assertSame(12.0, $test->getFontSize());
    }

    public function testSetFontSizeInvalid1()
    {
        $test = new TextElement('test');

        $this->expectException(LayoutArgumentException::class);

        $test->setFontSize(-12.0);
    }

    public function testSetFontSizeInvalid2()
    {
        $test = new TextElement('test');

        $this->expectException(LayoutArgumentException::class);

        $test->setFontSize(0.0);
    }

    public function testSetWrapMode()
    {
        $test = new TextElement('test');

        $test->setWrapMode(TextElement::WRAP_HARD);

        $this->assertSame(TextElement::WRAP_HARD, $test->getWrapMode());
    }

    public function testSetWrapModeInvalid()
    {
        $test = new TextElement('test');

        $this->expectException(LayoutArgumentException::class);

        $test->setWrapMode(\PHP_INT_MAX);
    }

    public function testGetFont()
    {
        $test = new TextElement('test');

        $test->setFont('Arial');

        $this->assertSame('Arial', $test->getFont());
    }

    public function testGetFontInvalid()
    {
        $test = new TextElement('test');

        $this->expectException(LayoutArgumentException::class);

        $test->getFont();
    }

    public function testIsValid()
    {
        $test = new TextElement('test');

        $this->assertFalse($test->isValid());

        $test->setFont('Arial');

        $this->assertFalse($test->isValid());

        $test->setFontSize(12.0);

        $this->assertFalse($test->isValid());

        $test->setText('test2');

        $this->assertFalse($test->isValid());

        $test->setVerticalAlignMode(TextElement::VERTICAL_ALIGN_TOP);

        $this->assertFalse($test->isValid());

        $test->setAlignMode(TextElement::ALIGN_LEFT);

        $this->assertFalse($test->isValid());

        $test->setWrapMode(TextElement::WRAP_NONE);

        $this->assertFalse($test->isValid());

        $test->setColor(new RgbColor(0.5, 0.5, 0.5));

        $this->assertTrue($test->isValid());
    }
}
