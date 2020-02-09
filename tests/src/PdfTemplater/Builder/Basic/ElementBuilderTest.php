<?php
declare(strict_types=1);

namespace Builder\Basic;

use PdfTemplater\Builder\Basic\ElementBuilder;
use PdfTemplater\Builder\BuildException;
use PdfTemplater\Layout\Basic\DataImageElement;
use PdfTemplater\Layout\Basic\FileImageElement;
use PdfTemplater\Layout\Basic\RgbColor;
use PdfTemplater\Layout\BookmarkElement;
use PdfTemplater\Layout\EllipseElement;
use PdfTemplater\Layout\LineElement;
use PdfTemplater\Layout\RectangleElement;
use PdfTemplater\Node\Basic\Node;
use PHPUnit\Framework\TestCase;

class ElementBuilderTest extends TestCase
{

    const TEST_IMAGE_PATH =
        __DIR__ . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR .
        'data' . \DIRECTORY_SEPARATOR . 'test_data' . \DIRECTORY_SEPARATOR . 'test_image.png';

    public function testCreateElementInvalid()
    {
        $test = new ElementBuilder();

        $node = new Node('invalid');

        $this->expectException(BuildException::class);

        $test->buildElement($node);
    }

    public function testBuildElementBookmark()
    {
        $test = new ElementBuilder();

        $node = new Node('bookmark', null, [
            'level'  => '1',
            'name'   => 'test',
            'width'  => '10.0',
            'height' => '11.0',
            'top'    => '12.0',
            'left'   => '13.0',
        ]);

        /**
         * @var $el BookmarkElement
         */
        $el = $test->buildElement($node);

        $this->assertInstanceOf(BookmarkElement::class, $el);
        $this->assertSame(1, $el->getLevel());
        $this->assertSame('test', $el->getName());
        $this->assertSame(10.0, $el->getWidth());
        $this->assertSame(11.0, $el->getHeight());
        $this->assertSame(12.0, $el->getTop());
        $this->assertSame(13.0, $el->getLeft());
    }

    public function testBuildElementBookmarkInvalid1()
    {
        $test = new ElementBuilder();

        $node = new Node('bookmark', null, [
            'level'  => '-1',
            'name'   => 'test',
            'width'  => '10.0',
            'height' => '11.0',
            'top'    => '12.0',
            'left'   => '13.0',
        ]);

        $this->expectException(BuildException::class);

        $test->buildElement($node);
    }

    public function testBuildElementBookmarkInvalid2()
    {
        $test = new ElementBuilder();

        $node = new Node('bookmark', null, [
            'level'  => '1',
            'name'   => 'test',
            'width'  => '-10.0',
            'height' => '11.0',
            'top'    => '12.0',
            'left'   => '13.0',
        ]);

        $this->expectException(BuildException::class);

        $test->buildElement($node);
    }

    public function testBuildElementBookmarkInvalid3()
    {
        $test = new ElementBuilder();

        $node = new Node('bookmark', null, [
            'level'  => '1',
            'name'   => 'test',
            'width'  => '10.0',
            'height' => '-11.0',
            'top'    => '12.0',
            'left'   => '13.0',
        ]);

        $this->expectException(BuildException::class);

        $test->buildElement($node);
    }

    public function testBuildElementBookmarkInvalid4()
    {
        $test = new ElementBuilder();

        $node = new Node('bookmark', null, [
            'level'  => '1',
            'name'   => '',
            'width'  => '10.0',
            'height' => '11.0',
            'top'    => '12.0',
            'left'   => '13.0',
        ]);

        $this->expectException(BuildException::class);

        $test->buildElement($node);
    }

    public function testBuildElementBookmarkInvalid5()
    {
        $test = new ElementBuilder();

        $node = new Node('bookmark', null, [
            'level'  => '1',
            'width'  => '10.0',
            'height' => '11.0',
            'top'    => '12.0',
            'left'   => '13.0',
        ]);

        $this->expectException(BuildException::class);

        $test->buildElement($node);
    }

