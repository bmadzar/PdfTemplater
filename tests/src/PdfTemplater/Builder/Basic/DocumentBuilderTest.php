<?php
declare(strict_types=1);

namespace Builder\Basic;

use PdfTemplater\Builder\Basic\DocumentBuilder;
use PdfTemplater\Builder\BuildException;
use PdfTemplater\Layout\Document;
use PdfTemplater\Layout\Font;
use PdfTemplater\Layout\Page;
use PdfTemplater\Node\Basic\Node;
use PHPUnit\Framework\TestCase;

class DocumentBuilderTest extends TestCase
{

    public function testBuildDocument1()
    {
        $test = new DocumentBuilder();

        $node = new Node('document', null, [
            'title' => 'Test Document',
        ]);

        $doc = $test->buildDocument($node);

        $this->assertInstanceOf(Document::class, $doc);
        $this->assertSame('Test Document', $doc->getMetadataValue('title'));
        $this->assertCount(0, $doc->getPages());
        $this->assertCount(0, $doc->getFonts());
    }

    public function testBuildDocument2()
    {
        $test = new DocumentBuilder();

        $node = new Node('document', null, [
            'title' => 'Test Document',
        ]);

        $pageNode = new Node('page', null, [
            'number' => '1',
            'width'  => '11.0',
            'height' => '10.0',
        ]);

        $node->addChild($pageNode);

        $doc = $test->buildDocument($node);

        $this->assertInstanceOf(Document::class, $doc);
        $this->assertSame('Test Document', $doc->getMetadataValue('title'));
        $this->assertCount(1, $doc->getPages());
        $this->assertCount(0, $doc->getFonts());

        $page = $doc->getPage(1);

        $this->assertInstanceOf(Page::class, $page);
        $this->assertSame(1, $page->getNumber());
        $this->assertSame(11.0, $page->getWidth());
        $this->assertSame(10.0, $page->getHeight());

        $this->assertNull($doc->getPage(2));
    }

    public function testBuildDocument3()
    {
        $test = new DocumentBuilder();

        $node = new Node('document', null, [
            'title' => 'Test Document',
        ]);

        $fontNode = new Node('font', null, [
            'file'  => '',
            'name'  => 'Courier',
            'style' => '',
        ]);

        $node->addChild($fontNode);

        $doc = $test->buildDocument($node);

        $this->assertInstanceOf(Document::class, $doc);
        $this->assertSame('Test Document', $doc->getMetadataValue('title'));
        $this->assertCount(0, $doc->getPages());
        $this->assertCount(1, $doc->getFonts());

        $font = $doc->getFont('Courier');

        $this->assertInstanceOf(Font::class, $font);
        $this->assertSame('Courier', $font->getName());
        $this->assertSame(Font::STYLE_NORMAL, $font->getStyle());

        $this->assertNull($doc->getFont('TestFont'));
    }

    public function testBuildDocument4()
    {
        $test = new DocumentBuilder();

        $node = new Node('document', null, [
            'title' => 'Test Document',
        ]);

        $fontNode = new Node('font', null, [
            'file'  => '',
            'name'  => 'Courier',
            'style' => '',
        ]);

        $pageNode = new Node('page', null, [
            'number' => '1',
            'width'  => '11.0',
            'height' => '10.0',
        ]);

        $node->addChild($pageNode);
        $node->addChild($fontNode);

        $doc = $test->buildDocument($node);

        $this->assertInstanceOf(Document::class, $doc);
        $this->assertCount(1, $doc->getPages());
        $this->assertCount(1, $doc->getFonts());

        $font = $doc->getFont('Courier');

        $this->assertInstanceOf(Font::class, $font);

        $page = $doc->getPage(1);

        $this->assertInstanceOf(Page::class, $page);
    }

    public function testBuildInvalid1()
    {
        $test = new DocumentBuilder();

        $node = new Node('page', null, [
            'title' => 'Test Document',
        ]);

        $this->expectException(BuildException::class);

        $test->buildDocument($node);
    }
}
