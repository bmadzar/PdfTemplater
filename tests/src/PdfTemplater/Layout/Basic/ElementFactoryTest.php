<?php

namespace Layout\Basic;

use PdfTemplater\Layout\Basic\BookmarkElement as BookmarkElementImpl;
use PdfTemplater\Layout\Basic\DataImageElement;
use PdfTemplater\Layout\Basic\ElementFactory;
use PdfTemplater\Layout\Basic\EllipseElement as EllipseElementImpl;
use PdfTemplater\Layout\Basic\FileImageElement;
use PdfTemplater\Layout\Basic\LineElement as LineElementImpl;
use PdfTemplater\Layout\Basic\RectangleElement as RectangleElementImpl;
use PdfTemplater\Layout\Basic\RgbColor;
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

        $test->createElement('invalid', 'test');
    }

    public function testSetExtendedAttributesBookmark()
    {
        $test = new ElementFactory();

        $el = new BookmarkElementImpl('test');

        $test->setExtendedAttributes($el, [
            'level'  => 1,
            'name'   => 'test',
            'width'  => 10.0,
            'height' => 11.0,
            'top'    => 12.0,
            'left'   => 13.0,
        ]);

        $this->assertSame(1, $el->getLevel());
        $this->assertSame('test', $el->getName());
        $this->assertSame(10.0, $el->getWidth());
        $this->assertSame(11.0, $el->getHeight());
        $this->assertSame(12.0, $el->getTop());
        $this->assertSame(13.0, $el->getLeft());
    }

    public function testSetExtendedAttributesBookmarkInvalid1()
    {
        $test = new ElementFactory();

        $el = new BookmarkElementImpl('test');

        $this->expectException(LayoutArgumentException::class);

        $test->setExtendedAttributes($el, [
            'level'  => -1,
            'name'   => 'test',
            'width'  => 10.0,
            'height' => 11.0,
            'top'    => 12.0,
            'left'   => 13.0,
        ]);
    }

    public function testSetExtendedAttributesBookmarkInvalid2()
    {
        $test = new ElementFactory();

        $el = new BookmarkElementImpl('test');

        $this->expectException(LayoutArgumentException::class);

        $test->setExtendedAttributes($el, [
            'level'  => 1,
            'name'   => 'test',
            'width'  => -10.0,
            'height' => 11.0,
            'top'    => 12.0,
            'left'   => 13.0,
        ]);
    }

    public function testSetExtendedAttributesBookmarkInvalid3()
    {
        $test = new ElementFactory();

        $el = new BookmarkElementImpl('test');

        $this->expectException(LayoutArgumentException::class);

        $test->setExtendedAttributes($el, [
            'level'  => 1,
            'name'   => 'test',
            'width'  => 10.0,
            'height' => -11.0,
            'top'    => 12.0,
            'left'   => 13.0,
        ]);
    }

    public function testSetExtendedAttributesBookmarkInvalid4()
    {
        $test = new ElementFactory();

        $el = new BookmarkElementImpl('test');

        $this->expectException(LayoutArgumentException::class);

        $test->setExtendedAttributes($el, [
            'level'  => 1,
            'name'   => null,
            'width'  => 10.0,
            'height' => 11.0,
            'top'    => 12.0,
            'left'   => 13.0,
        ]);
    }

    public function testSetExtendedAttributesBookmarkInvalid5()
    {
        $test = new ElementFactory();

        $el = new BookmarkElementImpl('test');

        $this->expectException(LayoutArgumentException::class);

        $test->setExtendedAttributes($el, [
            'level'  => 1,
            'width'  => 10.0,
            'height' => 11.0,
            'top'    => 12.0,
            'left'   => 13.0,
        ]);
    }

    public function testSetExtendedAttributesDataImage()
    {
        $test = new ElementFactory();

        $el = new DataImageElement('test');

        $data = \base64_encode(\file_get_contents(__DIR__ . '/../../../../test_data/test_image.png'));

        $test->setExtendedAttributes($el, [
            'data'   => $data,
            'width'  => 10.0,
            'height' => 11.0,
            'top'    => 12.0,
            'left'   => 13.0,
        ]);

        $this->assertSame($data, $el->getImageData());
        $this->assertSame(10.0, $el->getWidth());
        $this->assertSame(11.0, $el->getHeight());
        $this->assertSame(12.0, $el->getTop());
        $this->assertSame(13.0, $el->getLeft());
    }

    public function testSetExtendedAttributesDataImageInvalid1()
    {
        $test = new ElementFactory();

        $el = new DataImageElement('test');

        $this->expectException(LayoutArgumentException::class);

        $test->setExtendedAttributes($el, [
            'data'   => null,
            'width'  => 10.0,
            'height' => 11.0,
            'top'    => 12.0,
            'left'   => 13.0,
        ]);
    }

    public function testSetExtendedAttributesDataImageInvalid2()
    {
        $test = new ElementFactory();

        $el = new DataImageElement('test');

        $this->expectException(LayoutArgumentException::class);

        $test->setExtendedAttributes($el, [
            'width'  => 10.0,
            'height' => 11.0,
            'top'    => 12.0,
            'left'   => 13.0,
        ]);
    }

    public function testSetExtendedAttributesDataImageInvalid3()
    {
        $test = new ElementFactory();

        $el = new DataImageElement('test');

        $this->expectException(LayoutArgumentException::class);

        $data = \base64_encode(\file_get_contents(__DIR__ . '/../../../../test_data/test_image.png'));

        $test->setExtendedAttributes($el, [
            'data'   => $data,
            'width'  => -10.0,
            'height' => 11.0,
            'top'    => 12.0,
            'left'   => 13.0,
        ]);
    }


    public function testSetExtendedAttributesDataImageInvalid4()
    {
        $test = new ElementFactory();

        $el = new DataImageElement('test');

        $this->expectException(LayoutArgumentException::class);

        $data = \base64_encode(\file_get_contents(__DIR__ . '/../../../../test_data/test_image.png'));

        $test->setExtendedAttributes($el, [
            'data'   => $data,
            'width'  => 10.0,
            'height' => -11.0,
            'top'    => 12.0,
            'left'   => 13.0,
        ]);
    }

    public function testSetExtendedAttributesEllipse1()
    {
        $test = new ElementFactory();

        $el = new EllipseElementImpl('test');

        $test->setExtendedAttributes($el, [
            'width'  => 10.0,
            'height' => 11.0,
            'top'    => 12.0,
            'left'   => 13.0,
        ]);

        $this->assertNull($el->getStroke());
        $this->assertNull($el->getFill());
        $this->assertNull($el->getStrokeWidth());

        $this->assertSame(10.0, $el->getWidth());
        $this->assertSame(11.0, $el->getHeight());
        $this->assertSame(12.0, $el->getTop());
        $this->assertSame(13.0, $el->getLeft());
    }

    public function testSetExtendedAttributesEllipse2()
    {
        $test = new ElementFactory();

        $el = new EllipseElementImpl('test');

        $test->setExtendedAttributes($el, [
            'stroke'      => '#FFF',
            'fill'        => '#ABC',
            'strokewidth' => 9.0,
            'width'       => 10.0,
            'height'      => 11.0,
            'top'         => 12.0,
            'left'        => 13.0,
        ]);

        $this->assertEquals(new RgbColor(1.0, 1.0, 1.0), $el->getStroke());
        $this->assertEquals(new RgbColor(0xAA / 0xFF, 0xBB / 0xFF, 0xCC / 0xFF), $el->getFill());
        $this->assertSame(9.0, $el->getStrokeWidth());

        $this->assertSame(10.0, $el->getWidth());
        $this->assertSame(11.0, $el->getHeight());
        $this->assertSame(12.0, $el->getTop());
        $this->assertSame(13.0, $el->getLeft());
    }

    public function testSetExtendedAttributesEllipseInvalid1()
    {
        $test = new ElementFactory();

        $el = new EllipseElementImpl('test');

        $this->expectException(LayoutArgumentException::class);

        $test->setExtendedAttributes($el, [
            'width'  => -10.0,
            'height' => 11.0,
            'top'    => 12.0,
            'left'   => 13.0,
        ]);
    }

    public function testSetExtendedAttributesEllipseInvalid2()
    {
        $test = new ElementFactory();

        $el = new EllipseElementImpl('test');

        $this->expectException(LayoutArgumentException::class);

        $test->setExtendedAttributes($el, [
            'width'  => 10.0,
            'height' => -11.0,
            'top'    => 12.0,
            'left'   => 13.0,
        ]);
    }

    public function testSetExtendedAttributesFileImage()
    {
        $test = new ElementFactory();

        $el = new FileImageElement('test');

        $data = __DIR__ . '/../../../../test_data/test_image.png';

        $test->setExtendedAttributes($el, [
            'file'   => $data,
            'width'  => 10.0,
            'height' => 11.0,
            'top'    => 12.0,
            'left'   => 13.0,
        ]);

        $this->assertSame($data, $el->getImageFile());
        $this->assertSame(10.0, $el->getWidth());
        $this->assertSame(11.0, $el->getHeight());
        $this->assertSame(12.0, $el->getTop());
        $this->assertSame(13.0, $el->getLeft());
    }

    public function testSetExtendedAttributesFileImageInvalid1()
    {
        $test = new ElementFactory();

        $el = new FileImageElement('test');

        $this->expectException(LayoutArgumentException::class);

        $test->setExtendedAttributes($el, [
            'file'   => null,
            'width'  => 10.0,
            'height' => 11.0,
            'top'    => 12.0,
            'left'   => 13.0,
        ]);
    }

    public function testSetExtendedAttributesFileImageInvalid2()
    {
        $test = new ElementFactory();

        $el = new FileImageElement('test');

        $this->expectException(LayoutArgumentException::class);

        $test->setExtendedAttributes($el, [
            'width'  => 10.0,
            'height' => 11.0,
            'top'    => 12.0,
            'left'   => 13.0,
        ]);
    }

    public function testSetExtendedAttributesFileImageInvalid3()
    {
        $test = new ElementFactory();

        $el = new FileImageElement('test');

        $this->expectException(LayoutArgumentException::class);

        $data = __DIR__ . '/../../../../test_data/test_image.png';

        $test->setExtendedAttributes($el, [
            'file'   => $data,
            'width'  => -10.0,
            'height' => 11.0,
            'top'    => 12.0,
            'left'   => 13.0,
        ]);
    }


    public function testSetExtendedAttributesFileImageInvalid4()
    {
        $test = new ElementFactory();

        $el = new FileImageElement('test');

        $this->expectException(LayoutArgumentException::class);

        $data = __DIR__ . '/../../../../test_data/test_image.png';

        $test->setExtendedAttributes($el, [
            'file'   => $data,
            'width'  => 10.0,
            'height' => -11.0,
            'top'    => 12.0,
            'left'   => 13.0,
        ]);
    }

    public function testSetExtendedAttributesLine()
    {
        $test = new ElementFactory();

        $el = new LineElementImpl('test');

        $test->setExtendedAttributes($el, [
            'lineWidth' => 5.0,
            'lineColor' => '#ABC',
            'width'     => 10.0,
            'height'    => 11.0,
            'top'       => 12.0,
            'left'      => 13.0,
        ]);

        $this->assertSame(5.0, $el->getLineWidth());
        $this->assertEquals(new RgbColor(0xAA / 0xFF, 0xBB / 0xFF, 0xCC / 0xFF), $el->getLineColor());

        $this->assertSame(10.0, $el->getWidth());
        $this->assertSame(11.0, $el->getHeight());
        $this->assertSame(12.0, $el->getTop());
        $this->assertSame(13.0, $el->getLeft());
    }

    public function testSetExtendedAttributesLineInvalid1()
    {
        $test = new ElementFactory();

        $el = new LineElementImpl('test');

        $this->expectException(LayoutArgumentException::class);

        $test->setExtendedAttributes($el, [
            'lineWidth' => -5.0,
            'lineColor' => '#ABC',
            'width'     => 10.0,
            'height'    => 11.0,
            'top'       => 12.0,
            'left'      => 13.0,
        ]);
    }

    public function testSetExtendedAttributesLineInvalid2()
    {
        $test = new ElementFactory();

        $el = new LineElementImpl('test');

        $this->expectException(LayoutArgumentException::class);

        $test->setExtendedAttributes($el, [
            'lineWidth' => 5.0,
            'lineColor' => '####',
            'width'     => 10.0,
            'height'    => 11.0,
            'top'       => 12.0,
            'left'      => 13.0,
        ]);
    }

    public function testSetExtendedAttributesLineInvalid3()
    {
        $test = new ElementFactory();

        $el = new LineElementImpl('test');

        $this->expectException(LayoutArgumentException::class);

        $test->setExtendedAttributes($el, [
            'lineColor' => '#ABC',
            'width'     => 10.0,
            'height'    => 11.0,
            'top'       => 12.0,
            'left'      => 13.0,
        ]);
    }

    public function testSetExtendedAttributesLineInvalid4()
    {
        $test = new ElementFactory();

        $el = new LineElementImpl('test');

        $this->expectException(LayoutArgumentException::class);

        $test->setExtendedAttributes($el, [
            'lineWidth' => 5.0,
            'width'     => 10.0,
            'height'    => 11.0,
            'top'       => 12.0,
            'left'      => 13.0,
        ]);
    }

    public function testSetExtendedAttributesLineInvalid5()
    {
        $test = new ElementFactory();

        $el = new LineElementImpl('test');

        $this->expectException(LayoutArgumentException::class);

        $test->setExtendedAttributes($el, [
            'lineWidth' => 5.0,
            'lineColor' => '#ABC',
            'width'     => -10.0,
            'height'    => 11.0,
            'top'       => 12.0,
            'left'      => 13.0,
        ]);
    }

    public function testSetExtendedAttributesLineInvalid6()
    {
        $test = new ElementFactory();

        $el = new LineElementImpl('test');

        $this->expectException(LayoutArgumentException::class);

        $test->setExtendedAttributes($el, [
            'lineWidth' => 5.0,
            'lineColor' => '#ABC',
            'width'     => 10.0,
            'height'    => -11.0,
            'top'       => 12.0,
            'left'      => 13.0,
        ]);
    }

    public function testSetExtendedAttributesRectangle1()
    {
        $test = new ElementFactory();

        $el = new RectangleElementImpl('test');

        $test->setExtendedAttributes($el, [
            'width'  => 10.0,
            'height' => 11.0,
            'top'    => 12.0,
            'left'   => 13.0,
        ]);

        $this->assertNull($el->getStroke());
        $this->assertNull($el->getFill());
        $this->assertNull($el->getStrokeWidth());

        $this->assertSame(10.0, $el->getWidth());
        $this->assertSame(11.0, $el->getHeight());
        $this->assertSame(12.0, $el->getTop());
        $this->assertSame(13.0, $el->getLeft());
    }

    public function testSetExtendedAttributesRectangle2()
    {
        $test = new ElementFactory();

        $el = new RectangleElementImpl('test');

        $test->setExtendedAttributes($el, [
            'stroke'      => '#FFF',
            'fill'        => '#ABC',
            'strokewidth' => 9.0,
            'width'       => 10.0,
            'height'      => 11.0,
            'top'         => 12.0,
            'left'        => 13.0,
        ]);

        $this->assertEquals(new RgbColor(1.0, 1.0, 1.0), $el->getStroke());
        $this->assertEquals(new RgbColor(0xAA / 0xFF, 0xBB / 0xFF, 0xCC / 0xFF), $el->getFill());
        $this->assertSame(9.0, $el->getStrokeWidth());

        $this->assertSame(10.0, $el->getWidth());
        $this->assertSame(11.0, $el->getHeight());
        $this->assertSame(12.0, $el->getTop());
        $this->assertSame(13.0, $el->getLeft());
    }

    public function testSetExtendedAttributesRectangleInvalid1()
    {
        $test = new ElementFactory();

        $el = new RectangleElementImpl('test');

        $this->expectException(LayoutArgumentException::class);

        $test->setExtendedAttributes($el, [
            'width'  => -10.0,
            'height' => 11.0,
            'top'    => 12.0,
            'left'   => 13.0,
        ]);
    }

    public function testSetExtendedAttributesRectangleInvalid2()
    {
        $test = new ElementFactory();

        $el = new RectangleElementImpl('test');

        $this->expectException(LayoutArgumentException::class);

        $test->setExtendedAttributes($el, [
            'width'  => 10.0,
            'height' => -11.0,
            'top'    => 12.0,
            'left'   => 13.0,
        ]);
    }

}