    public function testBuildElementDataImage()
    {
        $test = new ElementBuilder();

        $data = \base64_encode(\file_get_contents(self::TEST_IMAGE_PATH));

        $node = new Node('image', null, [
            'data'   => $data,
            'width'  => '10.0',
            'height' => '11.0',
            'top'    => '12.0',
            'left'   => '13.0',
        ]);

        /**
         * @var $el DataImageElement
         */
        $el = $test->buildElement($node);

        $this->assertSame($data, $el->getImageData());
        $this->assertSame(10.0, $el->getWidth());
        $this->assertSame(11.0, $el->getHeight());
        $this->assertSame(12.0, $el->getTop());
        $this->assertSame(13.0, $el->getLeft());
    }

    public function testBuildElementDataImageInvalid1()
    {
        $test = new ElementBuilder();

        $node = new Node('image', null, [
            'data'   => '',
            'width'  => '10.0',
            'height' => '11.0',
            'top'    => '12.0',
            'left'   => '13.0',
        ]);

        $this->expectException(BuildException::class);

        $test->buildElement($node);
    }

    public function testBuildElementDataImageInvalid2()
    {
        $test = new ElementBuilder();

        $node = new Node('image', null, [
            'width'  => '10.0',
            'height' => '11.0',
            'top'    => '12.0',
            'left'   => '13.0',
        ]);

        $this->expectException(BuildException::class);

        $test->buildElement($node);
    }

    public function testBuildElementDataImageInvalid3()
    {
        $test = new ElementBuilder();

        $data = \base64_encode(\file_get_contents(self::TEST_IMAGE_PATH));

        $node = new Node('image', null, [
            'data'   => $data,
            'width'  => '-10.0',
            'height' => '11.0',
            'top'    => '12.0',
            'left'   => '13.0',
        ]);

        $this->expectException(BuildException::class);

        $test->buildElement($node);
    }


    public function testBuildElementDataImageInvalid4()
    {
        $test = new ElementBuilder();

        $data = \base64_encode(\file_get_contents(self::TEST_IMAGE_PATH));

        $node = new Node('image', null, [
            'data'   => $data,
            'width'  => '10.0',
            'height' => '-11.0',
            'top'    => '12.0',
            'left'   => '13.0',
        ]);

        $this->expectException(BuildException::class);

        $test->buildElement($node);
    }

    public function testBuildElementEllipse1()
    {
        $test = new ElementBuilder();

        $node = new Node('ellipse', null, [
            'width'  => '10.0',
            'height' => '11.0',
            'top'    => '12.0',
            'left'   => '13.0',
        ]);

        /**
         * @var $el EllipseElement
         */
        $el = $test->buildElement($node);

        $this->assertNull($el->getStroke());
        $this->assertNull($el->getFill());
        $this->assertNull($el->getStrokeWidth());

        $this->assertSame(10.0, $el->getWidth());
        $this->assertSame(11.0, $el->getHeight());
        $this->assertSame(12.0, $el->getTop());
        $this->assertSame(13.0, $el->getLeft());
    }

    public function testBuildElementEllipse2()
    {
        $test = new ElementBuilder();

        $node = new Node('ellipse', null, [
            'stroke'      => '#FFF',
            'fill'        => '#ABC',
            'strokewidth' => '9.0',
            'width'       => '10.0',
            'height'      => '11.0',
            'top'         => '12.0',
            'left'        => '13.0',
        ]);

        /**
         * @var $el EllipseElement
         */
        $el = $test->buildElement($node);

        $this->assertEquals(new RgbColor(1.0, 1.0, 1.0), $el->getStroke());
        $this->assertEquals(new RgbColor(0xAA / 0xFF, 0xBB / 0xFF, 0xCC / 0xFF), $el->getFill());
        $this->assertSame(9.0, $el->getStrokeWidth());

        $this->assertSame(10.0, $el->getWidth());
        $this->assertSame(11.0, $el->getHeight());
        $this->assertSame(12.0, $el->getTop());
        $this->assertSame(13.0, $el->getLeft());
    }

    public function testBuildElementEllipseInvalid1()
    {
        $test = new ElementBuilder();

        $node = new Node('ellipse', null, [
            'width'  => '-10.0',
            'height' => '11.0',
            'top'    => '12.0',
            'left'   => '13.0',
        ]);

        $this->expectException(BuildException::class);

        $test->buildElement($node);
    }

