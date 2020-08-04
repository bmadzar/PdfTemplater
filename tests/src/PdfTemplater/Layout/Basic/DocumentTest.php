<?php
declare(strict_types=1);

namespace Layout\Basic;

use PdfTemplater\Layout\Basic\Document;
use PdfTemplater\Layout\Basic\Font;
use PdfTemplater\Layout\Basic\Page;
use PHPUnit\Framework\TestCase;

class DocumentTest extends TestCase
{
    private const DATA_FILE_PATH =
        __DIR__ . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR .
        'data' . \DIRECTORY_SEPARATOR . 'test_data';

    public function testHasMetadataValue()
    {
        $test = new Document();

        $this->assertFalse($test->hasMetadataValue('author'));

        $test->setMetadataValue('author', 'test2');

        $this->assertTrue($test->hasMetadataValue('author'));
    }

    public function testGetPage()
    {
        $test = new Document();

        $this->assertNull($test->getPage(1));
        $this->assertNull($test->getPage(2));

        $page1 = new Page(1, 1.0, 1.0, []);
        $page2 = new Page(2, 1.0, 1.0, []);

        $test->addPage($page1);
        $test->addPage($page2);

        $this->assertSame($page1, $test->getPage(1));
        $this->assertSame($page2, $test->getPage(2));
    }

    public function testGetFonts()
    {
        $test = new Document();

        $this->assertSame([], $test->getFonts());

        $test->addFont(new Font('test1', Font::STYLE_NORMAL, self::DATA_FILE_PATH . \DIRECTORY_SEPARATOR . 'test_font.ttf'));
        $test->addFont(new Font('test2', Font::STYLE_NORMAL, self::DATA_FILE_PATH . \DIRECTORY_SEPARATOR . 'test_font.ttf'));

        $fonts = $test->getFonts();

        $this->assertCount(2, $fonts);
        $this->assertArrayHasKey('test1', $fonts);
        $this->assertArrayHasKey('test2', $fonts);
    }

    public function testSetMetadataValue()
    {
        $test = new Document();

        $test->setMetadataValue('author', 'test2');

        $this->assertSame('test2', $test->getMetadataValue('author'));
    }

    public function testSetFilename()
    {
        $test = new Document();

        $test->setFilename('test2');

        $this->assertSame('test2', $test->getFilename());

        $test->setFilename(null);

        $this->assertNull($test->getFilename());
    }

    public function testGetMetadataValue()
    {
        $test = new Document();

        $this->assertNull($test->getMetadataValue('author'));

        $test->setMetadataValue('author', 'test2');

        $this->assertSame('test2', $test->getMetadataValue('author'));
    }

    public function testSetPages()
    {
        $test = new Document();

        $page1 = new Page(1, 1.0, 1.0, []);
        $page2 = new Page(2, 1.0, 1.0, []);
        $page3 = new Page(3, 1.0, 1.0, []);

        $test->addPage($page1);
        $test->addPage($page2);

        $test->setPages([$page1, $page2]);

        $this->assertSame($page1, $test->getPage(1));
        $this->assertSame($page2, $test->getPage(2));
        $this->assertNull($test->getPage(3));

        $test->setPages([$page3]);

        $this->assertSame($page1, $test->getPage(1));
        $this->assertSame($page2, $test->getPage(2));
        $this->assertSame($page3, $test->getPage(3));
    }

    public function testSetPagesInvalid1()
    {
        $test = new Document();

        $this->expectException(\TypeError::class);

        /** @noinspection PhpParamsInspection */
        $test->setPages(['page1']);
    }

    public function testSetPagesInvalid2()
    {
        $test = new Document();

        $this->expectException(\TypeError::class);

        /** @noinspection PhpParamsInspection */
        $test->setPages([new \stdClass()]);
    }

    public function testResetPages()
    {
        $test = new Document();

        $page1 = new Page(1, 1.0, 1.0, []);
        $page2 = new Page(2, 1.0, 1.0, []);
        $page3 = new Page(3, 1.0, 1.0, []);

        $test->addPage($page1);
        $test->addPage($page2);

        $test->resetPages([$page1, $page2]);

        $this->assertSame($page1, $test->getPage(1));
        $this->assertSame($page2, $test->getPage(2));
        $this->assertNull($test->getPage(3));

        $test->resetPages([$page3]);

        $this->assertNull($test->getPage(1));
        $this->assertNull($test->getPage(2));
        $this->assertSame($page3, $test->getPage(3));

        $test->resetPages();

        $this->assertNull($test->getPage(1));
        $this->assertNull($test->getPage(2));
        $this->assertNull($test->getPage(3));
    }

