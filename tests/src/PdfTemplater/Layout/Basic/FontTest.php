<?php

namespace Layout\Basic;

use PdfTemplater\Layout\Basic\Font;
use PdfTemplater\Layout\LayoutArgumentException;
use PHPUnit\Framework\TestCase;

class FontTest extends TestCase
{
    private const DATA_FILE_PATH =
        __DIR__ . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR .
        'data' . \DIRECTORY_SEPARATOR . 'test_data';

    private const FONT_FILE_PATH = self::DATA_FILE_PATH . \DIRECTORY_SEPARATOR . 'test_font.ttf';

    public function testSetStyle()
    {
        $font = new Font('Arial', Font::STYLE_NORMAL, null);

        $this->assertSame(Font::STYLE_NORMAL, $font->getStyle());

        $font->setStyle(Font::STYLE_BOLD);

        $this->assertSame(Font::STYLE_BOLD, $font->getStyle());
    }

    public function testSetStyleInvalid()
    {
        $font = new Font('Arial', Font::STYLE_NORMAL, null);

        $this->expectException(LayoutArgumentException::class);

        $font->setStyle(-1);
    }

    public function testGetStyle()
    {
        $font = new Font('Arial', Font::STYLE_NORMAL, null);

        $this->assertSame(Font::STYLE_NORMAL, $font->getStyle());
    }

    public function testGetName()
    {
        $font = new Font('Arial', Font::STYLE_NORMAL, null);

        $this->assertSame('Arial', $font->getName());
    }

    public function testSetFile()
    {
        $font = new Font('Arial', Font::STYLE_NORMAL, null);

        $this->assertNull($font->getFile());

        $font->setFile(self::FONT_FILE_PATH);

        $this->assertSame(\realpath(self::FONT_FILE_PATH), \realpath($font->getFile()));

        $font->setFile(null);

        $this->assertNull($font->getFile());
    }

    public function testSetFileInvalid()
    {
        $font = new Font('Arial', Font::STYLE_NORMAL, null);

        $this->expectException(LayoutArgumentException::class);

        $font->setFile('');
    }

    public function testSetFileBadPath()
    {
        $font = new Font('Arial', Font::STYLE_NORMAL, null);

        $this->expectException(LayoutArgumentException::class);

        $font->setFile(self::FONT_FILE_PATH . \DIRECTORY_SEPARATOR . 'i_dont_exist.ttf');
    }

    public function testConstruct()
    {
        $font = new Font('Arial', Font::STYLE_NORMAL, self::FONT_FILE_PATH);

        $this->assertSame('Arial', $font->getName());
        $this->assertSame(Font::STYLE_NORMAL, $font->getStyle());
        $this->assertSame(\realpath(self::FONT_FILE_PATH), \realpath($font->getFile()));
    }

    public function testSetName()
    {
        $font = new Font('Arial', Font::STYLE_NORMAL, null);

        $this->assertSame('Arial', $font->getName());

        $font->setName('Arial2');

        $this->assertSame('Arial2', $font->getName());
    }

    public function testSetNameInvalid()
    {
        $font = new Font('Arial', Font::STYLE_NORMAL, null);

        $this->expectException(LayoutArgumentException::class);

        $font->setName('');
    }

    public function testGetFile()
    {
        $font = new Font('Arial', Font::STYLE_NORMAL, null);

        $this->assertNull($font->getFile());
    }
}
