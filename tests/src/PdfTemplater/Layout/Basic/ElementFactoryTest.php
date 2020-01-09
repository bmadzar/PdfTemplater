<?php

namespace Layout\Basic;

use PdfTemplater\Layout\Basic\ElementFactory;
use PdfTemplater\Layout\BookmarkElement;
use PdfTemplater\Layout\EllipseElement;
use PdfTemplater\Layout\ImageElement;
use PdfTemplater\Layout\LayoutArgumentException;
use PdfTemplater\Layout\LineElement;
use PdfTemplater\Layout\RectangleElement;
use PdfTemplater\Layout\TextElement;
use PHPUnit\Framework\TestCase;

class ElementFactoryTest extends TestCase
{

    public function testCreateElement()
    {
        $test = new ElementFactory();

        $this->assertInstanceOf(BookmarkElement::class, $test->createElement('bookmark', 'test'));
        $this->assertInstanceOf(ImageElement::class, $test->createElement('image', 'test'));
        $this->assertInstanceOf(EllipseElement::class, $test->createElement('ellipse', 'test'));
        $this->assertInstanceOf(ImageElement::class, $test->createElement('imagefile', 'test'));
        $this->assertInstanceOf(LineElement::class, $test->createElement('line', 'test'));
        $this->assertInstanceOf(RectangleElement::class, $test->createElement('rectangle', 'test'));
        $this->assertInstanceOf(TextElement::class, $test->createElement('text', 'test'));
    }

    public function testCreateElementInvalid()
    {
        $test = new ElementFactory();

        $this->expectException(LayoutArgumentException::class);

        $this->assertInstanceOf(BookmarkElement::class, $test->createElement('invalid', 'test'));
    }

    public function testSetExtendedAttributes()
    {

    }
}
