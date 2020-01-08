<?php
declare(strict_types=1);

namespace Layout\Basic;

use PdfTemplater\Layout\Basic\DataImageElement;
use PdfTemplater\Layout\LayoutArgumentException;
use PHPUnit\Framework\TestCase;

class DataImageElementTest extends TestCase
{

    public function testGetImageData()
    {
        $test = new DataImageElement('test');

        $fd = \file_get_contents(__DIR__ . '/../../../../data/builder_tests/test_image.png');

        $test->setData($fd);

        $this->assertSame($fd, $test->getImageData());
    }

    public function testGetImageDataUnset1()
    {
        $test = new DataImageElement('test');

        $this->expectException(LayoutArgumentException::class);

        $test->getImageData();
    }

    public function testGetImageDataUnset2()
    {
        $test = new DataImageElement('test');

        $this->expectException(LayoutArgumentException::class);

        $test->getImageFile();
    }

    public function testSetData()
    {
        $test = new DataImageElement('test');

        $if = \base64_encode(\file_get_contents(__DIR__ . '/../../../../data/builder_tests/test_image.png'));

        $test->setData($if);

        $this->assertSame($if, $test->getImageData());
    }

    public function testSetDataInvalid()
    {
        $test = new DataImageElement('test');

        $this->expectException(LayoutArgumentException::class);

        $test->setData('$$$');
    }

    public function testSetBinaryData()
    {
        $test = new DataImageElement('test');

        $if = \file_get_contents(__DIR__ . '/../../../../data/builder_tests/test_image.png');

        $test->setBinaryData($if);

        $if = \base64_encode($if);

        $this->assertSame($if, $test->getImageData());
    }

    public function testIsValid()
    {
        $test = new DataImageElement('test');


    }

    public function testSetAltText()
    {
        $test = new DataImageElement('test');

        $test->setAltText('test2');

        $this->assertSame('test2', $test->getAltText());

        $test->setAltText(null);

        $this->assertNull($test->getAltText());
    }

    public function testGetAltText()
    {
        $test = new DataImageElement('test');

        $this->assertNull($test->getAltText());

        $test->setAltText('test2');

        $this->assertSame('test2', $test->getAltText());
    }

    public function testGetImageFile()
    {
        $test = new DataImageElement('test');

        $test->setData(\file_get_contents(__DIR__ . '/../../../../data/builder_tests/test_image.png'));

        $if = $test->getImageFile();

        $this->assertNotNull($if);
        $this->assertFileIsReadable($if);
        $this->assertFileEquals(__DIR__ . '/../../../../data/builder_tests/test_image.png', $if);
    }
}
