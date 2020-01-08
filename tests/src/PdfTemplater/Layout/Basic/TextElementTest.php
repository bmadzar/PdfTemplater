<?php

namespace Layout\Basic;

use PdfTemplater\Layout\Basic\TextElement;
use PdfTemplater\Layout\LayoutArgumentException;
use PHPUnit\Framework\TestCase;

class TextElementTest extends TestCase
{

    public function testGetVerticalAlignMode()
    {
        $test = new TextElement('test');

        $this->assertSame(TextElement::VERTICAL_ALIGN_TOP, $test->getVerticalAlignMode());

        $test->setVerticalAlignMode(TextElement::VERTICAL_ALIGN_BOTTOM);

        $this->assertSame(TextElement::VERTICAL_ALIGN_BOTTOM, $test->getVerticalAlignMode());
    }

    public function testGetLineSize()
    {

    }

    public function testSetColor()
    {

    }

    public function testGetText()
    {

    }

    public function testGetWrapMode()
    {

    }

    public function testSetAlignMode()
    {

    }

    public function testSetFont()
    {

    }

    public function testGetFontSize()
    {

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

        $test->setVerticalAlignMode(PHP_INT_MAX);
    }

    public function testSetLineSize()
    {

    }

    public function testGetAlignMode()
    {

    }

    public function testSetText()
    {

    }

    public function testGetColor()
    {

    }

    public function testSetFontSize()
    {

    }

    public function testSetWrapMode()
    {

    }

    public function testGetFont()
    {

    }
}
