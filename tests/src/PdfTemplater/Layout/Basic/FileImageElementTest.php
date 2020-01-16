<?php
declare(strict_types=1);

namespace Layout\Basic;

use PdfTemplater\Layout\Basic\FileImageElement;
use PHPUnit\Framework\TestCase;

class FileImageElementTest extends TestCase
{
    /**
     * @var string First test file
     */
    private static string $fd1 = __DIR__ . '/../../../../data/test_data/test_image.png';

    /**
     * @var string Second test file
     */
    private static string $fd2 = __DIR__ . '/../../../../data/test_data/test_image_2.png';

    public function testSetAltText()
    {
        $test = new FileImageElement('test', 0.0, 0.0, 1.0, 1.0, null, null, null, self::$fd1, null);

        $test->setAltText('test2');

        $this->assertSame('test2', $test->getAltText());

        $test->setAltText(null);

        $this->assertNull($test->getAltText());
    }

    public function testGetAltText()
    {
        $test = new FileImageElement('test', 0.0, 0.0, 1.0, 1.0, null, null, null, self::$fd1, null);

        $this->assertNull($test->getAltText());

        $test->setAltText('test2');

        $this->assertSame('test2', $test->getAltText());
    }

    public function testBasic()
    {
        $test = new FileImageElement('test', 0.0, 0.0, 1.0, 1.0, null, null, null, self::$fd1, null);

        $this->assertSame('test', $test->getId());
    }

    public function testGetImageFile()
    {
        $test = new FileImageElement('test', 0.0, 0.0, 1.0, 1.0, null, null, null, self::$fd1, null);

        $if = $test->getImageFile();

        $this->assertNotNull($if);
        $this->assertFileIsReadable($if);
        $this->assertFileEquals(self::$fd1, $if);
    }

    public function testSetFile()
    {
        $test = new FileImageElement('test', 0.0, 0.0, 1.0, 1.0, null, null, null, self::$fd1, null);

        $test->setFile(self::$fd2);

        $if = $test->getImageFile();

        $this->assertNotNull($if);
        $this->assertFileIsReadable($if);
        $this->assertFileEquals(self::$fd2, $if);
    }

    public function testGetImageData()
    {
        $test = new FileImageElement('test', 0.0, 0.0, 1.0, 1.0, null, null, null, self::$fd1, null);

        $this->assertSame(\base64_encode(\file_get_contents(self::$fd1)), $test->getImageData());
    }
}