    public function testBuildElementEllipseInvalid2()
    {
        $test = new ElementBuilder();

        $node = new Node('ellipse', null, [
            'width'  => '10.0',
            'height' => '-11.0',
            'top'    => '12.0',
            'left'   => '13.0',
        ]);

        $this->expectException(BuildException::class);

        $test->buildElement($node);
    }

    public function testBuildElementFileImage()
    {
        $test = new ElementBuilder();

        $data = self::TEST_IMAGE_PATH;

        $node = new Node('imagefile', null, [
            'file'   => $data,
            'width'  => '10.0',
            'height' => '11.0',
            'top'    => '12.0',
            'left'   => '13.0',
        ]);

        /**
         * @var $el FileImageElement
         */
        $el = $test->buildElement($node);

        $this->assertSame($data, $el->getImageFile());
        $this->assertSame(10.0, $el->getWidth());
        $this->assertSame(11.0, $el->getHeight());
        $this->assertSame(12.0, $el->getTop());
        $this->assertSame(13.0, $el->getLeft());
    }

    public function testBuildElementFileImageInvalid1()
    {
        $test = new ElementBuilder();

        $node = new Node('imagefile', null, [
            'file'   => '',
            'width'  => '10.0',
            'height' => '11.0',
            'top'    => '12.0',
            'left'   => '13.0',
        ]);

        $this->expectException(BuildException::class);

        $test->buildElement($node);
    }

    public function testBuildElementFileImageInvalid2()
    {
        $test = new ElementBuilder();

        $node = new Node('imagefile', null, [
            'width'  => '10.0',
            'height' => '11.0',
            'top'    => '12.0',
            'left'   => '13.0',
        ]);

        $this->expectException(BuildException::class);

        $test->buildElement($node);
    }

    public function testBuildElementFileImageInvalid3()
    {
        $test = new ElementBuilder();

        $data = self::TEST_IMAGE_PATH;

        $node = new Node('imagefile', null, [
            'file'   => $data,
            'width'  => '-10.0',
            'height' => '11.0',
            'top'    => '12.0',
            'left'   => '13.0',
        ]);

        $this->expectException(BuildException::class);

        $test->buildElement($node);
    }


    public function testBuildElementFileImageInvalid4()
    {
        $test = new ElementBuilder();

        $data = self::TEST_IMAGE_PATH;

        $node = new Node('imagefile', null, [
            'file'   => $data,
            'width'  => '10.0',
            'height' => '-11.0',
            'top'    => '12.0',
            'left'   => '13.0',
        ]);

        $this->expectException(BuildException::class);

        $test->buildElement($node);
    }

    public function testBuildElementLine()
    {
        $test = new ElementBuilder();

        $node = new Node('line', null, [
            'linewidth' => '5.0',
            'linecolor' => '#ABC',
            'width'     => '10.0',
            'height'    => '11.0',
            'top'       => '12.0',
            'left'      => '13.0',
        ]);

        /**
         * @var $el LineElement
         */
        $el = $test->buildElement($node);

        $this->assertSame(5.0, $el->getLineWidth());
        $this->assertEquals(new RgbColor(0xAA / 0xFF, 0xBB / 0xFF, 0xCC / 0xFF), $el->getLineColor());

        $this->assertSame(10.0, $el->getWidth());
        $this->assertSame(11.0, $el->getHeight());
        $this->assertSame(12.0, $el->getTop());
        $this->assertSame(13.0, $el->getLeft());
    }

    public function testBuildElementLineInvalid1()
    {
        $test = new ElementBuilder();

        $node = new Node('line', null, [
            'lineWidth' => '-5.0',
            'lineColor' => '#ABC',
            'width'     => '10.0',
            'height'    => '11.0',
            'top'       => '12.0',
            'left'      => '13.0',
        ]);

        $this->expectException(BuildException::class);

        $test->buildElement($node);
    }

    public function testBuildElementLineInvalid2()
    {
        $test = new ElementBuilder();

        $node = new Node('line', null, [
            'lineWidth' => '5.0',
            'lineColor' => '####',
            'width'     => '10.0',
            'height'    => '11.0',
            'top'       => '12.0',
            'left'      => '13.0',
        ]);

        $this->expectException(BuildException::class);

        $test->buildElement($node);
    }

