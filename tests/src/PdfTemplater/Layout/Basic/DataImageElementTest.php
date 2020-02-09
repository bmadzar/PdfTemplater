<?php
declare(strict_types=1);

namespace Layout\Basic;

use PdfTemplater\Layout\Basic\DataImageElement;
use PdfTemplater\Layout\LayoutArgumentException;
use PHPUnit\Framework\TestCase;

class DataImageElementTest extends TestCase
{
    private const DATA_FILE_PATH =
        __DIR__ . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR .
        'data' . \DIRECTORY_SEPARATOR . 'test_data';

    /**
     * @var string First test file
     */
    private static string $fd1;

    /**
     * @var string Second test file
     */
    private static string $fd2;

    /**
     * Preloads the two test images to avoid doing file I/O repeatedly.
     */
    public static function setUpBeforeClass(): void
    {
        self::$fd1 = \base64_encode(\file_get_contents(self::DATA_FILE_PATH . \DIRECTORY_SEPARATOR . 'test_image.png'));
        self::$fd2 = \base64_encode(\file_get_contents(self::DATA_FILE_PATH . \DIRECTORY_SEPARATOR . 'test_image_2.png'));
    }

    public function testGetImageData()
    {
        $test = new DataImageElement('test', 0.0, 0.0, 1.0, 1.0, null, null, null, self::$fd1, null);

        $this->assertSame(self::$fd1, $test->getImageData());
    }

    public function testSetData()
    {
        $test = new DataImageElement('test', 0.0, 0.0, 1.0, 1.0, null, null, null, self::$fd1, null);

        $test->setData(self::$fd2);

        $this->assertSame(self::$fd2, $test->getImageData());
    }

    public function testSetDataInvalid()
    {
        $test = new DataImageElement('test', 0.0, 0.0, 1.0, 1.0, null, null, null, self::$fd1, null);

        $this->expectException(LayoutArgumentException::class);

        $test->setData('$$$');
    }

    public function testSetBinaryData()
    {
        $test = new DataImageElement('test', 0.0, 0.0, 1.0, 1.0, null, null, null, self::$fd1, null);

        $test->setBinaryData(\base64_decode(self::$fd2));

        $this->assertSame(self::$fd2, $test->getImageData());
    }

    public function testSetAltText()
    {
        $test = new DataImageElement('test', 0.0, 0.0, 1.0, 1.0, null, null, null, self::$fd1, null);

        $test->setAltText('test2');

        $this->assertSame('test2', $test->getAltText());

        $test->setAltText(null);

        $this->assertNull($test->getAltText());
    }

    public function testGetAltText()
    {
        $test = new DataImageElement('test', 0.0, 0.0, 1.0, 1.0, null, null, null, self::$fd1, null);

        $this->assertNull($test->getAltText());

        $test->setAltText('test2');

        $this->assertSame('test2', $test->getAltText());
    }

    public function testGetImageFile()
    {
        $test = new DataImageElement('test', 0.0, 0.0, 1.0, 1.0, null, null, null, self::$fd1, null);

        $if = $test->getImageFile();

        $this->assertNotNull($if);
        $this->assertFileIsReadable($if);
        $this->assertFileEquals(self::DATA_FILE_PATH . \DIRECTORY_SEPARATOR . 'test_image.png', $if);
    }
}