    public function testSetMetadata()
    {
        $test = new Document();

        $test->setMetadata(['author' => 'test2', 'title' => 'test3']);

        $this->assertEquals(['author' => 'test2', 'title' => 'test3'], $test->getMetadata());
    }

    public function testSetMetadataInvalid1()
    {
        $test = new Document();

        $this->expectException(\TypeError::class);

        $test->setMetadata(['author' => new \stdClass()]);
    }

    public function testSetMetadataInvalid2()
    {
        $test = new Document();

        $this->expectException(\TypeError::class);

        /** @noinspection PhpParamsInspection */
        $test->setMetadata(['author' => []]);
    }

    public function testAddPage()
    {
        $test = new Document();

        $page1 = new Page(1, 1.0, 1.0, []);

        $test->addPage($page1);

        $this->assertSame($page1, $test->getPage(1));
    }

    public function testGetFilename()
    {
        $test = new Document();

        $this->assertNull($test->getFilename());

        $test->setFilename('test2');

        $this->assertSame('test2', $test->getFilename());
    }

    public function testRemoveFont()
    {
        $test = new Document();

        $test->addFont(new Font('TestFont', Font::STYLE_NORMAL, self::DATA_FILE_PATH . \DIRECTORY_SEPARATOR . 'test_font.ttf'));

        $this->assertTrue($test->hasFont('TestFont'));

        $test->removeFont('TestFont');

        $this->assertFalse($test->hasFont('TestFont'));
    }

    public function testAddFont()
    {
        $test = new Document();

        $test->addFont(new Font('TestFont', Font::STYLE_NORMAL, self::DATA_FILE_PATH . \DIRECTORY_SEPARATOR . 'test_font.ttf'));

        $font = $test->getFont('TestFont');

        $this->assertInstanceOf(Font::class, $font);
        $this->assertSame(\realpath(self::DATA_FILE_PATH . \DIRECTORY_SEPARATOR . 'test_font.ttf'), \realpath($font->getFile()));
    }

    public function testGetFont()
    {
        $test = new Document();

        $this->assertNull($test->getFont('TestFont'));

        $test->addFont(new Font('TestFont', Font::STYLE_NORMAL, self::DATA_FILE_PATH . \DIRECTORY_SEPARATOR . 'test_font.ttf'));

        $font = $test->getFont('TestFont');

        $this->assertInstanceOf(Font::class, $font);
        $this->assertSame(\realpath(self::DATA_FILE_PATH . \DIRECTORY_SEPARATOR . 'test_font.ttf'), \realpath($font->getFile()));
    }

    public function testGetPages()
    {
        $test = new Document();

        $this->assertSame([], $test->getPages());

        $page1 = new Page(1, 1.0, 1.0, []);
        $page2 = new Page(2, 1.0, 1.0, []);

        $test->addPage($page1);
        $test->addPage($page2);

        $pages = $test->getPages();

        $this->assertContains($page1, $pages, '', false, true);
        $this->assertContains($page2, $pages, '', false, true);
    }

    public function testRemovePage()
    {
        $test = new Document();

        $page1 = new Page(1, 1.0, 1.0, []);

        $test->addPage($page1);

        $this->assertSame($page1, $test->getPage(1));

        $test->removePage(1);

        $this->assertNull($test->getPage(1));
    }

    public function testGetMetadata()
    {
        $test = new Document();

        $this->assertSame([], $test->getMetadata());

        $test->setMetadata(['author' => 'test2', 'title' => 'test3']);

        $this->assertEquals(['author' => 'test2', 'title' => 'test3'], $test->getMetadata());
    }

    public function testHasFont()
    {
        $test = new Document();

        $this->assertFalse($test->hasFont('TestFont'));

        $test->addFont(new Font('TestFont', Font::STYLE_NORMAL, self::DATA_FILE_PATH . \DIRECTORY_SEPARATOR . 'test_font.ttf'));

        $this->assertTrue($test->hasFont('TestFont'));
    }
}
