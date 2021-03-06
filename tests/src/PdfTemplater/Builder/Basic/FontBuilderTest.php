<?php
declare(strict_types=1);

namespace Builder\Basic;

use PdfTemplater\Builder\Basic\FontBuilder;
use PdfTemplater\Builder\BuildArgumentException;
use PdfTemplater\Builder\BuildException;
use PdfTemplater\Layout\Font;
use PdfTemplater\Node\Basic\Node;
use PHPUnit\Framework\TestCase;

class FontBuilderTest extends TestCase
{
    private const DATA_FILE_PATH =
        __DIR__ . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR .
        'data' . \DIRECTORY_SEPARATOR . 'test_data';

    public function testBuildFont1()
    {
        $test = new FontBuilder();

        $node = new Node('font', null, [
            'file'  => self::DATA_FILE_PATH . \DIRECTORY_SEPARATOR . 'test_font.ttf',
            'name'  => 'TestFont',
            'style' => '',
        ]);

        $font = $test->buildFont($node);

        $this->assertSame('TestFont', $font->getName());
        $this->assertSame(Font::STYLE_NORMAL, $font->getStyle());
    }

    public function testBuildFont2()
    {
        $test = new FontBuilder();

        $node = new Node('font', null, [
            'file'  => '',
            'name'  => 'Courier',
            'style' => '',
        ]);

        $font = $test->buildFont($node);

        $this->assertSame('Courier', $font->getName());
        $this->assertSame(Font::STYLE_NORMAL, $font->getStyle());
    }

    public function testBuildFont3()
    {
        $test = new FontBuilder();

        $node = new Node('font', null, [
            'file'  => '',
            'name'  => 'Courier',
            'style' => 'bold',
        ]);

        $font = $test->buildFont($node);

        $this->assertSame('Courier', $font->getName());
        $this->assertSame(Font::STYLE_BOLD, $font->getStyle());
    }

    public function testBuildFont4()
    {
        $test = new FontBuilder();

        $node = new Node('font', null, [
            'file'  => '',
            'name'  => 'Courier',
            'style' => 'bolditalic',
        ]);

        $font = $test->buildFont($node);

        $this->assertSame('Courier', $font->getName());
        $this->assertSame(Font::STYLE_BOLD_ITALIC, $font->getStyle());
    }

    public function testBuildFont5()
    {
        $test = new FontBuilder();

        $node = new Node('font', null, [
            'file'  => '',
            'name'  => 'Courier',
            'style' => 'italic',
        ]);

        $font = $test->buildFont($node);

        $this->assertSame('Courier', $font->getName());
        $this->assertSame(Font::STYLE_ITALIC, $font->getStyle());
    }

    public function testBuildFont6()
    {
        $test = new FontBuilder();

        foreach (['B' => Font::STYLE_BOLD, 'I' => Font::STYLE_ITALIC, 'BI' => Font::STYLE_BOLD_ITALIC, 'IB' => Font::STYLE_BOLD_ITALIC] as $key => $style) {
            $node = new Node('font', null, [
                'file'  => '',
                'name'  => 'Courier',
                'style' => $key,
            ]);

            $font = $test->buildFont($node);

            $this->assertSame($style, $font->getStyle());
        }
        unset($key, $style);
    }

    public function testBuildFont7()
    {
        $test = new FontBuilder();

        foreach (Font::STYLES as $style) {
            $node = new Node('font', null, [
                'file'  => '',
                'name'  => 'Courier',
                'style' => (string)$style,
            ]);

            $font = $test->buildFont($node);

            $this->assertSame($style, $font->getStyle());
        }
        unset($style);
    }

    public function testBuildFontInvalid1()
    {
        $test = new FontBuilder();

        $node = new Node('font', null, [
            'file'  => '',
            'name'  => 'FakeFont',
            'style' => '',
        ]);

        $this->expectException(BuildException::class);

        $test->buildFont($node);
    }

    public function testBuildFontInvalid2()
    {
        $test = new FontBuilder();

        $node = new Node('font', null, [
            'file'  => '',
            'name'  => 'Courier',
            'style' => 'fakestyle',
        ]);

        $this->expectException(BuildArgumentException::class);

        $test->buildFont($node);
    }

    public function testBuildFontInvalid3()
    {
        $test = new FontBuilder();

        $node = new Node('font', null, [
            'file'  => self::DATA_FILE_PATH . \DIRECTORY_SEPARATOR . 'does_not_exist.ttf',
            'name'  => 'TestFont',
            'style' => '',
        ]);

        $this->expectException(BuildException::class);

        $test->buildFont($node);
    }

    public function testBuildFontInvalid4()
    {
        $test = new FontBuilder();

        $node = new Node('wrongtype', null, [
            'file'  => '',
            'name'  => 'Courier',
            'style' => '',
        ]);

        $this->expectException(BuildException::class);

        $test->buildFont($node);
    }

    public function testBuildFontInvalid5()
    {
        $test = new FontBuilder();

        $node = new Node('font', null, [
            'file' => '',
            'name' => 'Courier',
        ]);

        $this->expectException(BuildArgumentException::class);

        $test->buildFont($node);

    }
}