    public function testBuildElementLineInvalid3()
    {
        $test = new ElementBuilder();

        $node = new Node('line', null, [
            'lineColor' => '#ABC',
            'width'     => '10.0',
            'height'    => '11.0',
            'top'       => '12.0',
            'left'      => '13.0',
        ]);

        $this->expectException(BuildException::class);

        $test->buildElement($node);
    }

    public function testBuildElementLineInvalid4()
    {
        $test = new ElementBuilder();

        $node = new Node('line', null, [
            'lineWidth' => '5.0',
            'width'     => '10.0',
            'height'    => '11.0',
            'top'       => '12.0',
            'left'      => '13.0',
        ]);

        $this->expectException(BuildException::class);

        $test->buildElement($node);
    }

    public function testBuildElementLineInvalid5()
    {
        $test = new ElementBuilder();

        $node = new Node('line', null, [
            'lineWidth' => '5.0',
            'lineColor' => '#ABC',
            'width'     => '-10.0',
            'height'    => '11.0',
            'top'       => '12.0',
            'left'      => '13.0',
        ]);

        $this->expectException(BuildException::class);

        $test->buildElement($node);
    }

    public function testBuildElementLineInvalid6()
    {
        $test = new ElementBuilder();

        $node = new Node('line', null, [
            'lineWidth' => '5.0',
            'lineColor' => '#ABC',
            'width'     => '10.0',
            'height'    => '-11.0',
            'top'       => '12.0',
            'left'      => '13.0',
        ]);

        $this->expectException(BuildException::class);

        $test->buildElement($node);
    }

    public function testBuildElementRectangle1()
    {
        $test = new ElementBuilder();

        $node = new Node('rectangle', null, [
            'width'  => '10.0',
            'height' => '11.0',
            'top'    => '12.0',
            'left'   => '13.0',
        ]);

        /**
         * @var $el RectangleElement
         */
        $el = $test->buildElement($node);

        $this->assertNull($el->getStroke());
        $this->assertNull($el->getFill());
        $this->assertNull($el->getStrokeWidth());

        $this->assertSame(10.0, $el->getWidth());
        $this->assertSame(11.0, $el->getHeight());
        $this->assertSame(12.0, $el->getTop());
        $this->assertSame(13.0, $el->getLeft());
    }

    public function testBuildElementRectangle2()
    {
        $test = new ElementBuilder();

        $node = new Node('rectangle', null, [
            'stroke'      => '#FFF',
            'fill'        => '#ABC',
            'strokewidth' => '9.0',
            'width'       => '10.0',
            'height'      => '11.0',
            'top'         => '12.0',
            'left'        => '13.0',
        ]);

        /**
         * @var $el RectangleElement
         */
        $el = $test->buildElement($node);

        $this->assertEquals(new RgbColor(1.0, 1.0, 1.0), $el->getStroke());
        $this->assertEquals(new RgbColor(0xAA / 0xFF, 0xBB / 0xFF, 0xCC / 0xFF), $el->getFill());
        $this->assertSame(9.0, $el->getStrokeWidth());

        $this->assertSame(10.0, $el->getWidth());
        $this->assertSame(11.0, $el->getHeight());
        $this->assertSame(12.0, $el->getTop());
        $this->assertSame(13.0, $el->getLeft());
    }

    public function testBuildElementRectangleInvalid1()
    {
        $test = new ElementBuilder();

        $node = new Node('rectangle', null, [
            'width'  => '-10.0',
            'height' => '11.0',
            'top'    => '12.0',
            'left'   => '13.0',
        ]);

        $this->expectException(BuildException::class);

        $test->buildElement($node);
    }

    public function testBuildElementRectangleInvalid2()
    {
        $test = new ElementBuilder();

        $node = new Node('rectangle', null, [
            'width'  => '10.0',
            'height' => '-11.0',
            'top'    => '12.0',
            'left'   => '13.0',
        ]);

        $this->expectException(BuildException::class);

        $test->buildElement($node);
    }
}
